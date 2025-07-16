@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-blue-50 py-12">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="p-8">
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $project->name }}</h1>
                    <p class="text-gray-600">رقم العقد: {{ $project->contract_number }}</p>
                </div>

                <div class="mb-8">
                    <div class="grid grid-cols-2 gap-4 text-right">
                        <div>
                            <p class="text-gray-600">نوع المشروع:</p>
                            <p class="font-semibold">{{ $project->getProjectTypeText() }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600">الموقع:</p>
                            <p class="font-semibold">{{ $project->location }}</p>
                        </div>
                    </div>
                </div>

                <div class="border-t border-gray-200 pt-8">
                    <h2 class="text-xl font-bold text-gray-900 mb-4 text-right">رفع المرفقات</h2>
                    
                    <form action="{{ route('projects.upload', $project) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        
                        <div class="space-y-2">
                            <label for="attachment" class="block text-right text-sm font-medium text-gray-700">
                                اختر الملف <span class="text-red-500">*</span>
                            </label>
                            <input type="file" 
                                   name="attachment" 
                                   id="attachment" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-right"
                                   required>
                            @error('attachment')
                                <p class="mt-1 text-right text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg shadow-sm transition duration-150 ease-in-out">
                                رفع الملف
                            </button>
                        </div>
                    </form>
                </div>

                @if($project->attachments && count($project->attachments) > 0)
                <div class="mt-8 border-t border-gray-200 pt-8">
                    <h2 class="text-xl font-bold text-gray-900 mb-4 text-right">المرفقات الحالية</h2>
                    <div class="space-y-4">
                        @foreach($project->attachments as $attachment)
                        <div class="flex items-center justify-between bg-gray-50 p-4 rounded-lg">
                            <a href="{{ Storage::url($attachment->file_path) }}" 
                               target="_blank"
                               class="text-blue-600 hover:text-blue-800">
                                {{ $attachment->original_filename }}
                            </a>
                            <span class="text-gray-500 text-sm">
                                {{ $attachment->created_at->format('Y-m-d H:i') }}
                            </span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection 