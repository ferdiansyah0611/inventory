<?= $this->extend('template') ?>
<?= $this->section('title') ?>
Create User
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="row mt-1">
	<div class="col-12">
		<div class="card mt-3">
			<div class="card-header">
				<div class="row align-items-center">
					<div class="col">
						<h6 class="text-light text-uppercase ls-1 mb-1"><?= isset($data['id']) ? 'Edit': 'Create' ?></h6>
					</div>
				</div>
			</div>
			<div class="card-body">
				<form action="<?= route_to($controller . '::create') ?>" method="post">
					<?= $this->include('component/alert-form') ?>
					<div class="row">
						<?php if(isset($data['id'])): ?>
						<input type="hidden" name="id" value="<?= $data['id']?>">
						<?php endif; ?>
						<div class="col-6">
							<div class="form-group">
								<label for="username" class="form-control-label">Username</label>
								<input id="username" value="<?= isset($data['username']) ? $data['username']: '' ?>" type="text" class="form-control form-control-alternative" name="username" placeholder="Required" required>
							</div>
						</div>
						<div class="col-6">
							<div class="form-group">
								<label for="email" class="form-control-label">Email</label>
								<input id="email" value="<?= isset($data['email']) ? $data['email']: '' ?>" type="email" class="form-control form-control-alternative" name="email" placeholder="Required" required>
							</div>
						</div>
						<div class="col-12">
							<?php if (!isset($data['id'])): ?>
								<div class="form-group">
									<label for="password" class="form-control-label">Password</label>
									<input id="password" type="password" class="form-control form-control-alternative" name="password" placeholder="Required" required>
								</div>
							<?php else: ?>
								<div class="form-group">
									<label for="new_password" class="form-control-label">New Password</label>
									<input id="new_password" type="password" class="form-control form-control-alternative" name="password" placeholder="Optional">
								</div>
							<?php endif ?>
							<div class="form-group">
								<label for="role" class="form-control-label">Role</label>
								<select name="role" id="role" class="form-control">
									<option value="">-- SELECT --</option>
									<option value="user">User</option>
									<option value="customer">Customer</option>
									<option value="admin">Admin</option>
								</select>
							</div>
						</div>
						<div class="col-12">
							<button type="submit" class="btn btn-primary">Save</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<?= $this->endSection() ?>
<?= $this->section('js') ?>
<script>
$(document).ready(() => {
	const init = () => {
		var role = "<?= isset($data['role']) ? $data['role']: '' ?>"
		$('select[name="role"]').val(role)
	}
	init()
})
</script>
<?= $this->endSection() ?>
<?= $this->section('header') ?>
<!-- Header -->
<div class="header bg-primary pb-6">
	<div class="container-fluid">
		<div class="header-body">
			<div class="row align-items-center py-4">
				<div class="col-lg-6 col-7">
					<h6 class="h2 text-white d-inline-block mb-0">Create</h6>
					<nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
						<ol class="breadcrumb breadcrumb-links breadcrumb-dark">
							<li class="breadcrumb-item"><a href="/"><i class="fas fa-home"></i></a></li>
							<li class="breadcrumb-item"><a href="<?= route_to($controller.'::index') ?>"><?= $active ?></a></li>
							<li class="breadcrumb-item active" aria-current="page"><?= isset($data['id']) ? 'edit': 'create' ?></li>
						</ol>
					</nav>
				</div>
			</div>
		</div>
	</div>
</div>
<?= $this->endSection() ?>