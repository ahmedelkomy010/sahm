<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class MakeAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:admin {email?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'جعل مستخدم مشرفاً من خلال البريد الإلكتروني';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        
        if (!$email) {
            $email = $this->ask('أدخل البريد الإلكتروني للمستخدم الذي تريد جعله مشرفاً:');
        }
        
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $this->error("لم يتم العثور على مستخدم بالبريد الإلكتروني: $email");
            return 1;
        }
        
        $user->is_admin = true;
        $user->save();
        
        $this->info("تم جعل المستخدم {$user->name} مشرفاً بنجاح!");
        return 0;
    }
} 