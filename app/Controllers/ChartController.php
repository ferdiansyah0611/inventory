<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Order;

class ChartController extends BaseController
{
	public function order()
	{
		$order = new Order();
		$value = array();
		$listmonth = array();
		for ($i=0; $i < 8; $i++) {
			$date = date_create(date('Y-m-d'));
			$date->modify('-'. $i .' month');
			$month = $date->format('M');
			$listmonth[$i] = $month;
			$order->where('MONTH(created_at)', $date->format('n'));
			if($this->user['role'] == 'customer'){
				$order->where('customer_id', $this->user['id']);
			}
			$value[$month] = $order->countAllResults();
		}
		return json_encode([
			'month' => $listmonth,
			'value' => $value
		]);
	}
	public function price_last()
	{
		$order = new Order();
		return json_encode($order->price_mount($this->user));
	}
}
