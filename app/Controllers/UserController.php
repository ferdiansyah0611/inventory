<?php

namespace App\Controllers;
use App\Models\User;

class UserController extends BaseController
{
	public function __construct()
	{
		$this->data['active'] = 'User';
		$this->data['controller'] = 'App\Controllers\UserController';
		$this->rules = [
			'username' => 'required|min_length[3]',
			'email' => 'required|valid_email',
			'role' => 'required|in_list[admin, user, customer, worker]'
		];
		$this->model = new User();
	}
	public function _wrap()
	{
		$request = $this->request;
		$data = [
			'username' => $request->getPost('username'),
			'email' => $request->getPost('email'),
			'role' => $request->getPost('role'),
		];
		if($request->getPost('id')){
			$data['id'] = $request->getPost('id');
			$data['updated_at'] = date("Y-m-d H:i:s");
			$new = $request->getPost('new_password');
			if($new){
				$data['password'] = password_hash($new, PASSWORD_DEFAULT);
			}
		}else{
			$data['password'] = password_hash($request->getPost('password'), PASSWORD_DEFAULT);
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
			$this->model->like('id', $_GET['search']);
			$this->model->orLike('username', $_GET['search']);
			$this->model->orLike('email', $_GET['search']);
			$this->model->orLike('role', $_GET['search']);
		}
		$this->data['list'] = $this->model->paginate(10);
		$this->data['pager'] = $this->model->pager;
		return view('user/index', $this->data);
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
		return view('user/create', $this->data);
	}

	/**
	 * Create a new resource object, from "posted" parameters
	 *
	 * @return mixed
	 */
	public function create()
	{
		$data = $this->_wrap();
		$mail = $this->model->where('email', $data['email'])->find();
		$this->hande_message($data);

		$validate = $this->validate($this->rules);
		if(!$validate){
			$this->session->setFlashdata('validation', $this->validator->getErrors());
			$this->session->setFlashdata($_POST);
			return redirect()->back();
		}
		// edit
		if(isset($data['id'])){
			if(count($mail) == 1){
				// dd($mail);
				if(isset($mail[0]['id']) && $data['id'] == $mail[0]['id']){
					$this->model->save($data);
					return redirect()->back();
				}
			}
		}
		// create
		if(!isset($data['id'])){
			if(count($mail) == 0){
				$this->model->save($data);
				return redirect()->back();
			}else{
				$this->session->setFlashdata($_POST);
				$this->session->setFlashdata('validation', ['Email has already register']);
			}
		}
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
		return view('user/create', $this->data);
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
	public function search_customer()
	{
		$model = $this->model->where('role', 'customer')
			->like('username', $_GET['term'])
			->limit(10)
			->get()->getResult();
		return json_encode($model);
	}
}
