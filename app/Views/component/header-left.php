<div class="col-lg-6 col-5 text-right">
	<?php if ($user['role'] == 'admin'): ?>
		<a href="<?= route_to($controller . '::new') ?>" class="btn btn-sm btn-neutral">New</a>
	<?php endif ?>
</div>