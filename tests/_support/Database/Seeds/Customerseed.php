<?php

namespace Tests\Support\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\User;
use Faker\Factory;

class Customerseed extends Seeder
{
	public function run()
	{
		$faker = Factory::create();
		$model = new User();
		for ($i = 1; $i <= 50; $i++) {
			$model->save([
				'username' => $faker->name(),
				'email' => $faker->email(),
				'password' => password_hash('password', PASSWORD_DEFAULT),
				'role' => 'customer',
				'created_at' => date("Y-m-d H:i:s")
			]);
		}
	}
}
