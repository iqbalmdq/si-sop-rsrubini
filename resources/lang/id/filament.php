<?php

return [
    'login' => [
        'heading' => 'Masuk ke akun Anda',
        'actions' => [
            'authenticate' => [
                'label' => 'Masuk',
            ],
        ],
        'fields' => [
            'email' => [
                'label' => 'Alamat email',
            ],
            'password' => [
                'label' => 'Kata sandi',
            ],
            'remember' => [
                'label' => 'Ingat saya',
            ],
        ],
        'messages' => [
            'failed' => 'Email atau kata sandi salah.',
        ],
    ],
    
    'pages' => [
        'dashboard' => [
            'title' => 'Dashboard',
        ],
    ],
    
    'resources' => [
        'label' => 'Data',
        'plural_label' => 'Data',
    ],
    
    'actions' => [
        'create' => [
            'label' => 'Tambah :label',
        ],
        'edit' => [
            'label' => 'Edit',
        ],
        'view' => [
            'label' => 'Lihat',
        ],
        'delete' => [
            'label' => 'Hapus',
        ],
        'save' => [
            'label' => 'Simpan',
        ],
        'cancel' => [
            'label' => 'Batal',
        ],
    ],
    
    'table' => [
        'actions' => [
            'label' => 'Aksi',
        ],
        'empty' => [
            'heading' => 'Tidak ada data',
            'description' => 'Belum ada data untuk ditampilkan.',
        ],
        'filters' => [
            'actions' => [
                'apply' => [
                    'label' => 'Terapkan filter',
                ],
                'remove' => [
                    'label' => 'Hapus filter',
                ],
                'remove_all' => [
                    'label' => 'Hapus semua filter',
                ],
            ],
        ],
        'pagination' => [
            'label' => 'Navigasi halaman',
            'overview' => 'Menampilkan :first sampai :last dari :total hasil',
            'fields' => [
                'records_per_page' => [
                    'label' => 'per halaman',
                ],
            ],
        ],
        'search' => [
            'label' => 'Cari',
            'placeholder' => 'Cari...',
        ],
    ],
    
    'forms' => [
        'are_you_sure' => 'Apakah Anda yakin?',
    ],
    
    'notifications' => [
        'saved' => [
            'title' => 'Tersimpan',
        ],
        'deleted' => [
            'title' => 'Terhapus',
        ],
    ],
];