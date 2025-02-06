<div class="row mt-4 riadok">
    <div class="col-lg-12 comments">
        <h3>Komentáre</h3>
        @if(\App\Helpers\Roles::isLogged())
        <form id="commentForm">
            <label for="comment">Pridaj komentár:</label>
            <textarea name="comment" id="comment" required></textarea>
            <input type="hidden" name="book_id" value="{{ $book->id }}">
            <input type="hidden" name="user_id" value="{{ auth()->id() }}">
            <button type="submit" class="btn btn btn-secondary">Pridať komentár</button>
        </form>
        @else
            <p>Pre pridanie komentára sa musíte prihlásiť.</p>
        @endif

        @if($comments->isNotEmpty()) <!-- Ak sú komentáre -->
        @foreach ($comments as $comment)
            @if(trim($comment->comment) !== '') <!-- Ak komentár nie je prázdny -->
            <div class="comment">
                <p>
                    <strong>{{ $comment->user_name ?? 'Anonymný používateľ' }}:</strong>
                </p>
                <p>{{ $comment->comment }}</p>

                @if(Auth::id() === $comment->user_id || Auth::user()->role ==='admin')
                    <!-- Tlačidlo na spustenie editácie komentára -->
                    <button class="editCommentButton">Upravit komentár</button>

                    <!-- Formulár na editovanie komentára (skrytý pred zobrazením formuláru) -->
                    <form class="editCommentForm" style="display: none;" action="#">
                        <input type="hidden" name="comment_id" value="{{ $comment->id }}">
                        <input type="hidden" name="book_id" value="{{ $book->id }}">
                        <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                        <label for="commentTextarea">Komentár:</label>
                        <textarea id="commentTextarea" name="comment" rows="4" cols="50"></textarea>
                        <button type="submit" id="saveButton">Uložiť</button>
                    </form>

                    <button type="button" class="deleteButton deleteComment" data-id="{{$comment->id}}" >Vymazať komentár</button>
                @endif
            </div>
            @endif
        @endforeach
        @else
            <p>Žiadne komentáre k tejto knihe.</p>
        @endif
    </div>
</div>



