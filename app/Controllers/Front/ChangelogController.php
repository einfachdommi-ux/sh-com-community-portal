<?php
namespace App\Controllers\Front;

use App\Core\Controller;
use App\Models\Changelog;

class ChangelogController extends Controller
{
    public function index(): void
    {
        $items = (new Changelog())->latest(50);
        $this->view('front/changelog_index', compact('items'));
    }
}
