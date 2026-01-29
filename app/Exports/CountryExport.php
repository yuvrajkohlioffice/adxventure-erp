<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CountryExport implements FromCollection, WithHeadings, WithTitle, WithStyles
{
    protected $headers;
    protected $data;
    protected $title;

    public function __construct(array $headers, array $data, string $title)
    {
        $this->headers = $headers;
        $this->data = $data;
        $this->title = $title;
    }

    public function collection()
    {
        return collect($this->data);
    }

    public function headings(): array
    {
        return $this->headers;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('1:1')->getFont()->setBold(true); // Apply bold style to the first row
    }
}
