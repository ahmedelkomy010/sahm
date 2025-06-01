<?php

namespace App\Http\Controllers;

use App\Models\LabLicense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LabLicenseWebController extends Controller
{
    public function index()
    {
        try {
            $labLicenses = LabLicense::latest()->get();
            return response()->json($labLicenses);
        } catch (\Exception $e) {
            Log::error('Error fetching lab licenses: ' . $e->getMessage());
            return response()->json(['error' => 'حدث خطأ أثناء جلب البيانات'], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $labLicense = LabLicense::create($request->all());
            return response()->json($labLicense, 201);
        } catch (\Exception $e) {
            Log::error('Error creating lab license: ' . $e->getMessage());
            return response()->json(['error' => 'حدث خطأ أثناء حفظ البيانات'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $labLicense = LabLicense::findOrFail($id);
            $labLicense->update($request->all());
            return response()->json($labLicense);
        } catch (\Exception $e) {
            Log::error('Error updating lab license: ' . $e->getMessage());
            return response()->json(['error' => 'حدث خطأ أثناء تحديث البيانات'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $labLicense = LabLicense::findOrFail($id);
            $labLicense->delete();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            Log::error('Error deleting lab license: ' . $e->getMessage());
            return response()->json(['error' => 'حدث خطأ أثناء حذف البيانات'], 500);
        }
    }
} 