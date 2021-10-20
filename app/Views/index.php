
<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<?php if (session()->has('LoggedUserData')) {?>
<div class="container text-center py-5 text-white border border-dark my-5">
	<h2 class="display-5 my-3"><?= session()->get('LoggedUserData')['user_username'] ?></h2>
	<p class="lead fs-5 text-muted fw-bold">Email : <?= session()->get('LoggedUserData')['user_email'] ?></p>
	<p class="lead fs-5 text-muted fw-bold">ID : <?= session()->get('LoggedUserData')['oauth_id'] ?></p>
</div>
<?php } else { ?>
<div class="container-fluid text-white text-center py-5">
	<p class="lead fw-bold p-5">Please login to see your data profile</p>
</div>
<?php } ?>

<?= $this->endSection() ?>