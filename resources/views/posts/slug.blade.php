

@extends('layouts.app')
@section('css')
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
@endsection
@section('content')
    @livewire('post-slug')
@endsection
