<?php

namespace App\Controllers;
use App\Models\Category;
use \Hermawan\DataTables\DataTable;

class CategoryController extends BaseController
{
	public function __construct()
	{
		$this->data['active'] = 'Category';
		$this->data['controller'] = 'App\Controllers\CategoryController';
		$this->rules = [
			'name' => 'required|min_length[3]',
			'status' => 'required|in_list[Available,Not Available]'
		];
		$this->model = new Category();
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
		if(isset($this->request->getGet['search'])){
			$this->model->like('name', $this->request->getGet['search']);
			$this->model->orLike('status', $this->request->getGet['search']);
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
		if($this->user['role'] !== 'admin'){
			return redirect()->back();
		}
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
		if($this->user['role'] !== 'admin'){
			return redirect()->back();
		}
		$validate = $this->validate($this->rules);
		if(!$validate){
			$this->session->setFlashdata('validation', $this->validator->getErrors());
			$this->session->setFlashdata($_POST);
			return redirect()->back();
		}
		$data = $this->_wrap();
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
		if($this->user['role'] !== 'admin'){
			return redirect()->back();
		}
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
		if($this->user['role'] !== 'admin'){
			return redirect()->back();
		}
		$this->model->where('id', $id)->delete();
		return redirect()->back();
	}
	public function json()
	{
		$db = db_connect();
    	$builder = $db->table('categories')->select('id, name, status, created_at, updated_at');
    	return DataTable::of($builder)->toJson();
	}
}
