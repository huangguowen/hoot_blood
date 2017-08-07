<?php
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_class('admin','admin',0);
pc_base::load_sys_class('format', '', 0);
pc_base::load_sys_class('form', '', 0);
pc_base::load_app_func('global');
class agent extends admin {
	private $db, $account_db, $member_db;
	function __construct() {
		//安装模块不要了
		// if (!module_exists(ROUTE_M)) showmessage(L('module_not_exists')); 
		parent::__construct();
		$this->db = pc_base::load_model('pay_payment_model');
		$this->account_db = pc_base::load_model('admin_model');
		$this->member_db = pc_base::load_model('member_model');
		$this->modules_path = PC_PATH.'modules'.DIRECTORY_SEPARATOR.'pay';
		pc_base::load_app_class('pay_method','','0');
		$this->method = new pay_method($this->modules_path);
	}
	/**
	 * 支付模块列表
	 */
	public function init() {	
		$infos = $this->method->get_list();
		$show_dialog = true;
		include $this->admin_tpl('payment_list');
	}

	public function change(){

		$id = intval($_GET['id']);
		$status = intval($_GET['status']);
		if($status == 1){
			$update = 0;
		}elseif($status == 0){
			$update = 1;
		}
		$this->account_db->update(array('status' => $update), array('userid' => $id));
		echo 1;
	}
	/**
	 * 支付订单列表
	 */
	public function agent_list_1() {
		// print_r($_GET);
		$where = '';
		if($_GET['dosubmit']){
			extract($_GET['info']);
			if($sn) $where = "AND `sn` LIKE '%$sn%' ";
			if($nickname) $where = "AND `nickname` LIKE '%$nickname%' ";
			if($start_addtime && $end_addtime) {
				$start = strtotime($start_addtime.' 00:00:00');
				$end = strtotime($end_addtime.' 23:59:59');
				$where .= "AND `addtime` >= '$start' AND  `addtime` <= '$end'";				
			}
			if($status) $where .= "AND `status` LIKE '%$status%' ";			
			if($where) $where = substr($where, 3);
		}			
		$infos = array();
		foreach(L('select') as $key=>$value) {
			$trade_status[$key] = $value;
		}
		$page = $_GET['page'] ? $_GET['page'] : '1';
		$roles = getcache('role','commons');
				$play_id = $this->account_db->get_play_id_for_userid($_SESSION['userid']);
			if($_SESSION['userid'] != 1){
		$where = "roleid = 2 and parent_id = '$play_id'";
		}else{
			$where = "roleid = 2";
		}
		$infos = $this->account_db->listinfo($where, $order = 'lastlogintime DESC,userid DESC', $page, $pagesize = 20);
		$pages = $this->account_db->pages;
		$number = count($infos);
		include $this->admin_tpl('agent_list_1');	
	}
	


	/**
	 * 支付订单列表
	 */
	public function agent_list_2() {

		$where = '';
		if($_GET['dosubmit']){
			extract($_GET['info']);
			$keyword = $_GET['info']['keyword'];
			$where = "realname = '$keyword' or play_id = '$keyword' or username = '$keyword'";
			$page = $_GET['page'] ? $_GET['page'] : '1';
		$roles = getcache('role','commons');
		$infos = $this->account_db->listinfo($where, $order = 'lastlogintime DESC,userid DESC', $page, $pagesize = 20);
		$pages = $this->account_db->pages;
		$number = count($infos);
		include $this->admin_tpl('agent_list_2');	die;
		}			
		$infos = array();
		foreach(L('select') as $key=>$value) {
			$trade_status[$key] = $value;
		}
		$page = $_GET['page'] ? $_GET['page'] : '1';
		$roles = getcache('role','commons');
				$play_id = $this->account_db->get_play_id_for_userid($_SESSION['userid']);
			if($_SESSION['userid'] != 1){
		$where = "roleid = 3 and (parent_id = '$play_id' or pp_id = '$play_id')";
		}else{
			$where = "roleid = 3";
		}
		$infos = $this->account_db->listinfo($where, $order = 'lastlogintime DESC,userid DESC', $page, $pagesize = 20);
		$pages = $this->account_db->pages;
		$number = count($infos);
		include $this->admin_tpl('agent_list_2');	
	}



	/**
	 * 支付订单列表
	 */
	public function agent_list_3() {
		$where = '';
		if($_GET['dosubmit']){
			extract($_GET['info']);
			$keyword = $_GET['info']['keyword'];
			$where = "realname = '$keyword' or play_id = '$keyword' or username = '$keyword'";
			$page = $_GET['page'] ? $_GET['page'] : '1';
		$roles = getcache('role','commons');
		$infos = $this->account_db->listinfo($where, $order = 'lastlogintime DESC,userid DESC', $page, $pagesize = 20);
		$pages = $this->account_db->pages;
		$number = count($infos);
		include $this->admin_tpl('agent_list_3');	die;
		}			
		$infos = array();
		foreach(L('select') as $key=>$value) {
			$trade_status[$key] = $value;
		}
		$page = $_GET['page'] ? $_GET['page'] : '1';
		$roles = getcache('role','commons');
		$play_id = $this->account_db->get_play_id_for_userid($_SESSION['userid']);
			if($_SESSION['userid'] != 1){
		$where = "roleid = 4 and (parent_id = '$play_id' or pp_id = '$play_id')";
		}else{
			$where = "roleid = 4";
		}
		$infos = $this->account_db->listinfo($where, $order = 'lastlogintime DESC,userid DESC', $page, $pagesize = 20);
		$pages = $this->account_db->pages;
		$number = count($infos);
		include $this->admin_tpl('agent_list_3');	
	}

