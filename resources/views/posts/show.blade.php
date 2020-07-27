@extends('layout')

@section('content')
<div class="row">
  <div class="col-8">

    @if ($post->image)
      <div style="background-image: url('{{ Storage::url($post->image->path) }}'); min-height: 500px; color: white; text-align: center; background-attachment: fixed;">
        <h1 style="padding-top: 100px; text-shadow: 1px 2px #000">
    @else
        <h1>
    @endif

      {{ $post->title }}

      @component('components.badge',['show'=>now()->diffInMinutes($post->created_at) < 30 ])
        Brand New Post !
      @endcomponent
        </h1>

    @if ($post->image)
      </div>
    @endif
        
    
    {{-- <img src="{{ Storage::url($post->image->path) }}" alt=""> --}}
    
    <h4>{{ $post->content }}</h4>

    @component('components.updated',['date'=> $post->created_at, 'name' => $post->user->name])
    @endcomponent

    @component('components.updated',['date'=> $post->updated_at])
      Updated
    @endcomponent

    @component('components.tags', ['tags' => $post->tags])
    @endcomponent

    <p>{{ trans_choice('messages.people.reading', $counter) }}</p>

    <h4 class="text-info"><b>Comments</b></h4>

    <br>

    @component('components.comment_form', [
      'route'=>route('posts.comment.store',['post'=>$post->id])
      ])
    @endcomponent

    @component('components.comment_list', ['comments' => $post->comments])
    @endcomponent

  </div>
  <div class="col-4">
    @include('posts._activity')
  </div>
</div>
@endsection