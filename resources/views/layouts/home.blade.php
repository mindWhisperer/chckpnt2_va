@extends('index')

@section('title', 'Novinky')

@section('content')
    <main>
        @foreach($books as $book)
            <div class="riadok">
                <div class="col-lg-6 textos">
                    <div class="btn">Novinka</div>
                    <a href="{{route('detail', ['id' => $book->id])}}" class="btn btn-outline-secondary">Čítať</a>
                    <h2>{{$book->name}}</h2>
                    <p>
                        {{\Illuminate\Support\Str::limit($book->description, 190, '...')}}
                    </p>
                </div>

                <div class="col-lg-6 obrazok">
                    <img src="{{$book->image}}" alt="c">
                </div>
            </div>
        @endforeach
    </main>
@endsection
