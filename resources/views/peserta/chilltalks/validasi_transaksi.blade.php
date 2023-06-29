{{-- menghubungkan file main --}}
@extends('peserta.main')

{{-- judul halaman disini --}}
@section('title', 'Form Lomba')

{{-- membuat content disini --}}
@section('content')
<!-- start content -->
<div class="p-4 sm:ml-64 mt-14">
    <!-- Start block -->
    <section class="p-3 sm:p-5 antialiased">
        <div class="mx-auto">
            <h2 class="text-base mb-6 font-semibold leading-7 text-gray-900">Validasi Admin</h2>
            <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
                <ol class="space-y-4 w-full">
                    <li>
                        <div class="w-full p-4 text-green-700 bg-green-100 border border-green-300 rounded-lg"
                            role="alert">
                            <div class="flex items-center justify-between">
                                <h3 class="font-medium">1. Bukti Transfer</h3>
                                <i class="fa-solid fa-check text-xl"></i>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="w-full p-4 text-blue-700 bg-blue-100 border border-blue-300 rounded-lg"
                            role="alert">
                            <div class="flex items-center justify-between">
                                <h3 class="font-medium">2. Validasi Transaksi</h3>
                                <i class="fa-solid fa-spinner text-xl animate-spin-slow"></i>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="w-full p-4 text-gray-900 bg-gray-100 border border-gray-300 rounded-lg dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400"
                            role="alert">
                            <div class="flex items-center justify-between">
                                <h3 class="font-medium">3. Mendapat Nomor Peserta</h3>
                            </div>
                        </div>
                    </li>
                </ol>
                <section class="text-gray-600 body-font xl:col-span-2">
                    <div class="mx-auto flex items-center justify-center flex-col">
                        <div class="w-full">
                            <img src="{{asset('images/maskot/maskot-kepo.jpg')}}" class="w-1/2 lg:w-1/3 mx-auto"
                                alt="ivy wondering">
                            <p class="mb-8 leading-relaxed mt-10 text-center">Mohon bersabar, bukti transaksi Anda sedang divalidasi oleh
                                panitia Intechfest. Jika tidak ada respon dalam waktu 2x24 jam, silahkan hubungi nomor
                                whatsapp dengan mengklik button dibawah ini. Terima kasih atas kerjasamanya..</p>
                            <div class="flex justify-center">
                                <button
                                    class="inline-flex text-white bg-primary-lightblue border-0 py-2 px-6 focus:outline-none hover:bg-primary-blue rounded text-lg">Hubungi Panitia</button>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </section>
</div>
@endsection