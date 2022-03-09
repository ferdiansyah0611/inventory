<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\Order;

class ChartController extends ResourceController
{
	public function order()
	{
		$book = new Order();
		$value = array();
		$listmonth = array();
		for ($i=0; $i < 8; $i++) { 
			$date = date_create(date('Y-m-d'));
			$date->modify('-'. $i .' month');
			$month = $date->format('M');
			$listmonth[$i] = $month;
			$value[$month] = $book->where('MONTH(created_at)', $date->format('n'))->countAllResults();
		}
		return $this->respond([
			'month' => $listmonth,
			'value' => $value
		]);
	}
	public function price_last()
	{
		$order = new Order();
		return $this->respond($order->price_mount());
	}
}
