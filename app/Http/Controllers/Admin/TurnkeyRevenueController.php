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
}
