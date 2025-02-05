@extends('index')

@section('title', $user->name . ' | Upraviť')

@section('content')
    <main>
        <div class="riadok formular">
            <form class="row g-3" id="editProf">
                <input type="hidden" name="id" value="{{ $user->id ?? '' }}">
                <div class="col-md-12">
                    <h4>Upravuješ profil - {{$user->name ?? ''}}</h4>

                    @include('partials.forms.edit_profile')

                </div>
                <div class="form-error-container"> </div>

                <div class="col-12">
                    <button class="btn btn-primary">Aktualizovať položku</button>
                </div>
            </form>
        </div>
    </main>
@endsection

