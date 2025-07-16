/**
 * ========================================
 * نظام الأعمال المدنية المحترف - إعادة كتابة كاملة
 * ========================================
 */

// 1. إعدادات النظام والثوابت
const SYSTEM_CONFIG = {
    MAX_RETRIES: 3,
    RETRY_DELAY: 1000,
    STORAGE_KEY_PREFIX: 'civilWorks_',
    DEBOUNCE_DELAY: 300,
    API_TIMEOUT: 30000
};

// 2. إدارة الحالة المركزية
class CivilWorksStateManager {
    constructor() {
        this.state = {
            workOrderId: null,
            csrfToken: null,
            isLoading: false,
            isSaving: false,
            dailyData: [],
            statistics: {
                totalLength: 0,
                totalAmount: 0,
                itemsCount: 0
            },
            errors: []
        };
        this.listeners = new Map();
        this.initialized = false;
    }

    // إنشاء نسخة وحيدة (Singleton)
    static getInstance() {
        if (!CivilWorksStateManager.instance) {
            CivilWorksStateManager.instance = new CivilWorksStateManager();
        }
        return CivilWorksStateManager.instance;
    }

    // تهيئة النظام
    initialize(workOrderId, csrfToken, savedData = []) {
        if (this.initialized) {
            console.warn('🟡 النظام تم تهيئته مسبقاً');
            return;
        }

        this.state.workOrderId = workOrderId;
        this.state.csrfToken = csrfToken;
        this.state.dailyData = Array.isArray(savedData) ? savedData : [];
        this.initialized = true;
        
        console.log('✅ تم تهيئة نظام الأعمال المدنية بنجاح');
        this.notify('SYSTEM_INITIALIZED');
    }

    // تحديث الحالة
    setState(updates) {
        const oldState = { ...this.state };
        this.state = { ...this.state, ...updates };
        this.notify('STATE_UPDATED', { oldState, newState: this.state });
    }

    // الحصول على الحالة
    getState() {
        return { ...this.state };
    }

    // إضافة مستمع للأحداث
    addEventListener(event, callback) {
        if (!this.listeners.has(event)) {
            this.listeners.set(event, new Set());
        }
        this.listeners.get(event).add(callback);
    }

    // إزالة مستمع الأحداث
    removeEventListener(event, callback) {
        if (this.listeners.has(event)) {
            this.listeners.get(event).delete(callback);
        }
    }

    // إشعار المستمعين
    notify(event, data = null) {
        if (this.listeners.has(event)) {
            this.listeners.get(event).forEach(callback => {
                try {
                    callback(data);
                } catch (error) {
                    console.error(`خطأ في مستمع الحدث ${event}:`, error);
                }
            });
        }
    }

    // إضافة خطأ
    addError(error) {
        this.state.errors.push({
            message: error.message || error,
            timestamp: new Date().toISOString(),
            stack: error.stack
        });
        this.notify('ERROR_ADDED', error);
    }

    // مسح الأخطاء
    clearErrors() {
        this.state.errors = [];
        this.notify('ERRORS_CLEARED');
    }
}

// 3. إدارة البيانات المحلية
class LocalStorageManager {
    constructor() {
        this.stateManager = CivilWorksStateManager.getInstance();
    }

    // بناء مفتاح التخزين
    getStorageKey(suffix = 'dailyData') {
        const { workOrderId } = this.stateManager.getState();
        return `${SYSTEM_CONFIG.STORAGE_KEY_PREFIX}${workOrderId}_${suffix}`;
    }

    // حفظ البيانات محلياً
    save(data, suffix = 'dailyData') {
        try {
            const key = this.getStorageKey(suffix);
            const jsonData = JSON.stringify(data);
            localStorage.setItem(key, jsonData);
            console.log(`💾 تم حفظ البيانات محلياً: ${key}`);
            return true;
        } catch (error) {
            console.error('خطأ في حفظ البيانات محلياً:', error);
            this.stateManager.addError(error);
            return false;
        }
    }

    // تحميل البيانات محلياً
    load(suffix = 'dailyData') {
        try {
            const key = this.getStorageKey(suffix);
            const data = localStorage.getItem(key);
            return data ? JSON.parse(data) : null;
        } catch (error) {
            console.error('خطأ في تحميل البيانات محلياً:', error);
            this.stateManager.addError(error);
            return null;
        }
    }

    // حذف البيانات محلياً
    remove(suffix = 'dailyData') {
        try {
            const key = this.getStorageKey(suffix);
            localStorage.removeItem(key);
            console.log(`🗑️ تم حذف البيانات محلياً: ${key}`);
            return true;
        } catch (error) {
            console.error('خطأ في حذف البيانات محلياً:', error);
            this.stateManager.addError(error);
            return false;
        }
    }

