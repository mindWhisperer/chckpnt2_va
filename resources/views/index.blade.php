<!DOCTYPE html>
<html lang=sk>
<meta charset=UTF-8>
<title>@yield('title') - {{PROJECT_NAME}}</title>
@include('partials.head')
<header>
    @include('partials.menu')
</header>

@section('content')

@show

@include('partials.footer')
