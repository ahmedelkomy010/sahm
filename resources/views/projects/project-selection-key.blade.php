@extends('layouts.app')

@section('content')
<x-main-header>
    <x-slot name="title">اختيار نوع المشروع</x-slot>
    <x-slot name="description">اختر نوع المشروع الذي تريد العمل عليه</x-slot>
</x-main-header>

<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Project Types Grid -->
        <div class="grid md:grid-cols-2 gap-8">
            <!-- Transport Project Card -->
            <div class="project-card group" onclick="selectProject('transport')" id="transport-card">
                <div class="relative bg-white rounded-2xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-xl border-2 border-transparent hover:border-blue-500 cursor-pointer">
                    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-blue-500 to-blue-600"></div>
                    <div class="p-8">
                        <div class="flex items-center justify-center mb-6">
                            <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-truck-moving text-4xl text-blue-600"></i>
                            </div>
                        </div>
                        <h3 class="text-2xl font-bold text-center text-gray-900 mb-4">مشروع النقل</h3>
                        <p class="text-gray-600 text-center mb-6">إدارة وتنفيذ مشاريع النقل والمواصلات</p>
                        <ul class="text-gray-600 space-y-3 mb-6">
                            <li class="flex items-center">
                                <i class="fas fa-check text-blue-500 ml-2"></i>
                                <span>تخطيط وتنفيذ مسارات النقل</span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check text-blue-500 ml-2"></i>
                                <span>إدارة المركبات والمعدات</span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check text-blue-500 ml-2"></i>
                                <span>تنسيق عمليات النقل</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Fire Project Card -->
            <div class="project-card group" onclick="selectProject('fire')" id="fire-card">
                <div class="relative bg-white rounded-2xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-xl border-2 border-transparent hover:border-red-500 cursor-pointer">
                    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-red-500 to-red-600"></div>
                    <div class="p-8">
                        <div class="flex items-center justify-center mb-6">
                            <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-fire-extinguisher text-4xl text-red-600"></i>
                            </div>
                        </div>
                        <h3 class="text-2xl font-bold text-center text-gray-900 mb-4">مشروع الحريق</h3>
                        <p class="text-gray-600 text-center mb-6">إدارة وتنفيذ مشاريع السلامة ومكافحة الحريق</p>
                        <ul class="text-gray-600 space-y-3 mb-6">
                            <li class="flex items-center">
                                <i class="fas fa-check text-red-500 ml-2"></i>
                                <span>تركيب أنظمة إنذار الحريق</span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check text-red-500 ml-2"></i>
                                <span>صيانة معدات السلامة</span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check text-red-500 ml-2"></i>
                                <span>تدريب فرق الطوارئ</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Selection Confirmation -->
        <div id="selection-confirmation" class="hidden mt-8">
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h4 class="text-xl font-bold text-gray-900" id="selected-project-title"></h4>
                        <p class="text-gray-600" id="selected-project-description"></p>
                    </div>
                    <button onclick="confirmSelection()" class="btn-primary flex items-center space-x-2 bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition-colors duration-300">
                        <span>تأكيد الاختيار</span>
                        <i class="fas fa-check"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.project-card.selected .border-transparent {
    @apply border-opacity-100;
}

#transport-card.selected > div {
    @apply border-blue-500 bg-blue-50;
}

#fire-card.selected > div {
    @apply border-red-500 bg-red-50;
}

.btn-primary {
    @apply inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-white focus:outline-none focus:ring-2 focus:ring-offset-2 transition-colors duration-200;
}

.btn-primary:disabled {
    @apply opacity-50 cursor-not-allowed;
}
</style>
@endpush

@push('scripts')
<script>
let selectedProject = null;

function selectProject(type) {
    selectedProject = type;
    
    // Reset all cards
    document.querySelectorAll('.project-card').forEach(card => {
        card.classList.remove('selected');
    });
    
    // Highlight selected card
    const selectedCard = document.getElementById(`${type}-card`);
    selectedCard.classList.add('selected');
    
    // Show confirmation section
    const confirmationSection = document.getElementById('selection-confirmation');
    const projectTitle = document.getElementById('selected-project-title');
    const projectDescription = document.getElementById('selected-project-description');
    
    if (type === 'transport') {
        projectTitle.textContent = 'مشروع النقل';
        projectDescription.textContent = 'سيتم توجيهك إلى إدارة مشاريع النقل والمواصلات';
    } else {
        projectTitle.textContent = 'مشروع الحريق';
        projectDescription.textContent = 'سيتم توجيهك إلى إدارة مشاريع السلامة ومكافحة الحريق';
    }
    
    confirmationSection.classList.remove('hidden');
}

function confirmSelection() {
    if (!selectedProject) return;
    
    // Show loading state
    const confirmButton = document.querySelector('button[onclick="confirmSelection()"]');
    const originalText = confirmButton.innerHTML;
    confirmButton.disabled = true;
    confirmButton.innerHTML = '<i class="fas fa-spinner fa-spin ml-2"></i> جاري التحميل...';
    
    // Send AJAX request
    $.ajax({
        url: '/project/set-type',
        method: 'POST',
        data: {
            project_type: selectedProject,
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            toastr.success('تم اختيار المشروع بنجاح');
            // Redirect after successful selection
            window.location.href = '/dashboard';
        },
        error: function(xhr) {
            toastr.error('حدث خطأ أثناء اختيار المشروع');
            // Reset button state
            confirmButton.disabled = false;
            confirmButton.innerHTML = originalText;
        }
    });
}

// Initialize Toastr options
toastr.options = {
    "closeButton": true,
    "progressBar": true,
    "positionClass": "toast-top-center",
    "timeOut": "3000"
};
</script>
@endpush 