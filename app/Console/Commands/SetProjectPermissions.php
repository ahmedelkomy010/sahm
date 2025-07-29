<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class SetProjectPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permissions:set-project {--admin-id= : ID المدير المحدد} {--all-admins : تعيين الصلاحيات لجميع المدراء}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'تعيين صلاحيات المشاريع للمدراء';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $adminId = $this->option('admin-id');
        $allAdmins = $this->option('all-admins');

        // صلاحيات المشاريع الكاملة
        $projectPermissions = [
            'access_unified_contracts',
            'access_turnkey_projects', 
            'access_special_projects'
        ];

        if ($adminId) {
            // تعيين الصلاحيات لمدير محدد
            $admin = User::find($adminId);
            if (!$admin) {
                $this->error("لم يتم العثور على المستخدم بالمعرف: {$adminId}");
                return 1;
            }

            if (!$admin->isAdmin()) {
                $this->error("المستخدم المحدد ليس مدير: {$admin->name}");
                return 1;
            }

            $currentPermissions = $admin->permissions ?? [];
            $newPermissions = array_unique(array_merge($currentPermissions, $projectPermissions));
            
            $admin->permissions = $newPermissions;
            $admin->save();

            $this->info("تم تعيين صلاحيات المشاريع للمدير: {$admin->name}");
            
        } elseif ($allAdmins) {
            // تعيين الصلاحيات لجميع المدراء
            $admins = User::where('is_admin', 1)->get();
            
            if ($admins->isEmpty()) {
                $this->error('لم يتم العثور على أي مدراء في النظام');
                return 1;
            }

            $count = 0;
            foreach ($admins as $admin) {
                $currentPermissions = $admin->permissions ?? [];
                $newPermissions = array_unique(array_merge($currentPermissions, $projectPermissions));
                
                $admin->permissions = $newPermissions;
                $admin->save();
                $count++;
            }

            $this->info("تم تعيين صلاحيات المشاريع لـ {$count} مدير");
            
        } else {
            // عرض قائمة المدراء للاختيار
            $admins = User::where('is_admin', 1)->get();
            
            if ($admins->isEmpty()) {
                $this->error('لم يتم العثور على أي مدراء في النظام');
                return 1;
            }

            $this->info('المدراء الموجودون في النظام:');
            $this->table(
                ['ID', 'الاسم', 'البريد الإلكتروني', 'صلاحيات المشاريع الحالية'],
                $admins->map(function ($admin) use ($projectPermissions) {
                    $currentProjectPermissions = array_intersect($admin->permissions ?? [], $projectPermissions);
                    return [
                        $admin->id,
                        $admin->name,
                        $admin->email,
                        empty($currentProjectPermissions) ? 'لا توجد' : implode(', ', $currentProjectPermissions)
                    ];
                })->toArray()
            );

            $this->info('لتعيين الصلاحيات لمدير محدد، استخدم: --admin-id=ID');
            $this->info('لتعيين الصلاحيات لجميع المدراء، استخدم: --all-admins');
        }

        return 0;
    }
}
