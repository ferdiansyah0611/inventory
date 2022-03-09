<?= $this->extend('template') ?>
<?= $this->section('title') ?>
Create Order
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
						
						<div class="col-6">
							<div class="form-group">
								<label for="product_id" class="form-control-label">Product</label>
								<select name="product_id" id="product_id" class="form-control form-control-alternative" required>
									<option value="">-- SELECT --</option>
									<?php foreach ($product as $key => $value): ?>
									<option value="<?= $value['id'] ?>"><?= $value['name'] ?></option>
									<?php endforeach ?>
								</select>
							</div>
						</div>
						<div class="col-6">
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
								<label for="client_name" class="form-control-label">Client Name</label>
								<input id="client_name" value="<?= isset($data['client_name']) ? $data['client_name']: '' ?>" type="text" class="form-control form-control-alternative" name="client_name" placeholder="Required" required>
							</div>
						</div>
						<div class="col-6">
							<div class="form-group">
								<label for="client_contact" class="form-control-label">Client Contact</label>
								<input id="client_contact" value="<?= isset($data['client_contact']) ? $data['client_contact']: '' ?>" type="text" class="form-control form-control-alternative" name="client_contact" placeholder="Required" required>
							</div>
						</div>
						<div class="col-12">
							<div class="form-group">
								<label for="quantity" class="form-control-label">Quantity</label>
								<input value="<?= isset($data['quantity']) ? $data['quantity']: '' ?>" class="form-control form-control-alternative" type="number" placeholder="Required" required name="quantity">
							</div>
						</div>
						<div class="col-4">
							<div class="form-group">
								<label for="payment_type" class="form-control-label">Payment Type</label>
								<select name="payment_type" id="payment_type" class="form-control form-control-alternative" required>
									<option value="">-- SELECT --</option>
									<?php foreach (array('Not Payment', 'Credit Card', 'BRI') as $key => $value): ?>
										<option value="<?= $value ?>"><?= $value ?></option>
									<?php endforeach ?>
								</select>
							</div>
						</div>
						<div class="col-4">
							<div class="form-group">
								<label for="payment_status" class="form-control-label">Payment Status</label>
								<select name="payment_status" id="payment_status" class="form-control form-control-alternative" required>
									<option value="">-- SELECT --</option>
									<?php foreach (array('Failed', 'Waiting', 'Success') as $key => $value): ?>
										<option value="<?= $value ?>"><?= $value ?></option>
									<?php endforeach ?>
								</select>
							</div>
						</div>
						<div class="col-4">
							<div class="form-group">
								<label for="payment_place" class="form-control-label">Payment Place</label>
								<input id="payment_place" value="<?= isset($data['payment_place']) ? $data['payment_place']: '' ?>" type="text" class="form-control form-control-alternative" name="payment_place" placeholder="Required" required>
							</div>
						</div>
						<div class="col-6">
							<div class="form-group">
								<label for="discount" class="form-control-label">Discount %</label>
								<input id="discount" value="<?= isset($data['discount']) ? ($data['discount']): '' ?>" type="number" class="form-control form-control-alternative" name="discount" placeholder="Optional">
							</div>
						</div>
						<div class="col-6">
							<div class="form-group">
								<label for="order_at" class="form-control-label">Order At</label>
								<input id="order_at" value="<?= isset($data['order_at']) ? date('Y-m-d\TH:i', strtotime($data['order_at'])): '' ?>" type="datetime-local" class="form-control form-control-alternative" name="order_at" placeholder="Required" required>
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
		var product_id = "<?= isset($data['product_id']) ? $data['product_id']: '' ?>"
		var payment_type = "<?= isset($data['payment_type']) ? $data['payment_type']: '' ?>"
		var payment_status = "<?= isset($data['payment_status']) ? $data['payment_status']: '' ?>"

		$('select[name="status"]').val(status)
		$('select[name="product_id"]').val(product_id)
		$('select[name="payment_type"]').val(payment_type)
		$('select[name="payment_status"]').val(payment_status)
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