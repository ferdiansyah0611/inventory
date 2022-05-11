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
            $this->get('/' . $this->resource[$i])->assertStatus(200);
        }
        // json
        for ($i=0; $i < sizeof($this->json); $i++) { 
            $this->get('/' . $this->json[$i] . '/json')->assertStatus(200);
        }
        // chart
        $this->get('/chart/order')->assertStatus(200);
        $this->get('/chart/price-last')->assertStatus(200);
        // search
        $this->get('/customer/search?term=test')->assertStatus(200);
        $this->get('/product/search?term=test')->assertStatus(200);

        $this->get('/product/alert')->assertStatus(200);
        $this->get('/customer')->assertStatus(200);
        $this->get('/worker')->assertStatus(200);
        $this->get('/profile')->assertStatus(200);
    }

    public function testAdd()
    {
        $this->post('/user', [
            'username' => 'ferdiansyah',
            'email' => 'hello@gmail.com',
            'password' => 'people-tested',
            'role' => 'admin'
        ])->assertStatus(302);
        $this->post('/brand', [
            'name' => 'iphone',
            'status' => 'Available'
        ])->assertStatus(302);
        $this->post('/category', [
            'name' => 'smartphone',
            'status' => 'Available'
        ])->assertStatus(302);
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
            'code' => null,
            'order_at' => date("Y-m-d H:i:s"),
        ])->assertStatus(302);
        $this->post('/product', [
            'brand_id' => '1',
            'category_id' => '1',
            'name' => 'test',
            'description' => 'hello',
            'rate' => 1000,
            'quantity' => 10,
            'status' => 'Available',
            'image' => $this->binary
        ])->assertStatus(302);
    }
    public function testUpdate()
    {
        $this->post('/user', [
            'id' => 1,
            'username' => 'ferdiansyah 1',
            'email' => 'hello@gmail.com',
            'password' => 'people-tested',
            'role' => 'admin'
        ])->assertStatus(302);
        $this->post('/brand', [
            'id' => 1,
            'name' => 'iphone +',
            'status' => 'Available'
        ])->assertStatus(302);
        $this->post('/category', [
            'id' => 1,
            'name' => 'smartphone',
            'status' => 'Available'
        ])->assertStatus(302);
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
        ])->assertStatus(302);
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
        ])->assertStatus(302);
    }
    public function testDelete()
    {
        for ($i=0; $i < sizeof($this->resource); $i++) { 
            $this->delete('/' . $this->resource[$i] . '/10')->assertStatus(302);
        }
    }
}
