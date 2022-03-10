<?php

namespace App\Models;

use CodeIgniter\Model;

class Product extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'products';
	protected $primaryKey           = 'id';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = 'array';
	protected $useSoftDelete        = false;
	protected $protectFields        = true;
	protected $allowedFields        = [
		'brand_id',
		'category_id',
		'name',
		'description',
		'image',
		'rate',
		'quantity',
		'status',
		'created_at',
		'updated_at',
	];

	// Dates
	protected $useTimestamps        = false;
	protected $dateFormat           = 'datetime';
	protected $createdField         = 'created_at';
	protected $updatedField         = 'updated_at';
	protected $deletedField         = 'deleted_at';

	// Validation
	protected $validationRules      = [];
	protected $validationMessages   = [];
	protected $skipValidation       = false;
	protected $cleanValidationRules = true;

	// Callbacks
	protected $allowCallbacks       = true;
	protected $beforeInsert         = [];
	protected $afterInsert          = [];
	protected $beforeUpdate         = [];
	protected $afterUpdate          = [];
	protected $beforeFind           = [];
	protected $afterFind            = [];
	protected $beforeDelete         = [];
	protected $afterDelete          = [];

	public function joined()
	{
		$page = intval(isset($_GET['page']) ? $_GET['page'] : 1);
		if($page){
			$builder = $this->db->table('products')
			->select('products.*, brands.name as brands_name, categories.name as category_name')
			->join('brands', 'brands.id = products.brand_id')
			->join('categories', 'categories.id = products.category_id');
			if(isset($_GET['search'])){
				$builder->like('products.id', $_GET['search']);
				$builder->orLike('products.name', $_GET['search']);
				$builder->orLike('categories.name', $_GET['search']);
				$builder->orLike('products.id', $_GET['search']);
			}
			// dd($builder->countAllResults());
			return [$builder->get(10, $page - 1)->getResult(), $builder->countAllResults(), 10, $page];
		}
		return array();
	}
	public function count()
	{
		$date = date_create(date('Y-m-d'));
		$date->modify('-1 month');
		$month = $date->format('Y-m-d H:i:s');
		$data = $this->db->table('products')->where('created_at >=', $month)->where('created_at <=', date('Y-m-d H:i:s'))->countAllResults();
		return $data;
	}
}
