@extends('peserta.main')

{{-- judul halaman disini --}}
@section('title', 'Dashboard Peserta')

@section('content')

<!-- start content -->
<div class="p-4 sm:ml-64 mt-14">
    <!-- Start block -->
    <section class="p-3 sm:p-5 antialiased">
        <div class="mx-auto">
            <form>
                <div class="space-y-12">
                    <div class="border-b border-gray-900/10 pb-12">
                        <h2 class="text-base font-semibold leading-7 text-gray-900">Profil Peserta</h2>
                        <p class="mt-1 text-sm leading-6 text-gray-600">Silakan lengkapi semua informasi yang diperlukan untuk profil Anda dengan benar</p>
                        <div class="mt-10 grid gap-x-4 gap-y-8 md:grid-cols-2 grid-cols-1">
                            <div>
                                <label for="username"
                                    class="block text-sm font-medium leading-6 text-gray-900">Email</label>
                                <div class="mt-2">
                                    <div
                                        class="flex rounded-md shadow-sm ring-1 ring-inset ring-gray-300 sm:max-w-md bg-slate-100">
                                        <input type="text" name="username" id="username" autocomplete="username"
                                            class="block flex-1 border-0 bg-transparent py-1.5 pl-3 text-gray-900 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6"
                                            disabled value="peserta@gmail.com">
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label for="username" class="block text-sm font-medium leading-6 text-gray-900">Nomer
                                    Peserta</label>
                                <div class="mt-2">
                                    <div
                                        class="flex rounded-md shadow-sm ring-1 ring-inset ring-gray-300 sm:max-w-md bg-slate-100">
                                        <input type="text" name="username" id="username" autocomplete="username"
                                            class="block flex-1 border-0 bg-transparent py-1.5 pl-3 text-gray-900 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6"
                                            disabled value="P12345">
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label for="username" class="block text-sm font-medium leading-6 text-gray-900">Nama
                                    Lengkap</label>
                                <div class="mt-2">
                                    <div
                                        class="flex rounded-md shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-inset focus-within:ring-primary-lightblue sm:max-w-md">
                                        <input type="text" name="username" id="username" autocomplete="username"
                                            class="block flex-1 border-0 bg-transparent py-1.5 pl-3 text-gray-900 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6"
                                            placeholder="Masukkan nama lengkap...">
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label for="username"
                                    class="block text-sm font-medium leading-6 text-gray-900">Alamat</label>
                                <div class="mt-2">
                                    <div
                                        class="flex rounded-md shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-inset focus-within:ring-primary-lightblue sm:max-w-md">
                                        <input type="text" name="username" id="username" autocomplete="username"
                                            class="block flex-1 border-0 bg-transparent py-1.5 pl-1 text-gray-900 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6"
                                            placeholder="Masukkan alamat...">
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label for="username" class="block text-sm font-medium leading-6 text-gray-900">Nama
                                    instansi</label>
                                <div class="mt-2">
                                    <div
                                        class="flex rounded-md shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-inset focus-within:ring-primary-lightblue sm:max-w-md">
                                        <input type="text" name="username" id="username" autocomplete="username"
                                            class="block flex-1 border-0 bg-transparent py-1.5 pl-1 text-gray-900 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6"
                                            placeholder="Masukkan nama instansi...">
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label for="username" class="block text-sm font-medium leading-6 text-gray-900">Nomer
                                    Handphone</label>
                                <div class="mt-2">
                                    <div
                                        class="flex rounded-md shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-inset focus-within:ring-primary-lightblue sm:max-w-md">
                                        <input type="text" name="username" id="username" autocomplete="username"
                                            class="block flex-1 border-0 bg-transparent py-1.5 pl-1 text-gray-900 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6"
                                            placeholder="Masukkan nomer handphone...">
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