<?php
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_sys_class('model', '', 0);
class announce_model extends model {
	
	public $table_name;
	public function __construct() {
		$this->db_config = pc_base::load_config('database');
		$this->db_setting = 'develop';
		$this->table_name = 'notice';
		parent::__construct();
	}
}
?>