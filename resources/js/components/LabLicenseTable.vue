<template>
  <div class="table-container">
    <div class="table-responsive">
      <table class="table table-bordered">
        <thead class="bg-primary text-white">
          <tr>
            <th rowspan="2" class="align-middle">الاستشاري</th>
            <th rowspan="2" class="align-middle">URS</th>
            <th rowspan="2" class="align-middle">كشف فسوحات المقاول<br>شركة سهم بلدي</th>
            <th rowspan="2" class="align-middle">التاريخ</th>
            <th colspan="2" class="text-center border-start">الفسح</th>
            <th colspan="3" class="text-center border-start">نوع الشارع</th>
            <th rowspan="2" class="align-middle">تدقيق المختبر</th>
            <th rowspan="2" class="align-middle">السنة</th>
            <th rowspan="2" class="align-middle">نوع العمل</th>
            <th rowspan="2" class="align-middle">عمق</th>
            <th rowspan="2" class="align-middle">دك التربة</th>
            <th rowspan="2" class="align-middle">MC1RC2<br>دك أسفلت وترابي</th>
            <th rowspan="2" class="align-middle">الكثافة القصوى<br>لأسفلت</th>
            <th rowspan="2" class="align-middle">نسبة الأسفلت</th>
            <th rowspan="2" class="align-middle">التدرج الحبيبي</th>
            <th rowspan="2" class="align-middle">تجربة مارشال</th>
            <th rowspan="2" class="align-middle">تقييم البلاط<br>والبردورات</th>
            <th rowspan="2" class="align-middle">تصنيف التربة</th>
            <th rowspan="2" class="align-middle">تجربة بروكتور</th>
            <th rowspan="2" class="align-middle">الخرسانة</th>
            <th rowspan="2" class="align-middle">الملاحظات</th>
            <th rowspan="2" class="align-middle">حذف</th>
          </tr>
          <tr>
            <th class="border-start">رقم الفسح</th>
            <th>تاريخ الفسح</th>
            <th class="border-start">ترابي</th>
            <th>أسفلت</th>
            <th>بلاط</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(row, idx) in rows" :key="row.id || idx" class="align-middle">
            <td><input class="form-control form-control-sm" v-model="row.consultant" placeholder="الاستشاري" /></td>
            <td><input class="form-control form-control-sm" v-model="row.urs" placeholder="URS" /></td>
            <td><input class="form-control form-control-sm" v-model="row.contractor" placeholder="المقاول" /></td>
            <td><input class="form-control form-control-sm" type="date" v-model="row.date" /></td>
            <td class="border-start"><input class="form-control form-control-sm" v-model="row.permit_no" placeholder="رقم الفسح" /></td>
            <td><input class="form-control form-control-sm" type="date" v-model="row.permit_date" /></td>
            <td class="text-center border-start">
              <div class="form-check d-flex justify-content-center">
                <input type="checkbox" class="form-check-input" v-model="row.street_type_terabi" />
              </div>
            </td>
            <td class="text-center">
              <div class="form-check d-flex justify-content-center">
                <input type="checkbox" class="form-check-input" v-model="row.street_type_asphalt" />
              </div>
            </td>
            <td class="text-center">
              <div class="form-check d-flex justify-content-center">
                <input type="checkbox" class="form-check-input" v-model="row.street_type_blat" />
              </div>
            </td>
            <td><input class="form-control form-control-sm" v-model="row.lab_check" placeholder="تدقيق المختبر" /></td>
            <td><input class="form-control form-control-sm" v-model="row.year" type="number" placeholder="السنة" /></td>
            <td><input class="form-control form-control-sm" v-model="row.work_type" placeholder="نوع العمل" /></td>
            <td><input class="form-control form-control-sm" v-model="row.depth" type="number" placeholder="عمق" /></td>
            <td><input class="form-control form-control-sm" v-model="row.soil_compaction" placeholder="دك التربة" /></td>
            <td><input class="form-control form-control-sm" v-model="row.mc1rc2" placeholder="MC1RC2" /></td>
            <td><input class="form-control form-control-sm" v-model="row.max_density" placeholder="الكثافة القصوى" /></td>
            <td><input class="form-control form-control-sm" v-model="row.asphalt_percent" placeholder="نسبة الأسفلت" /></td>
            <td><input class="form-control form-control-sm" v-model="row.gradation" placeholder="التدرج الحبيبي" /></td>
            <td><input class="form-control form-control-sm" v-model="row.marshall" placeholder="تجربة مارشال" /></td>
            <td><input class="form-control form-control-sm" v-model="row.tile_eval" placeholder="تقييم البلاط" /></td>
            <td><input class="form-control form-control-sm" v-model="row.soil_class" placeholder="تصنيف التربة" /></td>
            <td><input class="form-control form-control-sm" v-model="row.proctor" placeholder="تجربة بروكتور" /></td>
            <td><input class="form-control form-control-sm" v-model="row.concrete" placeholder="الخرسانة" /></td>
            <td><input class="form-control form-control-sm" v-model="row.notes" placeholder="الملاحظات" /></td>
            <td class="text-center">
              <button class="btn btn-danger btn-sm" @click="removeRow(idx, row.id)" title="حذف السجل">
                <i class="fas fa-trash-alt"></i>
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <div class="mt-3 d-flex gap-2 justify-content-start">
      <button class="btn btn-primary" @click="addRow">
        <i class="fas fa-plus-circle me-1"></i> إضافة صف جديد
      </button>
      <button class="btn btn-success" @click="saveRows">
        <i class="fas fa-save me-1"></i> حفظ التغييرات
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const rows = ref([])

