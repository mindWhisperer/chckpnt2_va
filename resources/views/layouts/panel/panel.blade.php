@extends('index')

@section('title', 'Dashboard')


@section('content')
    <div class="container-fluid mt-5">
        <div class="card p-4 shadow-sm w-100">
            <h3>Moje knihy</h3>

            @if($books->isEmpty())
                <p>Nemáte zatiaľ žiadne knihy.</p>
            @else
                <div class="post">
                    @foreach($books as $book)
                            <div class="post-item row">
                                <div class="post-image col-lg-2">
                                    <img src="{{ $book->image ?? 'default.jpg' }}" alt="Kniha {{ $book->id }}" class="book-image">
                                </div>
                                <div class="post-content col-lg-8">
                                    <h5 class="post-title">{{ $book->name }}</h5>
                                    <p class="post-description">
                                    {{\Illuminate\Support\Str::limit($book->description, 190, '...')}}</p>
                                </div>
                                <div class="post-actions col-lg-2">
                                    <a href="{{route('edit-book', ['id' => $book->id])}}" class="btn btn-outline-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="{{route('detail', ['id' => $book->id])}}" class="btn btn-outline-secondary"><i class="bi bi-book"></i></a>

                                </div>
                            </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection
