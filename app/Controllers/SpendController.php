<?php

namespace App\Controllers;
use App\Models\Spend;
use \Hermawan\DataTables\DataTable;

class SpendController extends BaseController
{
	public function __construct()
	{
		$this->data['active'] = 'Spend';
		$this->data['controller'] = 'App\Controllers\SpendController';
		$this->rules = [
			'name' => 'required|min_length[3]',
			'total' => 'required',
			'date' => 'required',
		];
		$this->model = new Spend();
	}
	public function _wrap()
	{
		$request = $this->request;
		$data = [
			'name' => $request->getPost('name'),
			'total' => $request->getPost('total'),
			'date' => $request->getPost('date'),
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
		}
		$this->data['list'] = $this->model->paginate(10);
		$this->data['pager'] = $this->model->pager;
		return view('spend/index', $this->data);
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
		return view('spend/create', $this->data);
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
		return view('spend/create', $this->data);
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
    	$builder = $db->table('spends')->select('id, name, total, date, created_at');
    	return DataTable::of($builder)->toJson();
	}
}
