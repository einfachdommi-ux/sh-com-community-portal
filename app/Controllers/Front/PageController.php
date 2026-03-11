<?php
namespace App\Controllers\Front;

use App\Core\Controller;
use App\Models\Page;

class PageController extends Controller
{
    public function show(string $slug): void
    {
        $page = (new Page())->findBySlug($slug);
        if (!$page) {
            http_response_code(404);
            exit('Seite nicht gefunden');
        }
        $this->view('front/page', compact('page'));
    }
}
