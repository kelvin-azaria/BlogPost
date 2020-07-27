<?php

namespace App;

use App\Scopes\LatestScope;
use App\BlogPost;
use App\Traits\Taggable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class Comment extends Model
{
    use SoftDeletes, Taggable;

    protected $fillable = ['content','user_id'];
    protected $hidden = ['deleted_at','commentable_type','commentable_id','user_id'];

    public function commentable()
    {
        return $this->morphTo();
    }

    public function User()
    {
        return $this->belongsTo('App\User');
    }

    public function ScopeLatest(Builder $query)
    {
        return $query->orderBy(static::CREATED_AT, 'desc');
    }
}
