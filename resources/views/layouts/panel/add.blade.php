@extends('index')

@section('title','Pridať')

@section('content')
    <main>
        <div class="riadok formular">
            <form class="row g-3" id="create">
                <div class="col-md-12">
                    <h4>Pridať novú knihu</h4>

                    @include('partials.forms.add_edit')

                </div>
                <div class="form-error-container"></div>

                <div class="col-12">
                    <button class="btn btn-primary">Pridať položku</button>
                </div>
            </form>
        </div>
    </main>
@endsection
