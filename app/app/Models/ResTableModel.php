<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class ResTableModel extends Model
{
    use HasFactory, HasApiTokens;

    protected $table = 'res_tables';
    protected $fillable = [
        'table_no',
        'no_of_seats',
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
