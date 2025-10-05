<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TurnkeyRevenue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TurnkeyRevenueController extends Controller
{
    /**
     * Store a new turnkey revenue
     */
    public function store(Request $request)
    {
        try {
            Log::info('Turnkey revenue store request', [
                'data' => $request->all(),
                'user' => auth()->user()->name,
            ]);
            
            $validated = $request->validate([
                'project' => 'required',
                'contract_number' => 'nullable|string',
                'location' => 'nullable|string',
                'project_type' => 'nullable|string',
                'extract_value' => 'nullable|numeric',
                'tax_value' => 'nullable|numeric',
                'penalties' => 'nullable|numeric',
                'net_extract_value' => 'nullable|numeric',
                'payment_value' => 'nullable|numeric',
                'remaining_amount' => 'nullable|numeric',
                'first_payment_tax' => 'nullable|numeric',
                'extract_date' => 'nullable|date',
                'payment_date' => 'nullable|date',
                'notes' => 'nullable|string',
            ]);
            
            $validated['created_by'] = auth()->id();
            $validated['updated_by'] = auth()->id();
            
            $revenue = TurnkeyRevenue::create($validated);
            
            Log::info('Turnkey revenue created successfully', [
                'id' => $revenue->id,
                'data' => $revenue->toArray(),
                'user' => auth()->user()->name,
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Revenue saved successfully',
                'data' => $revenue
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error in turnkey revenue: ' . json_encode($e->errors()));
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error creating turnkey revenue: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Error saving revenue: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Update existing turnkey revenue
     */
    public function update(Request $request, $id)
    {
        try {
            $revenue = TurnkeyRevenue::findOrFail($id);
            
            // Get all fields except _token and project
            $updateData = $request->except(['_token', 'project']);
            $updateData['updated_by'] = auth()->id();
            
            $revenue->update($updateData);
            
            Log::info('Turnkey revenue updated', [
                'id' => $revenue->id,
                'fields' => array_keys($updateData),
                'user' => auth()->user()->name,
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Revenue updated successfully',
                'data' => $revenue
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error updating turnkey revenue: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating revenue: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Delete turnkey revenue
     */
    public function destroy($id)
    {
        try {
            $revenue = TurnkeyRevenue::findOrFail($id);
            
            Log::info('Turnkey revenue deleted', [
                'id' => $revenue->id,
                'user' => auth()->user()->name,
            ]);
            
            $revenue->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Revenue deleted successfully'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error deleting turnkey revenue: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error deleting revenue: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Upload attachment for turnkey revenue
     */
    public function uploadAttachment(Request $request)
    {
        try {
            $request->validate([
                'attachment' => 'required|file|max:10240', // 10MB max
                'revenue_id' => 'required|exists:turnkey_revenues,id'
            ]);
            
            $revenue = TurnkeyRevenue::findOrFail($request->revenue_id);
            
            if ($request->hasFile('attachment')) {
                $file = $request->file('attachment');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('turnkey_revenues_attachments', $fileName, 'public');
                
                // Update revenue with attachment path
                $revenue->attachment_path = '/storage/' . $path;
                $revenue->updated_by = auth()->id();
                $revenue->save();
                
                Log::info('Turnkey revenue attachment uploaded', [
                    'revenue_id' => $revenue->id,
                    'attachment_path' => $revenue->attachment_path,
                    'user' => auth()->user()->name,
                ]);
                
                return response()->json([
                    'success' => true,
                    'message' => 'تم رفع المرفق بنجاح',
                    'attachment_path' => $revenue->attachment_path
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'لم يتم اختيار ملف'
            ], 400);
            
        } catch (\Exception $e) {
            Log::error('Error uploading turnkey revenue attachment: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء رفع المرفق: ' . $e->getMessage()
            ], 500);
        }
    }
}
