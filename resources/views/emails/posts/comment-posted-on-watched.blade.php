@component('mail::message')
# Comment was posted on a post you're watching

Hi {{$user->name}}

@component('mail::button', ['url' => route('posts.show', ['post'=>$comment->commentable->id]) ])
View the blog post
@endcomponent

@component('mail::button', ['url' => route('users.show',['user'=>$comment->user->id]) ])
Visit {{ $comment->user->name }}'s Profile
@endcomponent

@component('mail::panel')
{{$comment->content}}
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
