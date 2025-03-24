<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class DoctorsExport implements FromQuery, WithHeadings, WithMapping
{
    protected $query;

    public function __construct($query)
    {
        $this->query = $query;
    }

    public function query()
    {
        return $this->query;
    }

    public function headings(): array
    {
        return [
            'ID',
            'First Name',
            'Last Name',
            'Email',
            'Phone',
            'Specialty',
            'Price',
            'Status',
            'Joined At'
        ];
    }

    public function map($doctor): array
    {
        return [
            $doctor->id,
            $doctor->first_name,
            $doctor->last_name,
            $doctor->email,
            $doctor->phone,
            $doctor->specialty->name,
            $doctor->price,
            $doctor->status,
            $doctor->created_at
        ];
    }
}
