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
		'customer_id',
		'quantity',
		'discount',
		'status',
		'note',
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

	public function joined($user)
	{
		$page = intval(isset($_GET['page']) ? $_GET['page'] : 1);
		if($page){
			$builder = $this->db->table('orders')
			->select('orders.*, products.name as products_name, users.username as customer_name')
			->join('users', 'users.id = orders.customer_id')
			->join('products', 'products.id = orders.product_id');
			if($user['role'] == 'customer'){
				$builder->where('customer_id', $user['id']);
			}
			if(isset($_GET['search'])){
				$builder->like('orders.id', $_GET['search']);
				$builder->orLike('orders.product_id', $_GET['search']);
				$builder->orLike('products.name', $_GET['search']);
				$builder->orLike('users.username', $_GET['search']);
				$builder->orLike('orders.status', $_GET['search']);
			}
			return [$builder->get(10, $page - 1)->getResult(), $builder->countAllResults(), 10, $page];
		}
		return array();
	}
	public function count($user = null)
	{
		$date = date_create(date('Y-m-d'));
		$date->modify('-1 month');
		$month = $date->format('m');
		$data = $this->db->table('orders')
			->where('MONTH(created_at) >=', $month)
			->where('MONTH(created_at) <=', date('m'));
		if($user['role'] == 'customer'){
			$data->where('customer_id', $user['id']);
		}
		return $data->countAllResults();
	}
	public function count_all($user = null)
	{
		$data = $this->db->table('orders');
		if($user['role'] == 'customer'){
			$data->where('customer_id', $user['id']);
		}
		return $data->countAllResults();
	}
	public function price_mount($user)
	{
		$value = array();
		$listmonth = array();
		for ($i = 0; $i < 6; $i++) { 
			$date = date_create(date('Y-m-d'));
			$date->modify('-'. $i .' month');
			$month = $date->format('M');
			$listmonth[$i] = $month;
			$order = $this->db->table('orders')
				->where('MONTH(created_at)', $date->format('n'))
				->selectSum('price_total')
				->where('payment_status', 'Success');
			if($user['role'] == 'customer'){
				$order->where('customer_id', $user['id']);
			}
			$value[$month] = $order->get()->getResultObject();
		}
		return [
			'month' => $listmonth,
			'value' => $value
		];
	}
}