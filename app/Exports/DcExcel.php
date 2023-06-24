<?php

namespace App\Exports;

use App\Models\Dc;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DcExcel implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Dc::          
        join('peserta', 'dc.id_peserta', '=', 'peserta.id_peserta')
        ->leftJoin('transaksi', 'dc.id_transaksi', '=', 'transaksi.id_transaksi')
        ->select('peserta.*')
        ->get();
    }

    public function headings(): array
    {
        return ["ID", "Email", "Nomer Peserta", "Nama Lengkap", "Alamat", "Instansi", "No HP"];
    }
}
