$(document).ready(function() {
    // حفظ المخالفة
    $('#addViolationForm').on('submit', function(e) {
        e.preventDefault();
        const licenseId = $(this).data('license-id');
        
        $.ajax({
            url: `/licenses/${licenseId}/violations`,
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    // تحديث الجدول
                    addViolationToTable(response.violation);
                    // إعادة تعيين النموذج
                    $('#addViolationForm')[0].reset();
                } else {
                    toastr.error(response.message);
                }
            },
            error: function(xhr) {
                toastr.error('حدث خطأ أثناء حفظ المخالفة');
            }
        });
    });

    // تحديث المخالفة
    $(document).on('click', '.edit-violation', function() {
        const violationId = $(this).data('id');
        const row = $(this).closest('tr');
        
        // تعبئة نموذج التحديث بالبيانات الحالية
        $('#editViolationForm').data('violation-id', violationId);
        $('#editLicenseNumber').val(row.find('.license-number').text());
        $('#editViolationDate').val(row.find('.violation-date').text());
        $('#editPaymentDueDate').val(row.find('.payment-due-date').text());
        $('#editViolationAmount').val(row.find('.violation-amount').text());
        $('#editViolationNumber').val(row.find('.violation-number').text());
        $('#editResponsibleParty').val(row.find('.responsible-party').text());
        $('#editViolationDescription').val(row.find('.violation-description').text());
        $('#editNotes').val(row.find('.notes').text());
        
        $('#editViolationModal').modal('show');
    });

    // حفظ التحديثات
    $('#editViolationForm').on('submit', function(e) {
        e.preventDefault();
        const violationId = $(this).data('violation-id');
        
        $.ajax({
            url: `/violations/${violationId}`,
            method: 'PUT',
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    // تحديث الصف في الجدول
                    updateViolationInTable(response.violation);
                    $('#editViolationModal').modal('hide');
                } else {
                    toastr.error(response.message);
                }
            },
            error: function(xhr) {
                toastr.error('حدث خطأ أثناء تحديث المخالفة');
            }
        });
    });

    // حذف المخالفة
    $(document).on('click', '.delete-violation', function() {
        if (!confirm('هل أنت متأكد من حذف هذه المخالفة؟')) {
            return;
        }

        const violationId = $(this).data('id');
        const row = $(this).closest('tr');
        
        $.ajax({
            url: `/violations/${violationId}`,
            method: 'DELETE',
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    row.remove();
                } else {
                    toastr.error(response.message);
                }
            },
            error: function(xhr) {
                toastr.error('حدث خطأ أثناء حذف المخالفة');
            }
        });
    });

    // وظائف مساعدة
    function addViolationToTable(violation) {
        const newRow = `
            <tr data-id="${violation.id}">
                <td class="license-number">${violation.license_number}</td>
                <td class="violation-date">${violation.violation_date}</td>
                <td class="payment-due-date">${violation.payment_due_date}</td>
                <td class="violation-amount">${violation.violation_amount}</td>
                <td class="violation-number">${violation.violation_number}</td>
                <td class="responsible-party">${violation.responsible_party}</td>
                <td class="violation-description">${violation.violation_description || ''}</td>
                <td class="notes">${violation.notes || ''}</td>
                <td>
                    <div class="btn-group btn-group-sm">
                        <button type="button" class="btn btn-primary edit-violation" data-id="${violation.id}">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button type="button" class="btn btn-danger delete-violation" data-id="${violation.id}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
        $('#violationsTable tbody').append(newRow);
    }

    function updateViolationInTable(violation) {
        const row = $(`tr[data-id="${violation.id}"]`);
        row.find('.license-number').text(violation.license_number);
        row.find('.violation-date').text(violation.violation_date);
        row.find('.payment-due-date').text(violation.payment_due_date);
        row.find('.violation-amount').text(violation.violation_amount);
        row.find('.violation-number').text(violation.violation_number);
        row.find('.responsible-party').text(violation.responsible_party);
        row.find('.violation-description').text(violation.violation_description || '');
        row.find('.notes').text(violation.notes || '');
    }
}); 