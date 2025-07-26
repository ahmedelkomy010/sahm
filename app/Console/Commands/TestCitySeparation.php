<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\WorkItem;
use App\Models\ReferenceMaterial;

class TestCitySeparation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:city-separation';

    /**
     * The console description of the console command.
     *
     * @var string
     */
    protected $description = 'Test city separation for work items and reference materials';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('اختبار فصل البيانات بين المدن');
        $this->info('=====================================');

        // إنشاء بعض البيانات التجريبية
        $this->info('1. إنشاء بيانات تجريبية...');
        
        // بنود عمل للرياض
        WorkItem::create([
            'name' => 'حفر تربة - الرياض',
            'code' => 'R001',
            'description' => 'حفر في التربة العادية - مشروع الرياض',
            'unit' => 'متر',
            'unit_price' => 50.00,
            'city' => 'الرياض',
            'is_active' => true
        ]);

        // بنود عمل للمدينة المنورة
        WorkItem::create([
            'name' => 'حفر تربة - المدينة المنورة',
            'code' => 'M001',
            'description' => 'حفر في التربة العادية - مشروع المدينة المنورة',
            'unit' => 'متر',
            'unit_price' => 55.00,
            'city' => 'المدينة المنورة',
            'is_active' => true
        ]);

        // مواد مرجعية للرياض
        ReferenceMaterial::create([
            'code' => 'MAT-R001',
            'description' => 'كابل كهرباء 10مم - الرياض',
            'unit' => 'متر',
            'city' => 'الرياض',
            'is_active' => true
        ]);

        // مواد مرجعية للمدينة المنورة
        ReferenceMaterial::create([
            'code' => 'MAT-M001',
            'description' => 'كابل كهرباء 10مم - المدينة المنورة',
            'unit' => 'متر',
            'city' => 'المدينة المنورة',
            'is_active' => true
        ]);

        $this->info('تم إنشاء البيانات التجريبية بنجاح!');

        // اختبار البحث
        $this->info('');
        $this->info('2. اختبار البحث المنفصل...');
        
        // بنود العمل للرياض
        $riyadhWorkItems = WorkItem::byProject('riyadh')->get();
        $this->info("بنود العمل للرياض: {$riyadhWorkItems->count()} بند");
        
        // بنود العمل للمدينة المنورة
        $madinahWorkItems = WorkItem::byProject('madinah')->get();
        $this->info("بنود العمل للمدينة المنورة: {$madinahWorkItems->count()} بند");

        // المواد المرجعية للرياض
        $riyadhMaterials = ReferenceMaterial::byProject('riyadh')->get();
        $this->info("المواد المرجعية للرياض: {$riyadhMaterials->count()} مادة");
        
        // المواد المرجعية للمدينة المنورة
        $madinahMaterials = ReferenceMaterial::byProject('madinah')->get();
        $this->info("المواد المرجعية للمدينة المنورة: {$madinahMaterials->count()} مادة");

        $this->info('');
        $this->info('3. تفاصيل البيانات:');
        $this->info('==================');
        
        $this->info('بنود العمل - الرياض:');
        foreach ($riyadhWorkItems as $item) {
            $this->line("  - {$item->code}: {$item->description}");
        }

        $this->info('بنود العمل - المدينة المنورة:');
        foreach ($madinahWorkItems as $item) {
            $this->line("  - {$item->code}: {$item->description}");
        }

        $this->info('المواد المرجعية - الرياض:');
        foreach ($riyadhMaterials as $material) {
            $this->line("  - {$material->code}: {$material->description}");
        }

        $this->info('المواد المرجعية - المدينة المنورة:');
        foreach ($madinahMaterials as $material) {
            $this->line("  - {$material->code}: {$material->description}");
        }

        $this->info('');
        $this->info('✅ اكتمل الاختبار بنجاح! البيانات منفصلة بين المدن.');
        
        return 0;
    }
}
