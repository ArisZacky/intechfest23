@extends('peserta.main')

{{-- judul halaman disini --}}
@section('title', 'Dashboard Peserta')

@section('content')

<!-- start content -->
<div class="p-4 sm:ml-64 mt-14">
    <!-- Start block -->
    <section class="p-3 sm:p-5 antialiased">
        <div class="mx-auto">
            <h1>Selamat Datang Calon Peserta PNBCTF !</h1>
            <div class="relative mt-7 overflow-x-auto shadow-md sm:rounded-lg">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                Nomer Peserta
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Nama Peserta
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Alamat
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Nama Instansi
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Nama Team
                            </th>
                            <th scope="col" class="px-6 py-3">
                               Anggota ke-1
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Anggota ke-2
                            </th>
                            <th scope="col" class="px-6 py-3">
                               No Hp
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Status
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Bukti
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data_peserta as $data)    
                            <tr class="bg-white border-b dark:bg-gray-900 dark:border-gray-700">
                                <td class="px-6 py-4">
                                    {{ $data->peserta->nomer_peserta }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $data->peserta->nama_lengkap }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $data->peserta->alamat }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $data->peserta->nama_instansi }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $data->nama_team }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $data->anggota1 }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $data->anggota2 }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $data->peserta->no_hp }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $data->validasi }}
                                </td>
                                <td class="px-6 py-4">
                                    <img src="{{ asset('storage/Identitas/ctf/'.$data->foto) }}" alt="">
                                </td>
                                <td class="px-6 py-4">
                                @if ($data->validasi != 'Sudah Valid')
                                    <a href="#" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Hubungi Panitia</a>
                                @else
                                    <a href="#" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Bayar Tiket</a>
                                @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </section>
</div>

@endsection