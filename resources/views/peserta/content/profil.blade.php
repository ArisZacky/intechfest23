@extends('peserta.main')

{{-- judul halaman disini --}}
@section('title', 'Dashboard Peserta')

@section('content')

<!-- start content -->
<div class="p-4 sm:ml-64 mt-14">
    <!-- Start block -->
    <section class="p-3 sm:p-5 antialiased">
        <div class="mx-auto">
            <form action="/profile-peserta/{{ $peserta->id_peserta }}" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-12">
                    <div class="border-b border-gray-900/10 pb-12">
                        <h2 class="text-base font-semibold leading-7 text-gray-900">Profil Peserta</h2>
                        <p class="mt-1 text-sm leading-6 text-gray-600">Silakan lengkapi semua informasi yang diperlukan untuk profil Anda dengan benar</p>

                        @if ($message = Session::get('berhasil'))
                            <div id="alert-3" class="flex p-4 mb-4 mt-4 text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
                                <svg aria-hidden="true" class="flex-shrink-0 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                                <span class="sr-only">Info</span>
                                <div class="ml-3 text-sm font-medium">
                                    {{ $message }}
                                </div>
                                <button type="button" class="ml-auto -mx-1.5 -my-1.5 bg-green-50 text-green-500 rounded-lg focus:ring-2 focus:ring-green-400 p-1.5 hover:bg-green-200 inline-flex h-8 w-8 dark:bg-gray-800 dark:text-green-400 dark:hover:bg-gray-700" data-dismiss-target="#alert-3" aria-label="Close">
                                <span class="sr-only">Close</span>
                                <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                </button>
                            </div>
                        @endif

                        <input type="hidden" name="email" value="{{ $peserta->email }}">
                        <div class="mt-10 grid gap-x-4 gap-y-8 md:grid-cols-2 grid-cols-1">
                            <div>
                                <label for="username"
                                    class="block text-sm font-medium leading-6 text-gray-900">Email</label>
                                <div class="mt-2">
                                    <div
                                        class="flex rounded-md shadow-sm ring-1 ring-inset ring-gray-300 sm:max-w-md bg-slate-100">
                                        <input type="text" name="email_peserta" id="username" autocomplete="username"
                                            class="block flex-1 border-0 bg-transparent py-1.5 pl-3 text-gray-900 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6"
                                            disabled value="{{ $peserta->email }}">
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label for="username" class="block text-sm font-medium leading-6 text-gray-900">Nomer
                                    Peserta</label>
                                <div class="mt-2">
                                    <div
                                        class="flex rounded-md shadow-sm ring-1 ring-inset ring-gray-300 sm:max-w-md bg-slate-100">
                                        <input type="text" name="nomer_peserta" id="username" autocomplete="username"
                                            class="block flex-1 border-0 bg-transparent py-1.5 pl-3 text-gray-900 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6"
                                            disabled value="{{ $peserta->nomer_peserta }}">
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label for="username" class="block text-sm font-medium leading-6 text-gray-900">Nama
                                    Lengkap</label>
                                <div class="mt-2">
                                    <div
                                        class="flex rounded-md shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-inset focus-within:ring-primary-lightblue sm:max-w-md">
                                        <input type="text" name="nama_lengkap" id="username" autocomplete="username"
                                            class="block flex-1 border-0 bg-transparent py-1.5 pl-3 text-gray-900 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6"
                                            placeholder="Masukkan nama lengkap..." value="{{ $peserta->nama_lengkap }}">
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label for="username"
                                    class="block text-sm font-medium leading-6 text-gray-900">Alamat</label>
                                <div class="mt-2">
                                    <div
                                        class="flex rounded-md shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-inset focus-within:ring-primary-lightblue sm:max-w-md">
                                        <input type="text" name="alamat" id="username" autocomplete="username"
                                            class="block flex-1 border-0 bg-transparent py-1.5 pl-1 text-gray-900 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6"
                                            placeholder="Masukkan alamat..." value="{{ $peserta->alamat ? $peserta->alamat : '' }}">
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label for="username" class="block text-sm font-medium leading-6 text-gray-900">Nama
                                    instansi</label>
                                <div class="mt-2">
                                    <div
                                        class="flex rounded-md shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-inset focus-within:ring-primary-lightblue sm:max-w-md">
                                        <input type="text" name="nama_instansi" id="username" autocomplete="username"
                                            class="block flex-1 border-0 bg-transparent py-1.5 pl-1 text-gray-900 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6"
                                            placeholder="Masukkan nama instansi..." value="{{ $peserta->nama_instansi ? $peserta->nama_instansi : '' }}">
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label for="username" class="block text-sm font-medium leading-6 text-gray-900">Nomer
                                    Handphone</label>
                                <div class="mt-2">
                                    <div
                                        class="flex rounded-md shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-inset focus-within:ring-primary-lightblue sm:max-w-md">
                                        <input type="text" name="no_hp" id="username" autocomplete="username"
                                            class="block flex-1 border-0 bg-transparent py-1.5 pl-1 text-gray-900 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6"
                                            placeholder="Masukkan nomer handphone..." value="{{ $peserta->no_hp ? $peserta->no_hp : '' }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-6 flex items-center justify-end gap-x-6">
                        <input type="reset" class="text-sm rounded-md font-semibold px-2 py-[6px] text-gray-900 hover:outline hover:outline-primary-lightblue hover:outline-2 hover:outline-offset-2 tracking-wide cursor-pointer" value="Batal">
                        <input type="submit"
                            class="rounded-md bg-primary-lightblue px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-blue focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-lightblue cursor-pointer" value="Simpan">
                    </div>
            </form>

        </div>
    </section>
</div>

@endsection