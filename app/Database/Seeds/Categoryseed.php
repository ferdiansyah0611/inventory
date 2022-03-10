<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\Category;

class Categoryseed extends Seeder
{
	public function run()
	{
		$model = new Category();
		$faker = array(
			['name' => 'Technology', 'status' => 'Available', 'created_at' => date("Y-m-d H:i:s")],
			['name' => 'Accessories', 'status' => 'Available', 'created_at' => date("Y-m-d H:i:s")],
			['name' => 'Tools', 'status' => 'Available', 'created_at' => date("Y-m-d H:i:s")],
		);
		for ($i = 0; $i < count($faker); $i++) {
			$model->save($faker[$i]);
		}
	}
}
