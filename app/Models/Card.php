<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Card extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "cards";

    protected $fillable = [
        'board_id', 'title', 'description',
    ];

    public function board()
    {
        return $this->belongsTo(Board::class);
    }
}
