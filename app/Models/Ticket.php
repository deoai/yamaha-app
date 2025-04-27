<?php

namespace App\Models;

use Filament\Forms\Components\Builder;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $guarded = [];
    protected $table = 'tickets';

    protected $casts = [
        'file' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
