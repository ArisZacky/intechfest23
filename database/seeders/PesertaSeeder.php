<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PesertaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $totalData = 15;
        for ($i = 0; $i < $totalData; $i++) {
            DB::table('peserta')->insert([
                'email' => $faker->email(),
                'nomer_peserta' => $this->randomCharacters('string'),
                'nama_lengkap' => $faker->name(),
                'alamat' => $faker->address,
                'nama_instansi' => $faker->company(),
                'no_hp' => $this->randomCharacters()
            ]);
        }
    }
    public function randomCharacters($type="", $length = 10) {
        $characters = '123456789';
        if($type=="string"){
            $characters .= 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $randomString = '';
        } else {
            $randomString = '08';
        }
        $charactersLength = strlen($characters);
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}