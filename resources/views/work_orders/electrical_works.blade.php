<script>
$(document).ready(function() {
    // دالة لتحديث جدول الصور
    function updateImagesTable(images) {
        const tbody = $('#electrical-works-images-table tbody');
        tbody.empty();
        
        images.forEach(function(image) {
            const row = `
                <tr>
                    <td class="text-center">${image.id}</td>
                    <td class="text-center">
                        <img src="${image.url}" alt="${image.name}" class="img-thumbnail" style="max-width: 100px; max-height: 100px;">
                    </td>
                    <td class="text-center">${image.name}</td>
                    <td class="text-center">${image.size} MB</td>
                    <td class="text-center">${image.created_at}</td>
                    <td class="text-center">
                        <button type="button" class="btn btn-danger btn-sm delete-image" data-id="${image.id}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
            tbody.append(row);
        });

        // تحديث عداد الصور
        $('#electrical-works-images-count').text(images.length);
    }

    // تعديل دالة رفع الصور
    $('#electrical-works-images-form').on('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const submitBtn = $(this).find('button[type="submit"]');
        const originalBtnText = submitBtn.html();
        
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> جاري الرفع...');
        
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    // تحديث جدول الصور مباشرة
                    updateImagesTable(response.images);
                    
                    // إظهار رسالة نجاح
                    Swal.fire({
                        icon: 'success',
                        title: 'تم بنجاح',
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    });
                    
                    // إعادة تعيين نموذج الرفع
                    $('#electrical-works-images-form')[0].reset();
                    $('#electrical-works-images-preview').empty();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'خطأ',
                        text: response.message
                    });
                }
            },
            error: function(xhr) {
                let errorMessage = 'حدث خطأ أثناء رفع الصور';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                Swal.fire({
                    icon: 'error',
                    title: 'خطأ',
                    text: errorMessage
                });
            },
            complete: function() {
                submitBtn.prop('disabled', false).html(originalBtnText);
            }
        });
    });
});
</script> 