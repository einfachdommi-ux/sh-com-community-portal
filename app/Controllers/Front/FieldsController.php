<?php
namespace App\Controllers\Front;

use App\Core\Database;
use App\Core\Controller;
use App\Core\Auth;
use App\Models\Field;
use App\Models\Farm;
use PDO;

class FieldsController extends Controller
{
    protected function requireLogin(): array
    {
        if (!Auth::check()) {
            header('Location: /login');
            exit;
        }

        $user = Auth::user();
        if (!$user) {
            header('Location: /login');
            exit;
        }

        return $user;
    }

    protected function normalizeFieldData(array $input, ?array $existing = null): array
    {
        $farmId = null;

        if (array_key_exists('farm_id', $input)) {
            $farmId = trim((string)($input['farm_id'] ?? '')) !== ''
                ? (int)$input['farm_id']
                : null;
        } elseif ($existing && !empty($existing['farm_id'])) {
            $farmId = (int)$existing['farm_id'];
        }

        return [
            'field_name' => trim((string)($input['field_name'] ?? '')),
            'current_crop' => trim((string)($input['current_crop'] ?? '')),
            'planned_crop' => trim((string)($input['planned_crop'] ?? '')),
            'planned_sowing_date' => trim((string)($input['planned_sowing_date'] ?? '')),
            'pending_work' => trim((string)($input['pending_work'] ?? '')),
            'fertilization_stage_1' => (int)($input['fertilization_stage_1'] ?? 0),
            'plowed' => (int)($input['plowed'] ?? 0),
            'lime' => (int)($input['lime'] ?? 0),
            'rolled' => (int)($input['rolled'] ?? 0),
            'fertilization_stage_2' => (int)($input['fertilization_stage_2'] ?? 0),
            'yield_bonus' => trim((string)($input['yield_bonus'] ?? '')),
            'notes' => trim((string)($input['notes'] ?? '')),
            'farm_id' => $farmId,
        ];
    }

