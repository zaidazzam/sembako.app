<?php

namespace App\Enums;

enum UserRole: string
{
    case ADMIN = 'admin';
    case PETUGAS = 'petugas';
    case WARUNG = 'warung';
}
