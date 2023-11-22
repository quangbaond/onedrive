@extends('layouts.app')
@section('css')
    <link rel="stylesheet" href="{{ './css/style.css' }}">
@endsection
@section('content')
    @livewire('list-post')
@endsection
