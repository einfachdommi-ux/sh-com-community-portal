<h2>Teammitglied bearbeiten</h2>

<form method="post" action="/admin/team/update/<?= $member['id'] ?>">

<label>Name</label>
<input type="text" name="display_name" value="<?= $member['display_name'] ?>" class="form-control">

<label>Rolle</label>
<input type="text" name="role" value="<?= $member['role'] ?>" class="form-control">

<label>Beschreibung</label>
<textarea name="description" class="form-control"><?= $member['description'] ?></textarea>

<label>Discord</label>
<input type="text" name="discord" value="<?= $member['discord'] ?>" class="form-control">

<label>Sortierung</label>
<input type="number" name="sort_order" value="<?= $member['sort_order'] ?>" class="form-control">

<button class="btn btn-success mt-3">
Speichern
</button>

</form>