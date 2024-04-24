<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Karyawan extends Model
{
    use HasFactory;

    protected $table = 'karyawan';

    protected $fillable = [
        'id_karyawan',
        'nik',
        'nama',
        'jenis_kelamin',
        'tanggal_lahir',
        'alamat', 
        'no_hp',
        'jabatan_id',
        'email',
        'profil'
    ];

    /**
     * image
     *
     * @return Attribute
     */
    protected function image(): Attribute
    {
        return Attribute::make(
            get: fn ($image) => url('/storage/emp_profile/' . $image),
        );
    }
}
