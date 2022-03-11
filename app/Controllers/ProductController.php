<?php

namespace App\Controllers;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;

class ProductController extends BaseController
{
	public function __construct()
	{
		$this->data['active'] = 'Product';
		$this->data['controller'] = 'App\Controllers\ProductController';
		$this->rules = [
			'brand_id' => 'required|integer',
			'category_id' => 'required|integer',
			'name' => 'required',
			'description' => 'permit_empty',
			'rate' => 'required|integer',
			'quantity' => 'required|integer',
			'status' => 'required|in_list[Available,Not Available]',
			'image' => 'permit_empty|uploaded[image]|mime_in[image,image/jpg,image/jpeg,image/gif,image/png,image/webp]'
		];
		$this->model = new Product();
	}
	public function _wrap()
	{
		$request = $this->request;
		$data = [
			'brand_id' => $request->getPost('brand_id'),
			'category_id' => $request->getPost('category_id'),
			'name' => $request->getPost('name'),
			'description' => $request->getPost('description'),
			'rate' => $request->getPost('rate'),
			'quantity' => $request->getPost('quantity'),
			'status' => $request->getPost('status'),
		];
		if($request->getPost('id')){
			$data['id'] = $request->getPost('id');
			$data['updated_at'] = date("Y-m-d H:i:s");
		}else{
			$data['created_at'] = date("Y-m-d H:i:s");
		}
		return $data;
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
		return view('product/index', $this->data);
	}

	/**
	 * Return the properties of a resource object
	 *
	 * @return mixed
	 */
	public function show($id = null)
	{
		$this->data['data'] = $this->model->where('products.id', $id)
		->select('products.*, brands.name as brands_name, categories.name as categories_name')
		->join('brands', 'brands.id = products.brand_id')
		->join('categories', 'categories.id = products.category_id')
		->first();
		return view('product/show', $this->data);
	}

	/**
	 * Return a new resource object, with default properties
	 *
	 * @return mixed
	 */
	public function new()
	{
		$data = $this->session->getFlashdata();
		unset($data['validation']);
		$this->data['data'] = $data;

		$category = new Category();
		$brand = new Brand();
		$this->data['category'] = $category->where('status', 'Available')->find();
		$this->data['brand'] = $brand->where('status', 'Available')->find();
		return view('product/create', $this->data);
	}

	/**
	 * Create a new resource object, from "posted" parameters
	 *
	 * @return mixed
	 */
	public function create()
	{
		$data = $this->_wrap();
		if(!isset($data['id'])){
			$this->rules['image'] = 'uploaded[image]|mime_in[image,image/jpg,image/jpeg,image/gif,image/png,image/webp]';
		}
		$validate = $this->validate($this->rules);
		if(!$validate){
			$this->session->setFlashdata('validation', $this->validator->getErrors());
			$this->session->setFlashdata($_POST);
			return redirect()->back();
		}
		$img = $this->request->getFile('image');
		if($img->isValid()){
	        $path = '/uploads/' . date('Ymd');
	        $name = $img->getRandomName();
	        if ($img->move(ROOTPATH . 'public' . $path, $name)){
	            $data['image'] = $path . '/' . $name;
	            if(isset($data['id'])){
	            	$find = $this->model->where('id', $data['id'])->first();
	            	unlink(ROOTPATH . 'public' . $find['image']);
	            }
	        }else{
	        	$this->session->setFlashdata('validation', ['The image has already been moved.']);
	        }
		}
		$this->hande_message($data);
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
		$category = new Category();
		$brand = new Brand();
		$this->data['category'] = $category->find();
		$this->data['brand'] = $brand->find();
		$this->data['data'] = $this->model->where('id', $id)->first();
		return view('product/create', $this->data);
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
		$model = $this->model->where('id', $id);
	    unlink(ROOTPATH . 'public' . $model->first()['image']);
	    $delete = new Product();
		$delete->where('id', $id)->delete();
		return redirect()->back();
	}
	public function search()
	{
		$model = $this->model
			->like('id', $_GET['term'])
			->orLike('name', $_GET['term'])
			->limit(10)
			->find();
		return json_encode($model);
	}
}
