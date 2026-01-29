<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class MultiSheetExport implements WithMultipleSheets
{
    protected $sheetsData;

    public function __construct(array $sheetsData)
    {
        $this->sheetsData = $sheetsData;
    }

    public function sheets(): array
    {
        $sheets = [];
        foreach ($this->sheetsData as $sheetName => $sheetData) {
            if (isset($sheetData['exportClass'])) {
                $sheets[] = new $sheetData['exportClass']($sheetData['headers'], $sheetData['data'], $sheetName);
            } else {
                $sheets[] = new DynamicSheet($sheetData['headers'], $sheetData['data'], $sheetName);
            }
        }

        return $sheets;
    }
}