    // مسح جميع البيانات
    clearAll() {
        try {
            const keys = Object.keys(localStorage).filter(key => 
                key.startsWith(SYSTEM_CONFIG.STORAGE_KEY_PREFIX)
            );
            keys.forEach(key => localStorage.removeItem(key));
            console.log(`🧹 تم مسح ${keys.length} عنصر من البيانات المحلية`);
            return true;
        } catch (error) {
            console.error('خطأ في مسح البيانات المحلية:', error);
            this.stateManager.addError(error);
            return false;
        }
    }
}

// 4. إدارة API والتفاعل مع الخادم
class ApiManager {
    constructor() {
        this.stateManager = CivilWorksStateManager.getInstance();
        this.localStorage = new LocalStorageManager();
    }

    // إنشاء رأس الطلب
    getHeaders() {
        const { csrfToken } = this.stateManager.getState();
        return {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        };
    }

    // إرسال طلب مع إعادة المحاولة
    async request(url, options = {}, retries = SYSTEM_CONFIG.MAX_RETRIES) {
        try {
            const controller = new AbortController();
            const timeoutId = setTimeout(() => controller.abort(), SYSTEM_CONFIG.API_TIMEOUT);

            const response = await fetch(url, {
                ...options,
                headers: { ...this.getHeaders(), ...options.headers },
                signal: controller.signal
            });

            clearTimeout(timeoutId);

            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }

            return await response.json();
        } catch (error) {
            if (retries > 0 && !error.name === 'AbortError') {
                console.warn(`⚠️ إعادة المحاولة... المتبقي: ${retries}`);
                await new Promise(resolve => setTimeout(resolve, SYSTEM_CONFIG.RETRY_DELAY));
                return this.request(url, options, retries - 1);
            }
            throw error;
        }
    }

    // حفظ البيانات في الخادم
    async saveToServer(data) {
        const { workOrderId } = this.stateManager.getState();
        const url = `/admin/work-orders/${workOrderId}/civil-works/save-daily-data`;
        
        const payload = {
            daily_data: JSON.stringify(data),
            work_order_id: workOrderId
        };

        console.log('📤 إرسال البيانات للخادم:', payload);
        
        try {
            const result = await this.request(url, {
                method: 'POST',
                body: JSON.stringify(payload)
            });

            console.log('✅ تم حفظ البيانات في الخادم بنجاح');
            return result;
        } catch (error) {
            console.error('❌ خطأ في حفظ البيانات في الخادم:', error);
            throw error;
        }
    }

    // تحميل البيانات من الخادم
    async loadFromServer() {
        const { workOrderId } = this.stateManager.getState();
        const url = `/admin/work-orders/${workOrderId}/get-daily-civil-works`;
        
        try {
            const result = await this.request(url, { method: 'GET' });
            console.log('📥 تم تحميل البيانات من الخادم:', result);
            return result;
        } catch (error) {
            console.error('❌ خطأ في تحميل البيانات من الخادم:', error);
            throw error;
        }
    }
}

// 5. جمع البيانات من النماذج
class DataCollector {
    constructor() {
        this.stateManager = CivilWorksStateManager.getInstance();
    }

    // جمع البيانات من النماذج
    collectFormData() {
        const data = [];
        const timestamp = new Date();
        const workDate = timestamp.toISOString().split('T')[0];
        const workTime = timestamp.toLocaleTimeString('ar-SA');

        try {
            // جمع بيانات الحفريات العادية
            this.collectExcavationData(data, workDate, workTime);
            
            // جمع بيانات الحفر المفتوح
            this.collectOpenExcavationData(data, workDate, workTime);
            
            // جمع بيانات الأعمال الكهربائية
            this.collectElectricalData(data, workDate, workTime);

            // جمع بيانات الأسفلت
            this.collectAsphaltData(data, workDate, workTime);

            // جمع بيانات الحفريات الدقيقة
            this.collectPreciseExcavationData(data, workDate, workTime);

            console.log(`📊 تم جمع ${data.length} عنصر من البيانات`);
            return data;
        } catch (error) {
            console.error('خطأ في جمع البيانات:', error);
            this.stateManager.addError(error);
            return [];
        }
    }

