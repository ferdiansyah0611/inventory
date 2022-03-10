<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\User;

class Customerseed extends Seeder
{
	public function run()
	{
		$model = new User();
		for ($i = 1; $i <= 50; $i++) {
			$model->save([
				'username' => 'customer-' . $i,
				'email' => 'customer' . $i . '@gmail.com',
				'password' => password_hash('password', PASSWORD_DEFAULT),
				'role' => 'customer',
				'created_at' => date("Y-m-d H:i:s")
			]);
		}
	}
}
