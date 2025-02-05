<?php

namespace App\Http\Controllers;

use App\Providers\BookServiceProvider;
use App\Providers\CommentServiceProvider;
use App\Providers\GenreServiceProvider;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;

readonly class PublicController
{

    private BookServiceProvider $bookService;
    private GenreServiceProvider $genreService;
    private CommentServiceProvider $commentService;

    public function __construct()
    {
        $this->bookService = new BookServiceProvider();
        $this->genreService = new GenreServiceProvider();
        $this->commentService = new CommentServiceProvider();
    }

    public function index(): View|Factory|Application
    {
        $newest = $this->bookService->lastThree();
        return view('layouts.home', ['books' => $newest]);
    }

    public function detail($id): View|Factory|Application
    {
        $book = $this->bookService->read($id);
        $creator = $this->bookService->getCreator($id);
        $creatorName = $creator ? $creator->creator_name : 'Neznámy';
        $genreId = $book->genre;
        $genre = $this->genreService->read($genreId);
        $comments = $this->commentService->getCommentsForBook($id);

        return view('layouts.detail', [
            'book' => $book,
            'genre' => $genre,
            'comments' => $comments,
            'creatorName' => $creatorName
        ]);
    }


    public function bestOff(): View|Factory|Application
    {
        $allBooks = $this->bookService->readAll();
        return view('layouts.bestoff', ['books' => $allBooks]);
    }


}
