<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\WorkOrder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class WorkOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear un usuario administrador si no existe
        $user = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
            ]
        );

        // Crear órdenes de trabajo de prueba
        $workOrders = [
            [
                'user_id' => $user->id,
                'order_number' => 'WO-2023-001',
                'work_type' => '1',
                'work_description' => 'تمديد شبكات المياه في منطقة حي الشروق',
                'approval_date' => '2023-05-15',
                'subscriber_name' => 'محمد أحمد',
                'district' => 'حي الشروق',
                'station_number' => 'ST-001',
                'consultant_name' => 'علي محمود',
                'order_value_with_consultant' => 25000.50,
                'order_value_without_consultant' => 22000.00,
                'execution_status' => '1', // تم الاستلام من المقاول ولم تصدر شهادة الانجاز
                'actual_execution_value' => 23500.75,
                'procedure_155_delivery_date' => '2023-06-01',
                'procedure_211_date' => '2023-06-15',
                'partial_deletion' => false,
                'partial_payment_value' => null,
                'extract_number' => 'EXT-001',
                'invoice_number' => 'INV-2023-001',
                'purchase_order_number' => 'PO-2023-001',
                'tax_value' => 3750.08,
            ],
            [
                'user_id' => $user->id,
                'order_number' => 'WO-2023-002',
                'work_type' => '2',
                'work_description' => 'تمديد شبكات الصرف الصحي في منطقة الزهراء',
                'approval_date' => '2023-06-10',
                'subscriber_name' => 'خالد عبدالله',
                'district' => 'حي الزهراء',
                'station_number' => 'ST-002',
                'consultant_name' => 'سعيد راشد',
                'order_value_with_consultant' => 35000.00,
                'order_value_without_consultant' => 32000.00,
                'execution_status' => '2', // تم تسليم المقاول ولم يتم تسليم
                'actual_execution_value' => 33500.25,
                'procedure_155_delivery_date' => '2023-07-01',
                'procedure_211_date' => '2023-07-15',
                'partial_deletion' => false,
                'partial_payment_value' => null,
                'extract_number' => 'EXT-002',
                'invoice_number' => 'INV-2023-002',
                'purchase_order_number' => 'PO-2023-002',
                'tax_value' => 5250.00,
            ],
            [
                'user_id' => $user->id,
                'order_number' => 'WO-2023-003',
                'work_type' => '3',
                'work_description' => 'صيانة شبكات المياه في حي الخالدية',
                'approval_date' => '2023-07-05',
                'subscriber_name' => 'عمر سعيد',
                'district' => 'حي الخالدية',
                'station_number' => 'ST-003',
                'consultant_name' => 'فهد محمد',
                'order_value_with_consultant' => 18000.75,
                'order_value_without_consultant' => 15000.50,
                'execution_status' => '3', // دخلت مستخلص ولم تصرف
                'actual_execution_value' => 17500.00,
                'procedure_155_delivery_date' => '2023-08-01',
                'procedure_211_date' => '2023-08-15',
                'partial_deletion' => true,
                'partial_payment_value' => 5000.00,
                'extract_number' => 'EXT-003',
                'invoice_number' => 'INV-2023-003',
                'purchase_order_number' => 'PO-2023-003',
                'tax_value' => 2700.11,
            ],
            [
                'user_id' => $user->id,
                'order_number' => 'WO-2023-004',
                'work_type' => '4',
                'work_description' => 'صيانة شبكات الصرف الصحي في حي النزهة',
                'approval_date' => '2023-08-12',
                'subscriber_name' => 'سلطان خالد',
                'district' => 'حي النزهة',
                'station_number' => 'ST-004',
                'consultant_name' => 'ناصر سليمان',
                'order_value_with_consultant' => 22000.25,
                'order_value_without_consultant' => 19000.75,
                'execution_status' => '4', // صدرت شهادة الانجاز ولم تعتمد
                'actual_execution_value' => 21000.50,
                'procedure_155_delivery_date' => '2023-09-01',
                'procedure_211_date' => '2023-09-15',
                'partial_deletion' => false,
                'partial_payment_value' => null,
                'extract_number' => 'EXT-004',
                'invoice_number' => 'INV-2023-004',
                'purchase_order_number' => 'PO-2023-004',
                'tax_value' => 3300.04,
            ],
            [
                'user_id' => $user->id,
                'order_number' => 'WO-2023-005',
                'work_type' => '5',
                'work_description' => 'إنشاء محطات الضخ في حي المروج',
                'approval_date' => '2023-09-20',
                'subscriber_name' => 'فيصل عبدالعزيز',
                'district' => 'حي المروج',
                'station_number' => 'ST-005',
                'consultant_name' => 'بدر سعد',
                'order_value_with_consultant' => 45000.00,
                'order_value_without_consultant' => 40000.00,
                'execution_status' => '5', // منتهي
                'actual_execution_value' => 43000.25,
                'procedure_155_delivery_date' => '2023-10-01',
                'procedure_211_date' => '2023-10-15',
                'partial_deletion' => false,
                'partial_payment_value' => null,
                'extract_number' => 'EXT-005',
                'invoice_number' => 'INV-2023-005',
                'purchase_order_number' => 'PO-2023-005',
                'tax_value' => 6750.00,
            ],
        ];

        foreach ($workOrders as $orderData) {
            WorkOrder::firstOrCreate(
                ['order_number' => $orderData['order_number']],
                $orderData
            );
        }
    }
}
