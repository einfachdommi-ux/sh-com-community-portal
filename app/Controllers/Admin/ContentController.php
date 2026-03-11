<?php
namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Auth;
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

        $data = [
            'title' => trim((string)($_POST['title'] ?? '')),
            'slug' => trim((string)($_POST['slug'] ?? '')),
            'content' => (string)($_POST['content'] ?? ''),
            'meta_title' => trim((string)($_POST['meta_title'] ?? '')) ?: null,
            'meta_description' => trim((string)($_POST['meta_description'] ?? '')) ?: null,
            'visibility' => $_POST['visibility'] ?? 'public',
            'status' => $_POST['status'] ?? 'draft',
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ];

        $id = (new Page())->create($data);
        Logger::audit('pages', 'create', 'page', $id, null, $data);
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

        $this->view('admin/page_edit', compact('page'), 'backend');
    }

    public function pageUpdate(int $id): void
    {
        $this->requirePost();

        $model = new Page();
        $page = $model->find($id);
        if (!$page) {
            flash('error', 'Seite nicht gefunden.');
            redirect('/admin/pages');
        }

        $data = [
            'title' => trim((string)($_POST['title'] ?? '')),
            'slug' => trim((string)($_POST['slug'] ?? '')),
            'content' => (string)($_POST['content'] ?? ''),
            'meta_title' => trim((string)($_POST['meta_title'] ?? '')) ?: null,
            'meta_description' => trim((string)($_POST['meta_description'] ?? '')) ?: null,
            'visibility' => $_POST['visibility'] ?? 'public',
            'status' => $_POST['status'] ?? 'draft',
            'updated_by' => Auth::id(),
        ];

        $model->update($id, $data);
        Logger::audit('pages', 'update', 'page', $id, $page, $data);
        flash('success', 'Seite aktualisiert.');
        redirect('/admin/pages');
    }

    public function pageDelete(int $id): void
    {
        $this->requirePost();

        $model = new Page();
        $page = $model->find($id);

        if ($page) {
            $model->delete($id);
            Logger::audit('pages', 'delete', 'page', $id, $page, null);
            flash('success', 'Seite gelöscht.');
        } else {
            flash('error', 'Seite nicht gefunden.');
        }

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

        $data = [
            'title' => trim((string)($_POST['title'] ?? '')),
            'slug' => trim((string)($_POST['slug'] ?? '')),
            'teaser' => trim((string)($_POST['teaser'] ?? '')) ?: null,
            'content' => (string)($_POST['content'] ?? ''),
            'featured_image' => $this->handleNewsImageUpload(),
            'status' => $_POST['status'] ?? 'draft',
            'published_at' => !empty($_POST['published_at']) ? $_POST['published_at'] : null,
            'author_id' => Auth::id(),
        ];

        $id = (new News())->create($data);
        Logger::audit('news', 'create', 'news', $id, null, $data);
        flash('success', 'News gespeichert.');
        redirect('/admin/news');
    }

    public function newsEdit(int $id): void
    {
        $article = (new News())->find($id);
        if (!$article) {
            flash('error', 'News nicht gefunden.');
            redirect('/admin/news');
        }

        $this->view('admin/news_edit', compact('article'), 'backend');
    }

    public function newsUpdate(int $id): void
    {
        $this->requirePost();

        $model = new News();
        $article = $model->find($id);
        if (!$article) {
            flash('error', 'News nicht gefunden.');
            redirect('/admin/news');
        }

        $newImage = $this->handleNewsImageUpload();

        $data = [
            'title' => trim((string)($_POST['title'] ?? '')),
            'slug' => trim((string)($_POST['slug'] ?? '')),
            'teaser' => trim((string)($_POST['teaser'] ?? '')) ?: null,
            'content' => (string)($_POST['content'] ?? ''),
            'featured_image' => $newImage !== '' ? $newImage : ($article['featured_image'] ?? null),
            'status' => $_POST['status'] ?? 'draft',
            'published_at' => !empty($_POST['published_at']) ? $_POST['published_at'] : null,
            'author_id' => Auth::id(),
        ];

        $model->update($id, $data);
        Logger::audit('news', 'update', 'news', $id, $article, $data);
        flash('success', 'News aktualisiert.');
        redirect('/admin/news');
    }

    public function newsDelete(int $id): void
    {
        $this->requirePost();

        $model = new News();
        $article = $model->find($id);

        if ($article) {
            $model->delete($id);
            Logger::audit('news', 'delete', 'news', $id, $article, null);
            flash('success', 'News gelöscht.');
        } else {
            flash('error', 'News nicht gefunden.');
        }

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
            'version' => $_POST['version'],
            'title' => $_POST['title'],
            'change_type' => $_POST['change_type'],
            'content' => $_POST['content'],
            'visibility' => $_POST['visibility'] ?? 'public',
            'released_at' => $_POST['released_at'],
            'created_by' => Auth::id(),
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
        $social = [
            'discord' => trim((string)($_POST['discord'] ?? '')),
            'x' => trim((string)($_POST['x'] ?? '')),
            'instagram' => trim((string)($_POST['instagram'] ?? '')),
        ];
        $data = [
            'display_name' => trim((string)($_POST['display_name'] ?? '')),
            'team_role' => trim((string)($_POST['team_role'] ?? '')),
            'bio' => trim((string)($_POST['bio'] ?? '')),
            'image_path' => $this->handleTeamImageUpload(),
            'sort_order' => (int)($_POST['sort_order'] ?? 0),
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
        $links = json_decode((string)($member['social_links'] ?? '{}'), true) ?: [];
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
            'discord' => trim((string)($_POST['discord'] ?? '')),
            'x' => trim((string)($_POST['x'] ?? '')),
            'instagram' => trim((string)($_POST['instagram'] ?? '')),
        ];
        $newImage = $this->handleTeamImageUpload();
        $data = [
            'display_name' => trim((string)($_POST['display_name'] ?? '')),
            'team_role' => trim((string)($_POST['team_role'] ?? '')),
            'bio' => trim((string)($_POST['bio'] ?? '')),
            'image_path' => $newImage !== '' ? $newImage : ($member['image_path'] ?? null),
            'sort_order' => (int)($_POST['sort_order'] ?? 0),
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
        $id = (new NavigationItem())->create([
            'parent_id' => !empty($_POST['parent_id']) ? (int)$_POST['parent_id'] : null,
            'area' => $_POST['area'],
            'label' => $_POST['label'],
            'route' => $_POST['route'],
            'icon' => $_POST['icon'] ?? null,
            'permission_slug' => $_POST['permission_slug'] ?? null,
            'sort_order' => (int)($_POST['sort_order'] ?? 0),
            'is_active' => isset($_POST['is_active']) ? 1 : 0,
        ]);
        Logger::audit('navigation', 'create', 'navigation_item', $id);
        flash('success', 'Navigationseintrag gespeichert.');
        redirect('/admin/navigation');
    }

    private function handleTeamImageUpload(): string
    {
        return $this->handleImageUpload('image_file', 'team', 'team_');
    }

    private function handleNewsImageUpload(): string
    {
        return $this->handleImageUpload('featured_image_file', 'news', 'news_');
    }

    private function handleImageUpload(string $field, string $folder, string $prefix): string
    {
        if (!isset($_FILES[$field]) || !is_array($_FILES[$field])) {
            return '';
        }

        $error = $_FILES[$field]['error'] ?? UPLOAD_ERR_NO_FILE;
        if ($error === UPLOAD_ERR_NO_FILE || $error !== UPLOAD_ERR_OK) {
            return '';
        }

        $tmpPath = (string)($_FILES[$field]['tmp_name'] ?? '');
        $originalName = (string)($_FILES[$field]['name'] ?? '');
        if ($tmpPath === '' || $originalName === '') {
            return '';
        }

        $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
        if (!in_array($ext, $allowed, true)) {
            return '';
        }

        $uploadDir = BASE_PATH . '/public/assets/uploads/' . $folder;
        if (!is_dir($uploadDir)) {
            @mkdir($uploadDir, 0775, true);
        }

        $fileName = $prefix . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
        $target = $uploadDir . '/' . $fileName;

        if (!move_uploaded_file($tmpPath, $target)) {
            return '';
        }

        return '/assets/uploads/' . $folder . '/' . $fileName;
    }
}