    protected function loadFormOptions(): array
    {
        $crops = Database::query("
            SELECT name
            FROM field_data_crops
            ORDER BY name ASC
        ")->fetchAll(PDO::FETCH_ASSOC);

        $monthOptions = Database::query("
            SELECT value
            FROM field_data_
            WHERE type = 'planned_sowing_date'
            ORDER BY value ASC
        ")->fetchAll(PDO::FETCH_ASSOC);

        $pendingOptions = Database::query("
            SELECT value
            FROM field_data_
            WHERE type = 'pending_work'
            ORDER BY value ASC
        ")->fetchAll(PDO::FETCH_ASSOC);

        return [
            'crops' => array_map(static function (array $row): array {
                return [
                    'value' => (string)($row['name'] ?? ''),
                    'label' => (string)($row['name'] ?? ''),
                ];
            }, $crops),
            'monthOptions' => array_map(static function (array $row): array {
                return [
                    'value' => (string)($row['value'] ?? ''),
                    'label' => (string)($row['value'] ?? ''),
                ];
            }, $monthOptions),
            'pendingOptions' => array_map(static function (array $row): array {
                return [
                    'value' => (string)($row['value'] ?? ''),
                    'label' => (string)($row['value'] ?? ''),
                ];
            }, $pendingOptions),
        ];
    }

    public function index(): void
    {
        $user = $this->requireLogin();

        $filters = [
            'search' => trim((string)$this->input('search', '')),
            'pending_work' => trim((string)$this->input('pending_work', '')),
            'farm_id' => trim((string)$this->input('farm_id', '')),
            'planned_sowing_date' => trim((string)$this->input('planned_sowing_date', '')),
        ];

        $fieldModel = new Field();
        $fields = $fieldModel->all($filters);
        $farms = (new Farm())->all();

        $pendingWorks = Database::query("
            SELECT value
            FROM field_data_
            WHERE type = 'pending_work'
            ORDER BY value ASC
        ")->fetchAll(PDO::FETCH_ASSOC);

        $plannedSowingDates = Database::query("
            SELECT value
            FROM field_data_
            WHERE type = 'planned_sowing_date'
            ORDER BY value ASC
        ")->fetchAll(PDO::FETCH_ASSOC);

        $this->view('front/fields_list', [
            'title' => 'Felder',
            'fields' => $fields,
            'farms' => $farms,
            'filters' => $filters,
            'pendingWorks' => $pendingWorks,
            'plannedSowingDates' => $plannedSowingDates,
            'user' => $user,
        ], 'frontend');
    }

    public function create(): void
    {
        $user = $this->requireLogin();
        $options = $this->loadFormOptions();

        $this->view('front/fields_form', [
            'title' => 'Feld anlegen',
            'mode' => 'create',
            'field' => null,
            'farms' => (new Farm())->all(),
            'user' => $user,
            'crops' => $options['crops'],
            'monthOptions' => $options['monthOptions'],
            'pendingOptions' => $options['pendingOptions'],
        ], 'frontend');
    }

    public function store(): void
    {
        $this->requirePost();
        $this->requireLogin();

        if (!hash_equals($_SESSION['_csrf'] ?? '', $_POST['_csrf'] ?? '')) {
            $_SESSION['flash_error'] = 'Ungültiges CSRF-Token.';
            header('Location: /fields/create');
            exit;
        }

        $data = $this->normalizeFieldData($_POST);

        if ($data['field_name'] === '') {
            $_SESSION['flash_error'] = 'Bitte einen Feldnamen eingeben.';
            header('Location: /fields/create');
            exit;
        }

        (new Field())->create($data);

        $_SESSION['flash_success'] = 'Feld angelegt.';
        header('Location: /fields');
        exit;
    }

    public function edit(string $id): void
    {
        $user = $this->requireLogin();
        $field = (new Field())->find((int)$id);

        if (!$field) {
            http_response_code(404);
            echo 'Feld nicht gefunden.';
            return;
        }

        $options = $this->loadFormOptions();

        $this->view('front/fields_form', [
            'title' => 'Feld bearbeiten',
            'mode' => 'edit',
            'field' => $field,
            'farms' => (new Farm())->all(),
            'user' => $user,
            'crops' => $options['crops'],
            'monthOptions' => $options['monthOptions'],
            'pendingOptions' => $options['pendingOptions'],
        ], 'frontend');
    }

    public function update(string $id): void
    {
        $this->requirePost();
        $this->requireLogin();

        if (!hash_equals($_SESSION['_csrf'] ?? '', $_POST['_csrf'] ?? '')) {
            $_SESSION['flash_error'] = 'Ungültiges CSRF-Token.';
            header('Location: /fields/edit/' . (int)$id);
            exit;
        }

        $fieldId = (int)$id;
        $existing = (new Field())->find($fieldId);

        if (!$existing) {
            http_response_code(404);
            echo 'Feld nicht gefunden.';
            return;
        }

        $data = $this->normalizeFieldData($_POST, $existing);

        if ($data['field_name'] === '') {
            $_SESSION['flash_error'] = 'Bitte einen Feldnamen eingeben.';
            header('Location: /fields/edit/' . $fieldId);
            exit;
        }

        (new Field())->update($fieldId, $data);

        $_SESSION['flash_success'] = 'Feld gespeichert.';
        header('Location: /fields');
        exit;
    }

    public function delete(string $id): void
    {
        $this->requirePost();
        $this->requireLogin();

        if (!hash_equals($_SESSION['_csrf'] ?? '', $_POST['_csrf'] ?? '')) {
            $_SESSION['flash_error'] = 'Ungültiges CSRF-Token.';
            header('Location: /fields');
            exit;
        }

        (new Field())->delete((int)$id);

        $_SESSION['flash_success'] = 'Feld gelöscht.';
        header('Location: /fields');
        exit;
    }
}
