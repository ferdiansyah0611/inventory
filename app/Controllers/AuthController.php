<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\User;

class AuthController extends BaseController
{
	protected $helpers = ['form'];

	public function __construct(){
		$this->rules = [
		    'username' => 'required',
		    'email'    => 'required|valid_email',
		    'password' => 'required|min_length[8]',
		];
	}
	public function login()
	{
		$method = $this->request->getMethod();
		if($method == 'get'){
			if(!isset($this->user['id'])){
				return view('auth/login');
			}
			return redirect()->to('/');
		}
		if($method == 'post'){
			$rules = $this->rules;
			unset($rules['username']);
			$validate = $this->validate($rules);
			if(!$validate)
			{
				$this->session->setFlashdata('validation', $this->validator->getErrors());
				return view('auth/login');
			}
			$request = $this->request;
			$user = new User();
			$find = $user->where('email', $request->getPost('email'))->first();
			if(password_verify($request->getPost('password'), $find['password'])){
				$data = $find;
				$data['password'] = null;
				$this->session->set($data);
				if($find['role'] == 'user'){
					$this->session->setFlashdata('validation', ['Please wait, you are not confirmed to be customer.']);
					return view('auth/login', $data);
				}
				return redirect()->to('/');
			}
			$this->session->setFlashdata('validation', ['Password not match.']);
			return view('auth/login');
		}
	}
	public function register()
	{
		$request = $this->request;
		$method = $request->getMethod();
		if($method == 'get'){
			if(isset($this->user['id'])){
				return redirect()->to('/');
			}
			$data = $this->session->getFlashdata();
			return view('auth/register', $data);
		}
		if($method == 'post'){
			$validate = $this->validate($this->rules);
			if(!$validate)
			{
				$this->session->setFlashdata('validation', $this->validator->getErrors());
				$this->session->setFlashdata($_POST);
				return redirect()->back();
			}
			$user = new User();
			$find = $user->where('email', $request->getPost('email'))->first();
			if(!isset($find['id']))
			{
				$username = $request->getPost('username');
				$email = $request->getPost('email');
				$password = password_hash($request->getPost('password'), PASSWORD_DEFAULT);
				$insert = [
					'username' => $username,
					'email' => $email,
					'password' => $password,
					'role' => 'user',
					'created_at' => date("Y-m-d H:i:s"),
				];
				$user->save($insert);
				$data = $insert;
				$data['password'] = null;
				$this->session->setFlashdata('success', 'Successfuly registered. Signin now!');
				return redirect()->to('/auth/signin');
			}
			$this->session->setFlashdata($_POST);
			$this->session->setFlashdata('validation', ['User has registered.']);
			$data = $this->session->getFlashdata();
			return view('auth/register', $data);
		}
	}
	public function logout()
	{
		$this->session->destroy();
		return redirect()->to('/auth/signin');
	}
}
