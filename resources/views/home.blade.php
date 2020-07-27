@extends('layout')

@section('content')
<h1 class="text-center">
    {{ __('messages.welcome') }}
</h1>

<p class="text-center">
    {{ __('messages.example_with_value', ['name' => 'John']) }}
    <br>
    {{ trans_choice('messages.comments', 0) }}
    <br>
    {{ trans_choice('messages.comments', 1) }}
    <br>
    {{ trans_choice('messages.comments', 2) }}
</p>

<p class="text-center">
    Using JSON : {{ __('Welcome to Laravel!') }}
    <br>
    Using JSON : {{ __('Hello :name',['name' => 'John']) }}
</p>

<p class="text-center">This is the content of the main page</p>
@endsection