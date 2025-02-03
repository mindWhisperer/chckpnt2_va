@extends('index')

@section('title', $book->name . ' | detail')

@section('content')
    <main>
        <div class="riadok">

            <div class="col-lg-6 textos">
                {{-- IS_LOGGED --}}
                @if(\App\Helpers\Roles::isLogged())
                    <button type="button" id="delete" data-id="{{$book->id}}" class="btn btn-outline-dark">Vymazať
                    </button>
                    <a href="{{route('edit-book', ['id' => $book->id])}}" class="btn btn-outline-secondary">Upraviť</a>
                @endif
                <h2>{{$book->name}}</h2>
                <p>
                    {{$book->description}}
                </p>

            </div>
            <div class="col-lg-6 obrazok">
                <img src="{{$book->image}}" alt="c">
            </div>

        </div>

    </main>
@endsection
