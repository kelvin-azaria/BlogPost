
  <div class="row">
    @component('components.card',['title'=> 'Most Commented'])
      @slot('subtitle')
        What people are currently talking about
      @endslot
      @slot('items')
        @foreach ($most_commented as $post)
          <li class="list-group-item">
            <a href="{{ route('posts.show', ['post'=> $post->id]) }}">{{ $post->title }}</a>
          </li>
        @endforeach
      @endslot
    @endcomponent
  </div>
  <div class="row pt-4">
    {{--<div class="card" style="width: 18rem;">
      <div class="card-body">
        <h5 class="card-title">Most Active Users</h5>
        <p class="card-text text-muted">Users who made the most posts</p>
      </div>
      <ul class="list-group list-group-flush">
        @foreach ($most_active as $user)
          <li class="list-group-item">
            {{ $user->name }}
          </li>
        @endforeach
      </ul>
    </div>--}}
    @component('components.card',['title'=> 'Most Active'])
      @slot('subtitle')
        Users with most post written
      @endslot
      @slot('items', collect($most_active)->pluck('name'))
    @endcomponent
  </div>
  <div class="row pt-4">
    {{--<div class="card" style="width: 18rem;">
      <div class="card-body">
        <h5 class="card-title">Most Active Users Last Month</h5>
        <p class="card-text text-muted">Users who made the most posts in the last month</p>
      </div>
      <ul class="list-group list-group-flush">
        @foreach ($most_active_last_month as $user)
          <li class="list-group-item">
            {{ $user->name }}
          </li>
        @endforeach
      </ul>
    </div>--}}
    @component('components.card',['title'=> 'Most Active Users Last Month'])
      @slot('subtitle')
        Users with most post written in the last month
      @endslot
      @slot('items', collect($most_active_last_month)->pluck('name'))
    @endcomponent
  </div>