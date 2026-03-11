<h1>Team Verwaltung</h1>

<a href="/admin/team/create" class="btn btn-primary">Neues Teammitglied</a>

<table class="table mt-3">
<thead>
<tr>
<th>Name</th>
<th>Rolle</th>
<th>Discord</th>
<th>Aktionen</th>
</tr>
</thead>

<tbody>

<?php foreach($members as $member): ?>

<tr>
<td><?= $member['display_name'] ?></td>
<td><?= $member['role'] ?></td>
<td><?= $member['discord'] ?></td>

<td>

<a href="/admin/team/edit/<?= $member['id'] ?>" class="btn btn-warning btn-sm">
Bearbeiten
</a>

<a href="/admin/team/delete/<?= $member['id'] ?>" class="btn btn-danger btn-sm">
Löschen
</a>

</td>

</tr>

<?php endforeach; ?>

</tbody>
</table>
