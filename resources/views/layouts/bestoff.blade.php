@extends('index')

@section('title', 'Stálice')

@section('content')
    <main class="main-grid">
        @foreach($books as $book)
            <div class="card flex_card">
                <img src="{{$book->image}}" class="card-img-top" alt="{{$book->name}}">
                <div class="card-body">
                    <h5 class="card-title">{{$book->name}}</h5>
                    <p class="card-text">{{\Illuminate\Support\Str::limit($book->description, 190, '...')}}</p>
                </div>
                <div class="card-footer">
                    <a href="{{route('detail', ['id' => $book->id])}}" class="btn btn-outline-secondary">Čítať</a>
                </div>
            </div>
        @endforeach
    </main>
@endsection
