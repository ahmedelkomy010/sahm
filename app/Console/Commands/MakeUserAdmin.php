<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class MakeUserAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:make-admin {email : البريد الإلكتروني للمستخدم}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'جعل المستخدم مديرًا للنظام عن طريق البريد الإلكتروني';

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
        
        $this->info("تم جعل المستخدم {$user->name} مديرًا للنظام بنجاح!");
        
        return 0;
    }
} 