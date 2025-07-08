<?php

namespace App\Http\Controllers;

use App\Models\ExcavationDetail;
use App\Models\WorkOrder;
use Illuminate\Http\Request;

class ExcavationDetailController extends Controller
{
    public function store(Request $request, $license)
    {
        // Implementation will be added later
        return response()->json(['message' => 'تم حفظ تفاصيل الحفر بنجاح']);
    }

    public function update(Request $request, ExcavationDetail $excavationDetail)
    {
        // Implementation will be added later
        return response()->json(['message' => 'تم تحديث تفاصيل الحفر بنجاح']);
    }

    public function destroy(ExcavationDetail $excavationDetail)
    {
        // Implementation will be added later
        return response()->json(['message' => 'تم حذف تفاصيل الحفر بنجاح']);
    }
} 