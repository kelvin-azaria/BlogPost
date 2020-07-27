@extends('layout')

@section('content')
  <form method="POST" action="{{ route('login') }}">
    @csrf

    <div class="container">
      <div class="row d-flex justify-content-center">
        <div class="col-md-6">

          <h2>
            <b>Login</b>
          </h2>
          <br>
      
          <div class="form-group">
            <label>Email</label>
            <input name="email" value="{{ old('email') }}" required
              class="form-control{{ $errors->has('email') ? ' is-invalid': '' }}">

            @if ($errors->has('email'))
              <span class="invalid-feedback">
                <strong>{{ $errors->first('email') }}</strong>
              </span>
            @endif
          </div>
      
          <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" value="" required
              class="form-control{{ $errors->has('password') ? ' is-invalid': '' }}">

            @if ($errors->has('password'))
              <span class="invalid-feedback">
                <strong>{{ $errors->first('password') }}</strong>
              </span>
            @endif
          </div>

          <div class="form-group">
            <label for="remember" class="form-check-label">
              Remember me
            </label>
            <div class="form-check">
              <input type="checkbox" name="remember" class="form-check-input"
                  value="{{ old('remember') ? 'checked': '' }}">
            </div>
          </div>
          
          <br>
          
          <button type="submit" class="btn btn-primary btn-block">
            Login
          </button>

        </div>
      </div>
    </div>

  </form>
@endsection