<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Violation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ViolationController extends Controller
{
    public function index()
    {
        $violations = Violation::latest()->get();
        return response()->json($violations);
    }

    public function store(Request $request)
    {
        $request->validate([
            'violation_type' => 'required|string',
            'responsible_party' => 'required|string',
            'payment_status' => 'required|string',
            'description' => 'required|string',
            'actions_taken' => 'required|string',
            'violation_value' => 'required|numeric',
            'violation_file' => 'nullable|file|max:10240',
            'payment_proof' => 'nullable|file|max:10240',
        ]);

        $violation = new Violation();
        $violation->violation_type = $request->violation_type;
        $violation->responsible_party = $request->responsible_party;
        $violation->payment_status = $request->payment_status;
        $violation->description = $request->description;
        $violation->actions_taken = $request->actions_taken;
        $violation->violation_value = $request->violation_value;

        // Handle violation file upload
        if ($request->hasFile('violation_file')) {
            $path = $request->file('violation_file')->store('violations/files', 'public');
            $violation->violation_file = $path;
        }

        // Handle payment proof upload
        if ($request->hasFile('payment_proof')) {
            $path = $request->file('payment_proof')->store('violations/payments', 'public');
            $violation->payment_proof = $path;
        }

        $violation->save();

        return response()->json([
            'message' => 'تم حفظ المخالفة بنجاح',
            'violation' => $violation
        ]);
    }

    public function show(Violation $violation)
    {
        return response()->json($violation);
    }

    public function update(Request $request, Violation $violation)
    {
        $request->validate([
            'violation_type' => 'required|string',
            'responsible_party' => 'required|string',
            'payment_status' => 'required|string',
            'description' => 'required|string',
            'actions_taken' => 'required|string',
            'violation_value' => 'required|numeric',
            'violation_file' => 'nullable|file|max:10240',
            'payment_proof' => 'nullable|file|max:10240',
        ]);

        $violation->violation_type = $request->violation_type;
        $violation->responsible_party = $request->responsible_party;
        $violation->payment_status = $request->payment_status;
        $violation->description = $request->description;
        $violation->actions_taken = $request->actions_taken;
        $violation->violation_value = $request->violation_value;

        // Handle violation file upload
        if ($request->hasFile('violation_file')) {
            // Delete old file if exists
            if ($violation->violation_file) {
                Storage::disk('public')->delete($violation->violation_file);
            }
            $path = $request->file('violation_file')->store('violations/files', 'public');
            $violation->violation_file = $path;
        }

        // Handle payment proof upload
        if ($request->hasFile('payment_proof')) {
            // Delete old file if exists
            if ($violation->payment_proof) {
                Storage::disk('public')->delete($violation->payment_proof);
            }
            $path = $request->file('payment_proof')->store('violations/payments', 'public');
            $violation->payment_proof = $path;
        }

        $violation->save();

        return response()->json([
            'message' => 'تم تحديث المخالفة بنجاح',
            'violation' => $violation
        ]);
    }

    public function destroy(Violation $violation)
    {
        // Delete associated files
        if ($violation->violation_file) {
            Storage::disk('public')->delete($violation->violation_file);
        }
        if ($violation->payment_proof) {
            Storage::disk('public')->delete($violation->payment_proof);
        }

        $violation->delete();

        return response()->json([
            'message' => 'تم حذف المخالفة بنجاح'
        ]);
    }
} 