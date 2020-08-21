@extends('admin.layouts.app')

@section('content')
  <div id="app">
    <edit-blog-component :blog-id="{{$id}}">
    </edit-blog-component>
  </div>
@endsection