<!DOCTYPE html>
<html lang=sk>
<meta charset=UTF-8>
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title') - {{PROJECT_NAME}}</title>
@include('partials.head')
<header>
    @include('partials.menu')
</header>

@section('content')

@show

@include('partials.footer')
