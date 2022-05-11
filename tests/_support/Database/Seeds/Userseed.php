<?php

namespace Tests\Support\Database\Seeds;
use App\Models\User;
use Faker\Factory;

use CodeIgniter\Database\Seeder;

class Userseed extends Seeder
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
				'role' => 'user',
				'created_at' => date("Y-m-d H:i:s")
			]);
		}
	}
}