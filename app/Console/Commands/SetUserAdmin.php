<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class SetUserAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:admin {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'تعيين مستخدم كمشرف عن طريق البريد الإلكتروني';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $this->error("المستخدم بالبريد الإلكتروني {$email} غير موجود!");
            return 1;
        }
        
        $user->is_admin = true;
        $user->save();
        
        $this->info("تم تعيين المستخدم {$user->name} ({$email}) كمشرف بنجاح!");
        
        return 0;
    }
} 