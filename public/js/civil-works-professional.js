/**
 * ========================================
 * نظام الأعمال المدنية المحترف - إصدار نظيف
 * ========================================
 */

// 1. تنظيف شامل للنظام من أي تضارب سابق
console.log('🧹 بدء تنظيف النظام من أي تضارب سابق...');

// إزالة أي كائنات قديمة
const oldObjects = [
    'civilWorksSystem',
    'CivilWorksManager',
    'ExcavationLogger',
    'saveData',
    'clearSavedData',
    'loadSavedDailyWork',
    'updateStatisticsFromSavedData',
    'initializeCivilWorks',
    'saveImages'
];

oldObjects.forEach(obj => {
    if (window[obj]) {
        console.log(`🗑️ إزالة ${obj}...`);
        delete window[obj];
    }
});

// إزالة أي event listeners قديمة
const oldEventListeners = document.querySelectorAll('[data-civil-works-listener]');
oldEventListeners.forEach(element => {
    element.removeAttribute('data-civil-works-listener');
});

console.log('✅ تم تنظيف النظام بنجاح');

// 2. إعدادات النظام والثوابت
const SYSTEM_CONFIG = {
    MAX_RETRIES: 3,
    RETRY_DELAY: 1000,
    STORAGE_KEY_PREFIX: 'civilWorks_',
    DEBOUNCE_DELAY: 300,
    API_TIMEOUT: 30000
};

// 3. إدارة الحالة المركزية
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

    static getInstance() {
        if (!CivilWorksStateManager.instance) {
            CivilWorksStateManager.instance = new CivilWorksStateManager();
        }
        return CivilWorksStateManager.instance;
    }

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

    setState(updates) {
        const oldState = { ...this.state };
        this.state = { ...this.state, ...updates };
        this.notify('STATE_UPDATED', { oldState, newState: this.state });
    }

    getState() {
        return { ...this.state };
    }

    addError(error) {
        this.state.errors.push({
            message: error.message || error,
            timestamp: new Date().toISOString()
        });
        this.notify('ERROR_ADDED', error);
    }

    addEventListener(event, callback) {
        if (!this.listeners.has(event)) {
            this.listeners.set(event, new Set());
        }
        this.listeners.get(event).add(callback);
    }

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

    reset() {
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
        this.initialized = false;
        this.listeners.clear();
    }
}

// 4. إدارة التخزين المحلي
class LocalStorageManager {
    constructor() {
        this.stateManager = CivilWorksStateManager.getInstance();
    }

    getStorageKey(suffix = 'dailyData') {
        const { workOrderId } = this.stateManager.getState();
        return `${SYSTEM_CONFIG.STORAGE_KEY_PREFIX}${workOrderId}_${suffix}`;
    }

    save(data) {
        try {
            const key = this.getStorageKey();
            const serializedData = JSON.stringify(data);
            localStorage.setItem(key, serializedData);
            console.log(`💾 تم حفظ البيانات محلياً في ${key}`);
            return true;
        } catch (error) {
            console.error('❌ خطأ في حفظ البيانات محلياً:', error);
            return false;
        }
    }

    load() {
        try {
            const key = this.getStorageKey();
            const serializedData = localStorage.getItem(key);
            if (serializedData) {
                const data = JSON.parse(serializedData);
                console.log(`📥 تم تحميل البيانات المحلية من ${key}`);
                return Array.isArray(data) ? data : [];
            }
            return [];
        } catch (error) {
            console.error('❌ خطأ في تحميل البيانات المحلية:', error);
            return [];
        }
    }

    clearAll() {
        try {
            const keys = Object.keys(localStorage);
            const prefix = SYSTEM_CONFIG.STORAGE_KEY_PREFIX;
            keys.forEach(key => {
                if (key.startsWith(prefix)) {
                    localStorage.removeItem(key);
                }
            });
            console.log('🧹 تم مسح جميع البيانات المحلية');
        } catch (error) {
            console.error('❌ خطأ في مسح البيانات المحلية:', error);
        }
    }
}

// 5. إدارة API
class ApiManager {
    constructor() {
        this.stateManager = CivilWorksStateManager.getInstance();
    }

