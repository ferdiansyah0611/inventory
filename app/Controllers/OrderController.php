<?php

namespace App\Controllers;
use App\Models\Order;
use App\Models\Product;

class OrderController extends BaseController
{
	public function __construct()
	{
		$this->data['active'] = 'Order';
		$this->data['controller'] = 'App\Controllers\OrderController';
		$this->rules = [
			'product_id' => 'required|integer',
			'customer_id' => 'required|integer',
			'quantity' => 'required|integer',
			'discount' => 'permit_empty|integer',
			'status' => 'required|in_list[Done,Confirm,Delivery,Cancel]',
			'note' => 'permit_empty',
			'payment_type' => 'required',
			'payment_status' => 'required',
			'payment_place' => 'permit_empty',
			'order_at' => 'required',
		];
		$this->model = new Order();
	}
	public function _wrap()
	{
		$request = $this->request;
		$data = [
			'product_id' => $request->getPost('product_id'),
			'customer_id' => $request->getPost('customer_id'),
			'quantity' => $request->getPost('quantity'),
			'discount' => $request->getPost('discount'),
			'status' => $request->getPost('status'),
			'note' => $request->getPost('note'),
			'payment_type' => $request->getPost('payment_type'),
			'payment_status' => $request->getPost('payment_status'),
			'payment_place' => $request->getPost('payment_place'),
			'order_at' => $request->getPost('order_at'),
		];
		if($request->getPost('id')){
			$data['id'] = $request->getPost('id');
			$data['updated_at'] = date("Y-m-d H:i:s");
		}else{
			$data['created_at'] = date("Y-m-d H:i:s");
		}
		return $data;
	}
	public function _product()
	{
		$product = new Product();
		$this->data['product'] = $product->find();
	}
	/**
	 * Return an array of resource objects, themselves in array format
	 *
	 * @return mixed
	 */
	public function index()
	{
		$pager = \Config\Services::pager();
		$data = $this->model->joined();
		$pager->makeLinks($data[3], $data[2], $data[1]);
		$this->data['list'] = $data[0];
		$this->data['pager'] = $pager;
		return view('order/index', $this->data);
	}

	/**
	 * Return the properties of a resource object
	 *
	 * @return mixed
	 */
	public function show($id = null)
	{
		$this->data['data'] = $this->model->where('orders.id', $id)
		->select('orders.*, products.name as products_name, products.image as products_image, users.username as customer_name, users.email as customer_email')
		->join('users', 'users.id = orders.customer_id')
		->join('products', 'products.id = orders.product_id')
		->first();
		return view('order/show', $this->data);
	}

	/**
	 * Return a new resource object, with default properties
	 *
	 * @return mixed
	 */
	public function new()
	{
		$this->_product();
		$data = $this->session->getFlashdata();
		unset($data['validation']);
		$this->data['data'] = $data;
		return view('order/create', $this->data);
	}

	/**
	 * Create a new resource object, from "posted" parameters
	 *
	 * @return mixed
	 */
	public function create()
	{
		$validate = $this->validate($this->rules);
		if(!$validate){
			$this->session->setFlashdata('validation', $this->validator->getErrors());
			$this->session->setFlashdata($_POST);
			return redirect()->back();
		}
		$data = $this->_wrap();
		$product = new Product();
		$productUpdate = new Product();
		$productUpdate->where('id', $data['product_id']);
		$productId = $product->where('id', $data['product_id'])->first();
		// dd($data['discount']);
		if(isset($data['id'])){
			$old = $this->model->find($data['id']);
			$productUpdate->set(['quantity' => ($productId['quantity'] + intval($old['quantity'])) - $data['quantity']])->update();
		}else{
			$data['price_start'] = $productId['rate'];
			$productUpdate->set(['quantity' => $productId['quantity'] - $data['quantity']])->update();
		}
		$data['price_total'] = $data['discount'] !== '0' && $data['discount'] !== '' ? intval($productId['rate']) * intval($data['quantity']) * (intval($data['discount']) / 100) : (intval($productId['rate']) * intval($data['quantity']));

		$this->model->save($data);
		return redirect()->back();
	}

	/**
	 * Return the editable properties of a resource object
	 *
	 * @return mixed
	 */
	public function edit($id = null)
	{
		$this->_product();
		$this->data['data'] = $this->model->where('orders.id', $id)
			->select('orders.*, products.name as products_name, users.username as customer_name')
			->join('products', 'products.id = orders.product_id')
			->join('users', 'users.id = orders.customer_id')
			->first();
		return view('order/create', $this->data);
	}

	/**
	 * Add or update a model resource, from "posted" properties
	 *
	 * @return mixed
	 */
	public function update($id = null)
	{
		//
	}

	/**
	 * Delete the designated resource object from the model
	 *
	 * @return mixed
	 */
	public function delete($id = null)
	{
		$this->model->where('id', $id)->delete();
		return redirect()->back();
	}
}