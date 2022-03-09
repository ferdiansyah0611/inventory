<?= $this->extend('template') ?>
<?= $this->section('title') ?>
User
<?= $this->endSection() ?>
<?= $this->section('header') ?>
<!-- Header -->
<div class="header bg-primary pb-6">
	<div class="container-fluid">
		<div class="header-body">
			<div class="row align-items-center py-4">
				<div class="col-lg-6 col-7">
					<h6 class="h2 text-white d-inline-block mb-0">User</h6>
					<nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
						<ol class="breadcrumb breadcrumb-links breadcrumb-dark">
							<li class="breadcrumb-item"><a href="/"><i class="fas fa-home"></i></a></li>
							<li class="breadcrumb-item active" aria-current="page"><?= $active ?></li>
						</ol>
					</nav>
				</div>
				<div class="col-lg-6 col-5 text-right">
					<a href="<?= route_to($controller . '::new') ?>" class="btn btn-sm btn-neutral">New</a>
				</div>
			</div>
		</div>
	</div>
</div>
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="row mt-1">
	<div class="col">
		<div class="card">
			<div class="card-header border-0">
				<h3 class="mb-0"><?= $active ?></h3>
			</div>
			<div class="card-header border-0">
				<form action="">
					<input type="search" value="<?=  isset($_GET['search']) ? $_GET['search']: '' ?>" class="form-control form-control-alternative" placeholder="Search here..." name="search">
				</form>
			</div>
			<div class="table-responsive">
				<table class="table align-items-center table-flush">
					<thead class="thead-light">
						<tr>
							<th scope="col" class="sort">ID</th>
							<th scope="col" class="sort">Name</th>
							<th scope="col" class="sort">Email</th>
							<th scope="col" class="sort">Role</th>
							<th scope="col" class="sort">Created</th>
							<?php if ($user['role'] == 'admin'): ?>
								<th scope="col" class="sort">Action</th>
							<?php endif ?>
						</tr>
					</thead>
					<tbody class="list">
						<?php foreach ($list as $key => $data): ?>
						<tr>
							<th scope="row">
								<?= $data['id'] ?>
							</th>
							<td>
								<?= $data['username'] ?>
							</td>
							<td>
								<?= $data['email'] ?>
							</td>
							<td>
								<?= $data['role'] ?>
							</td>
							<td>
								<?= $data['created_at'] ?>
							</td>
							<td>
								<?php if($user['role'] == 'admin'): ?>
								<a href="<?= route_to($controller . '::edit', $data['id']) ?>" class="btn btn-sm btn-primary">Edit</a>
								<button data-id="<?= $data['id']?>" type="submit" class="btn btn-sm btn-danger deleted">Remove</button>
								<?php endif; ?>
							</td>
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
			<!-- Card footer -->
			<div class="card-footer py-4">
				<?= $pager->links() ?>
			</div>
		</div>
	</div>
</div>
<?= $this->endSection() ?>
<?= $this->section('js') ?>
<script>
$(document).ready(() => {
	const deleted = () => {
		$('button.deleted').click(function(e){
			var id = $(this).data('id')
			var url = `<?= route_to($controller . '::delete', '${id}') ?>`
			$.ajax({
				url: url,
				method: 'DELETE',
				success: () => location.reload(true)
			})
		})
	}
	deleted()
})
</script>
<?= $this->endSection() ?>