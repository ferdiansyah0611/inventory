<?= $this->extend('template') ?>
<?= $this->section('title') ?>
Dashboard
<?= $this->endSection() ?>
<?= $this->section('header') ?>
<!-- Header -->
<div class="header bg-primary pb-6">
	<div class="container-fluid">
		<div class="header-body">
			<div class="row align-items-center py-4">
				<div class="col-lg-6 col-7">
					<h6 class="h2 text-white d-inline-block mb-0">Dashboards</h6>
					<nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
						<ol class="breadcrumb breadcrumb-links breadcrumb-dark">
							<li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
							<li class="breadcrumb-item active"><a href="#">Dashboards</a></li>
						</ol>
					</nav>
				</div>
				<div class="col-lg-6 col-5 text-right">
					<a href="<?= route_to('App\Controllers\ReportController::today_report') ?>" target="_blank" class="btn btn-sm btn-neutral">Today's Report</a>
				</div>
			</div>
			<!-- Card stats -->
			<div class="row">
				<div class="col-xl-3 col-md-6">
					<div class="card card-stats">
						<!-- Card body -->
						<div class="card-body">
							<div class="row">
								<div class="col">
									<h5 class="card-title text-uppercase text-muted mb-0">Total brand</h5>
									<span class="h2 font-weight-bold mb-0"><?= $count['brand'] ?></span>
								</div>
								<div class="col-auto">
									<div class="icon icon-shape bg-gradient-red text-white rounded-circle shadow">
										<i class="ni ni-active-40"></i>
									</div>
								</div>
							</div>
							<p class="mt-3 mb-0 text-sm">
								<span class="text-nowrap">All time</span>
							</p>
						</div>
					</div>
				</div>
				<div class="col-xl-3 col-md-6">
					<div class="card card-stats">
						<!-- Card body -->
						<div class="card-body">
							<div class="row">
								<div class="col">
									<h5 class="card-title text-uppercase text-muted mb-0">New order</h5>
									<span class="h2 font-weight-bold mb-0"><?= $count['order'] ?></span>
								</div>
								<div class="col-auto">
									<div class="icon icon-shape bg-gradient-orange text-white rounded-circle shadow">
										<i class="ni ni-delivery-fast"></i>
									</div>
								</div>
							</div>
							<p class="mt-3 mb-0 text-sm">
								<span class="text-nowrap">Since last month</span>
							</p>
						</div>
					</div>
				</div>
				<div class="col-xl-3 col-md-6">
					<div class="card card-stats">
						<!-- Card body -->
						<div class="card-body">
							<div class="row">
								<div class="col">
									<h5 class="card-title text-uppercase text-muted mb-0">New user</h5>
									<span class="h2 font-weight-bold mb-0"><?= $count['user'] ?></span>
								</div>
								<div class="col-auto">
									<div class="icon icon-shape bg-gradient-green text-white rounded-circle shadow">
										<i class="ni ni-single-02"></i>
									</div>
								</div>
							</div>
							<p class="mt-3 mb-0 text-sm">
								<span class="text-nowrap">Since last month</span>
							</p>
						</div>
					</div>
				</div>
				<div class="col-xl-3 col-md-6">
					<div class="card card-stats">
						<!-- Card body -->
						<div class="card-body">
							<div class="row">
								<div class="col">
									<h5 class="card-title text-uppercase text-muted mb-0">New product</h5>
									<span class="h2 font-weight-bold mb-0"><?= $count['product'] ?></span>
								</div>
								<div class="col-auto">
									<div class="icon icon-shape bg-gradient-info text-white rounded-circle shadow">
										<i class="ni ni-chart-bar-32"></i>
									</div>
								</div>
							</div>
							<p class="mt-3 mb-0 text-sm">
								<span class="text-nowrap">Since last month</span>
							</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="mt-4">
	<div class="row">
		<div class="col-xl-4">
			<div class="card">
				<div class="card-header bg-transparent">
					<div class="row align-items-center">
						<div class="col">
							<h6 class="text-uppercase text-muted ls-1 mb-1">Performance</h6>
							<h5 class="h3 mb-0">Total orders</h5>
						</div>
					</div>
				</div>
				<div class="card-body">
					<!-- Chart -->
					<div class="chart"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
					<canvas id="chart-bars" class="chart-canvas chartjs-render-monitor" style="display: block; width: 278px; height: 350px;" width="278" height="350"></canvas>
				</div>
			</div>
		</div>
	</div>
	<div class="col-xl-8">
		<div class="card bg-default">
			<div class="card-header bg-transparent">
				<div class="row align-items-center">
					<div class="col">
						<h6 class="text-light text-uppercase ls-1 mb-1">Overview</h6>
						<h5 class="h3 text-white mb-0">Profit</h5>
					</div>
				</div>
			</div>
			<div class="card-body">
				<div class="chart">
					<canvas id="chart-sales-dark" class="chart-canvas"></canvas>
				</div>
			</div>
		</div>
	</div>
	<div class="col-12">
		<div class="card">
			<div class="card-header border-0">
				<h3 class="mb-0">Latest Product</h3>
			</div>
			<div class="table-responsive">
				<table class="table align-items-center table-flush">
					<thead class="thead-light">
						<tr>
							<th scope="col" class="sort">ID</th>
							<th scope="col" class="sort">Name</th>
							<th scope="col" class="sort">Rate</th>
							<th scope="col" class="sort">Quantity</th>
							<th scope="col" class="sort">Created</th>
							<?php if ($user['role'] == 'admin'): ?>
							<th scope="col" class="sort">Action</th>
							<?php endif ?>
						</tr>
					</thead>
					<tbody class="list">
						<?php foreach ($product as $key => $data): ?>
						<tr>
							<th scope="row">
								<?= $data['id'] ?>
							</th>
							<td>
								<?= $data['name'] ?>
							</td>
							<td>
								<?= $data['rate'] ?>
							</td>
							<td>
								<?= $data['quantity'] ?>
							</td>
							<td>
								<?= $data['created_at'] ?>
							</td>
							<td>
								<?php if($user['role'] == 'admin'): ?>
								<a href="<?= route_to('App\Controllers\ProductController::edit', $data['id']) ?>" class="btn btn-sm btn-primary">Edit</a>
								<button data-id="<?= $data['id']?>" type="submit" class="btn btn-sm btn-danger deleted">Remove</button>
								<?php endif; ?>
							</td>
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
</div>
<?= $this->endSection() ?>