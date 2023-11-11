<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class history extends Model
{
    use HasFactory;

    protected $table = 'histories';
    protected $fillable = ['status', 'amount', 'paid_amount', 'due_at', 'email', 'mobile', 'name', 'url'];
}
