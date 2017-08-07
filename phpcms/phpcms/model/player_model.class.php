<?php
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_sys_class('cmodel_model', '', 0);
class player_model extends model{
	public function __construct() {
		$this->db_config = pc_base::load_config('database');
		$this->db_setting = 'develop';
		$this->table_name = 'dz_player';
		parent::__construct();
	}
	//根据id 查询nickname ==
	public function get_player_data($id){
		$sql = "select * from t_dz_player where id = '$id'";
			 $query = $this->get_one_by_sql($sql);
			 return $query;
	}
	//根据play_id查询分销级别
	public function get_agent_level($play_id){
			$sql = "select rolename from phpcmsv9.v9_admin_role where roleid = (select roleid from phpcmsv9.v9_admin where play_id = '$play_id')";
			 $query = $this->get_one_by_sql($sql);
			 if(empty($query)){
			 	return '玩家';
			 }
			 return $query['rolename'];
	}
}
?>