<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;

class StudentsImport implements ToModel, WithHeadingRow, SkipsOnError
{
    use SkipsErrors;

    public function __construct(private int $classId) {}

    /**
     * Format kolom Excel yang diharapkan:
     * | nama | nis | password (opsional) |
     */
    public function model(array $row): ?User
    {
        // Skip baris kosong
        if (empty($row['nis']) || empty($row['nama'])) {
            return null;
        }

        // Skip jika NIS sudah ada
        if (User::where('username', (string) $row['nis'])->exists()) {
            return null;
        }

        return new User([
            'name'     => $row['nama'],
            'username' => (string) $row['nis'],
            'password' => Hash::make($row['password'] ?? 'smk1234'),
            'role'     => 'siswa',
            'class_id' => $this->classId,
        ]);
    }
}