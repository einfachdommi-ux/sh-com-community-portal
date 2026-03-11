<?php
namespace App\Controllers\Front;

use App\Core\Controller;
use App\Models\News;

class NewsController extends Controller
{
    public function index(): void
    {
        $news = (new News())->published();
        $this->view('front/news_index', compact('news'));
    }

    public function show(string $slug): void
    {
        $article = (new News())->findBySlug($slug);
        if (!$article) {
            http_response_code(404);
            exit('News nicht gefunden');
        }
        $this->view('front/news_show', compact('article'));
    }
}
