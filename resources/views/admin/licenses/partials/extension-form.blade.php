<!-- نموذج إضافة/تعديل التمديد -->
<div class="bg-white p-6 rounded-lg shadow-lg">
    <h3 class="text-lg font-semibold mb-4">بيانات التمديد</h3>
    
    <form id="extensionForm" action="{{ route('admin.licenses.save-section') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="section_type" value="extension">
        <input type="hidden" name="license_id" value="{{ $license->id }}">
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- رقم التمديد -->
            <div>
                <label for="extension_number" class="block text-sm font-medium text-gray-700 mb-2">رقم التمديد</label>
                <input type="text" 
                       name="extension_number" 
                       id="extension_number" 
                       class="form-input w-full rounded-md shadow-sm" 
                       required>
            </div>
            
            <!-- تاريخ الطلب -->
            <div>
                <label for="request_date" class="block text-sm font-medium text-gray-700 mb-2">تاريخ الطلب</label>
                <input type="date" 
                       name="request_date" 
                       id="request_date" 
                       class="form-input w-full rounded-md shadow-sm" 
                       required>
            </div>
            
            <!-- تاريخ البداية -->
            <div>
                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">تاريخ البداية</label>
                <input type="date" 
                       name="start_date" 
                       id="start_date" 
                       class="form-input w-full rounded-md shadow-sm" 
                       required>
            </div>
            
            <!-- تاريخ النهاية -->
            <div>
                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">تاريخ النهاية</label>
                <input type="date" 
                       name="end_date" 
                       id="end_date" 
                       class="form-input w-full rounded-md shadow-sm" 
                       required>
            </div>

            <!-- قيمة التمديد -->
            <div>
                <label for="extension_value" class="block text-sm font-medium text-gray-700 mb-2">قيمة التمديد</label>
                <input type="number" 
                       name="extension_value" 
                       id="extension_value" 
                       step="0.01"
                       class="form-input w-full rounded-md shadow-sm" 
                       required>
            </div>
            
            <!-- الحالة -->
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">الحالة</label>
                <select name="status" 
                        id="status" 
                        class="form-select w-full rounded-md shadow-sm" 
                        required>
                    <option value="pending">قيد المراجعة</option>
                    <option value="approved">معتمد</option>
                    <option value="rejected">مرفوض</option>
                </select>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">
            <!-- ملف التمديد -->
            <div>
                <label for="extension_license_file" class="block text-sm font-medium text-gray-700 mb-2">ملف التمديد</label>
                <input type="file" 
                       name="files[license]" 
                       id="extension_license_file" 
                       class="form-input w-full rounded-md shadow-sm" 
                       accept=".pdf,.jpg,.jpeg,.png">
            </div>
            
            <!-- إثبات الدفع -->
            <div>
                <label for="extension_payment_proof" class="block text-sm font-medium text-gray-700 mb-2">إثبات الدفع</label>
                <input type="file" 
                       name="files[payment]" 
                       id="extension_payment_proof" 
                       class="form-input w-full rounded-md shadow-sm" 
                       accept=".pdf,.jpg,.jpeg,.png">
            </div>
            
            <!-- إثبات البنك -->
            <div>
                <label for="extension_bank_proof" class="block text-sm font-medium text-gray-700 mb-2">إثبات البنك</label>
                <input type="file" 
                       name="files[bank]" 
                       id="extension_bank_proof" 
                       class="form-input w-full rounded-md shadow-sm" 
                       accept=".pdf,.jpg,.jpeg,.png">
            </div>
        </div>
        
        <!-- سبب التمديد / الملاحظات -->
        <div class="mt-6">
            <label for="extension_reason" class="block text-sm font-medium text-gray-700 mb-2">سبب التمديد / الملاحظات</label>
            <textarea name="extension_reason" 
                      id="extension_reason" 
                      rows="3" 
                      class="form-textarea w-full rounded-md shadow-sm"
                      placeholder="أذكر سبب التمديد أو أي ملاحظات إضافية..."></textarea>
        </div>
        
        <!-- أزرار التحكم -->
        <div class="mt-6 flex justify-end space-x-3">
            <button type="button" 
                    class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                    onclick="window.location.reload()">
                إلغاء
            </button>
            <button type="submit" 
                    class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                حفظ التمديد
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('extensionForm');
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');
    
    // التحقق من تواريخ التمديد
    form.addEventListener('submit', function(e) {
        const startDate = new Date(startDateInput.value);
        const endDate = new Date(endDateInput.value);
        
        if (endDate < startDate) {
            e.preventDefault();
            alert('تاريخ النهاية يجب أن يكون بعد تاريخ البداية');
            return false;
        }
    });
});
</script>
@endpush 