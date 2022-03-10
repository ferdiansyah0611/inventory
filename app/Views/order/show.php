<?= $this->extend('template') ?>
<?= $this->section('title') ?>
Show Product
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="row mt-1">
	<div class="col-12">
		<div class="card mt-3">
			<div class="card-header">
				<div class="row align-items-center">
					<div class="col">
						<h6 class="text-light text-uppercase ls-1 mb-1"><?= $data['products_name'] ?></h6>
					</div>
				</div>
			</div>
			<div class="card-body">
				<ol class="list-group list-group-numbered">
					<li class="list-group-item d-flex justify-content-between align-items-start">
						<div class="ms-2 me-auto">
							<div class="font-weight-bold">Image</div>
							<img src="<?= base_url($data['products_image']) ?>" class="img-fluid mt-2" width="300px" alt="">
						</div>
					</li>
					<li class="list-group-item d-flex justify-content-between align-items-start">
						<div class="ms-2 me-auto">
							<div class="font-weight-bold">Note</div>
							<?= $data['note'] ? esc($data['note']): 'no note' ?>
						</div>
					</li>
					<li class="list-group-item d-flex justify-content-between align-items-start">
						<div class="ms-2 me-auto">
							<div class="font-weight-bold">Quantity</div>
							<?= esc($data['quantity']) ?> item
						</div>
					</li>
					<li class="list-group-item d-flex justify-content-between align-items-start">
						<div class="ms-2 me-auto">
							<div class="font-weight-bold">Price Start</div>
							$<?= number_format($data['price_start']) ?>
						</div>
					</li>
					<?php if ($data['discount']): ?>
						<li class="list-group-item d-flex justify-content-between align-items-start">
							<div class="ms-2 me-auto">
								<div class="font-weight-bold">Discount</div>
								<?= esc($data['discount']) ?>%
							</div>
						</li>
					<?php endif ?>
					<li class="list-group-item d-flex justify-content-between align-items-start">
						<div class="ms-2 me-auto">
							<div class="font-weight-bold">Price Total</div>
							$<?= number_format($data['price_total']) ?>
						</div>
					</li>
					<li class="list-group-item d-flex justify-content-between align-items-start">
						<div class="ms-2 me-auto">
							<div class="font-weight-bold">Status</div>
							<?= esc($data['status']) ?>
						</div>
					</li>
					<li class="list-group-item d-flex justify-content-between align-items-start">
						<div class="ms-2 me-auto">
							<div class="font-weight-bold">Customer</div>
							<?= $data['customer_name'] ?>, <?= $data['customer_email'] ?>
						</div>
					</li>
					<li class="list-group-item d-flex justify-content-between align-items-start">
						<div class="ms-2 me-auto">
							<div class="font-weight-bold">Payment</div>
							<?= esc($data['payment_type']) ?>,
								<?php if (esc($data['payment_status']) == 'Success'): ?>
									<span class="badge badge-success"><?= esc($data['payment_status']) ?></span>
								<?php endif ?>
								<?php if (esc($data['payment_status']) == 'Waiting'): ?>
									<span class="badge badge-info"><?= esc($data['payment_status']) ?></span>
								<?php endif ?>
								<?php if (esc($data['payment_status']) == 'Failed'): ?>
									<span class="badge badge-danger"><?= esc($data['payment_status']) ?></span>
								<?php endif ?>
							<?= esc($data['payment_place']) ? ', ' . esc($data['payment_place']): '' ?>
						</div>
					</li>
					<li class="list-group-item d-flex justify-content-between align-items-start">
						<div class="ms-2 me-auto">
							<div class="font-weight-bold">Created</div>
							<?= $data['created_at'] ?>
						</div>
					</li>
				</ol>
			</div>
		</div>
	</div>
</div>
<?= $this->endSection() ?>
<?= $this->section('header') ?>
<!-- Header -->
<div class="header bg-primary pb-6">
	<div class="container-fluid">
		<div class="header-body">
			<div class="row align-items-center py-4">
				<div class="col-lg-6 col-7">
					<h6 class="h2 text-white d-inline-block mb-0">Show</h6>
					<nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
						<ol class="breadcrumb breadcrumb-links breadcrumb-dark">
							<li class="breadcrumb-item"><a href="/"><i class="fas fa-home"></i></a></li>
							<li class="breadcrumb-item"><a href="<?= route_to($controller.'::index') ?>"><?= $active ?></a></li>
							<li class="breadcrumb-item active" aria-current="page">Show</li>
						</ol>
					</nav>
				</div>
			</div>
		</div>
	</div>
</div>
<?= $this->endSection() ?>