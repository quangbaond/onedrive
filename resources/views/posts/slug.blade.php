

@extends('layouts.app')
@section('css')
    <link rel="stylesheet" href="{{ asset('css/post_slug.css') }}">
@endsection
@section('content')
    @livewire('post-slug')
@endsection
