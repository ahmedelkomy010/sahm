console.log('Simple Electrical Works Script Loaded');

// متغيرات عامة
let isInitialized = false;

// الانتظار حتى تحميل الصفحة
document.addEventListener('DOMContentLoaded', function() {
    if (isInitialized) return;
    isInitialized = true;
    
    console.log('DOM Loaded - Initializing Simple Electrical Works');
    
    // البحث عن العناصر
    const lengthInputs = document.querySelectorAll('.electrical-length');
    const priceInputs = document.querySelectorAll('.electrical-price');
    
    console.log('Found inputs:', lengthInputs.length, priceInputs.length);
    
    // إضافة مستمعي الأحداث
    lengthInputs.forEach(input => {
        input.addEventListener('input', function() {
            calculateRowTotal(this);
        });
    });
    
    priceInputs.forEach(input => {
        input.addEventListener('input', function() {
            calculateRowTotal(this);
        });
    });
    
    // تحديث الملخص الأولي
    updateSummary();
    
    console.log('Simple Electrical Works Initialized');
});

// حساب إجمالي الصف
function calculateRowTotal(input) {
    const row = input.closest('tr');
    const lengthInput = row.querySelector('.electrical-length');
    const priceInput = row.querySelector('.electrical-price');
    const totalInput = row.querySelector('.electrical-total');
    
    if (lengthInput && priceInput && totalInput) {
        const length = parseFloat(lengthInput.value) || 0;
        const price = parseFloat(priceInput.value) || 0;
        const total = length * price;
        
        totalInput.value = total.toFixed(2);
        
        console.log('Calculated:', length, '×', price, '=', total);
        
        // تحديث الملخص
        updateSummary();
        
        // الحفظ التلقائي
        setTimeout(saveData, 1000);
    }
}

// تحديث الملخص
function updateSummary() {
    const tbody = document.getElementById('summary-tbody');
    if (!tbody) return;
    
    tbody.innerHTML = '';
    let totalAmount = 0;
    let totalItems = 0;
    let totalLength = 0;
    
    // البحث في الجدول الرئيسي
    const rows = document.querySelectorAll('#electrical-works-form tbody tr');
    
    rows.forEach(row => {
        const label = row.querySelector('td:first-child');
        const lengthInput = row.querySelector('.electrical-length');
        const priceInput = row.querySelector('.electrical-price');
        const totalInput = row.querySelector('.electrical-total');
        
        if (label && lengthInput && priceInput && totalInput) {
            const labelText = label.textContent.trim();
            const length = parseFloat(lengthInput.value) || 0;
            const price = parseFloat(priceInput.value) || 0;
            const total = parseFloat(totalInput.value) || 0;
            
            if (length > 0 && price > 0) {
                const newRow = tbody.insertRow();
                newRow.innerHTML = `
                    <td>${labelText}</td>
                    <td class="text-center">${length.toFixed(0)}</td>
                    <td class="text-center">${price.toFixed(2)}</td>
                    <td class="text-center">${total.toFixed(2)}</td>
                `;
                
                totalAmount += total;
                totalItems++;
                totalLength += length;
            }
        }
    });
    
    // تحديث الإحصائيات
    const elements = {
        'total-length': totalLength.toFixed(0),
        'total-items': totalItems,
        'total-cost': totalAmount.toFixed(2),
        'total-amount': totalAmount.toFixed(2)
    };
    
    Object.keys(elements).forEach(id => {
        const element = document.getElementById(id);
        if (element) {
            element.textContent = elements[id];
        }
    });
    
    console.log('Summary updated:', totalItems, 'items, total:', totalAmount);
}

// حفظ البيانات
function saveData() {
    const form = document.getElementById('electrical-works-form');
    if (!form) {
        console.error('Form not found');
        return;
    }
    
    console.log('Saving data...');
    
    const formData = new FormData(form);
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    
    if (!csrfToken) {
        console.error('CSRF token not found');
        return;
    }
    
    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': csrfToken.content,
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        console.log('Save response status:', response.status);
        if (response.ok) {
            return response.json();
        }
        throw new Error('Save failed');
    })
    .then(data => {
        console.log('Save successful:', data);
    })
    .catch(error => {
        console.error('Save error:', error);
    });
}

// دوال للأزرار
function saveElectricalWorks() {
    saveData();
}

function updateAll() {
    const inputs = document.querySelectorAll('.electrical-length, .electrical-price');
    inputs.forEach(input => {
        if (input.value) {
            calculateRowTotal(input);
        }
    });
}

function printSummary() {
    window.print();
} 