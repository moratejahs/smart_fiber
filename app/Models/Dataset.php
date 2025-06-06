<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Dataset extends Model
{
    protected $guarded = [];
    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
