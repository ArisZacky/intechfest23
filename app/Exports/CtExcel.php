<?php

namespace App\Exports;

use App\Models\Ct;
use Maatwebsite\Excel\Concerns\FromCollection;

class CtExcel implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Ct::          
        join('peserta', 'ct.id_peserta', '=', 'peserta.id_peserta')
        ->leftJoin('transaksi', 'ct.id_transaksi', '=', 'transaksi.id_transaksi')
        ->select('peserta.*')
        ->get();
    }
}
