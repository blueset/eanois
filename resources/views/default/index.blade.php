@extends('layouts.outer')

@section('content')
    <h1>Hi!</h1>
    {{ dump($view_name) }}
    {{ dump(Route::current()) }}
@endsection