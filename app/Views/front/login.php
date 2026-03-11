<section class="container py-5">
  <div class="row justify-content-center">
    <div class="col-lg-5">
      <div class="card sh-card"><div class="card-body p-4">
        <h1 class="h3 mb-4">Login</h1>
        <form method="post">
          <?= csrf_field() ?>
          <div class="mb-3"><label class="form-label">E-Mail</label><input type="email" name="email" class="form-control" required></div>
          <div class="mb-3"><label class="form-label">Passwort</label><input type="password" name="password" class="form-control" required></div>
          <button class="btn btn-danger w-100">Einloggen</button>
        </form>
      </div></div>
    </div>
  </div>
</section>
