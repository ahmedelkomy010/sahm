<!-- @extends('layouts.admin')

@section('title', 'المواد')
@section('header', 'جدول المواد')

@section('content')
    <div class="mb-4">
        <a href="{{ route('admin.work-orders.index') }}" class="text-indigo-600 hover:text-indigo-900">
            <i class="fas fa-arrow-right ml-1"></i>
            العودة إلى القائمة
        </a>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    <div class="bg-white p-6 rounded-lg shadow mb-6">
        <h2 class="text-lg font-medium text-gray-900 mb-4">إضافة مادة جديدة</h2>
        <form action="{{ route('admin.work-orders.materials.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="work_order_id" class="block text-sm font-medium text-gray-700 mb-1">أمر العمل</label>
                    <select name="work_order_id" id="work_order_id" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" required>
                        <option value="">اختر أمر العمل</option>
                        @foreach($workOrders as $workOrder)
                            <option value="{{ $workOrder->id }}">{{ $workOrder->order_number }} - {{ $workOrder->subscriber_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="code" class="block text-sm font-medium text-gray-700 mb-1">كد المادة</label>
                    <input type="text" name="code" id="code" value="{{ old('code') }}" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" required>
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">وصف المادة</label>
                    <input type="text" name="description" id="description" value="{{ old('description') }}" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" required>
                </div>

                <div>
                    <label for="planned_quantity" class="block text-sm font-medium text-gray-700 mb-1">الكمية المخططة</label>
                    <input type="number" step="0.01" name="planned_quantity" id="planned_quantity" value="{{ old('planned_quantity') }}" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" required>
                </div>

                <div>
                    <label for="unit" class="block text-sm font-medium text-gray-700 mb-1">الوحدة</label>
                    <input type="text" name="unit" id="unit" value="{{ old('unit') }}" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" required>
                </div>

                <div>
                    <label for="line" class="block text-sm font-medium text-gray-700 mb-1">السطر</label>
                    <input type="text" name="line" id="line" value="{{ old('line') }}" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                </div>

                <div class="flex items-center">
                    <input type="checkbox" name="check_in" id="check_in" value="1" {{ old('check_in') ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    <label for="check_in" class="mr-2 block text-sm text-gray-900">CHECK IN</label>
                </div>

                <div class="flex items-center">
                    <input type="checkbox" name="check_out" id="check_out" value="1" {{ old('check_out') ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    <label for="check_out" class="mr-2 block text-sm text-gray-900">CHECK OUT</label>
                </div>

                <div>
                    <label for="date_gatepass" class="block text-sm font-medium text-gray-700 mb-1">DATE GATEPASS</label>
                    <input type="date" name="date_gatepass" id="date_gatepass" value="{{ old('date_gatepass') }}" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                </div>

                <div>
                    <label for="stock_in" class="block text-sm font-medium text-gray-700 mb-1">STOCK IN</label>
                    <input type="number" step="0.01" name="stock_in" id="stock_in" value="{{ old('stock_in', 0) }}" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                </div>

                <div>
                    <label for="stock_out" class="block text-sm font-medium text-gray-700 mb-1">STOCK OUT</label>
                    <input type="number" step="0.01" name="stock_out" id="stock_out" value="{{ old('stock_out', 0) }}" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                </div>

                <div>
                    <label for="actual_quantity" class="block text-sm font-medium text-gray-700 mb-1">الكمية المنفذة الفعلية</label>
                    <input type="number" step="0.01" name="actual_quantity" id="actual_quantity" value="{{ old('actual_quantity', 0) }}" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                </div>
            </div>

            <div class="mt-6">
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    إضافة المادة
                </button>
            </div>
        </form>
    </div>

    <div class="bg-white p-6 rounded-lg shadow">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            كد المادة
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            وصف المادة
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            الكميه المخططه
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            الوحدة
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            السطر
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            CHECK IN
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            CHECK OUT
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            DATE GATEPASS
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            STOCK IN
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            STOCK OUT
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            الكمية المنفذه الفعلية
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            الفرق
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            إجراءات
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($materials ?? [] as $material)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $material->code ?? '' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $material->description ?? '' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $material->planned_quantity ?? '' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $material->unit ?? '' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $material->line ?? '' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $material->check_in ? 'نعم' : 'لا' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $material->check_out ? 'نعم' : 'لا' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $material->date_gatepass ? $material->date_gatepass->format('Y-m-d') : '' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $material->stock_in ?? '' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $material->stock_out ?? '' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $material->actual_quantity ?? '' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $material->difference ?? '' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('admin.work-orders.materials.edit', $material) }}" class="text-indigo-600 hover:text-indigo-900 ml-2">تعديل</a>
                                <form action="{{ route('admin.work-orders.materials.destroy', $material) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('هل أنت متأكد من حذف هذه المادة؟')">حذف</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="13" class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">
                                لا توجد مواد مضافة حتى الآن
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(isset($materials) && $materials->count() > 0)
        <div class="mt-4">
            {{ $materials->links() }}
        </div>
        @endif
    </div>
@endsection  -->