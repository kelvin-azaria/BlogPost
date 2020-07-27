<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    public const LOCALES = [
        'en' => 'English',
        'es' => 'Espanol',
        'de' => 'Deutsch'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 
        'remember_token', 
        'email', 
        'email_verified_at', 
        'created_at', 
        'updated_at', 
        'is_admin',
        'locale'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function BlogPosts()
    {
        return $this->hasMany('App\BlogPost');
    }

    public function Comments()
    {
        return $this->hasMany('App\Comment');
    }

    public function CommentsOn()
    {
        return $this->morphMany('App\Comment','commentable')->latest();
    }

    public function Image()
    {
        return $this->morphOne('App\Image', 'imageable');
    }

    public function ScopeWithMostBlogPosts(Builder $query)
    {
        return $query->withCount('blogPosts')->orderBy('blog_posts_count', 'desc');
    }

    public function ScopeWithMostBlogPostsLastMonth(Builder $query)
    {
        return $query->withCount(['blogPosts' => function(Builder $query){
            $query->whereBetween(static::CREATED_AT, [now()->subMonths(3), now()]);
        }])->having('blog_posts_count', '>=', 2)
           ->orderBy('blog_posts_count', 'desc');
    }

    public function ScopeThatHasCommentedOnPost(Builder $query, BlogPost $post)
    {
        $query->whereHas('comments', function($query) use($post){
            return $query->where('commentable_id','=',$post->id)
                ->where('commentable_type','=',BlogPost::class);
        });
    }

    public function ScopeThatIsAnAdmin(Builder $query)
    {
        $query->where('is_admin', true);
    }
}
