<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExcavationDetail;
use App\Models\License;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExcavationDetailController extends Controller
{
    public function store(Request $request, License $license)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'contractor' => 'required|string|max:255',
            'duration' => 'required|integer|min:1',
            'length' => 'required|numeric|min:0',
            'width' => 'required|numeric|min:0',
            'depth' => 'required|numeric|min:0',
            'status' => 'required|in:active,expired,completed'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'بيانات غير صحيحة',
                'errors' => $validator->errors()
            ], 422);
        }

        $excavationDetail = new ExcavationDetail($request->all());
        $excavationDetail->license_id = $license->id;
        $excavationDetail->save();

        return response()->json([
            'status' => 'success',
            'message' => 'تم حفظ تفاصيل الحفر بنجاح',
            'data' => $excavationDetail
        ]);
    }

    public function update(Request $request, ExcavationDetail $excavationDetail)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'location' => 'sometimes|required|string|max:255',
            'contractor' => 'sometimes|required|string|max:255',
            'duration' => 'sometimes|required|integer|min:1',
            'length' => 'sometimes|required|numeric|min:0',
            'width' => 'sometimes|required|numeric|min:0',
            'depth' => 'sometimes|required|numeric|min:0',
            'status' => 'sometimes|required|in:active,expired,completed'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'بيانات غير صحيحة',
                'errors' => $validator->errors()
            ], 422);
        }

        $excavationDetail->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'تم تحديث تفاصيل الحفر بنجاح',
            'data' => $excavationDetail
        ]);
    }

    public function destroy(ExcavationDetail $excavationDetail)
    {
        $excavationDetail->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'تم حذف تفاصيل الحفر بنجاح'
        ]);
    }
} 