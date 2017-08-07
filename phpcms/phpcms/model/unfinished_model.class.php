<?php
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_sys_class('cmodel_model', '', 0);
class unfinished_model extends model{
	public function __construct() {
		$this->db_config = pc_base::load_config('database');
		$this->db_setting = 'develop';
		$this->table_name = 'dz_unfinished_consume';
		parent::__construct();
	}
}
?>