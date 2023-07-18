<?php

namespace App\Exports;

use App\Models\Ct;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CtExcel implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Ct::          
        join('peserta', 'ct.id_peserta', '=', 'peserta.id_peserta')
        ->leftJoin('transaksi', 'ct.id_transaksi', '=', 'transaksi.id_transaksi')
        ->select('peserta.id','peserta.email','ct.nomer_peserta','peserta.lengkap', 'peserta.alamat', 'peserta.nama_instansi', 'peserta.no_hp')
        ->get();
    }
    
    public function headings(): array
    {
        return ["ID", "Email", "Nomer Peserta", "Nama Lengkap", "Alamat", "Instansi", "No HP"];
    }
}
