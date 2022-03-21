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
							<th scope="col" class="sort" data-sort="id">Id</th>
							<th scope="col" class="sort" data-sort="name">Name</th>
							<th scope="col" class="sort" data-sort="status">Status</th>
							<?php if ($user['role'] == 'admin'): ?>
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
	const render = (key) => ( data, type, row, meta ) => row[key]
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
			            <a class="dropdown-item deleted" data-id="${id}" href="/">Remove</a>
			        </div>
			    </div>
			</td>
			`
		)
	}
	const isAdmin = '<?= $user['role'] == 'admin' ?>';
	const columns = [
		{ data: 'id', render: render(0) },
		{ data: 'name', render: render(1) },
		{ data: 'status', render: ( data, type, row, meta ) => {
			if (row[2] == 'Available') {
				classes = 'badge badge-success'
			} else {
				classes = 'badge badge-danger'
			}
			return `<span class="${classes}">${row[2]}</div>`
		} }
	]
	if (isAdmin) {
		columns.push({
			data: 'action',
			render: ( data, type, row, meta ) => menu(row[0])
		})
	}
	let table = $('#table').DataTable({
	  	processing: true,
	  	serverSide: true,
	  	ajax: {
	    	url: `<?= route_to($controller . '::json') ?>`
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