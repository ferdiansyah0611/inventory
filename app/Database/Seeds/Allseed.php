<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class Allseed extends Seeder
{
	public function run()
	{
		$this->call('Authseed');
		$this->call('Customerseed');
		$this->call('Userseed');
	}
}
