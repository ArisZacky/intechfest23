<?php

namespace App\Exports;

use App\Models\Ctf;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CtfExcel implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Ctf::          
        join('peserta', 'ctf.id_peserta', '=', 'peserta.id_peserta')
        ->leftJoin('transaksi', 'ctf.id_transaksi', '=', 'transaksi.id_transaksi')
        ->select('peserta.id_peserta', 'peserta.email', 'peserta.nomer_peserta', 'peserta.nama_lengkap'
        ,'peserta.alamat', 'peserta.nama_instansi', 'peserta.no_hp', 'ctf.nama_team', 'ctf.anggota1', 'ctf.anggota2')
        ->where('ctf.validasi', '=', 'Sudah Valid')
        ->get();
    }
    
    public function headings(): array
    {
        return ["ID", "Email", "Nomer Peserta", "Nama Lengkap", "Alamat", "Instansi", "No HP", "Nama Team", "Anggota 2", "Anggota 3"];
    }
}
