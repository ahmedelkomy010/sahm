<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class FixInvoiceAttachmentsTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:invoice-attachments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix invoice_attachments table migration status';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            // التحقق من وجود الجدول
            if (Schema::hasTable('invoice_attachments')) {
                $this->info('جدول invoice_attachments موجود بالفعل');
                
                // التحقق من سجل الهجرة
                $migrationExists = DB::table('migrations')
                    ->where('migration', '2025_05_20_000005_create_invoice_attachments_table')
                    ->exists();
                
                if (!$migrationExists) {
                    // إضافة سجل الهجرة
                    DB::table('migrations')->insert([
                        'migration' => '2025_05_20_000005_create_invoice_attachments_table',
                        'batch' => 2
                    ]);
                    $this->info('تم إضافة سجل الهجرة بنجاح');
                } else {
                    $this->info('سجل الهجرة موجود بالفعل');
                }
            } else {
                // إنشاء الجدول
                Schema::create('invoice_attachments', function ($table) {
                    $table->id();
                    $table->foreignId('work_order_id')->constrained()->onDelete('cascade');
                    $table->string('file_path');
                    $table->string('original_filename');
                    $table->string('file_type')->nullable();
                    $table->text('description')->nullable();
                    $table->timestamps();
                });
                
                // إضافة سجل الهجرة
                DB::table('migrations')->insert([
                    'migration' => '2025_05_20_000005_create_invoice_attachments_table',
                    'batch' => 2
                ]);
                
                $this->info('تم إنشاء جدول invoice_attachments وإضافة سجل الهجرة بنجاح');
            }
            
            return 0;
        } catch (\Exception $e) {
            $this->error('خطأ: ' . $e->getMessage());
            return 1;
        }
    }
}
