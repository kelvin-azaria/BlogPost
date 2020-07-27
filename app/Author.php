<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    public function Profile()
    {
        return $this->hasOne('App\Profile');
    }
}
