<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */

class BaseController extends Controller
{
	/**
	 * An array of helpers to be loaded automatically upon
	 * class instantiation. These helpers will be available
	 * to all other controllers that extend BaseController.
	 *
	 * @var array
	 */
	protected $helpers = [];

	/**
	 * Constructor.
	 *
	 * @param RequestInterface  $request
	 * @param ResponseInterface $response
	 * @param LoggerInterface   $logger
	 */
	public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
	{
		// Do Not Edit This Line
		parent::initController($request, $response, $logger);

		$this->session = \Config\Services::session();
		$this->user = [
			'id' => $this->session->get('id'),
			'username' => $this->session->get('username'),
			'email' => $this->session->get('email'),
			'role' => $this->session->get('role'),
		];
		$this->data['user'] = $this->user;
		if($this->user['id'] && ($this->user['role'] == 'admin' || $this->user['role'] == 'worker')){
			$product = new \App\Models\Product;
			$order = new \App\Models\Order;
			$data = $product->alert();
			$requestproduct = $order->alert();
			$this->data['alertproduct'] = $data;
			$this->data['requestproduct'] = $requestproduct;
		}
	}
	public function hande_message($data)
	{
		if(isset($data['id'])){
			$this->session->setFlashdata('success', 'Successfuly update data');
		}else{
			$this->session->setFlashdata('success', 'Successfuly add data');
		}
	}
}
