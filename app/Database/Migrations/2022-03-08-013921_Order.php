<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Order extends Migration
{
	public function up()
	{
		$this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'product_id' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'customer_id' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'quantity' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'price_total' => [
                'type' => 'INT',
                'constraint' => 255
            ],
            'discount' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true
            ],
            'status' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true
            ],
            'note' => [
                'type' => 'LONGTEXT',
                'null' => true
            ],
            'payment_type' => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],
            'payment_status' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true
            ],
            'payment_place' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true
            ],
            'order_at' => [
                'type' => 'DATETIME',
                'null' => true
            ],
            'created_at' => [
                'type' => 'DATETIME',
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('orders');
	}

	public function down()
	{
		$this->forge->dropTable('orders');
	}
}
