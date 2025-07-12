class LabLicenseManager {
    constructor(containerId) {
        this.container = document.getElementById(containerId);
        this.data = [];
        
        // Check if container exists before initializing
        if (!this.container) {
            console.warn(`Container with ID '${containerId}' not found. LabLicenseManager will not initialize.`);
            return;
        }
        
        this.init();
    }

    async init() {
        // Only initialize if container exists
        if (!this.container) {
            return;
        }
        
        await this.loadData();
        this.render();
    }

    async loadData() {
        try {
            const response = await fetch('/lab-licenses', {
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            if (response.ok) {
                this.data = await response.json();
                console.log('Loaded lab licenses:', this.data);
            } else {
                console.error('Failed to load data:', response.status, response.statusText);
                this.data = [];
            }
        } catch (error) {
            console.error('Error loading data:', error);
            this.data = [];
        }
    }

    addRow() {
        const newRow = {
            id: null,
            contractor: '',
            date: '',
            permit_no: '',
            permit_date: '',
            street_type_terabi: false,
            street_type_asphalt: false,
            street_type_blat: false,
            lab_check: '',
            year: new Date().getFullYear(),
            work_type: '',
            depth: '',
            soil_compaction: '',
            mc1rc2: '',
            max_density: '',
            asphalt_percent: '',
            gradation: '',
            marshall: '',
            tile_eval: '',
            soil_class: '',
            proctor: '',
            concrete: '',
            notes: ''
        };
        this.data.push(newRow);
        this.render();
    }

    async saveData() {
        try {
            for (const row of this.data) {
                if (row.id) {
                    await fetch(`/lab-licenses/${row.id}`, {
                        method: 'PUT',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify(row)
                    });
                } else {
                    const response = await fetch('/lab-licenses', {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify(row)
                    });
                    if (response.ok) {
                        const newData = await response.json();
                        row.id = newData.id;
                    }
                }
            }
            alert('تم حفظ البيانات بنجاح');
        } catch (error) {
            console.error('Error saving data:', error);
            alert('حدث خطأ أثناء حفظ البيانات');
        }
    }

    async deleteRow(index) {
        if (confirm('هل أنت متأكد من حذف هذا السجل؟')) {
            const row = this.data[index];
            try {
                if (row.id) {
                    await fetch(`/lab-licenses/${row.id}`, {
                        method: 'DELETE',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                }
                this.data.splice(index, 1);
                this.render();
            } catch (error) {
                console.error('Error deleting row:', error);
                alert('حدث خطأ أثناء حذف السجل');
            }
        }
    }

    render() {
        // Check if container exists before rendering
        if (!this.container) {
            console.warn('Cannot render: container not found');
            return;
        }
        
        this.container.innerHTML = `
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th>المقاول</th>
                            <th>التاريخ</th>
                            <th>رقم الفسح</th>
                            <th>تاريخ الفسح</th>
                            <th>ترابي</th>
                            <th>أسفلت</th>
                            <th>بلاط</th>
                            <th>تدقيق المختبر</th>
                            <th>السنة</th>
                            <th>نوع العمل</th>
                            <th>عمق</th>
                            <th>دك التربة</th>
                            <th>MC1-RC2</th>
                            <th>الكثافة القصوى</th>
                            <th>نسبة الأسفلت</th>
                            <th>التدرج الحبيبي</th>
                            <th>تجربة مارشال</th>
                            <th>تقييم البلاط</th>
                            <th>تصنيف التربة</th>
                            <th>تجربة بروكتور</th>
                            <th>الخرسانة</th>
                            <th>الملاحظات</th>
                            <th>حذف</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${this.data.map((row, index) => `
                            <tr>
                                <td><input class="form-control form-control-sm" value="${row.contractor || ''}" onchange="labManager.updateField(${index}, 'contractor', this.value)"></td>
                                <td><input class="form-control form-control-sm" type="date" value="${row.date || ''}" onchange="labManager.updateField(${index}, 'date', this.value)"></td>
                                <td><input class="form-control form-control-sm" value="${row.permit_no || ''}" onchange="labManager.updateField(${index}, 'permit_no', this.value)"></td>
                                <td><input class="form-control form-control-sm" value="${row.permit_date || ''}" onchange="labManager.updateField(${index}, 'permit_date', this.value)"></td>
                                <td><input type="checkbox" ${row.street_type_terabi ? 'checked' : ''} onchange="labManager.updateField(${index}, 'street_type_terabi', this.checked)"></td>
                                <td><input type="checkbox" ${row.street_type_asphalt ? 'checked' : ''} onchange="labManager.updateField(${index}, 'street_type_asphalt', this.checked)"></td>
                                <td><input type="checkbox" ${row.street_type_blat ? 'checked' : ''} onchange="labManager.updateField(${index}, 'street_type_blat', this.checked)"></td>
                                <td><input class="form-control form-control-sm" value="${row.lab_check || ''}" onchange="labManager.updateField(${index}, 'lab_check', this.value)"></td>
                                <td><input class="form-control form-control-sm" type="number" value="${row.year || ''}" onchange="labManager.updateField(${index}, 'year', this.value)"></td>
                                <td><input class="form-control form-control-sm" value="${row.work_type || ''}" onchange="labManager.updateField(${index}, 'work_type', this.value)"></td>
                                <td><input class="form-control form-control-sm" value="${row.depth || ''}" onchange="labManager.updateField(${index}, 'depth', this.value)"></td>
                                <td><input class="form-control form-control-sm" value="${row.soil_compaction || ''}" onchange="labManager.updateField(${index}, 'soil_compaction', this.value)"></td>
                                <td><input class="form-control form-control-sm" value="${row.mc1rc2 || ''}" onchange="labManager.updateField(${index}, 'mc1rc2', this.value)"></td>
                                <td><input class="form-control form-control-sm" value="${row.max_density || ''}" onchange="labManager.updateField(${index}, 'max_density', this.value)"></td>
                                <td><input class="form-control form-control-sm" value="${row.asphalt_percent || ''}" onchange="labManager.updateField(${index}, 'asphalt_percent', this.value)"></td>
                                <td><input class="form-control form-control-sm" value="${row.gradation || ''}" onchange="labManager.updateField(${index}, 'gradation', this.value)"></td>
                                <td><input class="form-control form-control-sm" value="${row.marshall || ''}" onchange="labManager.updateField(${index}, 'marshall', this.value)"></td>
                                <td><input class="form-control form-control-sm" value="${row.tile_eval || ''}" onchange="labManager.updateField(${index}, 'tile_eval', this.value)"></td>
                                <td><input class="form-control form-control-sm" value="${row.soil_class || ''}" onchange="labManager.updateField(${index}, 'soil_class', this.value)"></td>
                                <td><input class="form-control form-control-sm" value="${row.proctor || ''}" onchange="labManager.updateField(${index}, 'proctor', this.value)"></td>
                                <td><input class="form-control form-control-sm" value="${row.concrete || ''}" onchange="labManager.updateField(${index}, 'concrete', this.value)"></td>
                                <td><input class="form-control form-control-sm" value="${row.notes || ''}" onchange="labManager.updateField(${index}, 'notes', this.value)"></td>
                                <td><button class="btn btn-danger btn-sm" onclick="labManager.deleteRow(${index})">حذف</button></td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                <button class="btn btn-primary me-2" onclick="labManager.addRow()">إضافة صف جديد</button>
                <button class="btn btn-success" onclick="labManager.saveData()">حفظ التغييرات</button>
            </div>
        `;
    }

    updateField(index, field, value) {
        this.data[index][field] = value;
    }
}

// Initialize when page loads
let labManager;
document.addEventListener('DOMContentLoaded', function() {
    // Only initialize if the container exists
    const container = document.getElementById('lab-licenses-fallback');
    if (container) {
        labManager = new LabLicenseManager('lab-licenses-fallback');
    } else {
        console.log('Lab licenses container not found on this page');
    }
}); 