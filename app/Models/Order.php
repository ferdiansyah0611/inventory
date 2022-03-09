<?php

namespace App\Models;

use CodeIgniter\Model;

class Order extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'orders';
	protected $primaryKey           = 'id';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = 'array';
	protected $useSoftDelete        = false;
	protected $protectFields        = true;
	protected $allowedFields        = [
		'product_id',
		'client_name',
		'client_contact',
		'quantity',
		'discount',
		'status',
		'price_start',
		'price_total',
		'payment_type',
		'payment_status',
		'payment_place',
		'order_at',
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
			$builder = $this->db->table('orders')
			->select('orders.*, products.name as products_name')
			->join('products', 'products.id = orders.product_id');
			if(isset($_GET['search'])){
				$builder->like('orders.id', $_GET['search']);
				$builder->orLike('orders.product_id', $_GET['search']);
				$builder->orLike('products.name', $_GET['search']);
				$builder->orLike('orders.client_name', $_GET['search']);
				$builder->orLike('orders.status', $_GET['search']);
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
		$month = $date->format('m');
		$data = $this->db->table('orders')->where('MONTH(created_at) >=', $month)->where('MONTH(created_at) <=', date('m'))->countAllResults();
		return $data;
	}
	public function price_mount()
	{
		$value = array();
		$listmonth = array();
		for ($i = 0; $i < 6; $i++) { 
			$date = date_create(date('Y-m-d'));
			$date->modify('-'. $i .' month');
			$month = $date->format('M');
			$listmonth[$i] = $month;
			$value[$month] = $this->db->table('orders')->where('MONTH(created_at)', $date->format('n'))->selectSum('price_total')->get()->getResultObject();
		}
		// dd($value);
		return [
			'month' => $listmonth,
			'value' => $value
		];
	}
}
