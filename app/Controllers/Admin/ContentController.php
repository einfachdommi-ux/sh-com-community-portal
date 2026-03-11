<?php
namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Logger;
use App\Models\Page;
use App\Models\News;
use App\Models\Changelog;
use App\Models\TeamMember;
use App\Models\NavigationItem;

class ContentController extends Controller
{
    public function pages(): void
    {
        $pages = (new Page())->all('id DESC');
        $this->view('admin/pages', compact('pages'), 'backend');
    }

    public function pageStore(): void
    {
        $this->requirePost();
        $id = (new Page())->create([
            'title' => $_POST['title'], 'slug' => $_POST['slug'], 'content' => $_POST['content'],
            'meta_title' => $_POST['meta_title'] ?? null, 'meta_description' => $_POST['meta_description'] ?? null,
            'visibility' => $_POST['visibility'] ?? 'public', 'status' => $_POST['status'] ?? 'draft',
            'created_by' => Auth::id(), 'updated_by' => Auth::id(),
        ]);
        Logger::audit('pages', 'create', 'page', $id);
        flash('success', 'Seite gespeichert.');
        redirect('/admin/pages');
    }

    public function news(): void
    {
        $items = (new News())->all('id DESC');
        $this->view('admin/news', compact('items'), 'backend');
    }

    public function newsStore(): void
    {
        $this->requirePost();
        $id = (new News())->create([
            'title' => $_POST['title'], 'slug' => $_POST['slug'], 'teaser' => $_POST['teaser'] ?? null,
            'content' => $_POST['content'], 'status' => $_POST['status'] ?? 'draft',
            'published_at' => !empty($_POST['published_at']) ? $_POST['published_at'] : null, 'author_id' => Auth::id(),
        ]);
        Logger::audit('news', 'create', 'news', $id);
        flash('success', 'News gespeichert.');
        redirect('/admin/news');
    }

    public function changelogs(): void
    {
        $items = (new Changelog())->all('released_at DESC');
        $this->view('admin/changelogs', compact('items'), 'backend');
    }

    public function changelogStore(): void
    {
        $this->requirePost();
        $id = (new Changelog())->create([
            'version' => $_POST['version'], 'title' => $_POST['title'], 'change_type' => $_POST['change_type'],
            'content' => $_POST['content'], 'visibility' => $_POST['visibility'] ?? 'public',
            'released_at' => $_POST['released_at'], 'created_by' => Auth::id(),
        ]);
        Logger::audit('changelog', 'create', 'changelog', $id);
        flash('success', 'Changelog gespeichert.');
        redirect('/admin/changelogs');
    }

    public function team(): void
    {
        $items = (new TeamMember())->all('sort_order ASC, id ASC');
        $this->view('admin/team', compact('items'), 'backend');
    }

    public function teamStore(): void
    {
        $this->requirePost();
        $social = ['discord' => $_POST['discord'] ?? '', 'x' => $_POST['x'] ?? '', 'instagram' => $_POST['instagram'] ?? ''];
        $id = (new TeamMember())->create([
            'display_name' => $_POST['display_name'], 'team_role' => $_POST['team_role'], 'bio' => $_POST['bio'] ?? null,
            'sort_order' => (int)($_POST['sort_order'] ?? 0), 'social_links' => $social,
        ]);
        Logger::audit('team', 'create', 'team_member', $id);
        flash('success', 'Teammitglied gespeichert.');
        redirect('/admin/team');
    }

    public function navigation(): void
    {
        $items = (new NavigationItem())->all('area ASC, sort_order ASC');
        $this->view('admin/navigation', compact('items'), 'backend');
    }

    public function navigationStore(): void
    {
        $this->requirePost();
        $id = (new NavigationItem())->create([
            'parent_id' => !empty($_POST['parent_id']) ? (int)$_POST['parent_id'] : null,
            'area' => $_POST['area'], 'label' => $_POST['label'], 'route' => $_POST['route'],
            'icon' => $_POST['icon'] ?? null, 'permission_slug' => $_POST['permission_slug'] ?? null,
            'sort_order' => (int)($_POST['sort_order'] ?? 0), 'is_active' => isset($_POST['is_active']) ? 1 : 0,
        ]);
        Logger::audit('navigation', 'create', 'navigation_item', $id);
        flash('success', 'Navigationseintrag gespeichert.');
        redirect('/admin/navigation');
    }
}
