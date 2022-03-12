<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Spend extends Migration
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
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],
            'total' => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],
            'date' => [
                'type' => 'DATETIME',
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
        $this->forge->createTable('spends');
	}

	public function down()
	{
		$this->forge->dropTable('spends');
	}
}
