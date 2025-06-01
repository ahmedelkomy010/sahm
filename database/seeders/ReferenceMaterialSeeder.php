<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ReferenceMaterial;

class ReferenceMaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $materials = [
            // أنابيب ومواسير
            ['code' => '908112050', 'description' => 'CABLE,PWR,600V/1KV,QUADRUPLEX,3X50+1X50'],
            ['code' => '908112120', 'description' => 'CABLE,PWR,600V/1KV,QUADRUPLEX,3X120MM2'],
            ['code' => '908101001', 'description' => 'COND,BR,ACSR,QUAIL (2/0 AWG),6AL,1ALWLD'],
            ['code' => '908101002', 'description' => 'COND,BR,ACSR,MERLIN(336.4MCM)18AL,170MM2'],
            ['code' => '908111101', 'description' => 'CNDCTR,BR,CU,35SQMM,7STR,SOFT DRWN'],
            ['code' => '908111102', 'description' => 'CNDCTR,BR,CU,50SQMM,7STR,SOFT DRWN'],
            ['code' => '908111103', 'description' => 'CNDCTR,BR,CU,70SQMM,7STR,SOFT DRWN'],
            ['code' => '908111104', 'description' => 'CNDCTR,BR,CU,95SQMM,7STR,SOFT DRWN'],
            ['code' => '908111105', 'description' => 'CNDCTR,BR,CU,120SQMM,7STR,SOFT DRWN'],
            ['code' => '908111106', 'description' => 'CNDCTR,BR,CU,150SQMM,7STR,SOFT DRWN'],
            ['code' => '908111107', 'description' => 'CNDCTR,BR,CU,185SQMM,7STR,SOFT DRWN'],
            ['code' => '908111108', 'description' => 'CNDCTR,BR,CU,240SQMM,7STR,SOFT DRWN'],
            ['code' => '908111109', 'description' => 'CNDCTR,BR,CU,300SQMM,7STR,SOFT DRWN'],
            ['code' => '908111110', 'description' => 'CNDCTR,BR,CU,400SQMM,7STR,SOFT DRWN'],
            ['code' => '908113101', 'description' => 'CNDCTR,INSUL,XLPE,CU,1C,50MM2,0.6/1KV'],
            ['code' => '908113102', 'description' => 'CNDCTR,INSUL,XLPE,CU,1C,70MM2,0.6/1KV'],
            ['code' => '908113103', 'description' => 'CNDCTR,INSUL,XLPE,CU,1C,95MM2,0.6/1KV'],
            ['code' => '908113104', 'description' => 'CNDCTR,INSUL,XLPE,CU,1C,120MM2,0.6/1KV'],
            ['code' => '908113105', 'description' => 'CNDCTR,INSUL,XLPE,CU,1C,150MM2,0.6/1KV'],
            ['code' => '908113106', 'description' => 'CNDCTR,INSUL,XLPE,CU,1C,185MM2,0.6/1KV'],
            ['code' => '908113107', 'description' => 'CNDCTR,INSUL,XLPE,CU,1C,240MM2,0.6/1KV'],
            ['code' => '908113108', 'description' => 'CNDCTR,INSUL,XLPE,CU,1C,300MM2,0.6/1KV'],
            ['code' => '908113109', 'description' => 'CNDCTR,INSUL,XLPE,CU,1C,400MM2,0.6/1KV'],
            ['code' => '908113110', 'description' => 'CNDCTR,INSUL,XLPE,CU,1C,500MM2,0.6/1KV'],
            ['code' => '908113111', 'description' => 'CNDCTR,INSUL,XLPE,CU,1C,630MM2,0.6/1KV'],
            ['code' => '908113112', 'description' => 'CNDCTR,INSUL,XLPE,CU,1C,800MM2,0.6/1KV'],
            ['code' => '908113113', 'description' => 'CNDCTR,INSUL,XLPE,CU,1C,1000MM2,0.6/1KV'],
            ['code' => '908115001', 'description' => 'CNDCTR,CU,1C,10MM2,XLPE,0.6/1KV,YELLOW/GREEN'],
            ['code' => '908115002', 'description' => 'CNDCTR,CU,1C,16MM2,XLPE,0.6/1KV,YELLOW/GREEN'],
            ['code' => '908115003', 'description' => 'CNDCTR,CU,1C,25MM2,XLPE,0.6/1KV,YELLOW/GREEN'],
            ['code' => '908115004', 'description' => 'CNDCTR,CU,1C,35MM2,XLPE,0.6/1KV,YELLOW/GREEN'],
            ['code' => '908115005', 'description' => 'CNDCTR,CU,1C,50MM2,XLPE,0.6/1KV,YELLOW/GREEN'],
            ['code' => '908115006', 'description' => 'CNDCTR,CU,1C,70MM2,XLPE,0.6/1KV,YELLOW/GREEN'],
            ['code' => '908115007', 'description' => 'CNDCTR,CU,1C,95MM2,XLPE,0.6/1KV,YELLOW/GREEN'],
            ['code' => '908115008', 'description' => 'CNDCTR,CU,1C,120MM2,XLPE,0.6/1KV,YELLOW/GREEN'],
            ['code' => '908115009', 'description' => 'CNDCTR,CU,1C,150MM2,XLPE,0.6/1KV,YELLOW/GREEN'],
            ['code' => '908115010', 'description' => 'CNDCTR,CU,1C,185MM2,XLPE,0.6/1KV,YELLOW/GREEN'],
            ['code' => '908115011', 'description' => 'CNDCTR,CU,1C,240MM2,XLPE,0.6/1KV,YELLOW/GREEN'],
            ['code' => '908115012', 'description' => 'CNDCTR,CU,1C,300MM2,XLPE,0.6/1KV,YELLOW/GREEN'],
            ['code' => '908115013', 'description' => 'CNDCTR,CU,1C,400MM2,XLPE,0.6/1KV,YELLOW/GREEN'],
            ['code' => '908115014', 'description' => 'CNDCTR,CU,1C,500MM2,XLPE,0.6/1KV,YELLOW/GREEN'],
            ['code' => '908115015', 'description' => 'CNDCTR,CU,1C,630MM2,XLPE,0.6/1KV,YELLOW/GREEN'],
            ['code' => '908115016', 'description' => 'CNDCTR,CU,1C,800MM2,XLPE,0.6/1KV,YELLOW/GREEN'],
            ['code' => '908115017', 'description' => 'CNDCTR,CU,1C,1000MM2,XLPE,0.6/1KV,YELLOW/GREEN'],
            ['code' => '908117001', 'description' => 'CNDCTR,CU,1C,2.5MM2,XLPE,0.6/1KV,BLACK'],
            ['code' => '908117002', 'description' => 'CNDCTR,CU,1C,4MM2,XLPE,0.6/1KV,BLACK'],
            ['code' => '908117003', 'description' => 'CNDCTR,CU,1C,6MM2,XLPE,0.6/1KV,BLACK'],
            ['code' => '908117004', 'description' => 'CNDCTR,CU,1C,10MM2,XLPE,0.6/1KV,BLACK'],
            ['code' => '908117005', 'description' => 'CNDCTR,CU,1C,16MM2,XLPE,0.6/1KV,BLACK'],
            ['code' => '908117006', 'description' => 'CNDCTR,CU,1C,25MM2,XLPE,0.6/1KV,BLACK'],
            ['code' => '908117007', 'description' => 'CNDCTR,CU,1C,35MM2,XLPE,0.6/1KV,BLACK'],
            ['code' => '908117008', 'description' => 'CNDCTR,CU,1C,50MM2,XLPE,0.6/1KV,BLACK'],
            ['code' => '908117009', 'description' => 'CNDCTR,CU,1C,70MM2,XLPE,0.6/1KV,BLACK'],
            ['code' => '908117010', 'description' => 'CNDCTR,CU,1C,95MM2,XLPE,0.6/1KV,BLACK'],
            ['code' => '908117011', 'description' => 'CNDCTR,CU,1C,120MM2,XLPE,0.6/1KV,BLACK'],
            ['code' => '908117012', 'description' => 'CNDCTR,CU,1C,150MM2,XLPE,0.6/1KV,BLACK'],
            ['code' => '908117013', 'description' => 'CNDCTR,CU,1C,185MM2,XLPE,0.6/1KV,BLACK'],
            ['code' => '908117014', 'description' => 'CNDCTR,CU,1C,240MM2,XLPE,0.6/1KV,BLACK'],
            ['code' => '908117015', 'description' => 'CNDCTR,CU,1C,300MM2,XLPE,0.6/1KV,BLACK'],
            ['code' => '908117016', 'description' => 'CNDCTR,CU,1C,400MM2,XLPE,0.6/1KV,BLACK'],
            ['code' => '908117017', 'description' => 'CNDCTR,CU,1C,500MM2,XLPE,0.6/1KV,BLACK'],
            ['code' => '908117018', 'description' => 'CNDCTR,CU,1C,630MM2,XLPE,0.6/1KV,BLACK'],
            ['code' => '908117019', 'description' => 'CNDCTR,CU,1C,800MM2,XLPE,0.6/1KV,BLACK'],
            ['code' => '908117020', 'description' => 'CNDCTR,CU,1C,1000MM2,XLPE,0.6/1KV,BLACK'],
            ['code' => '908119001', 'description' => 'CNDCTR,CU,1C,2.5MM2,XLPE,0.6/1KV,RED'],
            ['code' => '908119002', 'description' => 'CNDCTR,CU,1C,4MM2,XLPE,0.6/1KV,RED'],
            ['code' => '908119003', 'description' => 'CNDCTR,CU,1C,6MM2,XLPE,0.6/1KV,RED'],
            ['code' => '908119004', 'description' => 'CNDCTR,CU,1C,10MM2,XLPE,0.6/1KV,RED'],
            ['code' => '908119005', 'description' => 'CNDCTR,CU,1C,16MM2,XLPE,0.6/1KV,RED'],
            ['code' => '908119006', 'description' => 'CNDCTR,CU,1C,25MM2,XLPE,0.6/1KV,RED'],
            ['code' => '908119007', 'description' => 'CNDCTR,CU,1C,35MM2,XLPE,0.6/1KV,RED'],
            ['code' => '908119008', 'description' => 'CNDCTR,CU,1C,50MM2,XLPE,0.6/1KV,RED'],
            ['code' => '908119009', 'description' => 'CNDCTR,CU,1C,70MM2,XLPE,0.6/1KV,RED'],
            ['code' => '908119010', 'description' => 'CNDCTR,CU,1C,95MM2,XLPE,0.6/1KV,RED'],
            ['code' => '908119011', 'description' => 'CNDCTR,CU,1C,120MM2,XLPE,0.6/1KV,RED'],
            ['code' => '908119012', 'description' => 'CNDCTR,CU,1C,150MM2,XLPE,0.6/1KV,RED'],
            ['code' => '908119013', 'description' => 'CNDCTR,CU,1C,185MM2,XLPE,0.6/1KV,RED'],
            ['code' => '908119014', 'description' => 'CNDCTR,CU,1C,240MM2,XLPE,0.6/1KV,RED'],
            ['code' => '908119015', 'description' => 'CNDCTR,CU,1C,300MM2,XLPE,0.6/1KV,RED'],
            ['code' => '908119016', 'description' => 'CNDCTR,CU,1C,400MM2,XLPE,0.6/1KV,RED'],
            ['code' => '908119017', 'description' => 'CNDCTR,CU,1C,500MM2,XLPE,0.6/1KV,RED'],
            ['code' => '908119018', 'description' => 'CNDCTR,CU,1C,630MM2,XLPE,0.6/1KV,RED'],
            ['code' => '908119019', 'description' => 'CNDCTR,CU,1C,800MM2,XLPE,0.6/1KV,RED'],
            ['code' => '908119020', 'description' => 'CNDCTR,CU,1C,1000MM2,XLPE,0.6/1KV,RED'],
            ['code' => '908121001', 'description' => 'CNDCTR,CU,1C,2.5MM2,XLPE,0.6/1KV,YELLOW'],
            ['code' => '908121002', 'description' => 'CNDCTR,CU,1C,4MM2,XLPE,0.6/1KV,YELLOW'],
            ['code' => '908121003', 'description' => 'CNDCTR,CU,1C,6MM2,XLPE,0.6/1KV,YELLOW'],
            ['code' => '908121004', 'description' => 'CNDCTR,CU,1C,10MM2,XLPE,0.6/1KV,YELLOW'],
            ['code' => '908121005', 'description' => 'CNDCTR,CU,1C,16MM2,XLPE,0.6/1KV,YELLOW'],
            ['code' => '908121006', 'description' => 'CNDCTR,CU,1C,25MM2,XLPE,0.6/1KV,YELLOW'],
            ['code' => '908121007', 'description' => 'CNDCTR,CU,1C,35MM2,XLPE,0.6/1KV,YELLOW'],
            ['code' => '908121008', 'description' => 'CNDCTR,CU,1C,50MM2,XLPE,0.6/1KV,YELLOW'],
            ['code' => '908121009', 'description' => 'CNDCTR,CU,1C,70MM2,XLPE,0.6/1KV,YELLOW'],
            ['code' => '908121010', 'description' => 'CNDCTR,CU,1C,95MM2,XLPE,0.6/1KV,YELLOW'],
            ['code' => '908121011', 'description' => 'CNDCTR,CU,1C,120MM2,XLPE,0.6/1KV,YELLOW'],
            ['code' => '908121012', 'description' => 'CNDCTR,CU,1C,150MM2,XLPE,0.6/1KV,YELLOW'],
            ['code' => '908121013', 'description' => 'CNDCTR,CU,1C,185MM2,XLPE,0.6/1KV,YELLOW'],
            ['code' => '908121014', 'description' => 'CNDCTR,CU,1C,240MM2,XLPE,0.6/1KV,YELLOW'],
            ['code' => '908121015', 'description' => 'CNDCTR,CU,1C,300MM2,XLPE,0.6/1KV,YELLOW'],
            ['code' => '908121016', 'description' => 'CNDCTR,CU,1C,400MM2,XLPE,0.6/1KV,YELLOW'],
            ['code' => '908121017', 'description' => 'CNDCTR,CU,1C,500MM2,XLPE,0.6/1KV,YELLOW'],
            ['code' => '908121018', 'description' => 'CNDCTR,CU,1C,630MM2,XLPE,0.6/1KV,YELLOW'],
            ['code' => '908121019', 'description' => 'CNDCTR,CU,1C,800MM2,XLPE,0.6/1KV,YELLOW'],
            ['code' => '908121020', 'description' => 'CNDCTR,CU,1C,1000MM2,XLPE,0.6/1KV,YELLOW'],
            ['code' => '908122001', 'description' => 'CNDCTR,CU,1C,2.5MM2,XLPE,0.6/1KV,BLUE'],
            ['code' => '908122002', 'description' => 'CNDCTR,CU,1C,4MM2,XLPE,0.6/1KV,BLUE'],
            ['code' => '908122003', 'description' => 'CNDCTR,CU,1C,6MM2,XLPE,0.6/1KV,BLUE'],
            ['code' => '908122004', 'description' => 'CNDCTR,CU,1C,10MM2,XLPE,0.6/1KV,BLUE'],
            ['code' => '908122005', 'description' => 'CNDCTR,CU,1C,16MM2,XLPE,0.6/1KV,BLUE'],
            ['code' => '908122006', 'description' => 'CNDCTR,CU,1C,25MM2,XLPE,0.6/1KV,BLUE'],
            ['code' => '908122007', 'description' => 'CNDCTR,CU,1C,35MM2,XLPE,0.6/1KV,BLUE'],
            ['code' => '908122008', 'description' => 'CNDCTR,CU,1C,50MM2,XLPE,0.6/1KV,BLUE'],
            ['code' => '908122009', 'description' => 'CNDCTR,CU,1C,70MM2,XLPE,0.6/1KV,BLUE'],
            ['code' => '908122010', 'description' => 'CNDCTR,CU,1C,95MM2,XLPE,0.6/1KV,BLUE'],
            ['code' => '908122011', 'description' => 'CNDCTR,CU,1C,120MM2,XLPE,0.6/1KV,BLUE'],
            ['code' => '908122012', 'description' => 'CNDCTR,CU,1C,150MM2,XLPE,0.6/1KV,BLUE'],
            ['code' => '908122013', 'description' => 'CNDCTR,CU,1C,185MM2,XLPE,0.6/1KV,BLUE'],
            ['code' => '908122014', 'description' => 'CNDCTR,CU,1C,240MM2,XLPE,0.6/1KV,BLUE'],
            ['code' => '908122015', 'description' => 'CNDCTR,CU,1C,300MM2,XLPE,0.6/1KV,BLUE'],
            ['code' => '908122016', 'description' => 'CNDCTR,CU,1C,400MM2,XLPE,0.6/1KV,BLUE'],
            ['code' => '908122017', 'description' => 'CNDCTR,CU,1C,500MM2,XLPE,0.6/1KV,BLUE'],
            ['code' => '908122018', 'description' => 'CNDCTR,CU,1C,630MM2,XLPE,0.6/1KV,BLUE'],
            ['code' => '908122019', 'description' => 'CNDCTR,CU,1C,800MM2,XLPE,0.6/1KV,BLUE'],
            ['code' => '908122020', 'description' => 'CNDCTR,CU,1C,1000MM2,XLPE,0.6/1KV,BLUE'],
            
            // محابس ووصلات
            ['code' => '908122020', 'description' => 'محبس إغلاق نحاسي 1/2 بوصة'],
            ['code' => 'VALVE002', 'description' => 'محبس إغلاق نحاسي 3/4 بوصة'],
            ['code' => 'VALVE003', 'description' => 'محبس إغلاق نحاسي 1 بوصة'],
            ['code' => 'VALVE004', 'description' => 'محبس كروي PVC قطر 50 مم'],
            ['code' => 'VALVE005', 'description' => 'محبس كروي PVC قطر 75 مم'],
            ['code' => 'VALVE006', 'description' => 'محبس تحكم بالضغط 1 بوصة'],
            
            // وصلات
            ['code' => 'FITTING001', 'description' => 'وصلة T شكل PVC قطر 50 مم'],
            ['code' => 'FITTING002', 'description' => 'وصلة T شكل PVC قطر 75 مم'],
            ['code' => 'FITTING003', 'description' => 'وصلة كوع 90 درجة PVC قطر 50 مم'],
            ['code' => 'FITTING004', 'description' => 'وصلة كوع 90 درجة PVC قطر 75 مم'],
            ['code' => 'FITTING005', 'description' => 'وصلة ربط مباشر PVC قطر 50 مم'],
            ['code' => 'FITTING006', 'description' => 'وصلة تخفيض PVC من 75 إلى 50 مم'],
            
            // مواد بناء
            ['code' => 'CEMENT001', 'description' => 'أسمنت بورتلاندي عادي 50 كيلو'],
            ['code' => 'CEMENT002', 'description' => 'أسمنت مقاوم للكبريتات 50 كيلو'],
            ['code' => 'SAND001', 'description' => 'رمل نظيف مدرج للخرسانة'],
            ['code' => 'GRAVEL001', 'description' => 'حصى مدرج للخرسانة حجم 20 مم'],
            ['code' => 'GRAVEL002', 'description' => 'حصى مدرج للخرسانة حجم 40 مم'],
            
            // مواد عزل
            ['code' => 'INSUL001', 'description' => 'شريط عزل كهربائي 19 مم'],
            ['code' => 'INSUL002', 'description' => 'مادة عزل بيتومين للأنابيب'],
            ['code' => 'INSUL003', 'description' => 'عزل حراري للأنابيب الساخنة'],
            
            // مواد تشطيب
            ['code' => 'TILE001', 'description' => 'بلاط سيراميك للأرضيات 30×30 سم'],
            ['code' => 'TILE002', 'description' => 'بلاط سيراميك للحوائط 20×30 سم'],
            ['code' => 'PAINT001', 'description' => 'دهان إيبوكسي للمعادن'],
            ['code' => 'PAINT002', 'description' => 'دهان أكريليك للحوائط الداخلية'],
            
            // مواد كهربائية
            ['code' => 'CABLE001', 'description' => 'كابل كهربائي 2.5 مم مرن'],
            ['code' => 'CABLE002', 'description' => 'كابل كهربائي 4 مم مرن'],
            ['code' => 'CABLE003', 'description' => 'كابل كهربائي 6 مم مرن'],
            ['code' => 'SWITCH001', 'description' => 'مفتاح كهربائي أحادي'],
            ['code' => 'SOCKET001', 'description' => 'مقبس كهربائي ثلاثي'],
            
            // مواد تسليح
            ['code' => 'STEEL001', 'description' => 'حديد تسليح قطر 8 مم'],
            ['code' => 'STEEL002', 'description' => 'حديد تسليح قطر 10 مم'],
            ['code' => 'STEEL003', 'description' => 'حديد تسليح قطر 12 مم'],
            ['code' => 'STEEL004', 'description' => 'حديد تسليح قطر 16 مم'],
            ['code' => 'STEEL005', 'description' => 'حديد تسليح قطر 20 مم'],
            
            // مواد أخرى
            ['code' => 'MISC001', 'description' => 'شبكة معدنية للحماية'],
            ['code' => 'MISC002', 'description' => 'مانع تسرب سيليكون شفاف'],
            ['code' => 'MISC003', 'description' => 'لاصق PVC للأنابيب'],
            ['code' => 'MISC004', 'description' => 'شريط تحذير للأنابيب المدفونة'],
            ['code' => 'MISC005', 'description' => 'مادة مطهرة لخزانات المياه'],
        ];

        foreach ($materials as $material) {
            ReferenceMaterial::updateOrCreate(
                ['code' => $material['code']],
                ['description' => $material['description']]
            );
        }
    }
}
