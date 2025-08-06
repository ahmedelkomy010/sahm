<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        // Filter by role
        if ($request->has('role') && !empty($request->role)) {
            if ($request->role === 'admin') {
                $query->where('is_admin', 1);
            } elseif ($request->role === 'user') {
                $query->where('is_admin', 0);
            }
        }

        $users = $query->latest()->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created user in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'is_admin' => ['sometimes', 'boolean'],
            'permissions' => ['sometimes', 'array'],
        ]);

        // إنشاء المستخدم الجديد
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'is_admin' => $request->has('is_admin') ? 1 : 0,
        ]);

        // حفظ الصلاحيات إذا تم تمريرها وليس مشرفًا
        if (!$request->has('is_admin') && $request->has('permissions')) {
            $user->permissions = $request->permissions;
            $user->save();
        }

        // تسجيل الحدث في سجل النظام
        \Illuminate\Support\Facades\Log::info('تم إنشاء مستخدم جديد', [
            'user_id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'is_admin' => $user->is_admin,
            'permissions' => $request->has('permissions') ? $request->permissions : [],
            'created_by' => auth()->id(),
            'created_by_name' => auth()->user()->name,
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', "تم إنشاء المستخدم {$user->name} بنجاح!");
    }

    /**
     * Display the specified user.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\View\View
     */
    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\View\View
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'is_admin' => ['sometimes', 'boolean'],
            'permissions' => ['sometimes', 'array'],
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->is_admin = $request->has('is_admin') ? 1 : 0;
        
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }
        
        // حفظ الصلاحيات إذا تم تمريرها وليس مشرفًا
        if (!$request->has('is_admin') && $request->has('permissions')) {
            $user->permissions = $request->permissions;
        } else {
            // إذا كان مشرفًا، امسح الصلاحيات المخصصة
            $user->permissions = null;
        }
        
        $user->save();

        // تسجيل الحدث في سجل النظام
        \Illuminate\Support\Facades\Log::info('تم تحديث بيانات مستخدم', [
            'user_id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'is_admin' => $user->is_admin,
            'permissions' => $request->has('permissions') ? $request->permissions : [],
            'updated_by' => auth()->id(),
            'updated_by_name' => auth()->user()->name,
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'تم تحديث بيانات المستخدم بنجاح!');
    }

    /**
     * Remove the specified user from the database.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $user)
    {
        // Prevent deleting yourself
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'لا يمكنك حذف حسابك الشخصي!');
        }

        // احتفظ بالبيانات قبل الحذف للتسجيل
        $userData = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ];

        try {
            // حذف الرخص المرتبطة بالمستخدم أولاً
            \App\Models\License::where('user_id', $user->id)->update(['user_id' => null]);
            
            // قم بحذف المستخدم
            $user->delete();
        } catch (\Exception $e) {
            return redirect()->route('admin.users.index')
                ->with('error', 'لا يمكن حذف المستخدم لوجود بيانات مرتبطة به!');
        }

        // تسجيل الحدث في سجل النظام
        \Illuminate\Support\Facades\Log::info('تم حذف مستخدم', [
            'deleted_user' => $userData,
            'deleted_by' => auth()->id(),
            'deleted_by_name' => auth()->user()->name,
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', "تم حذف المستخدم {$userData['name']} بنجاح!");
    }

    /**
     * Toggle admin status for a user.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toggleAdmin(User $user)
    {
        // Prevent changing your own admin status
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'لا يمكنك تغيير حالة المشرف الخاصة بك!');
        }

        $user->is_admin = !$user->is_admin;
        $user->save();

        $message = $user->is_admin 
            ? "تمت ترقية {$user->name} إلى مشرف بنجاح!"
            : "تم إلغاء حالة المشرف عن {$user->name} بنجاح!";

        return redirect()->route('admin.users.index')
            ->with('success', $message);
    }
}
