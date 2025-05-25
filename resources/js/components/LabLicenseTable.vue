<template>
  <div style="color:red;font-size:2rem;">جدول المختبر هنا (Vue Mount Test)</div>
  <div class="table-container">
    <table>
      <thead>
        <tr>
          <th rowspan="2">الاستشاري</th>
          <th rowspan="2">URS</th>
          <th rowspan="2">كشف فسوحات المقاول<br>شركة سهم بلدي</th>
          <th rowspan="2">التاريخ</th>
          <th colspan="2">الفسح</th>
          <th colspan="3">نوع الشارع</th>
          <th rowspan="2">تدقيق المختبر</th>
          <th rowspan="2">السنة</th>
          <th rowspan="2">نوع العمل</th>
          <th rowspan="2">عمق</th>
          <th rowspan="2">دك التربة</th>
          <th rowspan="2">MC1RC2 دك أسفلت وترابي</th>
          <th rowspan="2">الكثافة القصوى لأسفلت</th>
          <th rowspan="2">نسبة الأسفلت</th>
          <th rowspan="2">التدرج الحبيبي</th>
          <th rowspan="2">تجربة مارشال</th>
          <th rowspan="2">تقييم البلاط والبردورات</th>
          <th rowspan="2">تصنيف التربة</th>
          <th rowspan="2">تجربة بروكتور</th>
          <th rowspan="2">الخرسانة</th>
          <th rowspan="2">الملاحظات</th>
          <th rowspan="2">حذف</th>
        </tr>
        <tr>
          <th>رقم الفسح</th>
          <th>تاريخ الفسح</th>
          <th>ترابي</th>
          <th>أسفلت</th>
          <th>بلاط</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(row, idx) in rows" :key="row.id || idx">
          <td><input v-model="row.consultant" /></td>
          <td><input v-model="row.urs" /></td>
          <td><input v-model="row.contractor" /></td>
          <td><input type="date" v-model="row.date" /></td>
          <td><input v-model="row.permit_no" /></td>
          <td><input type="date" v-model="row.permit_date" /></td>
          <td><input type="checkbox" v-model="row.street_type_terabi" /></td>
          <td><input type="checkbox" v-model="row.street_type_asphalt" /></td>
          <td><input type="checkbox" v-model="row.street_type_blat" /></td>
          <td><input v-model="row.lab_check" /></td>
          <td><input v-model="row.year" type="number" /></td>
          <td><input v-model="row.work_type" /></td>
          <td><input v-model="row.depth" type="number" /></td>
          <td><input v-model="row.soil_compaction" /></td>
          <td><input v-model="row.mc1rc2" /></td>
          <td><input v-model="row.max_density" /></td>
          <td><input v-model="row.asphalt_percent" /></td>
          <td><input v-model="row.gradation" /></td>
          <td><input v-model="row.marshall" /></td>
          <td><input v-model="row.tile_eval" /></td>
          <td><input v-model="row.soil_class" /></td>
          <td><input v-model="row.proctor" /></td>
          <td><input v-model="row.concrete" /></td>
          <td><input v-model="row.notes" /></td>
          <td>
            <button @click="removeRow(idx, row.id)">🗑️</button>
          </td>
        </tr>
      </tbody>
    </table>
    <button @click="addRow">إضافة صف</button>
    <button @click="saveRows">حفظ</button>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const rows = ref([])

onMounted(async () => {
  const { data } = await axios.get('/api/lab-licenses')
  rows.value = data
})

function addRow() {
  rows.value.push({
    consultant: '', urs: '', contractor: '', date: '', permit_no: '', permit_date: '',
    street_type_terabi: false, street_type_asphalt: false, street_type_blat: false,
    lab_check: '', year: '', work_type: '', depth: '', soil_compaction: '', mc1rc2: '',
    max_density: '', asphalt_percent: '', gradation: '', marshall: '', tile_eval: '',
    soil_class: '', proctor: '', concrete: '', notes: ''
  })
}

async function saveRows() {
  for (const row of rows.value) {
    if (row.id) {
      await axios.put(`/api/lab-licenses/${row.id}`, row)
    } else {
      const { data } = await axios.post('/api/lab-licenses', row)
      row.id = data.id
    }
  }
  alert('تم الحفظ بنجاح')
}

async function removeRow(idx, id) {
  if (id) await axios.delete(`/api/lab-licenses/${id}`)
  rows.value.splice(idx, 1)
}
</script>

<style scoped>
.table-container { overflow-x: auto; }
table { border-collapse: collapse; width: 100%; }
th, td { border: 1px solid #ccc; padding: 6px; text-align: center; }
th { background: #007bff; color: #fff; }
button { margin: 5px; }
</style> 