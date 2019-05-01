<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_PengumumanLineFollowers_initial extends CI_Migration {

    public function up() {
        $fields = array(
            'userId' => array(
                'type' => 'VARCHAR',
                'constraint' => '256'
            )
        );
        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('userId', TRUE);
		$this->dbforge->create_table('PengumumanLineFollowers');
    }

    public function down() { 
		
	}

}
