<?php

namespace App\Http\ViewComposers;

use App\BlogPost;
use App\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class ActivityComposer
{
    public function compose(View $view)
    {
        $most_commented = Cache::tags(['blog-post'])->remember('most_commented', 60, function () {
            return BlogPost::mostCommented()->take(3)->get();
        });

        $most_active = Cache::remember('most_active', 60, function () {
            return User::withMostBlogPosts()->take(3)->get();
        });

        $most_active_last_month = Cache::remember('most_active_last_month', 60, function () {
            return User::withMostBlogPostsLastMonth()->take(5)->get();
        });

        $view->with('most_commented', $most_commented);
        $view->with('most_active', $most_active);
        $view->with('most_active_last_month', $most_active_last_month);
    }
}