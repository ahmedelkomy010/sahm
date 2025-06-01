<?php

namespace Database\Seeders;

use App\Models\WorkItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WorkItemsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $workItems = [
            [
                'code' => 'WI001',
                'description' => 'حفر خنادق للكابلات الأرضية',
                'unit' => 'متر طولي',
                'notes' => 'حفر خنادق بعمق 80 سم وعرض 40 سم'
            ],
            [
                'code' => 'WI002', 
                'description' => 'تركيب كابلات كهربائية أرضية',
                'unit' => 'متر طولي',
                'notes' => 'تركيب كابلات بجهد متوسط أو منخفض'
            ],
            [
                'code' => 'WI003',
                'description' => 'تركيب عمود إنارة معدني',
                'unit' => 'عدد',
                'notes' => 'عمود معدني بارتفاع 10-14 متر'
            ],
            [
                'code' => 'WI004',
                'description' => 'تركيب محول كهربائي أرضي',
                'unit' => 'عدد', 
                'notes' => 'محول بقدرة 500-1500 كيلو فولت أمبير'
            ],
            [
                'code' => 'WI005',
                'description' => 'تركيب صندوق عداد كهربائي',
                'unit' => 'عدد',
                'notes' => 'صندوق مفرد أو مزدوج أو رباعي'
            ],
            [
                'code' => 'WI006',
                'description' => 'أعمال الردم والرصف',
                'unit' => 'متر مربع',
                'notes' => 'ردم الخنادق وإعادة الرصف'
            ],
            [
                'code' => 'WI007',
                'description' => 'تركيب لوحة توزيع كهربائية',
                'unit' => 'عدد',
                'notes' => 'لوحة جهد منخفض أو متوسط'
            ],
            [
                'code' => 'WI008',
                'description' => 'تركيب نظام تأريض',
                'unit' => 'نظام',
                'notes' => 'نظام تأريض كامل للمحطة'
            ],
            [
                'code' => 'WI009',
                'description' => 'تركيب كابل هوائي',
                'unit' => 'متر طولي',
                'notes' => 'كابل هوائي معزول'
            ],
            [
                'code' => 'WI010',
                'description' => 'أعمال اختبار وتشغيل',
                'unit' => 'نظام',
                'notes' => 'اختبار النظام الكهربائي وتشغيله'
            ]
        ];

        foreach ($workItems as $item) {
            WorkItem::create($item);
        }
    }
}
