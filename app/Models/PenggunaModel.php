<?php

namespace App\Models;

use CodeIgniter\Model;

class PenggunaModel extends Model
{
    protected $table            = 'pengguna';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['nama', 'jabatan', 'profile_pic', 'password', 'email', 'no_telfon'];
    protected $useTimestamps    = true;
    protected $validationRules  = [
        'nama'      => 'required',
        'jabatan'   => 'required',
        'email'     => 'required|valid_email|is_unique[pengguna.email]',
        'password'  => 'required|min_length[8]',
    ];

    protected $validationMessages = [
        'email' => [
            'is_unique' => 'Email sudah terdaftar.'
        ],
        'password' => [
            'min_length' => 'Password minimal 8 karakter.'
        ]
    ];
}
