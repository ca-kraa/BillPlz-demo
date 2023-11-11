<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class payment extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'id_pembayaran',
        'collection_id',
        'paid',
        'state',
        'amount',
        'paid_amount',
        'due_at',
        'email',
        'mobile',
        'name',
        'url',
        'reference_1_label',
        'reference_1',
        'reference_2_label',
        'reference_2',
        'redirect_url',
        'callback_url',
        'description',
        'paid_at',
    ];
}
