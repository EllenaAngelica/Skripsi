<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Pengumuman_initial extends CI_Migration {

    public function up() {
        $fields = array(
            'id' => array(
                'type' => 'int',
				 'auto_increment' => TRUE
            ),
            'namaPengirim' => array(
                'type' => 'VARCHAR',
                'constraint' => '256'
            ),
			'emailPengirim' => array(
                'type' => 'VARCHAR',
                'constraint' => '256'
            ),
			'waktuTerkirim' => array(
                'type' => 'timestamp'
            ),
			'subjek' => array(
                'type' => 'VARCHAR',
                'constraint' => '256'
            ),
			'isi' => array(
                'type' => 'TEXT',
				'null' => TRUE
            ),
			'ketersediaanLampiran' => array(
				'type' => 'VARCHAR',
                'constraint' => '1'
			)
        );
        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table('Pengumuman');
    }

    public function down() { 
		
	}

}
