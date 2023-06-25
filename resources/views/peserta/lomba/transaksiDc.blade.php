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
            <h2 class="text-base mb-6 font-semibold leading-7 text-gray-900">Transaksi</h2>
            <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
                <ol class="space-y-4 w-full">
                    <li>
                        <div class="w-full p-4 text-green-700 bg-green-100 border border-green-300 rounded-lg"
                            role="alert">
                            <div class="flex items-center justify-between">
                                <h3 class="font-medium">1. Formulir Data Diri</h3>
                                <i class="fa-solid fa-check text-xl"></i>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="w-full p-4 text-green-700 bg-green-100 border border-green-300 rounded-lg"
                            role="alert">
                            <div class="flex items-center justify-between">
                                <h3 class="font-medium">2. Validasi Panitia</h3>
                                <i class="fa-solid fa-check text-xl"></i>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="w-full p-4 text-blue-700 bg-blue-100 border border-blue-300 rounded-lg"
                            role="alert">
                            <div class="flex items-center justify-between">
                                <h3 class="font-medium">3. Transaksi</h3>
                                <i class="fa-solid fa-arrow-right text-xl"></i>
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
                <div class="-mt-1 xl:col-span-2">
                    @if ($errors->has('foto'))    
                    <div class="flex p-4 mb-4 text-sm text-red-800 border border-red-300 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400 dark:border-red-800"
                        role="alert">
                        <svg aria-hidden="true" class="flex-shrink-0 inline w-5 h-5 mr-3" fill="currentColor"
                            viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <span class="sr-only">Error</span>
                        <div>
                            <span class="font-medium">{{$errors->first('foto')}}</span>
                        </div>
                    </div>
                    @endif
                    <form method="POST" action="/transaksi-dc/{{$peserta->id_peserta}}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                                for="user_avatar">Upload
                                Bukti Transfer</label>
                            <input type="file" name="foto" id="photo"
                                class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                        </div>
                        <div class="mt-1 mb-3 text-sm text-gray-500 dark:text-gray-300" id="user_avatar_help">Foto bukti
                            transfer harus dalam format jpg, jpeg, atau png</div>
                        <!-- Tambahkan elemen input lainnya -->
                        <button type="submit"
                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Kirim</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection