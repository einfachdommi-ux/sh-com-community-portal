<section class="container py-5">
  <div class="row justify-content-center">
    <div class="col-lg-6">
      <div class="card sh-card"><div class="card-body p-4">
        <h1 class="h3 mb-4">Registrierung</h1>
        <form method="post">
          <?= csrf_field() ?>
          <div class="row g-3">
            <div class="col-md-6"><label class="form-label">Benutzername</label><input type="text" name="username" class="form-control" value="<?= e(old('username')) ?>" required></div>
            <div class="col-md-6"><label class="form-label">E-Mail</label><input type="email" name="email" class="form-control" value="<?= e(old('email')) ?>" required></div>
            <div class="col-md-6"><label class="form-label">Passwort</label><input type="password" name="password" class="form-control" required></div>
            <div class="col-md-6"><label class="form-label">Passwort wiederholen</label><input type="password" name="password_confirmation" class="form-control" required></div>
          </div>
          <button class="btn btn-danger w-100 mt-4">Account erstellen</button>
        </form>
      </div></div>
    </div>
  </div>
</section>
