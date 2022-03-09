<?php

namespace App\Controllers;
use Dompdf\Dompdf;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Order;
use App\Models\User;

class Home extends BaseController
{
	public function __construct()
	{
		$this->data['active'] = '/';
	}
	public function index()
	{
		$product = new Product();
		$brand = new Brand();
		$order = new Order();
		$user = new User();
		$order->price_mount();

		$this->data['count']['product'] = $product->count();
		$this->data['count']['brand'] = $brand->count();
		$this->data['count']['order'] = $order->count();
		$this->data['count']['user'] = $user->count();
		$this->data['product'] = $product->orderBy('created_at', 'DESC')->limit(10)->get()->getResultArray();
		return view('home', $this->data);
	}
}