    // جمع بيانات الحفريات العادية
    collectExcavationData(data, workDate, workTime) {
        const excavationTypes = {
            'unsurfaced_soil': 'حفرية ترابية غير مسفلتة',
            'surfaced_soil': 'حفرية ترابية مسفلتة',
            'surfaced_rock': 'حفرية صخرية مسفلتة',
            'unsurfaced_rock': 'حفرية صخرية غير مسفلتة',
            'precise': 'حفريات دقيقة'
        };

        Object.entries(excavationTypes).forEach(([type, typeName]) => {
            const elements = document.querySelectorAll(`[data-table="${type}"]`);
            
            elements.forEach((element, index) => {
                if (element.classList.contains('calc-length')) {
                    const row = element.closest('tr');
                    if (!row) return;

                    const lengthInput = element;
                    const priceInput = row.querySelector('.calc-price');
                    const totalInput = row.querySelector('.total-calc');
                    const cableCell = row.querySelector('td:first-child');

                    const length = parseFloat(lengthInput.value) || 0;
                    const price = parseFloat(priceInput?.value) || 0;
                    const total = parseFloat(totalInput?.value) || 0;

                    if (length > 0 || price > 0 || total > 0) {
                        let cableName = `كابل ${index + 1}`;
                        
                        if (cableCell) {
                            const cellText = cableCell.textContent.trim();
                            if (cellText) {
                                cableName = cellText.replace(/\s+/g, ' ').split(' ').slice(0, 3).join(' ');
                            }
                        }

                        data.push({
                            id: `${type}_${index}_${Date.now()}`,
                            excavation_type: typeName,
                            cable_name: cableName,
                            length: length,
                            price: price,
                            total: total,
                            work_date: workDate,
                            work_time: workTime,
                            category: 'excavation'
                        });
                    }
                }
            });
        });
    }

    // جمع بيانات الحفر المفتوح
    collectOpenExcavationData(data, workDate, workTime) {
        const openTypes = {
            'unsurfaced_soil_open': 'حفر مفتوح - ترابية غير مسفلتة',
            'surfaced_soil_open': 'حفر مفتوح - ترابية مسفلتة',
            'surfaced_rock_open': 'حفر مفتوح - صخرية مسفلتة',
            'unsurfaced_rock_open': 'حفر مفتوح - صخرية غير مسفلتة'
        };

        Object.entries(openTypes).forEach(([type, typeName]) => {
            const lengthInput = document.querySelector(`[name="excavation_${type}[length]"]`);
            const widthInput = document.querySelector(`[name="excavation_${type}[width]"]`);
            const depthInput = document.querySelector(`[name="excavation_${type}[depth]"]`);
            const priceInput = document.querySelector(`[name="excavation_${type}_price"]`);
            const volumeElement = document.getElementById(`total_${type}`);
            const totalElement = document.getElementById(`final_total_${type}`);

            if (lengthInput && widthInput && depthInput) {
                const length = parseFloat(lengthInput.value) || 0;
                const width = parseFloat(widthInput.value) || 0;
                const depth = parseFloat(depthInput.value) || 0;
                const price = parseFloat(priceInput?.value) || 0;
                const volume = parseFloat(volumeElement?.value) || 0;
                const total = parseFloat(totalElement?.value) || 0;

                if (length > 0 || width > 0 || depth > 0 || volume > 0 || total > 0) {
                    data.push({
                        id: `${type}_${Date.now()}`,
                        excavation_type: typeName,
                        cable_name: 'حفر مفتوح',
                        length: length,
                        width: width,
                        depth: depth,
                        volume: volume,
                        price: price,
                        total: total,
                        work_date: workDate,
                        work_time: workTime,
                        category: 'open_excavation'
                    });
                }
            }
        });
    }

    // جمع بيانات الأعمال الكهربائية
    collectElectricalData(data, workDate, workTime) {
        const electricalTypes = {
            'cable_4x70_low': 'تمديد كيبل 4x70 منخفض',
            'cable_4x185_low': 'تمديد كيبل 4x185 منخفض',
            'cable_4x300_low': 'تمديد كيبل 4x300 منخفض',
            'cable_3x500_med': 'تمديد كيبل 3x500 متوسط',
            'cable_3x400_med': 'تمديد كيبل 3x400 متوسط'
        };

        Object.entries(electricalTypes).forEach(([type, typeName]) => {
            const lengthInput = document.querySelector(`[name="electrical_items[${type}][meters]"]`);
            const priceInput = document.querySelector(`[name="electrical_items[${type}][price]"]`);
            const totalElement = document.querySelector(`#final_total_${type}`);

            if (lengthInput && priceInput) {
                const length = parseFloat(lengthInput.value) || 0;
                const price = parseFloat(priceInput.value) || 0;
                const total = parseFloat(totalElement?.value) || 0;

                if (length > 0 || price > 0 || total > 0) {
                    data.push({
                        id: `${type}_${Date.now()}`,
                        excavation_type: typeName,
                        cable_name: 'تمديد كهربائي',
                        length: length,
                        price: price,
                        total: total,
                        work_date: workDate,
                        work_time: workTime,
                        category: 'electrical'
                    });
                }
            }
        });
    }

