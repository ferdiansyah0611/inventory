<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\Brand;

class Brandseed extends Seeder
{
	public function run()
	{
		$model = new Brand();
		$faker = array(
			['name' => 'Internet', 'status' => 'Available', 'created_at' => date("Y-m-d H:i:s")],
			['name' => 'Source Code', 'status' => 'Available', 'created_at' => date("Y-m-d H:i:s")],
			['name' => 'Software', 'status' => 'Available', 'created_at' => date("Y-m-d H:i:s")],
			['name' => 'Hardware', 'status' => 'Available', 'created_at' => date("Y-m-d H:i:s")],
		);
		for ($i = 0; $i < count($faker); $i++) {
			$model->save($faker[$i]);
		}
	}
}
