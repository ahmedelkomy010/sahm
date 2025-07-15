<?php

namespace App\Imports;

use App\Models\ReferenceMaterial;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Facades\Log;

class ReferenceMaterialsImport implements ToModel, WithHeadingRow, WithValidation
{
    private $importedMaterials = [];
    private $rowCount = 0;

    public function model(array $row)
    {
        $this->rowCount++;
        try {
            $code = isset($row['code']) ? trim($row['code']) : '';
            $description = isset($row['description']) ? trim($row['description']) : '';
            if (empty($code) || empty($description)) {
                Log::warning('Skipping row due to missing code or description', [
                    'row_number' => $this->rowCount,
                    'row' => $row
                ]);
                return null;
            }
            // Save or update in reference_materials
            $material = ReferenceMaterial::updateOrCreate(
                ['code' => $code],
                ['description' => $description]
            );
            $this->importedMaterials[] = $material;
            return $material;
        } catch (\Exception $e) {
            Log::error('Error importing reference material: ' . $e->getMessage(), [
                'row_number' => $this->rowCount,
                'row' => $row
            ]);
            return null;
        }
    }

    public function rules(): array
    {
        return [
            'code' => 'required|string|max:255',
            'description' => 'required|string',
        ];
    }

    public function getImportedMaterials(): array
    {
        return $this->importedMaterials;
    }
}
