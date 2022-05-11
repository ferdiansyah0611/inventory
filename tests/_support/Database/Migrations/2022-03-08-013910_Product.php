<?php

namespace Tests\Support\Database\Migrations;

use CodeIgniter\Database\Migration;

class Product extends Migration
{
	protected $DBGroup = 'tests';
    public function up()
	{
		$this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'brand_id' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'category_id' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],
            'description' => [
                'type' => 'LONGTEXT',
                'null' => true
            ],
            'image' => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],
            'rate' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'quantity' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'status' => [
                'type' => 'VARCHAR',
                'constraint' => 255
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
        $this->forge->createTable('products');
	}

	public function down()
	{
		$this->forge->dropTable('products');
	}
}
