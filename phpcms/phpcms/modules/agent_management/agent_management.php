<?php
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_class('admin','admin',0);
pc_base::load_sys_class('format', '', 0);
pc_base::load_sys_class('form', '', 0);
pc_base::load_app_func('global');
class agent_management extends admin {
	private $db, $account_db, $vip_db;
	function __construct() {
		//安装模块不要了
		// if (!module_exists(ROUTE_M)) showmessage(L('module_not_exists')); 
		parent::__construct();
		$this->db = pc_base::load_model('pay_payment_model');
		$this->account_db = pc_base::load_model('account_model');
		$this->vip_db = pc_base::load_model('vip_model');

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
	 * 自己的代理信息
	 */
	public function agent_vip() {
	if($_SESSION['userid'] == 1){
		showmessage_two('你不是代理商,无法查看该页面'); 
	}
	//玩家ID
	$play_id = $_SESSION['play_id'];
	$sql = "select a.*,b.* from phpcmsv9.v9_admin a,t_dz_player b where a.play_id = '$play_id'";
	$res = $this->account_db->get_one_by_sql($sql);
	if($res['roleid']==2){
		$res['rolename'] = '总代理';
	}elseif ($res['roleid']==3) {
		$res['rolename'] = '一级代理';
	}else{
		$res['rolename'] = '二级代理';
	}
	$sql = "select * from t_dz_player where id = '$res[parent_id]'";
	$parent_res = $this->account_db->get_one_by_sql($sql);
	// print_r($res);die;
	// 	$infos = $this->account_db->listinfo();
		include $this->admin_tpl('agent_vip');	
	}
	
	/**
	 * 我的代理
	 */
	public function my_agent() {
		$where = '';		
		$infos = array();
		foreach(L('select') as $key=>$value) {
			$trade_status[$key] = $value;
		}
		$page = $_GET['page'] ? $_GET['page'] : '1';
		$where = "select a.*,b.* from phpcmsv9.v9_admin a,t_dz_player b where pp_id = '$_SESSION[play_id]' or parent_id = '$_SESSION[play_id]' and (a.userid = b.id) group by a.userid";
		$infos = $this->account_db->mylistinfo($where, $page, $pagesize = 20);
		$pages = $this->account_db->pages;
		$number = count($infos);
		include $this->admin_tpl('my_agent_list');	
	}
	

		/**
	 * 我的会员
	 */
	public function my_vip() {
		$where = '';		
		$infos = array();
		foreach(L('select') as $key=>$value) {
			$trade_status[$key] = $value;
		}
		$page = $_GET['page'] ? $_GET['page'] : '1';
		$where = "select * from t_bs_vip where parent_id = '$_SESSION[play_id]'";
		$infos = $this->vip_db->mylistinfo($where, $page, $pagesize = 20);
		$pages = $this->vip_db->pages;
		$number = count($infos);
		include $this->admin_tpl('my_vip_list');	
	}


		/**
	 * 与我相关的订单
	 */
	public function billing_details() {

		$where = '';		
		$infos = array();
		foreach(L('select') as $key=>$value) {
			$trade_status[$key] = $value;
		}

		if(isset($_GET['dosubmit'])){
			$info = $_GET['info'];
		$sql = "select t_bs_rebate.*,phpcmsv9.v9_admin.* from phpcmsv9.v9_admin,t_bs_rebate where t_bs_rebate.play_id in (select phpcmsv9.v9_admin.play_id from phpcmsv9.v9_admin where phpcmsv9.v9_admin.parent_id = '$_SESSION[play_id]' or phpcmsv9.v9_admin.pp_id = '$_SESSION[play_id]' or phpcmsv9.v9_admin.play_id = '$_SESSION[play_id]') and t_bs_rebate.play_id = phpcmsv9.v9_admin.play_id and '$info[start_addtime]' < t_bs_rebate.addtime and t_bs_rebate.addtime < '$info[end_addtime]' group by t_bs_rebate.id";

		$qiantao = "select count(1) as count from (select t_bs_rebate.id from phpcmsv9.v9_admin,t_bs_rebate where t_bs_rebate.play_id in (select phpcmsv9.v9_admin.play_id from phpcmsv9.v9_admin where phpcmsv9.v9_admin.parent_id = '$_SESSION[play_id]' or phpcmsv9.v9_admin.pp_id = '$_SESSION[play_id]' or phpcmsv9.v9_admin.play_id = '$_SESSION[play_id]') and '$info[start_addtime]' < t_bs_rebate.addtime and t_bs_rebate.addtime < '$info[end_addtime]' group by t_bs_rebate.id) abc";
$infos = $this->account_db->mylistinfo_qiantao($qiantao,$sql, $page, $pagesize = 20);
		$pages = $this->account_db->pages;
		$number = count($infos);
		include $this->admin_tpl('bill_list');	die;
		}
		$page = $_GET['page'] ? $_GET['page'] : '1';
		//与我相关的账单 指pp_id = 自己的id 或者 parent_id = 自己的id 查询自己的session play id 下的 play_id
		$sql = "select t_bs_rebate.*,phpcmsv9.v9_admin.wechat,phpcmsv9.v9_admin.roleid from phpcmsv9.v9_admin,t_bs_rebate where t_bs_rebate.play_id in (select phpcmsv9.v9_admin.play_id from phpcmsv9.v9_admin where phpcmsv9.v9_admin.parent_id = '$_SESSION[play_id]' or phpcmsv9.v9_admin.pp_id = '$_SESSION[play_id]' or phpcmsv9.v9_admin.play_id = '$_SESSION[play_id]') and t_bs_rebate.play_id = phpcmsv9.v9_admin.play_id group by t_bs_rebate.id";
		// echo $sql;die;
		$qiantao = "select count(1) as count from (select t_bs_rebate.id from phpcmsv9.v9_admin,t_bs_rebate where t_bs_rebate.play_id in (select phpcmsv9.v9_admin.play_id from phpcmsv9.v9_admin where phpcmsv9.v9_admin.parent_id = '$_SESSION[play_id]' or phpcmsv9.v9_admin.pp_id = '$_SESSION[play_id]' or phpcmsv9.v9_admin.play_id = '$_SESSION[play_id]') group by t_bs_rebate.id) abc";
		$infos = $this->account_db->mylistinfo_qiantao($qiantao,$sql, $page, $pagesize = 20);
		$pages = $this->account_db->pages;
		$number = count($infos);
		include $this->admin_tpl('bill_list');	
	}




}
?>