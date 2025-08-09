@extends('layouts.admin')

@section('title', 'إدارة المستخدمين')

@section('header', 'إدارة المستخدمين')

@section('content')
<div class="bg-white rounded-lg shadow-md mb-6 p-6">
    <div class="mb-4 p-4 bg-gray-100 rounded-md">
        <h3 class="font-bold text-gray-800 mb-2">معلومات التشخيص:</h3>
        <div class="text-sm space-y-1">
            <p>المستخدم الحالي: <strong>{{ auth()->user()->name }}</strong> ({{ auth()->user()->email }})</p>
            <p>حالة المشرف: <strong class="{{ auth()->user()->is_admin ? 'text-green-600' : 'text-red-600' }}">{{ auth()->user()->is_admin ? 'مشرف نظام' : 'مستخدم عادي' }}</strong></p>
            <p>قيمة is_admin: <code class="bg-gray-200 px-1 rounded">{{ var_export(auth()->user()->is_admin, true) }}</code></p>
            
            @if(!auth()->user()->is_admin)
                <div class="mt-2 p-2 bg-yellow-50 border border-yellow-200 rounded">
                    <p class="mb-2 text-yellow-700">أنت لست مشرف، قد لا تتمكن من استخدام بعض الوظائف.</p>
                    <a href="{{ url('/make-me-admin') }}" class="inline-flex items-center px-3 py-1 bg-yellow-500 hover:bg-yellow-600 text-white rounded text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                        جعلني مشرف
                    </a>
                </div>
            @endif
        </div>
    </div>

    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">قائمة المستخدمين</h2>
            <p class="mt-1 text-sm text-gray-600">إدارة جميع مستخدمي النظام</p>
        </div>
        @if(auth()->user()->is_admin)
        <a href="{{ route('admin.users.create') }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring focus:ring-green-300 disabled:opacity-25 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            إضافة مستخدم جديد
        </a>
        @endif
    </div>

    <!-- بطاقة الإحصائيات -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-blue-50 p-4 rounded-lg border border-blue-200 flex items-center">
            <div class="bg-blue-500 rounded-full p-3 ml-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            </div>
            <div>
                <p class="text-sm text-gray-600">إجمالي المستخدمين</p>
                <p class="text-2xl font-bold text-gray-800">{{ $users->total() ?? count($users) }}</p>
            </div>
        </div>
        <div class="bg-green-50 p-4 rounded-lg border border-green-200 flex items-center">
            <div class="bg-green-500 rounded-full p-3 ml-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
            </div>
            <div>
                <p class="text-sm text-gray-600">المشرفين</p>
                <p class="text-2xl font-bold text-gray-800">{{ $users->where('is_admin', 1)->count() }}</p>
            </div>
        </div>
        <div class="bg-purple-50 p-4 rounded-lg border border-purple-200 flex items-center">
            <div class="bg-purple-500 rounded-full p-3 ml-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <p class="text-sm text-gray-600">آخر تسجيل</p>
                <p class="text-xl font-bold text-gray-800">{{ optional($users->sortByDesc('created_at')->first())->created_at->diffForHumans() ?? 'غير متوفر' }}</p>
            </div>
        </div>
    </div>

    <!-- البحث والفلترة -->
    <div class="bg-gray-50 p-4 rounded-lg shadow-sm mb-6">
        <form action="{{ route('admin.users.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">بحث</label>
                <div class="relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="ابحث بالاسم أو البريد الإلكتروني" class="block w-full px-10 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>
            </div>
            <div>
                <label for="role" class="block text-sm font-medium text-gray-700 mb-1">الصلاحية</label>
                <select name="role" id="role" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <option value="">جميع المستخدمين</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>المشرفين فقط</option>
                    <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>المستخدمين العاديين</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    بحث
                </button>
            </div>
        </form>
    </div>

    <!-- جدول المستخدمين -->
    <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 rounded-lg">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الاسم</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">البريد الإلكتروني</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الصلاحيات</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">تاريخ الإنشاء</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($users ?? [] as $user)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $loop->iteration }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-gradient-to-r from-blue-500 to-indigo-600 flex items-center justify-center">
                                        <span class="text-white font-medium text-sm">{{ substr($user->name ?? '', 0, 2) }}</span>
                                    </div>
                                </div>
                                <div class="mr-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $user->id === auth()->id() ? '(أنت)' : '' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $user->email }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            @if($user->is_admin)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    مشرف النظام
                                </span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                    مستخدم عادي
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <div class="text-sm text-gray-500">
                                {{ $user->created_at->format('Y-m-d') }}
                                <div class="text-xs text-gray-400">{{ $user->created_at->diffForHumans() }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <div class="flex space-x-2 space-x-reverse">
                                <a href="{{ route('admin.users.show', $user->id) }}" class="inline-flex items-center text-white bg-indigo-600 hover:bg-indigo-800 px-2 py-1 text-xs rounded-md">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    عرض
                                </a>
                                @if(auth()->user()->is_admin)
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="inline-flex items-center text-white bg-blue-600 hover:bg-blue-800 px-2 py-1 text-xs rounded-md mr-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    تعديل
                                </a>
                                
                                @if(auth()->id() !== $user->id)
                                <form action="{{ route('admin.users.toggle-admin', $user->id) }}" method="POST" class="inline mx-1">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="inline-flex items-center text-white {{ $user->is_admin ? 'bg-yellow-600 hover:bg-yellow-800' : 'bg-green-600 hover:bg-green-800' }} px-2 py-1 text-xs rounded-md">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                        </svg>
                                        {{ $user->is_admin ? 'إلغاء المشرف' : 'جعله مشرف' }}
                                    </button>
                                </form>
                                @endif
                                
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" onclick="confirmDelete('{{ $user->name }}', this.form)" class="inline-flex items-center text-white bg-red-600 hover:bg-red-800 px-2 py-1 text-xs rounded-md">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        حذف
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center py-6">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                <p class="text-gray-500 font-medium">لا يوجد مستخدمين متطابقين مع البحث</p>
                                <p class="text-gray-400 text-sm mt-1">جرب تغيير معايير البحث أو إعادة تعيين الفلتر</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if(isset($users) && method_exists($users, 'links'))
        <div class="px-4 py-3 bg-gray-50 border-t border-gray-200 sm:px-6">
            {{ $users->withQueryString()->links() }}
        </div>
        @endif
    </div>
</div>

<!-- مودال تأكيد الحذف -->
<div id="delete-modal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
        <div class="p-5 border-b border-gray-200">
            <h3 class="text-lg font-bold text-gray-800 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                تأكيد الحذف
            </h3>
        </div>
        <div class="p-5">
            <p class="text-gray-600">هل أنت متأكد أنك تريد حذف المستخدم <span id="user-name" class="font-bold text-red-600"></span>؟</p>
            <p class="text-gray-500 text-sm mt-2">هذا الإجراء لا يمكن التراجع عنه.</p>
        </div>
        <div class="p-5 border-t border-gray-200 flex justify-between">
            <button id="cancel-delete" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none">
                إلغاء
            </button>
            <button id="confirm-delete" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
                نعم، حذف المستخدم
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let currentForm = null;

function confirmDelete(name, form) {
    currentForm = form;
    document.getElementById('user-name').textContent = name;
    document.getElementById('delete-modal').classList.remove('hidden');
}

document.getElementById('cancel-delete').addEventListener('click', function() {
    document.getElementById('delete-modal').classList.add('hidden');
    currentForm = null;
});

document.getElementById('confirm-delete').addEventListener('click', function() {
    if (currentForm) {
        currentForm.submit();
    }
});

document.getElementById('delete-modal').addEventListener('click', function(e) {
    if (e.target === this) {
        this.classList.add('hidden');
        currentForm = null;
    }
});
</script>
@endpush 