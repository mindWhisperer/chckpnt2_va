@extends('index')

@section('title', 'Novinky')

@section('content')
    <main>
        <div class="riadok formular">
            <form class="row g-3">
                @include('partials.forms.register')
            </form>
        </div>
    </main>
@endsection
