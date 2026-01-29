<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Collection;

class SampleExport implements FromCollection
{
    public function collection()
    {
        return new Collection([
            ['Name', 'Email(optional)', 'Phone_no','Website(optional)'],
            ['manjeet', 'manjeet@gmail.com', '9997294527','demo@gmail.com'],
        ]);
    }
}
