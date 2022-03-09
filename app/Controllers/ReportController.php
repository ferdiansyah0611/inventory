<?php

namespace App\Controllers;
use Dompdf\Dompdf;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Order;
use App\Models\User;

class ReportController extends BaseController
{
	public function __construct()
	{
		$this->data['active'] = '/';
	}
	public function invoice(int $id)
    {
    	$order = new Order();
    	$data['data'] = $order->where('orders.id', $id)
    		->select('orders.*, products.name as products_name')
			->join('products', 'products.id = orders.product_id')->first();
		$date = date_create(date('Y-m-d'));
		$date->modify('+1 month');
		$due = $date->format('Y-m-d H:i:s');
		$data['data']['due'] = $due;

        $filename = date('y-m-d-H-i-s'). ' - invoice ' . $id;
        $dompdf = new Dompdf();
        $dompdf->loadHtml(view('pdf/invoice', $data));
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream($filename);
        // return view('invoice');
    }
    public function today_report()
    {
    	$order = new Order();
    	$list = $order
    		->select('orders.*, products.name as products_name')
			->join('products', 'products.id = orders.product_id')
			->where('DAY(orders.created_at)', date('d'))
			->get()->getResult();
    	$sum = $order->selectSum('price_total')
    		->where('DAY(orders.created_at)', date('d'))
    		->first();
    	$data['data']['created_at'] = date('Y/m/d');
    	$data['list'] = $list;
    	$data['price_total'] = $sum['price_total'];

    	$filename = date('y-m-d'). ' - report-today';
        $dompdf = new Dompdf();
        $dompdf->loadHtml(view('pdf/today_report', $data));
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream($filename);
    }
}