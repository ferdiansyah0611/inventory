<?php

use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;
use CodeIgniter\Test\CIUnitTestCase;

class DefaultHttpTest extends CIUnitTestCase
{
    use DatabaseTestTrait, FeatureTestTrait;

    protected function setUp(): void
    {
        parent::setUp();

        $session = [
            'id' => 1,
            'username' => 'admin',
            'email' => 'admin@gmail.com',
            'role' => 'admin',
        ];
        $this->withSession($session);

        $filesize = filesize('./tests/http/init.png');
        $fp = fopen('./tests/http/init.png', 'rb');
        $binary = fread($fp, $filesize);

        $this->fp = $fp;
        $this->binary = $binary;
        $this->resource = ['brand', 'spend', 'category', 'user', 'order', 'product'];
        $this->json = ['brand', 'spend', 'category', 'user', 'order', 'product', 'product/alert', 'order/request', 'customer', 'worker', 'spend'];
    }

    protected function tearDown(): void
    {
        fclose($this->fp);
        parent::tearDown();
    }

    public function testGet()
    {
        // resource
        for ($i=0; $i < sizeof($this->resource); $i++) { 
            $this->get('/' . $this->resource[$i])->assertOK();
        }
        // json
        for ($i=0; $i < sizeof($this->json); $i++) { 
            $this->get('/' . $this->json[$i] . '/json')->assertOK();
        }
        // chart
        $this->get('/chart/order')->assertOK();
        $this->get('/chart/price-last')->assertOK();
        // search
        $this->get('/customer/search?term=test')->assertOK();
        $this->get('/product/search?term=test')->assertOK();

        $this->get('/product/alert')->assertOK();
        $this->get('/customer')->assertOK();
        $this->get('/worker')->assertOK();
        $this->get('/profile')->assertOK();
    }

    public function testAdd()
    {
        $this->post('/user', [
            'username' => 'ferdiansyah',
            'email' => 'hello@gmail.com',
            'password' => 'people-tested',
            'role' => 'admin'
        ])->assertOK();
        $this->post('/brand', [
            'name' => 'iphone',
            'status' => 'Available'
        ])->assertOK();
        $this->post('/category', [
            'name' => 'smartphone',
            'status' => 'Available'
        ])->assertOK();
        $this->post('/order', [
            'product_id' => '1',
            'customer_id' => '1',
            'quantity' => 10,
            'discount' => 2,
            'status' => 'Done',
            'note' => 'hello',
            'payment_type' => 'bca',
            'payment_status' => 'Success',
            'payment_place' => '-',
            'order_at' => date("Y-m-d H:i:s"),
        ])->assertOK();
        $this->post('/product', [
            'brand_id' => '1',
            'category_id' => '1',
            'name' => 'test',
            'description' => 'hello',
            'rate' => 1000,
            'quantity' => 10,
            'status' => 'Available',
            'image' => $this->binary
        ])->assertOK();
    }
    public function testUpdate()
    {
        $this->post('/user', [
            'id' => 1,
            'username' => 'ferdiansyah 1',
            'email' => 'hello@gmail.com',
            'password' => 'people-tested',
            'role' => 'admin'
        ])->assertOK();
        $this->post('/brand', [
            'id' => 1,
            'name' => 'iphone +',
            'status' => 'Available'
        ])->assertOK();
        $this->post('/category', [
            'id' => 1,
            'name' => 'smartphone',
            'status' => 'Available'
        ])->assertOK();
        $this->post('/order', [
            'id' => 1,
            'product_id' => '1',
            'customer_id' => '1',
            'quantity' => 10,
            'discount' => 2,
            'status' => 'Done',
            'note' => 'hello world',
            'payment_type' => 'bca',
            'payment_status' => 'Success',
            'payment_place' => '-',
            'order_at' => date("Y-m-d H:i:s"),
        ])->assertOK();
        $this->post('/product', [
            'id' => 1,
            'brand_id' => '1',
            'category_id' => '1',
            'name' => 'test',
            'description' => 'hello',
            'rate' => 1000,
            'quantity' => 10,
            'status' => 'Available',
            'image' => $this->binary
        ])->assertOK();
    }
    public function testDelete()
    {
        for ($i=0; $i < sizeof($this->resource); $i++) { 
            $this->delete('/' . $this->resource[$i] . '/10')->assertOK();
        }
    }
}