    // جمع بيانات الأسفلت
    collectAsphaltData(data, workDate, workTime) {
        const asphaltTypes = {
            'first_asphalt': 'أسفلت طبقة أولى',
            'asphalt_scraping': 'كشط واعادة السفلتة'
        };

        Object.entries(asphaltTypes).forEach(([type, typeName]) => {
            const lengthInput = document.querySelector(`[name="open_excavation[${type}][length]"]`);
            const priceInput = document.querySelector(`[name="open_excavation[${type}][price]"]`);
            const totalElement = document.getElementById(`final_total_${type}`);

            if (lengthInput && priceInput) {
                const length = parseFloat(lengthInput.value) || 0;
                const price = parseFloat(priceInput.value) || 0;
                const total = parseFloat(totalElement?.value) || 0;

                if (length > 0 || price > 0 || total > 0) {
                    data.push({
                        id: `${type}_${Date.now()}`,
                        excavation_type: typeName,
                        cable_name: 'أسفلت',
                        length: length,
                        price: price,
                        total: total,
                        work_date: workDate,
                        work_time: workTime,
                        category: 'asphalt'
                    });
                }
            }
        });
    }

    // جمع بيانات الحفريات الدقيقة
    collectPreciseExcavationData(data, workDate, workTime) {
        const preciseTypes = {
            'medium': {
                name: 'حفر متوسط',
                dimensions: '20 × 80'
            },
            'low': {
                name: 'حفر منخفض',
                dimensions: '20 × 56'
            }
        };

        Object.entries(preciseTypes).forEach(([type, info]) => {
            const lengthInput = document.querySelector(`[name="excavation_precise[${type}]"]`);
            const priceInput = document.querySelector(`[name="excavation_precise[${type}_price]"]`);
            const totalElement = document.getElementById(`final_total_precise_${type}`);

            if (lengthInput && priceInput) {
                const length = parseFloat(lengthInput.value) || 0;
                const price = parseFloat(priceInput.value) || 0;
                const total = parseFloat(totalElement?.value) || 0;

                if (length > 0 || price > 0 || total > 0) {
                    data.push({
                        id: `precise_${type}_${Date.now()}`,
                        excavation_type: info.name,
                        cable_name: `حفر دقيق ${info.dimensions}`,
                        length: length,
                        price: price,
                        total: total,
                        work_date: workDate,
                        work_time: workTime,
                        category: 'precise_excavation'
                    });
                }
            }
        });
    }
}

// 6. عرض البيانات في الواجهة
class UIManager {
    constructor() {
        this.stateManager = CivilWorksStateManager.getInstance();
        this.setupEventListeners();
        this.setupCalculationListeners();
    }

    // إعداد مستمعي الأحداث
    setupEventListeners() {
        this.stateManager.addEventListener('STATE_UPDATED', (data) => {
            this.updateUI(data.newState);
        });

        this.stateManager.addEventListener('ERROR_ADDED', (error) => {
            this.showError(error);
        });
    }

    // إعداد مستمعي الحسابات
    setupCalculationListeners() {
        // مستمعي الحفريات العادية
        document.querySelectorAll('.calc-length, .calc-price').forEach(input => {
            input.addEventListener('input', (e) => this.handleNormalExcavationCalculation(e.target));
        });

        // مستمعي الحفر المفتوح
        document.querySelectorAll('.calc-volume-length, .calc-volume-width, .calc-volume-depth, .calc-volume-price').forEach(input => {
            input.addEventListener('input', (e) => this.handleOpenExcavationCalculation(e.target));
        });

        // مستمعي الحفريات الدقيقة
        document.querySelectorAll('.calc-precise-length, .calc-precise-price').forEach(input => {
            input.addEventListener('input', (e) => this.handlePreciseExcavationCalculation(e.target));
        });

        // مستمعي الأعمال الكهربائية
        document.querySelectorAll('.calc-electrical-length, .calc-electrical-price').forEach(input => {
            input.addEventListener('input', (e) => this.handleElectricalCalculation(e.target));
        });

        // مستمعي الأسفلت
        document.querySelectorAll('.calc-area-length, .calc-area-price').forEach(input => {
            input.addEventListener('input', (e) => this.handleAsphaltCalculation(e.target));
        });

        console.log('✅ تم إعداد مستمعي الحسابات');
    }

