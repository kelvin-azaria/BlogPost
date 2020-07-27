<?php

namespace App;

use App\Scopes\DeletedAdminScope;
use App\Scopes\LatestScope;
use App\Traits\Taggable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class BlogPost extends Model
{
    use SoftDeletes, Taggable;

    //protected $table = 'tb_blogposts';
    protected $fillable = ['title', 'content', 'user_id'];

    public function Comments()
    {
        return $this->morphMany('App\Comment','commentable')->latest();
    }

    public function User()
    {
        return $this->belongsTo('App\User');
    }

    public function Image()
    {
        return $this->morphOne('App\Image', 'imageable');
    }

    public function ScopeLatest(Builder $query)
    {
        return $query->orderBy(static::CREATED_AT, 'desc');
    }

    public function ScopeMostCommented(Builder $query)
    {
        //comments_count
        return $query->withCount('comments')->orderBy('comments_count', 'desc');
    }

    public function scopeLatestWithRelations(Builder $query)
    {
        return $query->latest()
                     ->withCount('comments')
                     ->with('user')
                     ->with('tags');
    }

    public static function Boot()
    {
        static::addGlobalScope(new DeletedAdminScope);
        parent::boot();

        // static::addGlobalScope(new LatestScope);
    }
}
