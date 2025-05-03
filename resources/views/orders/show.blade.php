@extends('layouts.admin')

@section('title', 'تفاصيل أمر العمل')
@section('header', 'عرض تفاصيل أمر العمل')

@section('content')
    <div class="mb-4 flex justify-between items-center">
        <a href="{{ route('admin.work-orders.index') }}" class="text-indigo-600 hover:text-indigo-900">
            <i class="fas fa-arrow-right ml-1"></i>
            العودة إلى القائمة
        </a>
        
        <div class="flex space-x-2 space-x-reverse">
            <a href="{{ route('admin.work-orders.edit', $workOrder) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md">
                <i class="fas fa-edit ml-1"></i>
                تعديل
            </a>
            <form action="{{ route('admin.work-orders.destroy', $workOrder) }}" method="POST" class="inline" onsubmit="return confirm('هل أنت متأكد من الحذف؟');">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md">
                    <i class="fas fa-trash ml-1"></i>
                    حذف
                </button>
            </form>
        </div>
    </div>

    <div class="bg-white p-6 rounded-lg shadow">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="text-lg font-medium text-gray-700 mb-2">معلومات أمر العمل</h3>
                <div class="border rounded-md p-4">
                    <div class="mb-4">
                        <span class="block text-sm font-medium text-gray-500">عنوان أمر العمل:</span>
                        <span class="block mt-1 text-base">{{ $workOrder->title }}</span>
                    </div>
                    <div class="mb-4">
                        <span class="block text-sm font-medium text-gray-500">تاريخ الإنشاء:</span>
                        <span class="block mt-1 text-base">{{ $workOrder->created_at->format('Y/m/d H:i') }}</span>
                    </div>
                    <div class="mb-4">
                        <span class="block text-sm font-medium text-gray-500">تاريخ الاستحقاق:</span>
                        <span class="block mt-1 text-base">{{ $workOrder->due_date->format('Y/m/d') }}</span>
                    </div>
                    <div class="mb-4">
                        <span class="block text-sm font-medium text-gray-500">الحالة:</span>
                        <span class="block mt-1">
                            @if ($workOrder->status == 'pending')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    قيد الانتظار
                                </span>
                            @elseif ($workOrder->status == 'in_progress')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    قيد التنفيذ
                                </span>
                            @elseif ($workOrder->status == 'completed')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    مكتمل
                                </span>
                            @elseif ($workOrder->status == 'cancelled')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    ملغي
                                </span>
                            @endif
                        </span>
                    </div>
                    <div>
                        <span class="block text-sm font-medium text-gray-500">التكلفة المقدرة:</span>
                        <span class="block mt-1 text-base">{{ number_format($workOrder->estimated_cost, 2) }} ريال</span>
                    </div>
                </div>
            </div>

            <div>
                <h3 class="text-lg font-medium text-gray-700 mb-2">معلومات العميل</h3>
                <div class="border rounded-md p-4">
                    <div class="mb-4">
                        <span class="block text-sm font-medium text-gray-500">اسم العميل:</span>
                        <span class="block mt-1 text-base">{{ $workOrder->client_name }}</span>
                    </div>
                    <div class="mb-4">
                        <span class="block text-sm font-medium text-gray-500">هاتف العميل:</span>
                        <span class="block mt-1 text-base">{{ $workOrder->client_phone }}</span>
                    </div>
                    @if ($workOrder->client_email)
                    <div class="mb-4">
                        <span class="block text-sm font-medium text-gray-500">بريد العميل الإلكتروني:</span>
                        <span class="block mt-1 text-base">{{ $workOrder->client_email }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="mt-6">
            <h3 class="text-lg font-medium text-gray-700 mb-2">الوصف</h3>
            <div class="border rounded-md p-4">
                <p class="text-gray-700 whitespace-pre-line">{{ $workOrder->description ?: 'لا يوجد وصف' }}</p>
            </div>
        </div>
    </div>
@endsection