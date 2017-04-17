@extends('layouts.default')
{{-- @section('title','Home') --}}
@section('content')
  <div class="jumbotron">
    <h1>Hello Home</h1>
    <p class="lead">
      你现在所看到的是<a href="https://laravel-china.org/laravel-tutorial/5.1">Laraval 入门教程</a>的
    </p>
    <p>
      <a class="btn btn-lg btn-success" href="{{ route('signup') }}" role="button">现在注册</a>
    </p>
  </div>
@stop
