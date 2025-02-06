<?php

namespace App\Http\Controllers;

use App\Providers\BookServiceProvider;
use App\Providers\GenreServiceProvider;
use App\Providers\UserServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;

readonly class PanelController
{
    private BookServiceProvider $bookService;
    private GenreServiceProvider $genreService;

    public function __construct()
    {
        $this->bookService = new BookServiceProvider();
        $this->genreService = new GenreServiceProvider();
    }

    public function panel(): View|Factory|Application
    {
        $books = $this->bookService->getBooksByCreator(Auth::id());
        return view('layouts.panel.panel', compact('books'));
    }

    public function addBook(): View|Factory|Application
    {
        $genreList = $this->genreService->readAll();
        return view('layouts.panel.add', ['genreList' => $genreList]);
    }

    public function editBook(int $id): View|Factory|Application
    {
        $genreList = $this->genreService->readAll();
        $book = $this->bookService->read($id);
        return view('layouts.panel.edit', ['genreList' => $genreList, 'book' => $book]);
    }

    public function profile()
    {
        $user = Auth::user();
        return view('layouts.panel.profile', compact('user'));
    }

    public function editProfile()
    {
        $user = Auth::user();
        return view('layouts.panel.editProfile', compact('user'));
    }

}