    // تحديث الواجهة
    updateUI(state) {
        this.updateStatistics(state.statistics);
        this.updateTable(state.dailyData);
        this.updateLoadingState(state.isLoading, state.isSaving);
    }

    // تحديث الإحصائيات
    updateStatistics(statistics) {
        const elements = {
            'total-length': statistics.totalLength,
            'total-amount': statistics.totalAmount,
            'items-count': statistics.itemsCount,
            'daily-items-count': statistics.itemsCount,
            'daily-total-length': statistics.totalLength,
            'daily-total-cost': statistics.totalAmount
        };

        Object.entries(elements).forEach(([id, value]) => {
            const element = document.getElementById(id);
            if (element) {
                element.textContent = typeof value === 'number' ? value.toFixed(2) : value;
            }
        });
    }

    // تحديث الجدول
    updateTable(data) {
        const tbody = document.getElementById('daily-excavation-tbody');
        if (!tbody) return;

        if (!data || data.length === 0) {
            tbody.innerHTML = `
                <tr id="no-data-row">
                    <td colspan="7" class="text-center text-muted py-4">
                        <i class="fas fa-clipboard-list fa-2x mb-2"></i>
                        <br>
                        لا توجد بيانات حفريات
                    </td>
                </tr>
            `;
            return;
        }

        tbody.innerHTML = data.map((item, index) => {
            const badgeClass = this.getBadgeClass(item.category);
            const lengthDisplay = this.formatLengthDisplay(item);
            
            return `
                <tr class="daily-excavation-row">
                    <td class="text-center">${index + 1}</td>
                    <td class="text-center">
                        <span class="badge ${badgeClass}">${item.excavation_type || 'غير محدد'}</span>
                    </td>
                    <td class="text-center">
                        <span class="badge bg-secondary">${item.cable_name || 'غير محدد'}</span>
                    </td>
                    <td class="text-center">${lengthDisplay}</td>
                    <td class="text-center">${parseFloat(item.price || 0).toFixed(2)} ريال</td>
                    <td class="text-center">${parseFloat(item.total || 0).toFixed(2)} ريال</td>
                    <td class="text-center">
                        <small class="text-muted">${item.work_date || ''} ${item.work_time || ''}</small>
                    </td>
                </tr>
            `;
        }).join('');
    }

    // تنسيق عرض الطول
    formatLengthDisplay(item) {
        if (item.category === 'open_excavation') {
            return `
                <div>الطول: ${parseFloat(item.length || 0).toFixed(2)} م</div>
                <div>العرض: ${parseFloat(item.width || 0).toFixed(2)} م</div>
                <div>العمق: ${parseFloat(item.depth || 0).toFixed(2)} م</div>
                <div class="mt-1 fw-bold">الحجم: ${parseFloat(item.volume || 0).toFixed(2)} م³</div>
            `;
        }
        return `${parseFloat(item.length || 0).toFixed(2)} م`;
    }

    // الحصول على كلاس البادج
    getBadgeClass(category) {
        const classes = {
            'excavation': 'bg-info',
            'open_excavation': 'bg-warning text-dark',
            'electrical': 'bg-primary'
        };
        return classes[category] || 'bg-secondary';
    }

    // تحديث حالة التحميل
    updateLoadingState(isLoading, isSaving) {
        const saveButton = document.getElementById('save-daily-summary-btn');
        if (saveButton) {
            saveButton.disabled = isLoading || isSaving;
            saveButton.innerHTML = isSaving ? 
                '<i class="fas fa-spinner fa-spin me-2"></i>جاري الحفظ...' : 
                '<i class="fas fa-save me-2"></i>حفظ الملخص';
        }
    }

    // عرض الأخطاء
    showError(error) {
        console.error('خطأ:', error);
        
        // يمكن إضافة نظام إشعارات أكثر تقدماً هنا
        const message = error.message || 'حدث خطأ غير متوقع';
        alert(`خطأ: ${message}`);
    }

    // عرض رسالة نجاح
    showSuccess(message) {
        console.log('نجح:', message);
        alert(`تم بنجاح: ${message}`);
    }

    // حساب الحفريات العادية
    handleNormalExcavationCalculation(input) {
        const row = input.closest('tr');
        if (!row) return;

        const lengthInput = row.querySelector('.calc-length');
        const priceInput = row.querySelector('.calc-price');
        const totalInput = row.querySelector('.total-calc');

        if (lengthInput && priceInput && totalInput) {
            const length = parseFloat(lengthInput.value) || 0;
            const price = parseFloat(priceInput.value) || 0;
            const total = length * price;

            totalInput.value = total.toFixed(2);
            this.updateTotalStatistics();
        }
    }

