@extends('index')

@section('title', 'Novinky')

@section('content')
    <main>
        <div class="riadok formular">
            <form class="row g-3" id="register">
                <h4>Registrácia užívateľa</h4>
                @include('partials.forms.register')

                <div class="col-12">
                    <button class="btn btn-primary">Registrovať</button>
                </div>
            </form>
        </div>
    </main>
@endsection
