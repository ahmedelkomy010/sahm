<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WorkOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkOrderDeleteController extends Controller
{
    public function __invoke(Request $request, WorkOrder $workOrder)
    {
        // التحقق من أن المستخدم مشرف
        if (!Auth::user()->is_admin) {
            return redirect()->back()->with('error', 'عذراً، لا يمكنك حذف أمر العمل. هذه العملية متاحة للمشرفين فقط.');
        }

        try {
            $workOrder->delete();
            return redirect()->route('admin.work-orders.index')->with('success', 'تم حذف أمر العمل بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء حذف أمر العمل');
        }
    }
}