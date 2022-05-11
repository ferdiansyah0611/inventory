<?php

namespace Tests\Support\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\User;

class Authseed extends Seeder
{
	public function run()
	{
		$model = new User();
		$model->save([
			'username' => 'admin',
			'email' => 'admin@gmail.com',
			'password' => password_hash('admin123', PASSWORD_DEFAULT),
			'role' => 'admin',
			'created_at' => date("Y-m-d H:i:s")
		]);
		$model->save([
			'username' => 'user',
			'email' => 'user@gmail.com',
			'password' => password_hash('user123', PASSWORD_DEFAULT),
			'role' => 'user',
			'created_at' => date("Y-m-d H:i:s")
		]);
		$model->save([
			'username' => 'customer',
			'email' => 'customer@gmail.com',
			'password' => password_hash('customer123', PASSWORD_DEFAULT),
			'role' => 'customer',
			'created_at' => date("Y-m-d H:i:s")
		]);
	}
}
