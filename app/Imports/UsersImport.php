<?php

namespace App\Imports;

// Other use statements and class definitions follow
use DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use Illuminate\Validation\Rule;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class UsersImport implements ToCollection
{
    public $errors = [];
    public $preparedData = [];

    public function collection(Collection $rows)
    {
        if ($rows->isEmpty()) {
            $this->errors[] = ['error' => 'The CSV file is empty.'];
            return;
        }

        $rows->skip(1)->each(function ($row, $index) {
            $rowNumber = $index + 2; // +2 because header is skipped and index starts from 0
            $rowArray = $row->toArray();
    
            if (count($rowArray) < 4) {
                $this->errors[] = [
                    'row'    => $rowNumber,
                    'errors' => ['Insufficient columns. Required: name, email, phone, website.']
                ];
                return;
            }

            $data = [
                'name'    => trim($rowArray[0] ?? ''),
                'email'   => trim($rowArray[1] ?? ''),
                'phone'   => trim($rowArray[2] ?? ''),
                'website' => trim($rowArray[3] ?? ''),  
            ];

            $validator = Validator::make($data, [
                'name'    => ['required', 'string', 'max:255'],
                'email'   => ['nullable', 'email'],
                'phone'   => ['required', 'string','unique:users,phone_no'],
                'website' => ['nullable', 'string', 'max:255'],
            ]);
    
            $customErrors = [];
    
            if ($validator->fails()) {
                $customErrors = $validator->errors()->all();
            }
    
            // Check DB for duplicate phone
            if (!empty($data['phone']) && DB::table('lead')->where('phone', $data['phone'])->exists()) {
                $customErrors[] = "Phone '{$data['phone']}' already exists.";
            }
    
            // Check DB for duplicate email (if not null)
            if (!empty($data['email']) && DB::table('lead')->where('email', $data['email'])->exists()) {
                $customErrors[] = "Email '{$data['email']}' already exists.";
            }
    
            // Optional: Check for special characters (basic example)
            // if (preg_match('/[^a-zA-Z0-9 @.\-]/', $data['name'])) {
            //     $customErrors[] = "Name contains special characters.";
            // }

            // Detect emoji or non-ASCII characters
            // if (preg_match('/[^\x20-\x7E]/', $data['name'])) {
            //     $customErrors[] = "Name contains invalid characters (e.g., emojis or symbols).";
            // }
    
            if (!empty($customErrors)) {
                $this->errors[] = [
                    'row'    => $rowNumber,
                    'errors' => $customErrors,
                ];
                return;
            }

            $this->preparedData[] = $data;
        });
    }
}

