<?php

namespace App\Controllers;
use App\Models\Brand;

class BrandController extends BaseController
{
	public function __construct()
	{
		$this->data['active'] = 'Brand';
		$this->data['controller'] = 'App\Controllers\BrandController';
		$this->rules = [
			'name' => 'required|min_length[3]',
			'status' => 'required'
		];
		$this->model = new Brand();
	}
	public function _wrap()
	{
		$request = $this->request;
		$data = [
			'name' => $request->getPost('name'),
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
		if(isset($_GET['search'])){
			$this->model->like('name', $_GET['search']);
			$this->model->orLike('status', $_GET['search']);
		}
		$this->data['list'] = $this->model->paginate(10);
		$this->data['pager'] = $this->model->pager;
		return view('brand/index', $this->data);
	}

	/**
	 * Return the properties of a resource object
	 *
	 * @return mixed
	 */
	public function show($id = null)
	{
		//
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
		return view('brand/create', $this->data);
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
		$this->data['data'] = $this->model->where('id', $id)->first();
		return view('brand/create', $this->data);
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
