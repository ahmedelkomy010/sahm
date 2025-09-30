<?php

namespace App\Imports;

use App\Models\Revenue;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class RevenuesImport implements ToModel, WithValidation, SkipsOnError, SkipsOnFailure, SkipsEmptyRows
{
    use SkipsErrors, SkipsFailures;

    private $importedRevenues = [];
    private $rowCount = 0;
    private $project;
    private $city;

    public function __construct($project = "riyadh", $city = null)
    {
        $this->project = $project;
        $this->city = $city ?: ($project === "madinah" ? "??????? ???????" : "??????");
    }

    public function model(array $row)
    {
        $this->rowCount++;
        
        try {
            // ??????? ???????? ????????
            $clientName = $this->getFieldValue($row, ["client_name", "??? ??????", "????? ???????", "??????"], 0);
            $contractNumber = $this->getFieldValue($row, ["contract_number", "??? ?????", "???"], 2);
            $extractNumber = $this->getFieldValue($row, ["extract_number", "??? ????????", "??????"], 3);

            // ?????? ?? ???????? ????????
            if (empty($clientName) && empty($contractNumber) && empty($extractNumber)) {
                Log::warning("Skipping row due to missing essential data", [
                    "row_number" => $this->rowCount,
                    "row" => $row
                ]);
                return null;
            }

            // ????? ????????
            $data = [
                "project" => $this->project,
                "city" => $this->city,
                "client_name" => $clientName,
                "project_area" => $this->getFieldValue($row, ["project_area", "???????", "???????"], 1),
                "contract_number" => $contractNumber,
                "extract_number" => $extractNumber,
                "office" => $this->getFieldValue($row, ["office", "??????", "????"], 4),
                "extract_type" => $this->getFieldValue($row, ["extract_type", "??? ????????", "?????"], 5),
                "po_number" => $this->getFieldValue($row, ["po_number", "??? PO", "PO"], 6),
                "invoice_number" => $this->getFieldValue($row, ["invoice_number", "??? ????????", "??????"], 7),
                "extract_value" => $this->convertToNumeric($this->getFieldValue($row, ["extract_value", "???? ????????", "??????"], 8)),
                "tax_percentage" => $this->convertToNumeric($this->getFieldValue($row, ["tax_percentage", "???? ???????", "??????"], 9)),
                "tax_value" => $this->convertToNumeric($this->getFieldValue($row, ["tax_value", "???? ???????", "?????"], 10)),
                "penalties" => $this->convertToNumeric($this->getFieldValue($row, ["penalties", "????????", "?????"], 11)),
                "first_payment_tax" => $this->convertToNumeric($this->getFieldValue($row, ["first_payment_tax", "????? ?????? ??????", "???? ????"], 12)),
                "net_extract_value" => $this->convertToNumeric($this->getFieldValue($row, ["net_extract_value", "???? ???? ????????", "????"], 13)),
                "extract_date" => $this->convertDate($this->getFieldValue($row, ["extract_date", "????? ????? ????????", "????? ???????"], 14)),
                "year" => $this->getFieldValue($row, ["year", "?????", "???"], 15),
                "payment_type" => $this->getFieldValue($row, ["payment_type", "??? ?????", "????? ?????"], 16),
                "reference_number" => $this->getFieldValue($row, ["reference_number", "????? ???????", "????"], 17),
                "payment_date" => $this->convertDate($this->getFieldValue($row, ["payment_date", "????? ?????", "????? ?????"], 18)),
                "payment_value" => $this->convertToNumeric($this->getFieldValue($row, ["payment_value", "???? ?????", "???? ?????"], 19)),
                "extract_status" => $this->getFieldValue($row, ["extract_status", "???? ????????", "??????"], 20),
            ];

            // ????? ???????? ???????
            $cleanData = array_filter($data, function($value) {
                return $value !== null && $value !== "";
            });

            // ????? ????? ??????????
            $cleanData["project"] = $this->project;
            $cleanData["city"] = $this->city;

            // ??? ????????
            $revenue = Revenue::create($cleanData);
            $this->importedRevenues[] = $revenue;

            Log::info("Revenue imported successfully", [
                "row_number" => $this->rowCount,
                "revenue_id" => $revenue->id
            ]);

            return $revenue;

        } catch (\Exception $e) {
            Log::error("Error importing revenue: " . $e->getMessage(), [
                "row_number" => $this->rowCount,
                "row" => $row
            ]);
            return null;
        }
    }

    /**
     * ????? ?? ???? ????? ?? ????
     */
    protected function getFieldValue(array $row, array $possibleKeys, int $columnIndex = null)
    {
        if ($columnIndex !== null) {
            return isset($row[$columnIndex]) ? trim($row[$columnIndex]) : null;
        }
        
        foreach ($possibleKeys as $key) {
            // ????? ??????? ???????? ??????
            if (isset($row[$key]) && !empty(trim($row[$key]))) {
                return trim($row[$key]);
            }
            
            // ????? ???????? ?????? ????? ?????
            $normalizedKey = strtolower(trim($key));
            if (isset($row[$normalizedKey]) && !empty(trim($row[$normalizedKey]))) {
                return trim($row[$normalizedKey]);
            }
        }
        
        return null;
    }

    /**
     * ????? ?????? ??? ???
     */
    private function convertToNumeric($value)
    {
        if ($value === null || $value === "") {
            return null;
        }

        // ????? ??????? ?????????
        $value = str_replace([",", " "], "", $value);
        
        if (is_numeric($value)) {
            return (float) $value;
        }
        
        return null;
    }

    /**
     * ????? ???????
     */
    private function convertDate($value)
    {
        if (empty($value)) {
            return null;
        }

        try {
            // ??? ??? ??? Excel date
            if (is_numeric($value)) {
                return Carbon::instance(Date::excelToDateTimeObject($value))->format("Y-m-d");
            }
            
            // ??? ??? ????? ???
            if (is_string($value)) {
                $date = Carbon::parse($value);
                return $date->format("Y-m-d");
            }
            
            return null;
        } catch (\Exception $e) {
            Log::warning("Failed to convert date: " . $value . " Error: " . $e->getMessage());
            return null;
        }
    }

    public function rules(): array
    {
        return [
            // ????? ???????? ??????
        ];
    }

    public function getImportedRevenues(): array
    {
        return $this->importedRevenues;
    }

    public function getErrors()
    {
        return $this->failures();
    }
}
