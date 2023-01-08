<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Board extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "boards";

    protected $fillable = [
        'title'
    ];

    public function cards()
    {
        return $this->hasMany(Card::class);
    }
}
