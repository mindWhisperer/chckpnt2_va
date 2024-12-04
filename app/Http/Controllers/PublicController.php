<?php

namespace App\Http\Controllers;

use App\Providers\BookServiceProvider;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;

readonly class PublicController
{

    private BookServiceProvider $bookService;

    public function __construct()
    {
        $this->bookService = new BookServiceProvider();
    }

    public function index(): View|Factory|Application
    {
        $newest = $this->bookService->lastThree();
        return view('layouts.home', ['books' => $newest]);
    }

    public function detail($id): View|Factory|Application
    {
        $book = $this->bookService->read($id);
        return view('layouts.detail', ['book' => $book]);
    }

    public function bestOff(): View|Factory|Application
    {
        $allBooks = $this->bookService->readAll();
        return view('layouts.bestoff', ['books' => $allBooks]);
    }
}
