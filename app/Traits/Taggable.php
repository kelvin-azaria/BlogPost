<?php

namespace App\Traits;

use App\Tag;

trait Taggable{

    protected static function bootTaggable()
    {
        static::updating(function ($model){
            $model->Tags()->sync(static::findTagsInContent($model->content));
        });

        static::created(function ($model){
            $model->Tags()->sync(static::findTagsInContent($model->content));
        });
    }

    public function Tags()
    {
        return $this->morphToMany('App\Tag','taggable')->withTimestamps();
    }

    private static function findTagsInContent($content)
    {
        preg_match_all('/@([^@]+)@/m', $content, $tags);

        return Tag::whereIn('name', $tags[1] ?? [])->get();
    }
}