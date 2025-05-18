<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrderEntry;

class OrderEntryController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'work_order_id' => 'required|exists:work_orders,id',
            'type' => 'required|in:purchase_order_number,entry_sheet',
            'value' => 'required|string|max:255',
        ]);

        $entry = OrderEntry::create([
            'work_order_id' => $request->work_order_id,
            'type' => $request->type,
            'value' => $request->value,
        ]);

        return redirect()->back()->with('success', 'تم حفظ البيانات بنجاح');
    }

    public function destroy($id)
    {
        $entry = OrderEntry::findOrFail($id);
        $entry->delete();
        return redirect()->back()->with('success', 'تم حذف السطر بنجاح');
    }
} 