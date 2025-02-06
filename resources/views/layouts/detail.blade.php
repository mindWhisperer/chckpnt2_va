@extends('index')

@section('title', $book->name . ' | detail')

@section('content')
    <main>
        <div class="container">
            <!-- Kniha a jej detaily ostávajú v .riadok -->
            <div class="riadok row">
                <!-- Textová časť -->
                <div class="col-lg-6 textos">
                    @if(\App\Helpers\Roles::isLogged() && (Auth::id() === $book->creator_id || Auth::user()->role ==='admin'))
                        <button type="button" id="delete" data-id="{{$book->id}}" class="btn btn-outline-dark">Vymazať</button>
                        <a href="{{route('edit-book', ['id' => $book->id])}}" class="btn btn-outline-secondary">Upraviť</a>
                        @endif
                    <h2>{{$book->name}}</h2>
                        <br>
                    <p><strong>Žáner:</strong> {{ $genre ? $genre->name : 'N/A' }}</p>
                    <p><strong>Autor:</strong> {{ $creatorName }}</p>
                    <p>{{$book->description}}</p>
                </div>

                <!-- Obrázok -->
                <div class="col-lg-6 obrazok">
                    <img src="{{$book->image}}" alt="c" class="img-fluid">
                </div>
            </div>
            @include('partials.comments')
        </div>
    </main>
@endsection
