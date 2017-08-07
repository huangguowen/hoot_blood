<?php
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_class('admin','admin',0);
pc_base::load_sys_class('format', '', 0);
pc_base::load_sys_class('form', '', 0);
pc_base::load_app_func('global');
class payment extends admin {
	private $db, $account_db, $member_db;
	function __construct() {
		if (!module_exists(ROUTE_M)) showmessage(L('module_not_exists')); 
		parent::__construct();
		$this->db = pc_base::load_model('pay_payment_model');
		$this->account_db = pc_base::load_model('pay_account_model');
		$this->player_db = pc_base::load_model('player_model');
		$this->member_db = pc_base::load_model('member_model');
		$this->consume_db = pc_base::load_model('consume_model');
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

	/**
	 * 支付订单列表
	 */
	public function pay_list() {

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

		$infos = $this->account_db->listinfo($where, $page, $pagesize = 20);
	
		$pages = $this->account_db->pages;
		$number = count($infos);
		include $this->admin_tpl('pay_list');	
	}
	

		function duihuan(){
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

		$infos = $this->account_db->listinfo($where, $page, $pagesize = 20);
	
		$pages = $this->account_db->pages;
		$number = count($infos);
		include $this->admin_tpl('duihuan');	
		}



	/**
	 * 详细单
	 */
	public function details_data() {

	date_default_timezone_set('Asia/Shanghai');
	$n = time() - 86400 * date('N', time());
	$yes_3 = date('Y-m-d', $n - 86400 * 4 );
	$yes_2 = date('Y-m-d', $n+86400 * 2);

		$where = '';
		if($_GET['dosubmit']){
			extract($_GET['info']);
			if($nickname) $where = "AND `phone` = '$nickname' ";
			if($start_addtime && $end_addtime) {
				$start = $start_addtime;
				$end = $end_addtime;
				$where .= "AND `addtime` >= '$start' AND  `addtime` <= '$end'";				
			}
			if(isset($status) and $status != -1) $where .= "AND `status` = '$status' ";			
		$where = "select * from t_bs_rebate where 1 = 1 $where";
			$page = isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : 1;
			$list = $this->player_db->mylistinfo($where,$page);
			$pages = $this->player_db->pages;
			$show_dialog = true;
			include $this->admin_tpl('jiesuanmingx');
			die;
		}			

		
		$where = "select * from t_bs_rebate";
		$page = isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : 1;
		$infos = $this->player_db->mylistinfo($where,$page);
		// print_r($infos);die;
		$pages = $this->player_db->pages;
		include $this->admin_tpl('jiesuanmingx');	
	}
	


	/**
	 * 结算汇总
	 */
	public function summary_data() {

	date_default_timezone_set('Asia/Shanghai');
	$n = time() - 86400 * date('N', time());
	$yes_3 = date('Y-m-d', $n - 86400 * 4 );
	$yes_2 = date('Y-m-d', $n+86400 * 2);

		$tiaojian = '';
		if($_GET['dosubmit']){
			extract($_GET['info']);
			if($nickname) $tiaojian = "AND a.phone = '$nickname' ";
			if($start_addtime && $end_addtime) {
				$start = $start_addtime;
				$end = $end_addtime;
				$tiaojian .= "AND a.paytime >= '$start' AND a.paytime <= '$end'";				
			}
			if(isset($status) and $status != -1) $tiaojian .= "AND a.status = '$status' ";	


			//后期周二到周三 范围判断
		$where = "select b.*,a.play_id,a.status,a.conment,a.paytime,sum(a.money) as total from t_bs_rebate a,phpcmsv9.v9_admin b where a.play_id = b.play_id and 1 = 1 $tiaojian group by a.play_id,a.status";
		$qiantao = "select count(1) as count from (select sum(a.money) as total from t_bs_rebate a,phpcmsv9.v9_admin b where a.play_id = b.play_id and 1 = 1 $tiaojian group by a.play_id ) abc";
		// print_r($qiantao);die;
		$page = isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : 1;
		$infos = $this->player_db->mylistinfo_qiantao($qiantao,$where,$page);
		//开始的时间
		$stime = '0';
		//结束的时间
		$etime = '0';
		$pages = $this->player_db->pages;
		include $this->admin_tpl('jiesuanhuizong');	die;
		}			
		//后期周二到周三 范围判断
		$where = "select b.*,a.play_id,a.status,a.conment,a.paytime,sum(a.money) as total from t_bs_rebate a,phpcmsv9.v9_admin b where a.play_id = b.play_id group by a.play_id,a.status";
		$qiantao = "select count(1) as count from (select sum(a.money) as total from t_bs_rebate a,phpcmsv9.v9_admin b where a.play_id = b.play_id group by a.play_id ) abc";
		$page = isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : 1;
		$infos = $this->player_db->mylistinfo_qiantao($qiantao,$where,$page);
		//开始的时间
		$stime = '0';
		//结束的时间
		$etime = '0';
		$pages = $this->player_db->pages;
		include $this->admin_tpl('jiesuanhuizong');	
	}
	
	public function change_pay_s(){
		$this->rebate_db = pc_base::load_model('rebate_model');
		$_GET['conment']=='' ? $_GET['conment'] : $_GET['conment']=='默认支付备注';
		//将在这个时间段的且PLAY_ID 的全部状态置于1
		$info = array('status' => 1,'paytime' => date('Y-m-d H:i:s',time()),'conment' => $_GET['conment']);
		$_GET['etime'] = date('Y-m-d H:i:s',time());
		$where = "play_id = '$_GET[play_id]' and '$_GET[stime]' < addtime < '$_GET[etime]'";
		$res = $this->rebate_db->update($info,$where);
		if($res){
			echo 1;
		}
	}

		/**
	 * 充值管理
	 */
	public function agent_recharge() {
	if(isset($_GET['info'])){
		$start_time = isset($_GET['info']['start_time']) ? $_GET['info']['start_time'] : '';
		$end_time = isset($_GET['info']['end_time']) ? $_GET['info']['end_time'] :  date('Y-m-d', SYS_TIME);
		$type = isset($_GET['info']['type']) ? $_GET['info']['type'] : '';
		$status = isset($_GET['info']['status']) ? $_GET['info']['status'] : '';
		$keyword = isset($_GET['info']['keyword']) ? $_GET['info']['keyword'] : '';
	

					//默认选取一个月内的用户，防止用户量过大给数据造成灾难
			$where_start_time = strtotime($start_time) ? strtotime($start_time) : 0;
			$where_end_time = strtotime($end_time) + 86400;
			//开始时间大于结束时间，置换变量
			if($where_start_time > $where_end_time) {
				$tmp = $where_start_time;
				$where_start_time = $where_end_time;
				$where_end_time = $tmp;
				$tmptime = $start_time;
				
				$start_time = $end_time;
				$end_time = $tmptime;
				unset($tmp, $tmptime);
			}
				$where = '';
		if($start_time and $end_time){
			$where = "and '$start_time' < a.deliver_time and a.deliver_time < '$end_time'";
		}
		if($keyword){
			$where .= "and (b.nickname LIKE '%$keyword%')";
		}
			$where = "select a.*,b.* from t_dz_consume_history a,t_dz_player b where a.player_id = b.id $where";
			$page = isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : 1;
			$list = $this->player_db->mylistinfo($where,$page);
			$pages = $this->player_db->pages;
			$show_dialog = true;
			include $this->admin_tpl('agent_recharge');
			die;
	}
		$where = "select a.*,b.* from t_dz_consume_history a,t_dz_player b where a.player_id = b.id";
		$page = isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : 1;
		$list = $this->player_db->mylistinfo($where,$page);
		$pages = $this->player_db->pages;
		$show_dialog = true;
		include $this->admin_tpl('agent_recharge');
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