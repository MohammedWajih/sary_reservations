<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class ReservationModel extends Model
{
    use HasFactory, HasApiTokens;
    protected $table = 'reservations';
    protected $fillable = [
        'table_no',
        'starting',
        'ending',
        'reservation_date',
    ];
}
