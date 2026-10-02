<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TestType extends Model
{
    protected $fillable = ['name', 'test_code'];
    
public function tests(): HasMany
{
    return $this->hasMany(Test::class);
}

}