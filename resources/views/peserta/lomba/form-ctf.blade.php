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
            <h2 class="text-base font-semibold leading-7 text-gray-900">Profil Peserta</h2>
            <p class="mt-1 mb-6 text-sm leading-6 text-gray-600">Silakan lengkapi semua informasi yang diperlukan untuk profil Anda dengan benar</p>
            @if ($errors->any())
            <div id="alert-2" class="flex p-4 mb-4 text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400"
                role="alert">
                <svg aria-hidden="true" class="flex-shrink-0 w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                    xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                        clip-rule="evenodd"></path>
                </svg>
                <span class="sr-only">Info</span>
                <div class="ml-3 text-sm font-medium">
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <button type="button"
                    class="ml-auto -mx-1.5 -my-1.5 bg-red-50 text-red-500 rounded-lg focus:ring-2 focus:ring-red-400 p-1.5 hover:bg-red-200 inline-flex h-8 w-8 dark:bg-gray-800 dark:text-red-400 dark:hover:bg-gray-700"
                    data-dismiss-target="#alert-2" aria-label="Close">
                    <span class="sr-only">Close</span>
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>
            @endif
            
            <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
                <ol class="space-y-4 w-full">
                    <li>
                        <div class="w-full p-4 text-blue-700 bg-blue-100 border border-blue-300 rounded-lg dark:bg-gray-800 dark:border-blue-800 dark:text-blue-400"
                            role="alert">
                            <div class="flex items-center justify-between">
                                <h3 class="font-medium">1. Formulir Data Diri</h3>
                                <i class="fa-solid fa-arrow-right text-xl"></i>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="w-full p-4 text-gray-900 bg-gray-100 border border-gray-300 rounded-lg dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400"
                            role="alert">
                            <div class="flex items-center justify-between">
                                <h3 class="font-medium">2. Validasi Admin</h3>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="w-full p-4 text-gray-900 bg-gray-100 border border-gray-300 rounded-lg dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400"
                            role="alert">
                            <div class="flex items-center justify-between">
                                <h3 class="font-medium">3. Transaksi</h3>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="w-full p-4 text-gray-900 bg-gray-100 border border-gray-300 rounded-lg dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400"
                            role="alert">
                            <div class="flex items-center justify-between">
                                <h3 class="font-medium">4. Validasi Transaksi</h3>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="w-full p-4 text-gray-900 bg-gray-100 border border-gray-300 rounded-lg dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400"
                            role="alert">
                            <div class="flex items-center justify-between">
                                <h3 class="font-medium">5. Pengumpulan Project</h3>
                            </div>
                        </div>
                    </li>
                </ol>

                <form action="/daftar-ctf/{{ $data_peserta->id_peserta }}" method="POST" enctype="multipart/form-data"
                    class="xl:col-span-2">
                    @csrf
                    @method('PUT')
                    {{-- Id peserta ini digunakan untuk melakukan insert ke table lomba(wdc/dc/ctf) --}}
                    <input type="hidden" name="id_peserta" value="{{ $data_peserta->id_peserta }}">
                    <input type="hidden" name="email" value="{{ $data_peserta->email }}">
                    {{-- <div class="relative z-0 w-full mb-6 group">
                        <input type="email" name="email" id="floating_email"
                            class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                            placeholder="" value="{{ $data_peserta->email ? $data_peserta->email : '' }}" disabled />
                        <label for="floating_email"
                            class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Email
                            address</label>
                    </div> --}}
                    <div>
                        <label for="username" class="block text-sm font-medium leading-6 -mt-2 text-gray-900">Nama
                            Lengkap</label>
                        <div class="my-2">
                            <div
                                class="flex rounded-md shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-inset focus-within:ring-primary-lightblue w-full">
                                <input type="text" name="nama_lengkap" id="username" autocomplete="username"
                                    class=" block flex-1 border-0 bg-transparent py-1.5 pl-3 text-gray-900 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6"
                                    placeholder="Masukkan nama lengkap..." value="{{ $data_peserta->nama_lengkap ? $data_peserta->nama_lengkap : '' }}" required>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label for="username"
                            class="block text-sm font-medium leading-6 text-gray-900">Alamat</label>
                        <div class="my-2">
                            <div
                                class="flex rounded-md shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-inset focus-within:ring-primary-lightblue w-full">
                                <input type="text" name="alamat" id="username" autocomplete="username"
                                    class="block flex-1 border-0 bg-transparent py-1.5 pl-3 text-gray-900 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6"
                                    placeholder="Masukkan alamat..." value="{{ $data_peserta->alamat ? $data_peserta->alamat : '' }}" required>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label for="username" class="block text-sm font-medium leading-6 text-gray-900">Nama
                            instansi</label>
                        <div class="my-2">
                            <div
                                class="flex rounded-md shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-inset focus-within:ring-primary-lightblue w-full">
                                <input type="text" name="nama_instansi" id="username" autocomplete="username"
                                    class="block flex-1 border-0 bg-transparent py-1.5 pl-3 text-gray-900 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6"
                                    placeholder="Masukkan nama instansi..." value="{{ $data_peserta->nama_instansi ? $data_peserta->nama_instansi : '' }}"
                                    required>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label for="username" class="block text-sm font-medium leading-6 text-gray-900">Nomer
                            Handphone</label>
                        <div class="my-2">
                            <div
                                class="flex rounded-md shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-inset focus-within:ring-primary-lightblue w-full">
                                <input type="text" name="no_hp" id="username" autocomplete="username"
                                    class="block flex-1 border-0 bg-transparent py-1.5 pl-3 text-gray-900 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6"
                                    placeholder="Masukkan nomer handphone..." value="{{ $data_peserta->no_hp ? $data_peserta->no_hp : '' }}" required>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label for="username" class="block text-sm font-medium leading-6 text-gray-900">Nama
                            Anggota 1</label>
                        <div class="my-2">
                            <div
                                class="flex rounded-md shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-inset focus-within:ring-primary-lightblue w-full">
                                <input type="text" name="anggota1" id="username" autocomplete="username"
                                    class="block flex-1 border-0 bg-transparent py-1.5 pl-3 text-gray-900 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6"
                                    placeholder="Masukkan nama Anggota 1..." value="{{ $data_peserta->anggota1 ? $data_peserta->anggota1 : '' }}"
                                    required>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label for="username" class="block text-sm font-medium leading-6 text-gray-900">Nama
                            Anggota 2</label>
                        <div class="my-2">
                            <div
                                class="flex rounded-md shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-inset focus-within:ring-primary-lightblue w-full">
                                <input type="text" name="anggota2" id="username" autocomplete="username"
                                    class="block flex-1 border-0 bg-transparent py-1.5 pl-3 text-gray-900 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6"
                                    placeholder="Masukkan nama Anggota 2..." value="{{ $data_peserta->anggota2 ? $data_peserta->anggota2 : '' }}"
                                    required>
                            </div>
                        </div>
                    </div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="user_avatar">Upload
                        Foto</label>
                    <input
                        class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                        aria-describedby="user_avatar_help" id="user_avatar" name="foto" type="file">
                    <div class="mt-1 mb-5 text-sm text-gray-500 dark:text-gray-300" id="user_avatar_help">Foto Data Diri
                        Berupa KTP/KTM/SIM/Surat Keterangan Mahasiswa/Surat Rekomendasi Sekolah atau Kampus</div>
                    <button type="submit"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Daftar</button>
                </form>
            </div>
        </div>
    </section>
    <!-- End block -->
</div>
<!-- end content -->