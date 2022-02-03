<?php

namespace App\Http\Controllers;

use App\Services\AuthorService;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    public function __construct(
        protected AuthorService $authorService
    )
    {}

    /**
     * Send authors from the currently authenticated user
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $authors = $this->authorService->getAll();

        return $this->sendResponse($authors, 'Authors retrieved correctly');
    }
}
