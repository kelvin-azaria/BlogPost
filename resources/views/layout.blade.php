<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Laravel</title>

	<link rel="stylesheet" href="{{ mix('css/app.css') }}">
</head>

<body>

  <div class="d-flex flex-column flex-md-row align-items-center p-3 px-md-4 mb-3 bg-white border-bottom shadow-sm">
    <h5 class="my-0 mr-md-auto font-weight-normal">Laravel Blog</h5>
    <nav class="my-2 my-md-0 mr-md-3">
      <a class="p-2 text-dark" href="{{ route('home') }}"> {{__('Home')}} </a>
      <a class="p-2 text-dark" href="{{ route('contact') }}"> {{__('Contact')}} </a>
      <a class="p-2 text-dark" href="{{ route('posts.index') }}"> {{__('Blog Posts')}} </a>
      <a class="p-2 text-dark" href="{{ route('posts.create') }}"> {{__('Add')}} </a>
      @guest

        @if (Route::has('register'))
          <a class="btn btn-primary" href="{{ route('register') }}"> {{__('Register')}} </a>
        @endif

        <a class="btn btn-success" href="{{ route('login') }}"> {{__('Login')}} </a>

      @else
        <a class="p-2 text-dark" 
          href="{{ route('users.show', ['user' => Auth::user()->id]) }}"> 
          {{__('Profile')}} 
        </a>
        <a class="p-2 text-dark" 
          href="{{ route('users.edit', ['user' => Auth::user()->id]) }}"> 
          {{__('Edit Profile')}} 
        </a>
        <form id="logout-btn" class="d-inline" method="POST" action="{{ route('logout') }}">
            @csrf
            <input type="submit" class="btn btn-danger" value="{{__('Logout')}} ({{ Auth::user()->name }})">
        </form>
      @endguest
    </nav>
  </div>

  <div class="container">
  
    @if (session()->has('status'))
        <p style="color: green">
          {{ session()->get('status') }}
        </p>
    @endif
  
    @yield('content')
  </div>
  
  <script type="text/javascript" src="{{ mix('js/app.js') }}"></script>
</body>
</html>