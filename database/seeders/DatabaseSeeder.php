<?php

namespace Database\Seeders;

use App\Models\ClassRoom;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Buat akun guru
        $guru = User::create([
            'name'     => 'Guru ClassEditor',
            'username' => 'guru',
            'email'    => 'guru@classeditor.smk',
            'role'     => 'guru',
            'password' => 'guru1234',
        ]);

        // Buat 2 kelas
        $kelasA = ClassRoom::create([
            'name'        => 'X TJKT A',
            'guru_id'     => $guru->id,
            'description' => 'Kelas X TJKT A - Pemrograman Dasar',
        ]);

        $kelasB = ClassRoom::create([
            'name'        => 'X TJKT B',
            'guru_id'     => $guru->id,
            'description' => 'Kelas X TJKT B - Pemrograman Dasar',
        ]);

        // Buat beberapa siswa contoh untuk X TJKT A
        $siswaA = [
            ['name' => 'Ahmad Fauzi',    'username' => '2024001'],
            ['name' => 'Budi Santoso',   'username' => '2024002'],
            ['name' => 'Citra Dewi',     'username' => '2024003'],
            ['name' => 'Dian Pratama',   'username' => '2024004'],
            ['name' => 'Eka Rahayu',     'username' => '2024005'],
        ];

        foreach ($siswaA as $siswa) {
            User::create([
                'name'     => $siswa['name'],
                'username' => $siswa['username'],
                'role'     => 'siswa',
                'class_id' => $kelasA->id,
                'password' => 'smk1234',
            ]);
        }

        // Buat beberapa siswa contoh untuk X TJKT B
        $siswaB = [
            ['name' => 'Fajar Nugroho',  'username' => '2024101'],
            ['name' => 'Galih Wibowo',   'username' => '2024102'],
            ['name' => 'Hani Safitri',   'username' => '2024103'],
            ['name' => 'Indra Kurnia',   'username' => '2024104'],
            ['name' => 'Joko Susilo',    'username' => '2024105'],
        ];

        foreach ($siswaB as $siswa) {
            User::create([
                'name'     => $siswa['name'],
                'username' => $siswa['username'],
                'role'     => 'siswa',
                'class_id' => $kelasB->id,
                'password' => 'smk1234',
            ]);
        }

        $this->command->info('Seeder selesai!');
        $this->command->info('Login Guru   -> username: guru    | password: guru1234');
        $this->command->info('Login Siswa  -> username: 2024001 | password: smk1234');
    }
}
