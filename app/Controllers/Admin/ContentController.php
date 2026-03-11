<?php
namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Logger;
use App\Models\Changelog;
use App\Models\NavigationItem;
use App\Models\News;
use App\Models\Page;
use App\Models\TeamMember;

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
            'title' => trim((string) ($_POST['title'] ?? '')),
            'slug' => trim((string) ($_POST['slug'] ?? '')),
            'content' => (string) ($_POST['content'] ?? ''),
            'meta_title' => trim((string) ($_POST['meta_title'] ?? '')),
            'meta_description' => trim((string) ($_POST['meta_description'] ?? '')),
            'visibility' => trim((string) ($_POST['visibility'] ?? 'public')),
            'status' => trim((string) ($_POST['status'] ?? 'draft')),
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ]);

        Logger::audit('pages', 'create', 'page', $id);
        flash('success', 'Seite gespeichert.');
        redirect('/admin/pages');
    }

    public function pageEdit(int $id): void
    {
        $page = (new Page())->find($id);

        if (!$page) {
            flash('error', 'Seite nicht gefunden.');
            redirect('/admin/pages');
        }

        $this->view('admin/pages/edit', compact('page'), 'backend');
    }

    public function pageUpdate(int $id): void
    {
        $this->requirePost();

        $model = new Page();
        $old = $model->find($id);

        if (!$old) {
            flash('error', 'Seite nicht gefunden.');
            redirect('/admin/pages');
        }

        $data = [
            'title' => trim((string) ($_POST['title'] ?? '')),
            'slug' => trim((string) ($_POST['slug'] ?? '')),
            'content' => (string) ($_POST['content'] ?? ''),
            'meta_title' => trim((string) ($_POST['meta_title'] ?? '')),
            'meta_description' => trim((string) ($_POST['meta_description'] ?? '')),
            'visibility' => trim((string) ($_POST['visibility'] ?? 'public')),
            'status' => trim((string) ($_POST['status'] ?? 'draft')),
            'updated_by' => Auth::id(),
        ];

        $model->update($id, $data);
        Logger::audit('pages', 'update', 'page', $id, $old, $data);
        flash('success', 'Seite aktualisiert.');
        redirect('/admin/pages');
    }

    public function pageDelete(int $id): void
    {
        $this->requirePost();

        $model = new Page();
        $old = $model->find($id);

        if (!$old) {
            flash('error', 'Seite nicht gefunden.');
            redirect('/admin/pages');
        }

        $model->delete($id);
        Logger::audit('pages', 'delete', 'page', $id, $old, null);
        flash('success', 'Seite gelöscht.');
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

        $featuredImage = $this->handleNewsImageUpload();

        $data = [
            'title' => trim((string) ($_POST['title'] ?? '')),
            'slug' => trim((string) ($_POST['slug'] ?? '')),
            'teaser' => trim((string) ($_POST['teaser'] ?? '')),
            'content' => (string) ($_POST['content'] ?? ''),
            'status' => trim((string) ($_POST['status'] ?? 'draft')),
            'published_at' => !empty($_POST['published_at']) ? (string) $_POST['published_at'] : null,
            'author_id' => Auth::id(),
            'featured_image' => $featuredImage !== '' ? $featuredImage : null,
        ];

        $id = (new News())->create($data);
        Logger::audit('news', 'create', 'news', $id, null, $data);
        flash('success', 'News gespeichert.');
        redirect('/admin/news');
    }

    public function newsEdit(int $id): void
    {
        $item = (new News())->find($id);

        if (!$item) {
            flash('error', 'News-Eintrag nicht gefunden.');
            redirect('/admin/news');
        }

        $this->view('admin/news/edit', compact('item'), 'backend');
    }

    public function newsUpdate(int $id): void
    {
        $this->requirePost();

        $model = new News();
        $old = $model->find($id);

        if (!$old) {
            flash('error', 'News-Eintrag nicht gefunden.');
            redirect('/admin/news');
        }

        $featuredImage = $this->handleNewsImageUpload();
        if ($featuredImage === '') {
            $featuredImage = (string) ($old['featured_image'] ?? '');
        }

        $data = [
            'title' => trim((string) ($_POST['title'] ?? '')),
            'slug' => trim((string) ($_POST['slug'] ?? '')),
            'teaser' => trim((string) ($_POST['teaser'] ?? '')),
            'content' => (string) ($_POST['content'] ?? ''),
            'status' => trim((string) ($_POST['status'] ?? 'draft')),
            'published_at' => !empty($_POST['published_at']) ? (string) $_POST['published_at'] : null,
            'featured_image' => $featuredImage !== '' ? $featuredImage : null,
        ];

        $model->update($id, $data);
        Logger::audit('news', 'update', 'news', $id, $old, $data);
        flash('success', 'News aktualisiert.');
        redirect('/admin/news');
    }

    public function newsDelete(int $id): void
    {
        $this->requirePost();

        $model = new News();
        $old = $model->find($id);

        if (!$old) {
            flash('error', 'News-Eintrag nicht gefunden.');
            redirect('/admin/news');
        }

        $model->delete($id);
        Logger::audit('news', 'delete', 'news', $id, $old, null);
        flash('success', 'News gelöscht.');
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

        $data = [
            'version' => trim((string) ($_POST['version'] ?? '')),
            'title' => trim((string) ($_POST['title'] ?? '')),
            'change_type' => trim((string) ($_POST['change_type'] ?? 'Changed')),
            'content' => trim((string) ($_POST['content'] ?? '')),
            'visibility' => trim((string) ($_POST['visibility'] ?? 'public')),
            'released_at' => (string) ($_POST['released_at'] ?? now()),
            'created_by' => Auth::id(),
        ];

        $id = (new Changelog())->create($data);
        Logger::audit('changelog', 'create', 'changelog', $id, null, $data);
        flash('success', 'Changelog gespeichert.');
        redirect('/admin/changelogs');
    }

    public function changelogEdit(int $id): void
    {
        $item = (new Changelog())->find($id);

        if (!$item) {
            flash('error', 'Changelog nicht gefunden.');
            redirect('/admin/changelogs');
        }

        $this->view('admin/changelogs/edit', compact('item'), 'backend');
    }

    public function changelogUpdate(int $id): void
    {
        $this->requirePost();

        $model = new Changelog();
        $old = $model->find($id);

        if (!$old) {
            flash('error', 'Changelog nicht gefunden.');
            redirect('/admin/changelogs');
        }

        $data = [
            'version' => trim((string) ($_POST['version'] ?? '')),
            'title' => trim((string) ($_POST['title'] ?? '')),
            'change_type' => trim((string) ($_POST['change_type'] ?? 'Changed')),
            'content' => trim((string) ($_POST['content'] ?? '')),
            'visibility' => trim((string) ($_POST['visibility'] ?? 'public')),
            'released_at' => (string) ($_POST['released_at'] ?? now()),
        ];

        $model->update($id, $data);
        Logger::audit('changelog', 'update', 'changelog', $id, $old, $data);
        flash('success', 'Changelog aktualisiert.');
        redirect('/admin/changelogs');
    }

    public function changelogDelete(int $id): void
    {
        $this->requirePost();

        $model = new Changelog();
        $old = $model->find($id);

        if (!$old) {
            flash('error', 'Changelog nicht gefunden.');
            redirect('/admin/changelogs');
        }

        $model->delete($id);
        Logger::audit('changelog', 'delete', 'changelog', $id, $old, null);
        flash('success', 'Changelog gelöscht.');
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

        $social = [
            'discord' => trim((string) ($_POST['discord'] ?? '')),
            'x' => trim((string) ($_POST['x'] ?? '')),
            'instagram' => trim((string) ($_POST['instagram'] ?? '')),
        ];

        $data = [
            'display_name' => trim((string) ($_POST['display_name'] ?? '')),
            'team_role' => trim((string) ($_POST['team_role'] ?? '')),
            'bio' => trim((string) ($_POST['bio'] ?? '')),
            'image_path' => $this->handleTeamImageUpload(),
            'sort_order' => (int) ($_POST['sort_order'] ?? 0),
            'is_active' => isset($_POST['is_active']) ? 1 : 0,
            'social_links' => $social,
        ];

        $id = (new TeamMember())->create($data);
        Logger::audit('team', 'create', 'team_member', $id, null, $data);
        flash('success', 'Teammitglied gespeichert.');
        redirect('/admin/team');
    }

    public function teamEdit(int $id): void
    {
        $member = (new TeamMember())->find($id);

        if (!$member) {
            flash('error', 'Teammitglied nicht gefunden.');
            redirect('/admin/team');
        }

        $links = json_decode((string) ($member['social_links'] ?? '{}'), true) ?: [];
        $member['discord'] = $links['discord'] ?? '';
        $member['x'] = $links['x'] ?? '';
        $member['instagram'] = $links['instagram'] ?? '';

        $this->view('admin/team/edit', compact('member'), 'backend');
    }

    public function teamUpdate(int $id): void
    {
        $this->requirePost();

        $model = new TeamMember();
        $member = $model->find($id);

        if (!$member) {
            flash('error', 'Teammitglied nicht gefunden.');
            redirect('/admin/team');
        }

        $social = [
            'discord' => trim((string) ($_POST['discord'] ?? '')),
            'x' => trim((string) ($_POST['x'] ?? '')),
            'instagram' => trim((string) ($_POST['instagram'] ?? '')),
        ];

        $newImage = $this->handleTeamImageUpload();

        $data = [
            'display_name' => trim((string) ($_POST['display_name'] ?? '')),
            'team_role' => trim((string) ($_POST['team_role'] ?? '')),
            'bio' => trim((string) ($_POST['bio'] ?? '')),
            'image_path' => $newImage !== '' ? $newImage : ($member['image_path'] ?? null),
            'sort_order' => (int) ($_POST['sort_order'] ?? 0),
            'is_active' => isset($_POST['is_active']) ? 1 : 0,
            'social_links' => $social,
        ];

        $model->update($id, $data);
        Logger::audit('team', 'update', 'team_member', $id, $member, $data);
        flash('success', 'Teammitglied aktualisiert.');
        redirect('/admin/team');
    }

    public function teamDelete(int $id): void
    {
        $this->requirePost();

        $model = new TeamMember();
        $member = $model->find($id);

        if ($member) {
            $model->delete($id);
            Logger::audit('team', 'delete', 'team_member', $id, $member, null);
            flash('success', 'Teammitglied gelöscht.');
        } else {
            flash('error', 'Teammitglied nicht gefunden.');
        }

        redirect('/admin/team');
    }

    public function teamSort(): void
    {
        $this->requirePost();

        $ids = $_POST['ids'] ?? [];
        if (!is_array($ids) || $ids === []) {
            flash('error', 'Ungültige Sortierung.');
            redirect('/admin/team');
        }

        (new TeamMember())->updateSortOrder($ids);
        Logger::audit('team', 'sort', 'team_member');

        header('Content-Type: application/json');
        echo json_encode(['success' => true]);
        exit;
    }

    public function navigation(): void
    {
        $items = (new NavigationItem())->all('area ASC, sort_order ASC');
        $this->view('admin/navigation', compact('items'), 'backend');
    }

    public function navigationStore(): void
    {
        $this->requirePost();

        $data = [
            'parent_id' => !empty($_POST['parent_id']) ? (int) $_POST['parent_id'] : null,
            'area' => trim((string) ($_POST['area'] ?? 'frontend')),
            'label' => trim((string) ($_POST['label'] ?? '')),
            'route' => trim((string) ($_POST['route'] ?? '')),
            'icon' => trim((string) ($_POST['icon'] ?? '')),
            'permission_slug' => trim((string) ($_POST['permission_slug'] ?? '')),
            'sort_order' => (int) ($_POST['sort_order'] ?? 0),
            'is_active' => isset($_POST['is_active']) ? 1 : 0,
        ];

        $id = (new NavigationItem())->create($data);
        Logger::audit('navigation', 'create', 'navigation_item', $id, null, $data);
        flash('success', 'Navigationseintrag gespeichert.');
        redirect('/admin/navigation');
    }

    public function navigationEdit(int $id): void
    {
        $item = (new NavigationItem())->find($id);

        if (!$item) {
            flash('error', 'Navigationseintrag nicht gefunden.');
            redirect('/admin/navigation');
        }

        $this->view('admin/navigation/edit', compact('item'), 'backend');
    }

    public function navigationUpdate(int $id): void
    {
        $this->requirePost();

        $model = new NavigationItem();
        $old = $model->find($id);

        if (!$old) {
            flash('error', 'Navigationseintrag nicht gefunden.');
            redirect('/admin/navigation');
        }

        $data = [
            'parent_id' => !empty($_POST['parent_id']) ? (int) $_POST['parent_id'] : null,
            'area' => trim((string) ($_POST['area'] ?? 'frontend')),
            'label' => trim((string) ($_POST['label'] ?? '')),
            'route' => trim((string) ($_POST['route'] ?? '')),
            'icon' => trim((string) ($_POST['icon'] ?? '')),
            'permission_slug' => trim((string) ($_POST['permission_slug'] ?? '')),
            'sort_order' => (int) ($_POST['sort_order'] ?? 0),
            'is_active' => isset($_POST['is_active']) ? 1 : 0,
        ];

        $model->update($id, $data);
        Logger::audit('navigation', 'update', 'navigation_item', $id, $old, $data);
        flash('success', 'Navigationseintrag aktualisiert.');
        redirect('/admin/navigation');
    }

    public function navigationDelete(int $id): void
    {
        $this->requirePost();

        $model = new NavigationItem();
        $old = $model->find($id);

        if (!$old) {
            flash('error', 'Navigationseintrag nicht gefunden.');
            redirect('/admin/navigation');
        }

        $model->delete($id);
        Logger::audit('navigation', 'delete', 'navigation_item', $id, $old, null);
        flash('success', 'Navigationseintrag gelöscht.');
        redirect('/admin/navigation');
    }

    private function handleTeamImageUpload(): string
    {
        if (!isset($_FILES['image_file']) || !is_array($_FILES['image_file'])) {
            return '';
        }

        $error = $_FILES['image_file']['error'] ?? UPLOAD_ERR_NO_FILE;
        if ($error === UPLOAD_ERR_NO_FILE || $error !== UPLOAD_ERR_OK) {
            return '';
        }

        $tmpPath = (string) ($_FILES['image_file']['tmp_name'] ?? '');
        $originalName = (string) ($_FILES['image_file']['name'] ?? '');

        if ($tmpPath === '' || $originalName === '') {
            return '';
        }

        $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];

        if (!in_array($ext, $allowed, true)) {
            return '';
        }

        $uploadDir = BASE_PATH . '/public/assets/uploads/team';
        if (!is_dir($uploadDir)) {
            @mkdir($uploadDir, 0775, true);
        }

        $fileName = 'team_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
        $target = $uploadDir . '/' . $fileName;

        if (!move_uploaded_file($tmpPath, $target)) {
            return '';
        }

        return '/assets/uploads/team/' . $fileName;
    }

    private function handleNewsImageUpload(): string
    {
        if (!isset($_FILES['featured_image_file']) || !is_array($_FILES['featured_image_file'])) {
            return '';
        }

        $error = $_FILES['featured_image_file']['error'] ?? UPLOAD_ERR_NO_FILE;
        if ($error === UPLOAD_ERR_NO_FILE || $error !== UPLOAD_ERR_OK) {
            return '';
        }

        $tmpPath = (string) ($_FILES['featured_image_file']['tmp_name'] ?? '');
        $originalName = (string) ($_FILES['featured_image_file']['name'] ?? '');

        if ($tmpPath === '' || $originalName === '') {
            return '';
        }

        $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];

        if (!in_array($ext, $allowed, true)) {
            return '';
        }

        $uploadDir = BASE_PATH . '/public/assets/uploads/news';
        if (!is_dir($uploadDir)) {
            @mkdir($uploadDir, 0775, true);
        }

        $fileName = 'news_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
        $target = $uploadDir . '/' . $fileName;

        if (!move_uploaded_file($tmpPath, $target)) {
            return '';
        }

        return '/assets/uploads/news/' . $fileName;
    }
}