    // حساب الحفر المفتوح
    handleOpenExcavationCalculation(input) {
        const type = input.dataset.table;
        if (!type) return;

        const lengthInput = document.querySelector(`[name="excavation_${type}[length]"]`);
        const widthInput = document.querySelector(`[name="excavation_${type}[width]"]`);
        const depthInput = document.querySelector(`[name="excavation_${type}[depth]"]`);
        const priceInput = document.querySelector(`[name="excavation_${type}_price"]`);
        const volumeElement = document.getElementById(`total_${type}`);
        const totalElement = document.getElementById(`final_total_${type}`);

        if (lengthInput && widthInput && depthInput && priceInput && volumeElement && totalElement) {
            const length = parseFloat(lengthInput.value) || 0;
            const width = parseFloat(widthInput.value) || 0;
            const depth = parseFloat(depthInput.value) || 0;
            const price = parseFloat(priceInput.value) || 0;

            const volume = length * width * depth;
            const total = volume * price;

            volumeElement.value = volume.toFixed(2);
            totalElement.value = total.toFixed(2);
            this.updateTotalStatistics();
        }
    }

    // حساب الحفريات الدقيقة
    handlePreciseExcavationCalculation(input) {
        const type = input.dataset.type;
        if (!type) return;

        const lengthInput = document.querySelector(`[name="excavation_precise[${type}]"]`);
        const priceInput = document.querySelector(`[name="excavation_precise[${type}_price]"]`);
        const totalElement = document.getElementById(`final_total_precise_${type}`);

        if (lengthInput && priceInput && totalElement) {
            const length = parseFloat(lengthInput.value) || 0;
            const price = parseFloat(priceInput.value) || 0;
            const total = length * price;

            totalElement.value = total.toFixed(2);
            this.updateTotalStatistics();
        }
    }

    // حساب الأعمال الكهربائية
    handleElectricalCalculation(input) {
        const type = input.dataset.type;
        if (!type) return;

        const lengthInput = document.querySelector(`[name="electrical_items[${type}][meters]"]`);
        const priceInput = document.querySelector(`[name="electrical_items[${type}][price]"]`);
        const totalElement = document.getElementById(`final_total_${type}`);

        if (lengthInput && priceInput && totalElement) {
            const length = parseFloat(lengthInput.value) || 0;
            const price = parseFloat(priceInput.value) || 0;
            const total = length * price;

            totalElement.value = total.toFixed(2);
            this.updateTotalStatistics();
        }
    }

    // حساب الأسفلت
    handleAsphaltCalculation(input) {
        const type = input.dataset.table;
        if (!type) return;

        const lengthInput = document.querySelector(`[name="open_excavation[${type}][length]"]`);
        const priceInput = document.querySelector(`[name="open_excavation[${type}][price]"]`);
        const totalElement = document.getElementById(`final_total_${type}`);

        if (lengthInput && priceInput && totalElement) {
            const length = parseFloat(lengthInput.value) || 0;
            const price = parseFloat(priceInput.value) || 0;
            const total = length * price;

            totalElement.value = total.toFixed(2);
            this.updateTotalStatistics();
        }
    }

    // تحديث الإحصائيات الكلية
    updateTotalStatistics() {
        let totalLength = 0;
        let totalAmount = 0;

        // حساب الحفريات العادية
        document.querySelectorAll('.calc-length').forEach(lengthInput => {
            totalLength += parseFloat(lengthInput.value) || 0;
        });

        // حساب الحفر المفتوح
        document.querySelectorAll('.calc-volume-length').forEach(lengthInput => {
            totalLength += parseFloat(lengthInput.value) || 0;
        });

        // حساب الحفريات الدقيقة
        document.querySelectorAll('.calc-precise-length').forEach(lengthInput => {
            totalLength += parseFloat(lengthInput.value) || 0;
        });

        // حساب الأعمال الكهربائية
        document.querySelectorAll('.calc-electrical-length').forEach(lengthInput => {
            totalLength += parseFloat(lengthInput.value) || 0;
        });

        // حساب الأسفلت
        document.querySelectorAll('.calc-area-length').forEach(lengthInput => {
            totalLength += parseFloat(lengthInput.value) || 0;
        });

        // حساب إجمالي المبالغ
        document.querySelectorAll('.total-calc, .volume-total-calc, .precise-total-calc, .electrical-total-calc, .area-total-calc').forEach(totalInput => {
            totalAmount += parseFloat(totalInput.value) || 0;
        });

        // تحديث العناصر في الواجهة
        const elements = {
            'total-length': totalLength,
            'total-amount': totalAmount,
            'daily-total-length': totalLength,
            'daily-total-cost': totalAmount
        };

        Object.entries(elements).forEach(([id, value]) => {
            const element = document.getElementById(id);
            if (element) {
                element.textContent = value.toFixed(2);
            }
        });
    }
}

