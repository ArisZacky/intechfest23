<?php

namespace App\Exports;

use App\Models\Wdc;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class WdcExcel implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Wdc::          
        join('peserta', 'wdc.id_peserta', '=', 'peserta.id_peserta')
        ->leftJoin('transaksi', 'wdc.id_transaksi', '=', 'transaksi.id_transaksi')
        ->select('peserta.id_peserta', 'peserta.email', 'peserta.nomer_peserta', 'peserta.nama_lengkap',
        'peserta.alamat', 'peserta.nama_instansi', 'peserta.no_hp')
        ->where('wdc.validasi', '=', 'Sudah Valid')
        ->get();
    }
    
    public function headings(): array
    {
        return ["ID", "Email", "Nomer Peserta", "Nama Lengkap", "Alamat", "Instansi", "No HP"];
    }
}