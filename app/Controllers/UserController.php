<?php

namespace App\Controllers;
use App\Models\User;
use \Hermawan\DataTables\DataTable;

class UserController extends BaseController
{
	public function __construct()
	{
		$this->data['active'] = 'User';
		$this->data['controller'] = 'App\Controllers\UserController';
		$this->rules = [
			'username' => 'required|string|min_length[3]',
			'email' => 'required|valid_email',
			'role' => 'required|in_list[admin, user, customer, worker]',
			'place' => 'required|string|min_length[3]',
			'phone' => 'required|integer',
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
			'place' => $request->getPost('place'),
			'phone' => $request->getPost('phone'),
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
		$this->data['json'] = route_to($this->data['controller'] . '::json');
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
			if(!isset($data['id'])){
				$this->session->setFlashdata($_POST);
			}
			return redirect()->back();
		}
		// edit
		if(isset($data['id'])){
			if(count($mail) == 1){
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
			->like('username', $this->request->getGet('term'))
			->limit(10)
			->get()->getResult();
		return json_encode($model);
	}
	public function customer()
	{
		$this->data['active'] = 'Customer';
		$this->data['json'] = route_to($this->data['controller'] . '::json_customer');
		return view('user/index', $this->data);
	}
	public function worker()
	{
		$this->data['active'] = 'Worker';
		$this->data['json'] = route_to($this->data['controller'] . '::json_worker');
		return view('user/index', $this->data);
	}
	public function profile()
	{
		$request = $this->request;
		$method = $request->getMethod();
		$this->data['data'] = $this->model->where('id', $this->user['id'])->first();

		if ($method == 'post') {
			$data = $this->_wrap();
			unset($data['password']);

			$data['id'] = $this->user['id'];
			$data['role'] = $this->user['role'];
			$data['email'] = $this->user['email'];
			$old_password = $request->getPost('old_password');
			$new_password = $request->getPost('new_password');

			unset($this->rules['email']);
			unset($this->rules['password']);
			unset($this->rules['role']);

			if($old_password){
				$this->rules['old_password'] = 'required|min_length[8]|string';
				$this->rules['new_password'] = 'required|min_length[8]|string';
			}
			$validate = $this->validate($this->rules);

			$mail = $this->data['data'];
			if(!$validate){
				$this->session->setFlashdata('validation', $this->validator->getErrors());
				if(!$data['id']){
					$this->session->setFlashdata($_POST);
				}
				return redirect()->back();
			}

			if ($old_password && $new_password) {
				if (password_verify($old_password, $mail['password'])) {
					$data['password'] = password_hash($new_password, PASSWORD_DEFAULT);
				}
			}

			$data['updated_at'] = date("Y-m-d H:i:s");
			$this->model->save($data);
			$user = new User();
			$user = $user->where('email', $this->user['email'])->first();
			unset($user['password']);
			$this->session->set($user);
			return redirect()->back();
		}
		if ($method == 'get') {
			$this->data['active'] = 'Profile';
			return view('profile', $this->data);
		}
	}
	public function json()
	{
		$db = db_connect();
    	$builder = $db->table('users')->select('id, username, email, role, created_at');
    	return DataTable::of($builder)->toJson();
	}
	public function json_customer()
	{
		$db = db_connect();
    	$builder = $db->table('users')->select('id, username, email, role, created_at')->where('role', 'customer');
    	return DataTable::of($builder)->toJson();
	}
	public function json_worker()
	{
		$db = db_connect();
    	$builder = $db->table('users')->select('id, username, email, role, created_at')->where('role', 'worker');
    	return DataTable::of($builder)->toJson();
	}
}
