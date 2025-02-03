@extends('index')

@section('title', $book->name . ' | detail')

@section('content')
    <main>
        <div class="container">
            <!-- Kniha a jej detaily ostávajú v .riadok -->
            <div class="riadok row">
                <!-- Textová časť -->
                <div class="col-lg-6 textos">
                    @if(\App\Helpers\Roles::isLogged())
                        <button type="button" id="delete" data-id="{{$book->id}}" class="btn btn-outline-dark">Vymazať</button>
                        <a href="{{route('edit-book', ['id' => $book->id])}}" class="btn btn-outline-secondary">Upraviť</a>
                    @endif
                    <p>Žáner: {{ $genre ? $genre->name : 'N/A' }}</p>
                    <h2>{{$book->name}}</h2>
                    <p>{{$book->description}}</p>
                </div>

                <!-- Obrázok -->
                <div class="col-lg-6 obrazok">
                    <img src="{{$book->image}}" alt="c" class="img-fluid">
                </div>
            </div>

            <div class="row mt-4 riadok">
                <div class="col-lg-12 comments">
                    @if($comments->isNotEmpty()) <!-- Ak sú komentáre -->
                    <h3>Komentáre</h3>
                    @foreach ($comments as $comment)
                        @if(trim($comment->comment) !== '') <!-- Ak komentár nie je prázdny -->
                        <div class="comment">
                            <p>
                                <strong>{{ $comment->user_name ?? 'Anonymný používateľ' }}:</strong>
                            </p>
                            <p>{{ $comment->comment }}</p>

                            @if(Auth::id() === $comment->user_id)
                                <button type="button" id="deleteComment" class="btn btn-outline-dark" data-id="{{$comment->id}}" >Vymazať</button>
                            @endif
                        </div>
                        @endif
                    @endforeach
                    @else
                        <p>Žiadne komentáre k tejto knihe.</p>
                    @endif
                </div>
            </div>


        </div>


    </main>
@endsection
