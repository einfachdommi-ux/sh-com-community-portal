<?php
namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Logger;

class DbToolsController extends Controller
{
    private array $whitelist = ['users', 'roles', 'permissions', 'pages', 'news', 'changelogs', 'team_members', 'navigation_items'];

    public function index(): void
    {
        $result = $_SESSION['_dbtools_result'] ?? null;
        unset($_SESSION['_dbtools_result']);
        $this->view('admin/dbtools', ['result' => $result, 'tables' => $this->whitelist], 'backend');
    }

    public function run(): void
    {
        $this->requirePost();
        $action = $_POST['action'] ?? 'select';
        $table = $_POST['table'] ?? '';
        if (!in_array($table, $this->whitelist, true)) {
            flash('error', 'Tabelle nicht erlaubt.');
            redirect('/admin/db-tools');
        }

        try {
            if ($action === 'select') {
                $rows = Database::query('SELECT * FROM ' . $table . ' ORDER BY id DESC LIMIT 50')->fetchAll();
                $_SESSION['_dbtools_result'] = ['type' => 'table', 'rows' => $rows];
            } elseif ($action === 'delete' && !empty($_POST['id'])) {
                Database::query('DELETE FROM ' . $table . ' WHERE id = ?', [(int)$_POST['id']]);
                $_SESSION['_dbtools_result'] = ['type' => 'message', 'message' => 'Datensatz gelöscht.'];
            } elseif ($action === 'insert' && !empty($_POST['json'])) {
                $data = json_decode($_POST['json'], true, 512, JSON_THROW_ON_ERROR);
                $columns = array_keys($data);
                $sql = 'INSERT INTO ' . $table . ' (' . implode(',', $columns) . ') VALUES (' . implode(',', array_fill(0, count($columns), '?')) . ')';
                Database::query($sql, array_values($data));
                $_SESSION['_dbtools_result'] = ['type' => 'message', 'message' => 'Datensatz eingefügt.'];
            } elseif ($action === 'update' && !empty($_POST['id']) && !empty($_POST['json'])) {
                $data = json_decode($_POST['json'], true, 512, JSON_THROW_ON_ERROR);
                $sets = [];
                foreach (array_keys($data) as $column) $sets[] = $column . ' = ?';
                $values = array_values($data);
                $values[] = (int)$_POST['id'];
                $sql = 'UPDATE ' . $table . ' SET ' . implode(', ', $sets) . ' WHERE id = ?';
                Database::query($sql, $values);
                $_SESSION['_dbtools_result'] = ['type' => 'message', 'message' => 'Datensatz aktualisiert.'];
            }
            Logger::audit('dbtools', $action, $table, !empty($_POST['id']) ? (int)$_POST['id'] : null);
        } catch (\Throwable $e) {
            $_SESSION['_dbtools_result'] = ['type' => 'error', 'message' => $e->getMessage()];
        }

        redirect('/admin/db-tools');
    }
}
