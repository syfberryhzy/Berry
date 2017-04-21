@extends('layouts.default')
{{-- @section('title','Home') --}}
@section('content')
@if(Auth::check())
  <div class="row">
    <div class="col-md-8">
      <section class="status_form">
        @include('shared.status_form')
      </section>
      <h3>微博列表</h3>
      @include('shared.feed')
    </div>
    <aside class="col-md-4">
      <section class="user_info">
        @include('shared.user_info',['user'=> Auth::user()])
      </section>
    </aside>
  </div>

@else

  <div class="jumbotron">
    <h1>Hello Home</h1>
    <p class="lead">
      你现在所看到的是<a href="https://laravel-china.org/laravel-tutorial/5.1">Laraval 入门教程</a>的
    </p>
    <p>
      <a class="btn btn-lg btn-success" href="{{ route('signup') }}" role="button">现在注册</a>
    </p>
  </div>
@endif
@stop
