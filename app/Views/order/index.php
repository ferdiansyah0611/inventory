<?= $this->extend('template') ?>
<?= $this->section('title') ?>
Index
<?= $this->endSection() ?>
<?= $this->section('header') ?>
<!-- Header -->
<div class="header bg-primary pb-6">
	<div class="container-fluid">
		<div class="header-body">
			<div class="row align-items-center py-4">
				<div class="col-lg-6 col-7">
					<h6 class="h2 text-white d-inline-block mb-0">Title</h6>
					<nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
						<ol class="breadcrumb breadcrumb-links breadcrumb-dark">
							<li class="breadcrumb-item"><a href="/"><i class="fas fa-home"></i></a></li>
							<li class="breadcrumb-item active" aria-current="page"><?= $active ?></li>
						</ol>
					</nav>
				</div>
				<?= $this->include('component/header-left') ?>
			</div>
		</div>
	</div>
</div>
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="row mt-1">
	<div class="col">
		<div class="card">
			<div class="card-header">
				<h3 class="mb-0"><?= $active ?></h3>
			</div>
			<div class="table-responsive py-4">
				<table id="table" class="table table-flush">
					<thead class="thead-light">
						<tr>
							<th scope="col" class="sort" data-sort="id">ID</th>
							<th scope="col" class="sort" data-sort="products_name">Products_name</th>
							<th scope="col" class="sort" data-sort="customer_name">Customer_name</th>
							<th scope="col" class="sort" data-sort="status">Status</th>
							<th scope="col" class="sort" data-sort="payment_status">Payment_status</th>
							<th scope="col" class="sort" data-sort="discount">Discount</th>
							<th scope="col" class="sort" data-sort="price_total">Price_total</th>
							<th scope="col" class="sort" data-sort="order_at">Order_at</th>
							<?php if ($user['role'] == 'admin' || $user['role'] == 'worker'): ?>
							<th scope="col" class="sort">Action</th>
							<?php endif ?>
						</tr>
					</thead>
				</table>
			</div>
		</div>
	</div>
</div>
<?= $this->endSection() ?>
<?= $this->section('js') ?>
<?= $this->include('component/datatable.php') ?>
<script>
$(document).ready(() => {
	const formatter = new Intl.NumberFormat('en-US', {
		style: 'currency',
		currency: 'USD',
	});
	const menu = (id) => {
		return(
			`
			<td class="text-right">
			    <div class="dropdown">
			        <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			          <i class="fas fa-ellipsis-v"></i>
			        </a>
			        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
			            <a class="dropdown-item" href="<?= route_to($controller . '::edit', '${id}') ?>">Edit</a>
			            <a class="dropdown-item" href="<?= base_url() ?>/report/invoice/${id}" target="_blank">Invoice</a>
			            <a class="dropdown-item deleted" data-id="${id}" href="/">Remove</a>
			        </div>
			    </div>
			</td>
			`
		)
	}
	const render = (key) => ( data, type, row, meta ) => row[key]
	const hasAccess = '<?= $user['role'] == 'admin' || $user['role'] == 'worker' ?>';
	const columns = [
		{ data: 'id', render: ( data, type, row, meta ) => `<a href="<?= route_to($controller . '::show', '${row[0]}') ?>">${row[0]}</a>` },
		{ data: 'products_name', render: render(1) },
		{ data: 'customer_name', render: render(2) },
		{ data: 'status', render: ( data, type, row, meta ) => {
			const status = row[3]
			var classes = 'badge '
			switch(status){
				case "Done":
					classes += 'badge-success'
					break
				case "Confirm":
					classes += 'badge-info'
					break
				case "Request":
					classes += 'badge-info'
					break
				case "Delivery":
					classes += 'badge-warning'
					break
				case "Cancel":
					classes += 'badge-danger'
					break
			}
			return `<span class="${classes}">${status}</div>`
		} },
		{ data: 'payment_status', render: ( data, type, row, meta ) => {
			const status = row[4]
			var classes = 'badge '
			switch(status){
				case "Success":
					classes += 'badge-success'
					break
				case "Waiting":
					classes += 'badge-info'
					break
				case "Debt":
					classes += 'badge-warning'
					break
				case "Failed":
					classes += 'badge-danger'
					break
			}
			return `<span class="${classes}">${status}</div>`
		} },
		{ data: 'discount', render: ( data, type, row, meta ) => row[5] + '%' },
		{ data: 'price_total', render: ( data, type, row, meta ) => formatter.format(row[6]) },
		{ data: 'order_at', render: ( data, type, row, meta ) => row[7] ? row[7]: '-' },
	]
	if (hasAccess) {
		columns.push({
			data: 'action',
			render: ( data, type, row, meta ) => menu(row[0])
		})
	}
	let table = $('#table').DataTable({
	  	processing: true,
	  	serverSide: true,
	  	ajax: {
	    	url: `<?= $json ?>`
	  	},
	  	columns: columns,
     	columnDefs: [
			{
        		targets: [columns.length - 1],
        		orderable: false,
     			searchable: false
     		}
     	]
	});
	$("#table").on("draw.dt", function () {
		const deleted = () => {
			$('.deleted').click(function(e){
				e.preventDefault()
				var id = $(this).data('id')
				var url = `<?= route_to($controller . '::index') ?>/${id}`
				$.ajax({
					url: url,
					method: 'DELETE',
					success: () => table.ajax.reload()
				})
			})
		}
		deleted()
	})
})
</script>
<?= $this->endSection() ?>