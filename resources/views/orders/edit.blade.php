@extends('layouts.admin')

@section('title', 'تعديل أمر العمل')
@section('header', 'تعديل أمر العمل')

@section('content')
    <div class="mb-4">
        <a href="{{ route('admin.work-orders.index') }}" class="text-indigo-600 hover:text-indigo-900">
            <i class="fas fa-arrow-right ml-1"></i>
            العودة إلى القائمة
        </a>
    </div>

    <div class="bg-white p-6 rounded-lg shadow">
        <form action="{{ route('admin.work-orders.update', $workOrder) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1">عنوان أمر العمل</label>
                    <input type="text" name="title" id="title" value="{{ old('title', $workOrder->title) }}" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" required>
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="client_name" class="block text-sm font-medium text-gray-700 mb-1">اسم العميل</label>
                    <input type="text" name="client_name" id="client_name" value="{{ old('client_name', $workOrder->client_name) }}" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" required>
                    @error('client_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="client_phone" class="block text-sm font-medium text-gray-700 mb-1">هاتف العميل</label>
                    <input type="text" name="client_phone" id="client_phone" value="{{ old('client_phone', $workOrder->client_phone) }}" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" required>
                    @error('client_phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="client_email" class="block text-sm font-medium text-gray-700 mb-1">بريد العميل الإلكتروني</label>
                    <input type="email" name="client_email" id="client_email" value="{{ old('client_email', $workOrder->client_email) }}" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                    @error('client_email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="due_date" class="block text-sm font-medium text-gray-700 mb-1">تاريخ الاستحقاق</label>
                    <input type="date" name="due_date" id="due_date" value="{{ old('due_date', $workOrder->due_date->format('Y-m-d')) }}" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" required>
                    @error('due_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>