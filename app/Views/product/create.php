<?= $this->extend('template') ?>
<?= $this->section('title') ?>
Create Product
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
				<form action="<?= route_to($controller . '::create') ?>" method="post" enctype="multipart/form-data">
					<?php if(session()->getFlashData('validation')): ?>
					<div class="alert alert-danger" role="alert">
						<?php foreach ((array) session()->getFlashData('validation') as $key => $value): ?>
						<li>
							<?= $value ?>
						</li>
						<?php endforeach; ?>
					</div>
					<?php endif; ?>
					<div class="row">
						<?php if(isset($data['id'])): ?>
						<input type="hidden" name="id" value="<?= $data['id']?>">
						<?php endif; ?>
						<div class="col-12">
							<div class="form-group">
								<label for="name" class="form-control-label">Name</label>
								<input id="name" value="<?= isset($data['name']) ? $data['name']: '' ?>" type="text" class="form-control form-control-alternative" name="name" placeholder="Required" required>
							</div>
							<div class="form-group">
								<label for="status" class="form-control-label">Status</label>
								<select name="status" id="status" class="form-control form-control-alternative" required>
									<option value="">-- SELECT --</option>
									<option value="Not Available">Not Available</option>
									<option value="Available">Available</option>
								</select>
							</div>
						</div>
						<div class="col-6">
							<div class="form-group">
								<label for="brand_id" class="form-control-label">Brand</label>
								<select name="brand_id" id="brand_id" class="form-control form-control-alternative" required>
									<option value="">-- SELECT --</option>
									<?php foreach ($brand as $key => $value): ?>
									<option value="<?= $value['id'] ?>"><?= $value['name'] ?></option>
									<?php endforeach ?>
								</select>
							</div>
						</div>
						<div class="col-6">
							<div class="form-group">
								<label for="category_id" class="form-control-label">Category</label>
								<select name="category_id" id="category_id" class="form-control form-control-alternative" required>
									<option value="">-- SELECT --</option>
									<?php foreach ($category as $key => $value): ?>
									<option value="<?= $value['id'] ?>"><?= $value['name'] ?></option>
									<?php endforeach ?>
								</select>
							</div>
						</div>
						<div class="col-6">
							<div class="form-group">
								<label for="rate" class="form-control-label">Rate</label>
								<input value="<?= isset($data['rate']) ? $data['rate']: '' ?>" class="form-control form-control-alternative" type="number" placeholder="Required" required name="rate">
							</div>
						</div>
						<div class="col-6">
							<div class="form-group">
								<label for="quantity" class="form-control-label">Quantity</label>
								<input value="<?= isset($data['quantity']) ? $data['quantity']: '' ?>" class="form-control form-control-alternative" type="number" placeholder="Required" required name="quantity">
							</div>
						</div>
						<div class="col-12">
							<div class="form-group">
								<label for="description" class="form-control-label">Description</label>
								<textarea class="form-control form-control-alternative" name="description" id="description" rows="3"><?= isset($data['description']) ? $data['description']: '' ?></textarea>
							</div>
						</div>
						<div class="col-12">
							<div class="form-group">
								<label for="image" class="form-control-label">Image</label>
								<input accept="image*" class="form-control form-control-alternative" type="file" <?= !isset($data['id']) ? 'required ': '' ?>name="image">
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
		var status = "<?= isset($data['status']) ? $data['status']: '' ?>"
		var brand_id = "<?= isset($data['brand_id']) ? $data['brand_id']: '' ?>"
		var category_id = "<?= isset($data['category_id']) ? $data['category_id']: '' ?>"
		$('select[name="status"]').val(status)
		$('select[name="brand_id"]').val(brand_id)
		$('select[name="category_id"]').val(category_id)
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