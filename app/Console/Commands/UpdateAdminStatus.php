<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UpdateAdminStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:admin-status {email?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'تحديث حالة المشرف للمستخدمين الحاليين وإصلاح مشاكل البيانات';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        
        if ($email) {
            // تحديث مستخدم محدد
            $user = User::where('email', $email)->first();
            
            if (!$user) {
                $this->error("لم يتم العثور على مستخدم بالبريد الإلكتروني: $email");
                return 1;
            }
            
            $user->is_admin = 1;
            $user->save();
            
            $this->info("تم تحديث حالة المستخدم {$user->name} ليكون مشرفاً بنجاح!");
            
        } else {
            // إصلاح مشاكل عمود is_admin
            $this->info("جاري فحص وإصلاح عمود is_admin...");
            
            // تحويل القيم غير الصحيحة إلى أصفار
            $nullUpdated = DB::table('users')
                ->whereNull('is_admin')
                ->update(['is_admin' => 0]);
            
            $this->info("تم تحديث $nullUpdated سجل من القيم الفارغة إلى 0");
            
            // تحويل true إلى 1 (في حالة استخدام boolean)
            $trueUpdated = DB::table('users')
                ->where('is_admin', true)
                ->update(['is_admin' => 1]);
                
            $this->info("تم تحديث $trueUpdated سجل من القيم true إلى 1");
            
            // التأكد من وجود مشرف واحد على الأقل
            $adminsCount = User::where('is_admin', 1)->count();
            
            if ($adminsCount == 0) {
                $firstUser = User::first();
                if ($firstUser) {
                    $firstUser->is_admin = 1;
                    $firstUser->save();
                    $this->info("تم تعيين المستخدم {$firstUser->name} كمشرف افتراضي");
                } else {
                    $this->warn("لا يوجد مستخدمين في النظام!");
                }
            } else {
                $this->info("يوجد $adminsCount مشرف في النظام");
            }
        }
        
        $this->info("تم الانتهاء بنجاح!");
        return 0;
    }
}
