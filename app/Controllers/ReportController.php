<?php

namespace App\Controllers;
use Dompdf\Dompdf;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Order;
use App\Models\User;
use App\Models\Spend;

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
    		->select('orders.*, products.name as products_name, users.username as customer_name, users.email as customer_email')
            ->join('users', 'users.id = orders.customer_id')
			->join('products', 'products.id = orders.product_id')->first();
		if(!($this->user['role'] == 'admin' || $this->user['role'] == 'worker') && $data['data']['customer_id'] !== $this->user['id']){
            return redirect()->back();
        }
        $date = date_create(date('Y-m-d'));
		$date->modify('+1 month');
		$due = $date->format('Y-m-d H:i:s');
		$data['data']['due'] = $due;

        $filename = date('y-m-d'). '_invoice_' . $id;
        $dompdf = new Dompdf();
        $dompdf->loadHtml(view('pdf/invoice', $data));
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream($filename);
        // return view('invoice');
    }
    public function today_report()
    {
        $data['title'] = 'Today Report';
        $filename = date('y-m-d'). '_report_today';

        if(!($this->user['role'] == 'admin' || $this->user['role'] == 'worker')){
            return redirect()->back();
        }
    	$order = new Order();
        $spend = new Spend();
        $at = $this->request->getGet('at');
    	$list = $order
    		->select('orders.*, products.name as products_name, users.username as customer_name')
			->join('products', 'products.id = orders.product_id')
            ->join('users', 'users.id = orders.customer_id')
            ->where('orders.payment_status', 'Success');
        $sum = new Order();
    	$sum->selectSum('price_total')->where('payment_status', 'Success');
        if ($at == 'month') {
            $list->where('MONTH(orders.created_at)', date('m'));
    		$sum->where('MONTH(orders.created_at)', date('m'));
        }
        if (!($at == 'month')) {
			$list->where('DAY(orders.created_at)', date('d'));
            $sum->where('DAY(orders.created_at)', date('d'));
        }
		$list = $list->get()->getResult();
    	$sum = $sum->first();
    	$data['data']['created_at'] = date('Y/m/d');
    	$data['list'] = $list;
    	$data['price_total'] = $sum['price_total'];

        if ($at == 'month') {
            $data['title'] = 'Month Report';
            $filename = date('y-m-d'). '_report_month';
            $valuespend = $spend->where('DATE(created_at)', date('Y-m-d'))->find();
            $totalspend = $spend->where('DATE(created_at)', date('Y-m-d'))->selectSum('total')->first();
            $data['spend'] = $valuespend;
            $data['totalspend'] = $totalspend['total'];
            $data['netprofit'] = $data['price_total'] - $data['totalspend'];
            $data['netprofitmargin'] = ($data['netprofit'] / $data['price_total']) * 100;
        }

        $dompdf = new Dompdf();
        $dompdf->loadHtml(view('pdf/today_report', $data));
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream($filename);
        return view('pdf/today_report', $data);
    }
}