	/**
	 * 财务统计
	 * Enter description here ...
	 */
	public function pay_stat() {
		$where = '';
		$infos = array();
		if($_GET['dosubmit']){
			extract($_GET['info']);
			if($username) $where = "AND `username` LIKE '%$username%' ";
			if($start_addtime && $end_addtime) {
				$start = strtotime($start_addtime.' 00:00:00');
				$end = strtotime($end_addtime.' 23:59:59');
				$where .= "AND `addtime` >= '$start' AND  `addtime` <= '$end'";				
			}
			if($status) $where .= "AND `status` LIKE '%$status%' ";			
			if($where) $where = substr($where, 3);
			$infos = $this->account_db->select($where);
			$num= count($infos);
			foreach ($infos as $_v) {
				if($_v['type'] == 1) {
					$amount_num++;
					$amount += $_v['money']; 
					if($_v['status'] =='succ') {$amount_succ += $_v['money'];$amount_num_succ++;}
				}  elseif ($_v['type'] == 2) {
					$point_num++;
					$point += $_v['money']; 
					if($_v['status'] =='succ') {$point_succ += $_v['money'];$point_num_succ++;}
				}
			}			
		}		
		foreach(L('select') as $key=>$value) $trade_status[$key] = $value;		
		$total_infos = $this->account_db->select();
		$total_num= count($total_infos);
		foreach ($total_infos as $_v) {
			if($_v['type'] == 1) {
				$total_amount_num++;
				$total_amount += $_v['money']; 
				if($_v['status'] =='succ') {$total_amount_succ += $_v['money'];$total_amount_num_succ++;}
			}  elseif ($_v['type'] == 2) {
				$total_point_num++;
				$total_point += $_v['money']; 
				if($_v['status'] =='succ') {$total_point_succ += $_v['money'];$total_point_num_succ++;}
			}			
		}
		include $this->admin_tpl('pay_stat');
	}

public function diamond_list(){
		$where = '';
		if($_GET['dosubmit']){
			extract($_GET['info']);
			if($trade_sn) $where = "AND `trade_sn` LIKE '%$trade_sn%' ";
			if($username) $where = "AND `username` LIKE '%$username%' ";
			if($start_addtime && $end_addtime) {
				$start = strtotime($start_addtime.' 00:00:00');
				$end = strtotime($end_addtime.' 23:59:59');
				$where .= "AND `addtime` >= '$start' AND  `addtime` <= '$end'";				
			}
			if($status) $where .= "AND `status` LIKE '%$status%' ";			
			if($where) $where = substr($where, 3);
		}			
		$infos = array();
		foreach(L('select') as $key=>$value) {
			$trade_status[$key] = $value;
		}
		$page = $_GET['page'] ? $_GET['page'] : '1';
		
		$infos = $this->account_db->listinfo($where, $order = 'addtime DESC,id DESC', $page, $pagesize = 20);
		$pages = $this->account_db->pages;
		$number = count($infos);
		include $this->admin_tpl('diamond_list');	
}
	
	public function coins_list(){
		$where = '';
		if($_GET['dosubmit']){
			extract($_GET['info']);
			if($trade_sn) $where = "AND `trade_sn` LIKE '%$trade_sn%' ";
			if($username) $where = "AND `username` LIKE '%$username%' ";
			if($start_addtime && $end_addtime) {
				$start = strtotime($start_addtime.' 00:00:00');
				$end = strtotime($end_addtime.' 23:59:59');
				$where .= "AND `addtime` >= '$start' AND  `addtime` <= '$end'";				
			}
			if($status) $where .= "AND `status` LIKE '%$status%' ";			
			if($where) $where = substr($where, 3);
		}			
		$infos = array();
		foreach(L('select') as $key=>$value) {
			$trade_status[$key] = $value;
		}
		$page = $_GET['page'] ? $_GET['page'] : '1';
		
		$infos = $this->account_db->listinfo($where, $order = 'addtime DESC,id DESC', $page, $pagesize = 20);
		$pages = $this->account_db->pages;
		$number = count($infos);
		include $this->admin_tpl('coins_list');	
}

	public function fangka_list(){
		echo $_GET;
		$where = '';
		if($_GET['dosubmit']){
			extract($_GET['info']);
			if($trade_sn) $where = "AND `trade_sn` LIKE '%$trade_sn%' ";
			if($username) $where = "AND `username` LIKE '%$username%' ";
			if($start_addtime && $end_addtime) {
				$start = strtotime($start_addtime.' 00:00:00');
				$end = strtotime($end_addtime.' 23:59:59');
				$where .= "AND `addtime` >= '$start' AND  `addtime` <= '$end'";				
			}
			if($status) $where .= "AND `status` LIKE '%$status%' ";			
			if($where) $where = substr($where, 3);
		}			
		$infos = array();
		foreach(L('select') as $key=>$value) {
			$trade_status[$key] = $value;
		}
		$page = $_GET['page'] ? $_GET['page'] : '1';
		
		$infos = $this->account_db->listinfo($where, $order = 'addtime DESC,id DESC', $page, $pagesize = 20);
		$pages = $this->account_db->pages;
		$number = count($infos);
		include $this->admin_tpl('fangka_list');	
}
}
?>