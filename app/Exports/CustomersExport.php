<?php

namespace App\Exports;

use App\Models\Customers\Customer;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CustomersExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    /**
     * @return Collection
     */
    public function collection()
    {
        return Customer::with('department')->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Cadena',
            'Nombre',
            'Apellidos',
            'Teléfono',
            'Correo',
            'Fecha de nacimiento',
            'Departamento'
        ];
    }

    /**
     * @param mixed $row
     *
     * @return array
     */
    public function map($row): array
    {
        return [
            $row->trade_name,
            $row->name,
            $row->lastname,
            $row->cellphone,
            $row->email,
            date("d-m-Y", strtotime($row->birthdate)),
            $row->department->name
        ];
    }
}
