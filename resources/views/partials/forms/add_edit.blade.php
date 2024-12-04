<div class="col-12">
    <label class="form-label">Názov knihy <br>
        <input type="text" name="name" value="{{$book->name ?? ''}}" class="form-control" id="book-name">
    </label>
</div>
<div class="col-12">
    <label for="exampleFormControlTextarea1" class="form-label">O knihe</label>
    <textarea name="description" class="form-control" id="book-description" rows="3">{{$book->description ?? ''}}</textarea>
</div>
<div class="col-md-6">
    <label for="inputCity" class="form-label">Link na pútavý obrázok</label>
    <input type="url" name="image" value="{{$book->image ?? ''}}" class="form-control" id="book-image">
</div>
<div class="col-md-4">
    <label class="form-label">Žáner</label> <br>
    <select name="genre" class="form-select" id="book-genre">
        <option value="">...</option>
        @foreach($genreList as $genre)
            @php
                $isSelected = ($book->genre ?? '') === $genre->id ? "selected" : "";
            @endphp
            <option value="{{$genre->id}}" {{$isSelected}}>{{$genre->name}}</option>
        @endforeach
    </select>
</div>

