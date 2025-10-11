<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WorkOrder;
use App\Models\Material;
use App\Models\WorkOrderMaterial;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    /**
     * عرض الفرق بين المواد المنفذة والمصروفة - الرياض
     */
    public function executedDisbursedRiyadh(Request $request)
    {
        $city = 'الرياض';
        $project = 'riyadh';
        
        return $this->executedDisbursedView($request, $city, $project);
    }

    /**
     * عرض الفرق بين المواد المنفذة والمصروفة - المدينة
     */
    public function executedDisbursedMadinah(Request $request)
    {
        $city = 'المدينة المنورة';
        $project = 'madinah';
        
        return $this->executedDisbursedView($request, $city, $project);
    }

    /**
     * عرض المواد المطلوب إرجاعها - الرياض
     */
    public function toReturnRiyadh(Request $request)
    {
        $city = 'الرياض';
        $project = 'riyadh';
        
        return $this->toReturnView($request, $city, $project);
    }

    /**
     * عرض المواد المطلوب إرجاعها - المدينة
     */
    public function toReturnMadinah(Request $request)
    {
        $city = 'المدينة المنورة';
        $project = 'madinah';
        
        return $this->toReturnView($request, $city, $project);
    }

    /**
     * عرض المواد المطلوب صرفها - الرياض
     */
    public function toDisburseRiyadh(Request $request)
    {
        $city = 'الرياض';
        $project = 'riyadh';
        
        return $this->toDisburseView($request, $city, $project);
    }

    /**
     * عرض المواد المطلوب صرفها - المدينة
     */
    public function toDisburseMadinah(Request $request)
    {
        $city = 'المدينة المنورة';
        $project = 'madinah';
        
        return $this->toDisburseView($request, $city, $project);
    }

    /**
     * View للفرق بين المواد المنفذة والمصروفة
     */
    private function executedDisbursedView(Request $request, $city, $project)
    {
        $query = WorkOrder::with(['workOrderMaterials'])
            ->where(function($q) use ($city) {
                $q->where('city', $city)->orWhere('city', strtolower($project));
            });

        // Apply filters
        if ($request->filled('search')) {
            $query->where('order_number', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('start_date')) {
            $query->where('approval_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->where('approval_date', '<=', $request->end_date);
        }

        $perPage = $request->input('per_page', 20);
        $workOrders = $query->orderBy('approval_date', 'desc')
            ->paginate($perPage)
            ->appends($request->except('page'));

        // Calculate statistics
        $totalOrders = $query->count();
        $totalMaterials = WorkOrderMaterial::whereIn('work_order_id', $query->pluck('id'))->count();

        return view('admin.materials.executed-disbursed', compact(
            'workOrders',
            'totalOrders',
            'totalMaterials',
            'city',
            'project'
        ));
    }

    /**
     * View للمواد المطلوب إرجاعها
     */
    private function toReturnView(Request $request, $city, $project)
    {
        $query = WorkOrder::with(['workOrderMaterials'])
            ->where(function($q) use ($city, $project) {
                $q->where('city', $city)->orWhere('city', strtolower($project));
            });

        // Apply filters
        if ($request->filled('search')) {
            $query->where('order_number', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('start_date')) {
            $query->where('approval_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->where('approval_date', '<=', $request->end_date);
        }

        $perPage = $request->input('per_page', 20);
        $workOrders = $query->orderBy('approval_date', 'desc')
            ->paginate($perPage)
            ->appends($request->except('page'));

        // Calculate statistics
        $totalOrders = $query->count();
        $totalMaterials = WorkOrderMaterial::whereIn('work_order_id', $query->pluck('id'))->count();

        return view('admin.materials.to-return', compact(
            'workOrders',
            'totalOrders',
            'totalMaterials',
            'city',
            'project'
        ));
    }

    /**
     * View للمواد المطلوب صرفها
     */
    private function toDisburseView(Request $request, $city, $project)
    {
        $query = WorkOrder::with(['workOrderMaterials'])
            ->where(function($q) use ($city, $project) {
                $q->where('city', $city)->orWhere('city', strtolower($project));
            });

        // Apply filters
        if ($request->filled('search')) {
            $query->where('order_number', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('start_date')) {
            $query->where('approval_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->where('approval_date', '<=', $request->end_date);
        }

        $perPage = $request->input('per_page', 20);
        $workOrders = $query->orderBy('approval_date', 'desc')
            ->paginate($perPage)
            ->appends($request->except('page'));

        // Calculate statistics
        $totalOrders = $query->count();
        $totalMaterials = WorkOrderMaterial::whereIn('work_order_id', $query->pluck('id'))->count();

        return view('admin.materials.to-disburse', compact(
            'workOrders',
            'totalOrders',
            'totalMaterials',
            'city',
            'project'
        ));
    }
}