onMounted(async () => {
  try {
    const { data } = await axios.get('/api/lab-licenses')
    rows.value = data
  } catch (error) {
    console.error('Error fetching lab licenses:', error)
    alert('حدث خطأ أثناء تحميل البيانات')
  }
})

function addRow() {
  rows.value.push({
    consultant: '', 
    urs: '', 
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
  })
}

async function saveRows() {
  try {
    for (const row of rows.value) {
      if (row.id) {
        await axios.put(`/api/lab-licenses/${row.id}`, row)
      } else {
        const { data } = await axios.post('/api/lab-licenses', row)
        row.id = data.id
      }
    }
    alert('تم حفظ البيانات بنجاح')
  } catch (error) {
    console.error('Error saving lab licenses:', error)
    alert('حدث خطأ أثناء حفظ البيانات')
  }
}

async function removeRow(idx, id) {
  if (confirm('هل أنت متأكد من حذف هذا السجل؟')) {
    try {
      if (id) await axios.delete(`/api/lab-licenses/${id}`)
      rows.value.splice(idx, 1)
    } catch (error) {
      console.error('Error removing lab license:', error)
      alert('حدث خطأ أثناء حذف السجل')
    }
  }
}
</script>

<style scoped>
.table-container {
  margin: 1rem 0;
}

.table {
  width: 100%;
  border-collapse: collapse;
  font-size: 0.875rem;
}

.table th {
  background-color: #0d6efd;
  color: white;
  font-weight: 600;
  text-align: center;
  vertical-align: middle;
  padding: 0.75rem;
  white-space: nowrap;
  border: 1px solid #0a58ca;
}

.table td {
  padding: 0.5rem;
  vertical-align: middle;
  border: 1px solid #dee2e6;
}

.form-control-sm {
  padding: 0.25rem 0.5rem;
  font-size: 0.875rem;
  border-radius: 0.25rem;
  border: 1px solid #ced4da;
}

.form-control-sm:focus {
  border-color: #86b7fe;
  box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}

.form-check-input {
  width: 1.25rem;
  height: 1.25rem;
  margin: 0;
  cursor: pointer;
  border: 2px solid #dee2e6;
}

.form-check-input:checked {
  background-color: #0d6efd;
  border-color: #0d6efd;
}

.btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
  padding: 0.375rem 0.75rem;
  font-size: 0.875rem;
  border-radius: 0.25rem;
  transition: all 0.2s;
}

.btn:hover {
  transform: translateY(-1px);
}

.btn-danger {
  padding: 0.25rem 0.5rem;
}

.table-responsive {
  overflow-x: auto;
  margin-bottom: 1rem;
}

/* Custom scrollbar */
.table-responsive::-webkit-scrollbar {
  height: 8px;
}

.table-responsive::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 4px;
}

.table-responsive::-webkit-scrollbar-thumb {
  background: #888;
  border-radius: 4px;
}

.table-responsive::-webkit-scrollbar-thumb:hover {
  background: #555;
}

/* Border separators */
.border-start {
  border-left: 2px solid #0a58ca !important;
}

/* Placeholder styling */
.form-control-sm::placeholder {
  color: #adb5bd;
  opacity: 0.8;
}
</style> 