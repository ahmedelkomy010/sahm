<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WorkOrder;
use App\Models\InvoiceAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InvoiceAttachmentController extends Controller
{
    public function store(Request $request, WorkOrder $workOrder)
    {
        $request->validate([
            'attachments.*' => 'required|file|mimes:pdf,jpg,jpeg,png|max:1024', // 1 MB max
            'file_type' => 'required|string|max:255',
            'description' => 'nullable|string',
        ], [
            'attachments.*.max' => 'حجم الملف يجب أن لا يتجاوز 1 ميجابايت'
        ]);

        if (count($request->file('attachments')) > 20) {
            return back()->with('error', 'يمكن رفع 20 ملف كحد أقصى');
        }

        foreach ($request->file('attachments') as $file) {
            $path = $file->store('invoice-attachments/' . $workOrder->id, 'public');
            
            InvoiceAttachment::create([
                'work_order_id' => $workOrder->id,
                'file_path' => $path,
                'original_filename' => $file->getClientOriginalName(),
                'file_type' => $request->file_type,
                'description' => $request->description,
            ]);
        }

        return back()->with('success', 'تم رفع المرفقات بنجاح');
    }

    public function destroy(InvoiceAttachment $invoiceAttachment)
    {
        // حذف الملف من التخزين
        Storage::delete($invoiceAttachment->file_path);
        
        // حذف السجل من قاعدة البيانات
        $invoiceAttachment->delete();

        return back()->with('success', 'تم حذف المرفق بنجاح');
    }
} 