// 7. التحكم الرئيسي في النظام
class CivilWorksController {
    constructor() {
        this.stateManager = CivilWorksStateManager.getInstance();
        this.localStorage = new LocalStorageManager();
        this.apiManager = new ApiManager();
        this.dataCollector = new DataCollector();
        this.uiManager = new UIManager();
        
        this.debounceTimer = null;
    }

    // تهيئة النظام
    async initialize(workOrderId, csrfToken, savedData = []) {
        try {
            console.log('🚀 بدء تهيئة نظام الأعمال المدنية المحترف');
            
            // تهيئة إدارة الحالة
            this.stateManager.initialize(workOrderId, csrfToken, savedData);
            
            // تحميل البيانات
            await this.loadData();
            
            // إعداد واجهة المستخدم
            this.setupUI();
            
            console.log('✅ تم تهيئة النظام بنجاح');
            return true;
        } catch (error) {
            console.error('❌ خطأ في تهيئة النظام:', error);
            this.stateManager.addError(error);
            return false;
        }
    }

    // تحميل البيانات
    async loadData() {
        this.stateManager.setState({ isLoading: true });
        
        try {
            let data = [];
            
            // أولاً: البيانات المحفوظة في الحالة
            const currentData = this.stateManager.getState().dailyData;
            if (currentData && currentData.length > 0) {
                data = currentData;
                console.log('📋 تم تحميل البيانات من الحالة الحالية');
            }
            
            // ثانياً: البيانات المحلية
            if (data.length === 0) {
                const localData = this.localStorage.load();
                if (localData && localData.length > 0) {
                    data = localData;
                    console.log('💾 تم تحميل البيانات من التخزين المحلي');
                }
            }
            
            // ثالثاً: البيانات من الخادم
            if (data.length === 0) {
                try {
                    const serverResponse = await this.apiManager.loadFromServer();
                    if (serverResponse.success && serverResponse.data) {
                        data = Array.isArray(serverResponse.data) ? serverResponse.data : [];
                        console.log('🌐 تم تحميل البيانات من الخادم');
                        
                        // حفظ البيانات محلياً للمرة القادمة
                        this.localStorage.save(data);
                    }
                } catch (error) {
                    console.warn('⚠️ فشل في تحميل البيانات من الخادم:', error);
                }
            }
            
            // تحديث الحالة والإحصائيات
            this.updateStateWithData(data);
            
        } catch (error) {
            console.error('❌ خطأ في تحميل البيانات:', error);
            this.stateManager.addError(error);
        } finally {
            this.stateManager.setState({ isLoading: false });
        }
    }

    // تحديث الحالة مع البيانات
    updateStateWithData(data) {
        const statistics = this.calculateStatistics(data);
        
        this.stateManager.setState({
            dailyData: data,
            statistics: statistics
        });
    }

    // حساب الإحصائيات
    calculateStatistics(data) {
        if (!Array.isArray(data)) {
            return { totalLength: 0, totalAmount: 0, itemsCount: 0 };
        }

        const statistics = data.reduce((acc, item) => {
            acc.totalLength += parseFloat(item.length || 0);
            acc.totalAmount += parseFloat(item.total || 0);
            acc.itemsCount += 1;
            return acc;
        }, { totalLength: 0, totalAmount: 0, itemsCount: 0 });

        return statistics;
    }

    // إعداد واجهة المستخدم
    setupUI() {
        const saveButton = document.getElementById('save-daily-summary-btn');
        if (saveButton && !saveButton.hasAttribute('data-civil-works-listener')) {
            saveButton.addEventListener('click', (e) => {
                e.preventDefault();
                this.saveData();
            });
            saveButton.setAttribute('data-civil-works-listener', 'true');
            console.log('🎯 تم ربط زر الحفظ بالنظام');
        }

        const clearButton = document.querySelector('[onclick*="clearSavedData"]');
        if (clearButton) {
            clearButton.onclick = () => this.clearData();
        }
    }

