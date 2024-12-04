@extends('index')

@section('title', 'Novinky')

@section('content')
    <main>
        <div class="riadok formular">
            <form class="row g-3" id="login">
                @include('partials.forms.login')
            </form>
        </div>
    </main>
@endsection