    async request(url, options = {}) {
        const { csrfToken } = this.stateManager.getState();
        
        const defaultOptions = {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            timeout: SYSTEM_CONFIG.API_TIMEOUT
        };

        const mergedOptions = { ...defaultOptions, ...options };

        try {
            const response = await fetch(url, mergedOptions);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            return data;
        } catch (error) {
            console.error(`❌ خطأ في طلب API ${url}:`, error);
            throw error;
        }
    }

    async saveToServer(data) {
        const { workOrderId } = this.stateManager.getState();
        const url = `/admin/work-orders/${workOrderId}/civil-works/save-daily-data`;
        
        const payload = {
            daily_work: data,
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

// 6. جمع البيانات
class DataCollector {
    constructor() {
        this.stateManager = CivilWorksStateManager.getInstance();
    }

    collectFormData() {
        const data = [];
        const timestamp = new Date();
        const workDate = timestamp.toISOString().split('T')[0];
        const workTime = timestamp.toLocaleTimeString('ar-SA');

        try {
            this.collectExcavationData(data, workDate, workTime);
            this.collectOpenExcavationData(data, workDate, workTime);
            this.collectElectricalData(data, workDate, workTime);
            this.collectAsphaltData(data, workDate, workTime);
            this.collectPreciseExcavationData(data, workDate, workTime);

            console.log(`📊 تم جمع ${data.length} عنصر من البيانات`);
            return data;
        } catch (error) {
            console.error('خطأ في جمع البيانات:', error);
            this.stateManager.addError(error);
            return [];
        }
    }

    collectExcavationData(data, workDate, workTime) {
        const excavationTypes = {
            'unsurfaced_soil': 'حفرية ترابية غير مسفلتة',
            'surfaced_soil': 'حفرية ترابية مسفلتة', 
            'surfaced_rock': 'حفرية صخرية مسفلتة',
            'unsurfaced_rock': 'حفرية صخرية غير مسفلتة'
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

// 7. إدارة الواجهة
class UIManager {
    constructor() {
        this.stateManager = CivilWorksStateManager.getInstance();
        this.setupEventListeners();
    }

    setupEventListeners() {
        this.stateManager.addEventListener('STATE_UPDATED', (data) => {
            this.updateUI(data.newState);
        });

        this.stateManager.addEventListener('ERROR_ADDED', (error) => {
            this.showError(error);
        });

        // تأخير إعداد مستمعي الحسابات للتأكد من تحميل DOM
        setTimeout(() => {
            this.setupCalculationListeners();
        }, 100);
    }

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

    updateUI(state) {
        this.updateStatistics(state.statistics);
        this.updateTable(state.dailyData);
        this.updateLoadingState(state.isLoading, state.isSaving);
    }

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

    getBadgeClass(category) {
        const classes = {
            'excavation': 'bg-primary',
            'open_excavation': 'bg-success',
            'electrical': 'bg-warning',
            'asphalt': 'bg-info',
            'precise_excavation': 'bg-danger'
        };
        return classes[category] || 'bg-secondary';
    }

    updateLoadingState(isLoading, isSaving) {
        const saveBtn = document.getElementById('save-daily-summary-btn');
        if (saveBtn) {
            saveBtn.disabled = isLoading || isSaving;
            saveBtn.textContent = isSaving ? 'جاري الحفظ...' : 'حفظ البيانات';
        }
    }

    showError(error) {
        const message = error.message || error;
        console.error('❌ خطأ:', message);
        if (typeof toastr !== 'undefined') {
            toastr.error(message);
        } else {
            alert(`خطأ: ${message}`);
        }
    }

    showSuccess(message) {
        console.log('✅ نجح:', message);
        if (typeof toastr !== 'undefined') {
            toastr.success(message);
        } else {
            alert(`تم بنجاح: ${message}`);
        }
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

// 8. التحكم الرئيسي
class CivilWorksController {
    constructor() {
        this.stateManager = CivilWorksStateManager.getInstance();
        this.localStorage = new LocalStorageManager();
        this.apiManager = new ApiManager();
        this.dataCollector = new DataCollector();
        this.uiManager = new UIManager();
        this.debounceTimer = null;
    }

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

    async loadData() {
        this.stateManager.setState({ isLoading: true });
        
        try {
            let data = [];
            
            // البيانات المحفوظة في الحالة
            const currentData = this.stateManager.getState().dailyData;
            if (currentData && currentData.length > 0) {
                data = currentData;
                console.log('📋 تم تحميل البيانات من الحالة الحالية');
            }
            
            // البيانات المحلية
            if (data.length === 0) {
                const localData = this.localStorage.load();
                if (localData && localData.length > 0) {
                    data = localData;
                    console.log('💾 تم تحميل البيانات من التخزين المحلي');
                }
            }
            
            // البيانات من الخادم
            if (data.length === 0) {
                try {
                    const serverResponse = await this.apiManager.loadFromServer();
                    if (serverResponse.success && serverResponse.data) {
                        data = Array.isArray(serverResponse.data) ? serverResponse.data : [];
                        console.log('🌐 تم تحميل البيانات من الخادم');
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

    updateStateWithData(data) {
        const statistics = this.calculateStatistics(data);
        
        this.stateManager.setState({
            dailyData: data,
            statistics: statistics
        });
    }

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
                this.uiManager.showError('لا توجد بيانات للحفظ');
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

    clearData() {
        if (confirm('هل تريد فعلاً مسح جميع البيانات المحفوظة؟')) {
            try {
                this.localStorage.clearAll();
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

// 9. إنشاء النظام وتصديره
const civilWorksSystem = new CivilWorksController();

// 10. إنشاء الدوال العامة
window.saveData = function() {
    console.log('🔄 استدعاء saveData');
    civilWorksSystem.saveData();
};

window.clearSavedData = function() {
    console.log('🔄 استدعاء clearSavedData');
    civilWorksSystem.clearData();
};

window.loadSavedDailyWork = function() {
    console.log('🔄 استدعاء loadSavedDailyWork');
    civilWorksSystem.loadData();
};

window.updateStatisticsFromSavedData = function(data) {
    console.log('🔄 استدعاء updateStatisticsFromSavedData');
    if (data && Array.isArray(data)) {
        civilWorksSystem.updateStateWithData(data);
    }
};

// 11. دالة التهيئة الرئيسية
window.initializeCivilWorks = async function(workOrderId, csrfToken, savedData = []) {
    console.log('🚀 بدء تهيئة نظام الأعمال المدنية المحترف');
    
    try {
        // تنظيف أي حالة سابقة
        if (civilWorksSystem.stateManager.initialized) {
            console.log('🔄 إعادة تهيئة النظام...');
            civilWorksSystem.stateManager.reset();
        }

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

// 12. جعل النظام متاح عالمياً
window.civilWorksSystem = civilWorksSystem;

// 13. تأكيد تحميل النظام
console.log('🏗️ تم تحميل نظام الأعمال المدنية المحترف - إصدار نظيف');
console.log('📋 الوظائف المتاحة: saveData, clearSavedData, loadSavedDailyWork, updateStatisticsFromSavedData');
console.log('🔧 للتطوير: civilWorksSystem متاح في window');
console.log('🎯 دالة التهيئة: initializeCivilWorks');

// 14. تشخيص النظام
console.log('🔍 تشخيص النظام:');
console.log('  - civilWorksSystem:', typeof window.civilWorksSystem);
console.log('  - initializeCivilWorks:', typeof window.initializeCivilWorks);
console.log('  - saveData:', typeof window.saveData);
console.log('  - clearSavedData:', typeof window.clearSavedData);

// 15. التحقق من عدم وجود كائنات قديمة
const checkOldObjects = ['CivilWorksManager', 'ExcavationLogger'];
checkOldObjects.forEach(obj => {
    if (window[obj]) {
        console.warn(`⚠️ تم العثور على كائن قديم: ${obj}`);
    }
});

console.log('✅ تم التحقق من النظام بنجاح');

// تعريف دالة حفظ الصور
window.saveImages = async function() {
    console.log('🖼️ بدء عملية حفظ الصور...');
    
    // التأكد من وجود Toastr
    if (typeof toastr === 'undefined') {
        console.error('❌ مكتبة Toastr غير متوفرة');
        alert('الرجاء اختيار الصور أولاً');
        return;
    }
    
    const imagesInput = document.getElementById('civil_works_images');
    if (!imagesInput || !imagesInput.files || imagesInput.files.length === 0) {
        toastr.warning('الرجاء اختيار الصور أولاً');
        return;
    }

    const formData = new FormData();
    for (let i = 0; i < imagesInput.files.length; i++) {
        formData.append('civil_works_images[]', imagesInput.files[i]);
    }

    const workOrderId = document.querySelector('meta[name="work-order-id"]').content;
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    try {
        const response = await fetch(`/admin/work-orders/${workOrderId}/civil-works/images`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            body: formData
        });

        const result = await response.json();

        if (result.success) {
            toastr.success('تم حفظ الصور بنجاح');
            // تحديث عرض الصور إذا لزم الأمر
            location.reload();
        } else {
            toastr.error(result.message || 'حدث خطأ أثناء حفظ الصور');
        }
    } catch (error) {
        console.error('❌ خطأ في حفظ الصور:', error);
        toastr.error('حدث خطأ أثناء حفظ الصور');
    }
};

// دالة حذف الصور
window.deleteImage = async function(imageId) {
    console.log('🗑️ بدء عملية حذف الصورة...', imageId);
    
    if (!confirm('هل أنت متأكد من حذف هذه الصورة؟')) {
        return;
    }

    const workOrderId = document.querySelector('meta[name="work-order-id"]').content;
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    try {
        const response = await fetch(`/admin/work-orders/${workOrderId}/civil-works/images/${imageId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json'
            }
        });

        const result = await response.json();

        if (result.success) {
            // إزالة عنصر الصورة من DOM
            const imageContainer = document.querySelector(`[data-image-id="${imageId}"]`);
            if (imageContainer) {
                imageContainer.remove();
            }
            toastr.success('تم حذف الصورة بنجاح');
        } else {
            toastr.error(result.message || 'حدث خطأ أثناء حذف الصورة');
        }
    } catch (error) {
        console.error('❌ خطأ في حذف الصورة:', error);
        toastr.error('حدث خطأ أثناء حذف الصورة');
    }
};

// دالة حفظ المرفقات
window.saveAttachments = async function() {
    console.log('📎 بدء عملية حفظ المرفقات...');
    
    const attachmentsInput = document.getElementById('civil_works_attachments');
    if (!attachmentsInput || !attachmentsInput.files || attachmentsInput.files.length === 0) {
        toastr.warning('الرجاء اختيار المرفقات أولاً');
        return;
    }

    const formData = new FormData();
    for (let i = 0; i < attachmentsInput.files.length; i++) {
        formData.append('civil_works_attachments[]', attachmentsInput.files[i]);
    }

    const workOrderId = document.querySelector('meta[name="work-order-id"]').content;
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    try {
        const response = await fetch(`/admin/work-orders/${workOrderId}/civil-works/attachments`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            body: formData
        });

        const result = await response.json();

        if (result.success) {
            toastr.success('تم حفظ المرفقات بنجاح');
            // تحديث عرض المرفقات
            location.reload();
        } else {
            toastr.error(result.message || 'حدث خطأ أثناء حفظ المرفقات');
        }
    } catch (error) {
        console.error('❌ خطأ في حفظ المرفقات:', error);
        toastr.error('حدث خطأ أثناء حفظ المرفقات');
    }
};

// دالة حذف المرفقات
window.deleteAttachment = async function(attachmentId) {
    console.log('🗑️ بدء عملية حذف المرفق...', attachmentId);
    
    if (!confirm('هل أنت متأكد من حذف هذا المرفق؟')) {
        return;
    }

    const workOrderId = document.querySelector('meta[name="work-order-id"]').content;
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    try {
        const response = await fetch(`/admin/work-orders/${workOrderId}/civil-works/attachments/${attachmentId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json'
            }
        });

        const result = await response.json();

        if (result.success) {
            // إزالة عنصر المرفق من DOM
            const attachmentContainer = document.querySelector(`[data-attachment-id="${attachmentId}"]`);
            if (attachmentContainer) {
                attachmentContainer.remove();
            }
            toastr.success('تم حذف المرفق بنجاح');
        } else {
            toastr.error(result.message || 'حدث خطأ أثناء حذف المرفق');
        }
    } catch (error) {
        console.error('❌ خطأ في حذف المرفق:', error);
        toastr.error('حدث خطأ أثناء حذف المرفق');
    }
};