    // حفظ البيانات
    async saveData() {
        if (this.stateManager.getState().isSaving) {
            console.log('⏳ عملية حفظ جارية بالفعل');
            return;
        }

        this.stateManager.setState({ isSaving: true });
        
        try {
            // جمع البيانات من النماذج
            const formData = this.dataCollector.collectFormData();
            
            if (formData.length === 0) {
                this.uiManager.showError({ message: 'لا توجد بيانات للحفظ' });
                return;
            }

            console.log(`💾 بدء حفظ ${formData.length} عنصر`);

            // حفظ في الخادم
            let serverSuccess = false;
            try {
                const serverResponse = await this.apiManager.saveToServer(formData);
                if (serverResponse.success) {
                    serverSuccess = true;
                    console.log('✅ تم حفظ البيانات في الخادم');
                }
            } catch (error) {
                console.error('❌ فشل في حفظ البيانات في الخادم:', error);
            }

            // حفظ محلياً (دائماً)
            const currentData = this.stateManager.getState().dailyData;
            const updatedData = [...currentData, ...formData];
            this.localStorage.save(updatedData);

            // تحديث الحالة
            this.updateStateWithData(updatedData);

            // رسالة النجاح
            const message = serverSuccess ? 
                `تم حفظ ${formData.length} عنصر في الخادم والتخزين المحلي` :
                `تم حفظ ${formData.length} عنصر في التخزين المحلي (سيتم المزامنة لاحقاً)`;
            
            this.uiManager.showSuccess(message);

        } catch (error) {
            console.error('❌ خطأ في عملية الحفظ:', error);
            this.stateManager.addError(error);
        } finally {
            this.stateManager.setState({ isSaving: false });
        }
    }

    // مسح البيانات
    clearData() {
        if (confirm('هل تريد فعلاً مسح جميع البيانات المحفوظة؟')) {
            try {
                // مسح البيانات المحلية
                this.localStorage.clearAll();
                
                // إعادة تعيين الحالة
                this.stateManager.setState({
                    dailyData: [],
                    statistics: { totalLength: 0, totalAmount: 0, itemsCount: 0 }
                });
                
                console.log('🧹 تم مسح جميع البيانات');
                this.uiManager.showSuccess('تم مسح جميع البيانات');
            } catch (error) {
                console.error('❌ خطأ في مسح البيانات:', error);
                this.stateManager.addError(error);
            }
        }
    }

    // الحصول على إحصائيات النظام
    getSystemStats() {
        const state = this.stateManager.getState();
        return {
            workOrderId: state.workOrderId,
            dataCount: state.dailyData.length,
            statistics: state.statistics,
            errors: state.errors.length,
            isLoading: state.isLoading,
            isSaving: state.isSaving
        };
    }
}

// 8. إنشاء النسخة الوحيدة من النظام
const civilWorksSystem = new CivilWorksController();

// 9. الدوال العامة للتوافق مع النظام القديم
window.saveData = function() {
    console.log('🔄 استدعاء saveData من النظام القديم');
    civilWorksSystem.saveData();
};

window.clearSavedData = function() {
    console.log('🔄 استدعاء clearSavedData من النظام القديم');
    civilWorksSystem.clearData();
};

window.loadSavedDailyWork = function() {
    console.log('🔄 استدعاء loadSavedDailyWork من النظام القديم');
    civilWorksSystem.loadData();
};

window.updateStatisticsFromSavedData = function(data) {
    console.log('🔄 استدعاء updateStatisticsFromSavedData من النظام القديم');
    if (data && Array.isArray(data)) {
        civilWorksSystem.updateStateWithData(data);
    }
};

// 10. تهيئة النظام عند تحميل الصفحة
window.initializeCivilWorks = async function(workOrderId, csrfToken, savedData = []) {
    console.log('🚀 بدء تهيئة نظام الأعمال المدنية المحترف v2.0');
    
    try {
        const success = await civilWorksSystem.initialize(workOrderId, csrfToken, savedData);
        if (success) {
            console.log('✅ تم تهيئة النظام بنجاح');
            console.log('📊 إحصائيات النظام:', civilWorksSystem.getSystemStats());
        } else {
            console.error('❌ فشل في تهيئة النظام');
        }
        return success;
    } catch (error) {
        console.error('❌ خطأ في تهيئة النظام:', error);
        return false;
    }
};

// 11. جعل النظام متاح عالمياً للتطوير والصيانة
window.civilWorksSystem = civilWorksSystem;

console.log('🏗️ تم تحميل نظام الأعمال المدنية المحترف v2.0');
console.log('📋 الوظائف المتاحة: saveData, clearSavedData, loadSavedDailyWork, updateStatisticsFromSavedData');
console.log('🔧 للتطوير: civilWorksSystem متاح في window');