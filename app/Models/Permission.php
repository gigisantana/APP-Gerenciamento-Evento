<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    public function role() {
        return $this->belongsTo('App\Models\Role');
    }

    public function resource() {
        return $this->belongsTo('App\Models\Resource');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'permissions')
                    ->withPivot(['evento_id'])
                    ->withTimestamps();
    }

}
