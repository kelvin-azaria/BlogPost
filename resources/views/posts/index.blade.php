@extends('layout')

@section('content')
  <div class="row">
    <div class="col-8">
      @forelse ($posts as $post)
        <p>
          <h3>
            @if ($post->trashed())
              <del>
            @endif
            <a href="{{ route('posts.show', ['post'=> $post->id]) }}">{{ $post->title }}</a>
              </del>
          </h3>
          
          @component('components.tags', ['tags' => $post->tags])
          @endcomponent

          @component('components.updated',['date'=> $post->created_at, 'name' => $post->user->name, 'userId' => $post->user->id])
          @endcomponent

          <p>
            {{ trans_choice('messages.comments', $post->comments_count) }}
          </p>


          @auth
            @can('update', $post)
              <a href="{{ route('posts.edit', ['post'=> $post->id] )}}"
                class="btn btn-secondary">Edit</a>
            @endcan
          @endauth
            
          @cannot('delete', $post)
            <p>
              You cannot delete this post!
            </p>
          @endcannot
          
          @auth
            @if (!$post->trashed())
              @can('delete', $post)
              <form method="POST" class="d-inline" class="btn"
                    action="{{ route('posts.update',['post' => $post->id]) }}">
                @csrf
                @method('DELETE')

                <input type="submit" class="btn btn-danger" value="Delete">
              </form>
              @endcan
            @endif
          @endauth
        
        </p>
      @empty
        <p>Uh oh, there is nothing here</p>
      @endforelse
    </div>

    <div class="col-4">
      @include('posts._activity')
    </div>

  </div>
@endsection