@extends('layouts.app')

@push('styles')
<style>
    .header-gradient {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        position: relative;
        overflow: hidden;
    }
    
    .header-gradient::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.1'%3E%3Ccircle cx='30' cy='30' r='2'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E") repeat;
        opacity: 0.3;
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-green-50 to-emerald-100 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Back Button -->
        <div class="mb-8 flex justify-end">
            <a href="{{ route('projects.design', $project) }}" 
               class="inline-flex items-center px-6 py-3 bg-white/80 backdrop-blur-sm border border-green-200 text-green-700 rounded-xl font-semibold shadow-lg hover:bg-white hover:shadow-xl transition-all duration-300 hover:transform hover:scale-105">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                العودة للتصاميم
            </a>
        </div>
        
        <!-- Header -->
        <div class="header-gradient rounded-3xl shadow-2xl overflow-hidden mb-12 relative">
            <div class="relative z-10 p-8 text-center text-white">
                <div class="mb-6">
                    <div class="w-20 h-20 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                    </div>
                    <h1 class="text-4xl font-bold mb-3">Detail Design</h1>
                    <p class="text-green-100 text-lg">{{ $project->name }}</p>
                    <p class="text-green-100 text-sm mt-2">رقم العقد: {{ $project->contract_number }}</p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-2xl mx-auto">
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4">
                        <p class="text-green-100 text-sm mb-1">نوع المشروع</p>
                        <p class="font-semibold text-lg">{{ $project->getProjectTypeText() }}</p>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4">
                        <p class="text-green-100 text-sm mb-1">الموقع</p>
                        <p class="font-semibold text-lg">{{ $project->location }}</p>
                    </div>
                </div>
                </div>
            </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-lg">
            <div class="flex items-center">
                <svg class="w-6 h-6 text-green-500 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                <p class="text-green-700 font-medium">{{ session('success') }}</p>
            </div>
        </div>
        @endif

        @if(session('error'))
        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-lg">
            <div class="flex items-center">
                <svg class="w-6 h-6 text-red-500 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                <p class="text-red-700 font-medium">{{ session('error') }}</p>
            </div>
        </div>
        @endif

        <!-- Create Folder & Upload Detail Design -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-8">
            <div class="p-8">
                <h2 class="text-xl font-bold text-green-900 mb-6 text-right">إدارة المجلدات والمرفقات</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <!-- Create Folder -->
                    <div class="bg-gradient-to-br from-green-50 to-white rounded-xl p-6 border-2 border-green-100">
                        <div class="flex items-center mb-4">
                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center ml-3">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-green-900">إنشاء مجلد جديد</h3>
                        </div>
                        
                        <form action="{{ route('projects.design.detail.create-folder', $project) }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label for="folder_name" class="block text-sm font-medium text-green-700 mb-2 text-right">اسم المجلد</label>
                                <input type="text" 
                                       id="folder_name" 
                                       name="folder_name" 
                                       required
                                       class="w-full px-4 py-3 border border-green-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 text-right"
                                       placeholder="مثال: المواصفات الفنية">
                                @error('folder_name')
                                    <p class="mt-1 text-sm text-red-600 text-right">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="folder_description" class="block text-sm font-medium text-green-700 mb-2 text-right">الوصف (اختياري)</label>
                                <textarea id="folder_description" 
                                          name="folder_description" 
                                          rows="2"
                                          class="w-full px-4 py-3 border border-green-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 text-right"
                                          placeholder="وصف المجلد..."></textarea>
                            </div>
                            
                            <button type="submit" 
                                    class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-6 rounded-lg shadow-sm transition duration-150 ease-in-out flex items-center justify-center">
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                إنشاء المجلد
                            </button>
                        </form>
                    </div>
                    
                    <!-- Upload Multiple Files -->
                    <div class="bg-gradient-to-br from-green-50 to-white rounded-xl p-6 border-2 border-green-100">
                        <div class="flex items-center mb-4">
                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center ml-3">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-green-900">رفع ملفات متعددة</h3>
                        </div>
                        
                        <form action="{{ route('projects.design.detail.upload-files', $project) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                            @csrf
                            <div>
                                <label for="folder_id" class="block text-sm font-medium text-green-700 mb-2 text-right">اختر المجلد</label>
                                <select id="folder_id" 
                                        name="folder_id" 
                                        class="w-full px-4 py-3 border border-green-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 text-right">
                                    <option value="">المجلد الرئيسي</option>
                                    @foreach($folders as $folder)
                                    <option value="{{ $folder['name'] }}">{{ $folder['name'] }} ({{ $folder['file_count'] }} ملفات)</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div>
                                <label for="files" class="block text-sm font-medium text-green-700 mb-2 text-right">
                                    اختر الملفات
                                    <span class="text-xs text-green-500">(يمكنك اختيار ملفات متعددة)</span>
                                </label>
                                <div class="relative">
                                    <input type="file" 
                                           id="files" 
                                           name="files[]" 
                                           multiple
                                           required
                                           class="hidden"
                                           onchange="updateFileList(this)">
                                    <label for="files" 
                                           class="flex items-center justify-center w-full px-4 py-8 border-2 border-dashed border-green-300 rounded-lg cursor-pointer hover:border-green-500 hover:bg-green-50 transition-all">
                                        <div class="text-center">
                                            <svg class="w-10 h-10 text-green-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                            </svg>
                                            <p class="text-sm text-green-600">اضغط لاختيار الملفات</p>
                                            <p class="text-xs text-green-500 mt-1">أو اسحب الملفات هنا</p>
                                        </div>
                                    </label>
                    </div>
                                <div id="file-list" class="mt-3 space-y-2"></div>
                                @error('files')
                                    <p class="mt-1 text-sm text-red-600 text-right">{{ $message }}</p>
                                @enderror
                    </div>
                            
                            <button type="submit" 
                                    class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-6 rounded-lg shadow-sm transition duration-150 ease-in-out flex items-center justify-center">
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                </svg>
                                رفع الملفات
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Folders and Files List -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="p-8">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-green-900">المجلدات والملفات</h2>
                    <div class="flex items-center space-x-reverse space-x-2">
                        <span class="text-sm text-green-500">
                            {{ count($folders) }} مجلد | {{ count($files) }} ملف
                        </span>
                    </div>
                </div>
                
                @if(count($folders) > 0 || count($files) > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <!-- المجلدات -->
                        @foreach($folders as $folder)
                        <div class="bg-gradient-to-br from-green-50 to-white border-2 border-green-100 rounded-xl p-4 hover:shadow-lg transition-all duration-300 hover:scale-105 cursor-pointer">
                            <div class="flex items-start mb-3">
                                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center ml-3 flex-shrink-0">
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-lg font-semibold text-green-900 mb-1 truncate text-right">{{ $folder['name'] }}</h3>
                                    @if($folder['description'])
                                    <p class="text-xs text-green-500 line-clamp-2 text-right">{{ $folder['description'] }}</p>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="flex items-center justify-between text-sm pt-3 border-t border-green-100">
                                <span class="text-green-400 text-xs">{{ $folder['created_at'] }}</span>
                                <span class="text-green-500">{{ $folder['file_count'] }} ملفات</span>
                            </div>
                            
                            <div class="flex items-center gap-2 mt-3">
                                <a href="{{ route('projects.design.detail.folder', ['project' => $project, 'folderName' => $folder['name']]) }}" class="flex-1 px-3 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm transition-colors text-center">
                                    <svg class="w-4 h-4 inline ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
                                    </svg>
                                    فتح
                                </a>
                                <button onclick="openRenameModal('{{ $folder['name'] }}')" class="flex-1 px-3 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-lg text-sm transition-colors text-center">
                                    <svg class="w-4 h-4 inline ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    إعادة تسمية
                                </button>
                            </div>
                        </div>
                        @endforeach
                        
                        <!-- الملفات في المجلد الرئيسي -->
                        @foreach($files as $file)
                        <div class="bg-gradient-to-br from-green-50 to-white border-2 border-green-100 rounded-xl p-4 hover:shadow-lg transition-all duration-300 hover:scale-105">
                            <div class="flex items-start mb-3">
                                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center ml-3 flex-shrink-0">
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-sm font-semibold text-green-900 mb-1 truncate text-right">{{ $file['name'] }}</h3>
                                    <p class="text-xs text-green-500">{{ number_format($file['size'] / 1024, 2) }} KB</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center justify-between text-xs pt-3 border-t border-green-100">
                                <span class="text-green-400">{{ $file['created_at'] }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-green-900 mb-2">لا توجد مجلدات أو ملفات</h3>
                        <p class="text-green-600">ابدأ بإنشاء مجلد جديد أو رفع ملفات</p>
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>

<!-- Rename Folder Modal -->
<div id="renameModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-lg bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">إعادة تسمية المجلد</h3>
                <button onclick="closeRenameModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <form id="renameForm" action="{{ route('projects.design.detail.rename-folder', $project) }}" method="POST">
                @csrf
                <input type="hidden" id="oldFolderName" name="old_name">
                
                <div class="mb-4">
                    <label for="newFolderName" class="block text-sm font-medium text-gray-700 mb-2 text-right">اسم المجلد الجديد</label>
                    <input type="text" 
                           id="newFolderName" 
                           name="new_name" 
                           required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 text-right"
                           placeholder="أدخل اسم المجلد الجديد">
                </div>
                
                <div class="flex gap-3 justify-end">
                    <button type="button" 
                            onclick="closeRenameModal()"
                            class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg text-sm transition-colors">
                        إلغاء
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm transition-colors">
                        إعادة تسمية
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function openRenameModal(folderName) {
    document.getElementById('oldFolderName').value = folderName;
    document.getElementById('newFolderName').value = folderName;
    document.getElementById('renameModal').classList.remove('hidden');
}

function closeRenameModal() {
    document.getElementById('renameModal').classList.add('hidden');
    document.getElementById('newFolderName').value = '';
}

function updateFileList(input) {
    const fileList = document.getElementById('file-list');
    fileList.innerHTML = '';
    
    if (input.files.length > 0) {
        Array.from(input.files).forEach((file, index) => {
            const fileItem = document.createElement('div');
            fileItem.className = 'flex items-center justify-between p-3 bg-green-50 rounded-lg border border-green-200';
            fileItem.innerHTML = `
                <div class="flex items-center flex-1">
                    <svg class="w-5 h-5 text-green-500 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-green-900 text-right">${file.name}</p>
                        <p class="text-xs text-green-500 text-right">${formatFileSize(file.size)}</p>
                    </div>
                </div>
                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            `;
            fileList.appendChild(fileItem);
        });
        
        // إضافة ملخص
        const summary = document.createElement('div');
        summary.className = 'mt-3 p-3 bg-green-50 rounded-lg border border-green-200';
        summary.innerHTML = `
            <p class="text-sm text-green-700 font-medium text-right">
                تم اختيار ${input.files.length} ${input.files.length === 1 ? 'ملف' : 'ملفات'}
            </p>
        `;
        fileList.appendChild(summary);
    }
}

function formatFileSize(bytes) {
    if (bytes === 0) return '0 بايت';
    const k = 1024;
    const sizes = ['بايت', 'كيلوبايت', 'ميجابايت', 'جيجابايت'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
}

// Drag and drop support
document.addEventListener('DOMContentLoaded', function() {
    const dropZone = document.querySelector('label[for="files"]');
    const fileInput = document.getElementById('files');
    
    if (dropZone && fileInput) {
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, preventDefaults, false);
        });
        
        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }
        
        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, highlight, false);
        });
        
        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, unhighlight, false);
        });
        
        function highlight(e) {
            dropZone.classList.add('border-green-500', 'bg-green-50');
        }
        
        function unhighlight(e) {
            dropZone.classList.remove('border-green-500', 'bg-green-50');
        }
        
        dropZone.addEventListener('drop', handleDrop, false);
        
        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            fileInput.files = files;
            updateFileList(fileInput);
        }
    }
});
</script>
@endpush
@endsection

