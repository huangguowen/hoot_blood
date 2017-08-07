<?php
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_class('admin','admin',0);
pc_base::load_app_func('admin');
class admin_manage extends admin {
	private $db,$role_db;
	function __construct() {
		parent::__construct();
		$this->db = pc_base::load_model('admin_model');
		//加载play table
		$this->account_db = pc_base::load_model('account_model');
		$this->role_db = pc_base::load_model('admin_role_model');
		$this->spread_db = pc_base::load_model('spread_relation_model');
		$this->op = pc_base::load_app_class('admin_op');
	}
	
	/**
	 * 管理员管理列表(已经注册)
	 */
	public function init() {
		$userid = $_SESSION['userid'];
		$admin_username = param::get_cookie('admin_username');
		$page = $_GET['page'] ? intval($_GET['page']) : '1';
		if($_SESSION['userid'] != 1){
		//根据userid查询play_id
		$parent_id = $this->db->get_play_id_for_userid($_SESSION['userid']);
		$where = "parent_id = $parent_id or pp_id = $parent_id";
	}else{
		$where = '';
	}
		$infos = $this->db->listinfo($where, '', $page, 10);
		$pages = $this->db->pages;
		$roles = getcache('role','commons');
		include $this->admin_tpl('admin_list');
	}


	/**
	 * 管理员管理列表 (未注册)
	 */
	public function edit_n() {
		$userid = $_SESSION['userid'];
		$admin_username = param::get_cookie('admin_username');
		$page = $_GET['page'] ? intval($_GET['page']) : '1';
		$play_id = $this->db->get_play_id_for_userid($_SESSION['userid']);
		$where = "spread_player_extern_id = $play_id and isset = 0";
		$infos = $this->spread_db->listinfo($where, '', $page, 10);
		// print_r($infos);die;
		$pages = $this->db->pages;
		$roles = getcache('role','commons');
		include $this->admin_tpl('admin_list_n');
	}
	
	
//返利比例
	public function fanli(){
			$roles = $this->role_db->select(array('disabled'=>'0'));
			if($_SESSION['roleid'] == 1){
			}elseif($_SESSION['roleid'] == 2){
				unset($roles[0]);
				unset($roles[1]);
				
			}elseif($_SESSION['roleid'] == 3){
			unset($roles[0]);
				unset($roles[1]);
				unset($roles[2]);
			}else{
			unset($roles[0]);
			unset($roles[2]);
				unset($roles[1]);
				unset($roles[3]);
			}
			$admin_manage_code = $this->get_admin_manage_code();
			include $this->admin_tpl('admin_add_fanli');
	}

	/**
	 * 添加管理员
	 */
	public function add() {
		if(isset($_POST['dosubmit'])) {
			if($this->check_admin_manage_code()==false){
				showmessage("error auth code");
			}
			$info = array();
			if(!$this->op->checkname($_POST['info']['phone'])){
				showmessage(L('admin_already_exists'));
			}
			$info = checkuserinfo($_POST['info']);		
			if(!checkpasswd($info['password'])){
				showmessage(L('pwd_incorrect'));
			}
			//判断是否已经是代理了
			$chk = $this->db->check_agent_by_id($_POST['info']['play_id']);
			if($chk != 0){
				showmessage('该玩家已经是代理了！');
			}
			$passwordinfo = password($info['password']);
			$info['password'] = $passwordinfo['password'];
			$info['encrypt'] = $passwordinfo['encrypt'];
			//拼接字符串
			$info['bank_place'] = $_POST['info']['province'].$_POST['info']['city'].$_POST['info']['area'];
				//根据玩家ID查询 邀请码
	   		$sql = "select extern_id from t_dz_player where id = '$info[play_id]'";
	   		$play = $this->account_db->get_one_by_sql($sql);
	   		$extern_id = $play['extern_id'];
	   		$info['wechat'] = $_POST['info']['phone'];
			$info['code'] = $extern_id;
			//手机号作为登陆账号
			$info['username'] = $info['wechat'];
			if($info['url'] == 72){
				$url = "?m=agent&c=agent&a=agent_list_1";
			}elseif($info['url'] == 839){
				$url = "?m=agent&c=agent&a=agent_list_3";
			}elseif($info['url'] == 74){
				$url = "?m=agent&c=agent&a=agent_list_2";
			}else{
				$url = "?m=admin&c=admin_manage";
			}
			//加入的邀请码 查询 用户，给agent_account+1
			$join_code = $this->db->get_code_foruserid($_SESSION['userid']);
			//根据userid查询 play_id
			$info['parent_id'] = $this->db->get_play_id_for_userid($_SESSION['userid']);
			//注册时间
			$info['register_time'] = date('Y-m-d H:i:s',time());
			$admin_fields = array('username', 'email', 'password', 'encrypt','roleid','realname','play_id','code','parent_id','wechat','bank_code','bank_place','bank_name','register_time');
			foreach ($info as $k=>$value) {
				if (!in_array($k, $admin_fields)){
					unset($info[$k]);
				}
			}
			//如果是二级提三级 三级会有一个pp_id 一级 代理
			$sql = "select roleid from phpcmsv9.v9_admin where userid = '$_SESSION[userid]'";
	   		$sb = $this->account_db->get_one_by_sql($sql);
	   		$roleid = $sb['roleid'];
	   		//如果是2级代理拉三级
	   		if($roleid == 3){
	   			$pid = $this->account_db->get_parent_id_by_id($_SESSION['userid']);
	   			$info['pp_id'] = $pid;
	   		}

	
			//会员数+1
		$this->db->update_agent_acount($info['parent_id']);
			//admin 表加入成功
		$table = $this->db->insert($info);
		// //根据userid 查询paly_id
		// $play_id = $this->db->get_play_id_for_userid($_SESSION['userid']);
		// 	$content = array('spread_player_extern_id' => $play_id,'player_extern_id' => $info['play_id'],'spread_time' => date('Y-m-d H:i:s',time()),'spread_type' => 1,'isset' => 1);

		// 		$ins = $this->spread_db->insert($content);

			if($table){
				showmessage(L('operation_success'),"$url");
			}
		} else {
			$roles = $this->role_db->select(array('disabled'=>'0'));
			if($_SESSION['roleid'] == 1){
			}elseif($_SESSION['roleid'] == 2){
				unset($roles[0]);
				unset($roles[1]);
				
			}elseif($_SESSION['roleid'] == 3){
			unset($roles[0]);
				unset($roles[1]);
				unset($roles[2]);
			}else{
			unset($roles[0]);
			unset($roles[2]);
				unset($roles[1]);
				unset($roles[3]);
			}
			$admin_manage_code = $this->get_admin_manage_code();
			include $this->admin_tpl('admin_add');
		}
		
	}
	
	/**
	 * 修改管理员
	 */
	public function edit() {
		//先判断是否需要被自己（2级代理以上编辑）
		
		if(isset($_POST['dosubmit'])) {
			if($this->check_admin_manage_code()==false){
				showmessage("error auth code");
			}
			$memberinfo = $info = array();			
			$info = checkuserinfo_edit($_POST['info']);
			if(isset($info['password']) && !empty($info['password']))
			{
				$this->op->edit_password($info['userid'], $info['password']);
			}
			$userid = $info['userid'];
			//限制被edit的字段
			$admin_fields = array('username','roleid','realname');
			foreach ($info as $k=>$value) {
				if (!in_array($k, $admin_fields)){
					unset($info[$k]);
				}
			}
			$this->db->update($info,array('userid'=>$userid));
			showmessage(L('operation_success'),'','','edit');
		} else {					
			$info = $this->db->get_one(array('userid'=>$_GET['userid']));
			extract($info);	
			$roles = $this->role_db->select(array('disabled'=>'0'));	
			$show_header = true;
			$admin_manage_code = $this->get_admin_manage_code();
			include $this->admin_tpl('admin_edit');		
		}
	}
	
	/**
	 * 删除管理员
	 */
	public function delete() {
		$userid = intval($_GET['userid']);
		if($userid == '1') showmessage(L('this_object_not_del'), HTTP_REFERER);
		$this->db->delete(array('userid'=>$userid));
		showmessage(L('admin_cancel_succ'));
	}
	
	/**
	 * 更新管理员状态
	 */
	public function lock(){
		$userid = intval($_GET['userid']);
		$disabled = intval($_GET['disabled']);
		$this->db->update(array('disabled'=>$disabled),array('userid'=>$userid));
		showmessage(L('operation_success'),'?m=admin&c=admin_manage');
	}
	
	/**
	 * 管理员自助修改密码
	 */
	public function public_edit_pwd() {
		$userid = $_SESSION['userid'];
		if(isset($_POST['dosubmit'])) {
			$r = $this->db->get_one(array('userid'=>$userid),'password,encrypt');
			if ( password($_POST['old_password'],$r['encrypt']) !== $r['password'] ) showmessage(L('old_password_wrong'),HTTP_REFERER);
			if(isset($_POST['new_password']) && !empty($_POST['new_password'])) {
				$this->op->edit_password($userid, $_POST['new_password']);
			}
			showmessage(L('password_edit_succ_logout'),'?m=admin&c=index&a=public_logout');			
		} else {
			$info = $this->db->get_one(array('userid'=>$userid));
			extract($info);
			include $this->admin_tpl('admin_edit_pwd');			
		}

	}
	/*
	 * 编辑用户信息
	 */
	public function public_edit_info() {
		$userid = $_SESSION['userid'];
		if(isset($_POST['dosubmit'])) {
			$admin_fields = array('email','realname','lang');
			$info = array();
			$info = $_POST['info'];
			if(trim($info['lang'])=='') $info['lang'] = 'zh-cn';
			foreach ($info as $k=>$value) {
				if (!in_array($k, $admin_fields)){
					unset($info[$k]);
				}
			}
			$this->db->update($info,array('userid'=>$userid));
			param::set_cookie('sys_lang', $info['lang'],SYS_TIME+86400*30);
			showmessage(L('operation_success'),HTTP_REFERER);			
		} else {
			$info = $this->db->get_one(array('userid'=>$userid));
			extract($info);
			
			$lang_dirs = glob(PC_PATH.'languages/*');
			$dir_array = array();
			foreach($lang_dirs as $dirs) {
				$dir_array[] = str_replace(PC_PATH.'languages/','',$dirs);
			}
			include $this->admin_tpl('admin_edit_info');			
		}	
	
	}
	/**
	 * 异步检测用户名
	 */
	function public_checkname_ajx() {
		$username = isset($_GET['username']) && trim($_GET['username']) ? trim($_GET['username']) : exit(0);
		if ($this->db->get_one(array('username'=>$username),'userid')){
			exit('0');
		}
		exit('1');
	}

		/**
	 * 异步检测用户PLAY_id
	 */
	function public_checkplay_id_ajx() {
		$extern_id = isset($_GET['extern_id']) && trim($_GET['extern_id']) ? trim($_GET['extern_id']) : exit(0);
		$sql = "select b.account from t_dz_player a,t_dz_account b where a.extern_id = '$extern_id' and a.id = b.id";
		if ($account = $this->account_db->get_one_by_sql($sql)){
			exit($account['account']);
		}
		exit('0');
	}

	/**
	 * 异步检测密码
	 */
	function public_password_ajx() {
		$userid = $_SESSION['userid'];
		$r = array();
		$r = $this->db->get_one(array('userid'=>$userid),'password,encrypt');
		if ( password($_GET['old_password'],$r['encrypt']) == $r['password'] ) {
			exit('1');
		}
		exit('0');
	}
	/**
	 * 异步检测emial合法性
	 */
	function public_email_ajx() {
		$email = $_GET['email'];
		$userid = $_SESSION['userid'];
		$check = $this->db->get_one(array('email'=>$email),'userid');
		if ($check && $check['userid']!=$userid){
			exit('0');
		}else{
			exit('1');
		}
 	}

	//电子口令卡
	function card() {
		if (pc_base::load_config('system', 'safe_card') != 1) {
			showmessage(L('your_website_opened_the_card_no_password'));
		}
		$userid = isset($_GET['userid']) && intval($_GET['userid']) ? intval($_GET['userid']) : showmessage(L('user_id_cannot_be_empty'));
		$data = array();
		if ($data = $this->db->get_one(array('userid'=>$userid), '`card`,`username`')) {
			$pic_url = '';
			if ($data['card']) {
				pc_base::load_app_class('card', 'admin', 0);
				$pic_url = card::get_pic($data['card']);
			}
			$show_header = true;
			include $this->admin_tpl('admin_card');
		} else {
			showmessage(L('users_were_not_found'));
		}
	}
	
	//绑定电子口令卡
	function creat_card() {
		if (pc_base::load_config('system', 'safe_card') != 1) {
			showmessage(L('your_website_opened_the_card_no_password'));
		}
		$userid = isset($_GET['userid']) && intval($_GET['userid']) ? intval($_GET['userid']) : showmessage(L('user_id_cannot_be_empty'));
		$data = $card = '';
		if ($data = $this->db->get_one(array('userid'=>$userid), '`card`,`username`')) {
			if (empty($data['card'])) {
				pc_base::load_app_class('card', 'admin', 0);
				$card = card::creat_card();
				if ($this->db->update(array('card'=>$card), array('userid'=>$userid))) {
					showmessage(L('password_card_application_success'), '?m=admin&c=admin_manage&a=card&userid='.$userid);
				} else {
					showmessage(L('a_card_with_a_local_database_please_contact_the_system_administrators'));
				}
			} else {
				showmessage(L('please_lift_the_password_card_binding'),HTTP_REFERER);
			}
		} else {
			showmessage(L('users_were_not_found'));
		}
	}
	
	//解除口令卡绑定
	function remove_card() {
		if (pc_base::load_config('system', 'safe_card') != 1) {
			showmessage(L('your_website_opened_the_card_no_password'));
		}
		$userid = isset($_GET['userid']) && intval($_GET['userid']) ? intval($_GET['userid']) : showmessage(L('user_id_cannot_be_empty'));
		$data = $result = '';
		if ($data = $this->db->get_one(array('userid'=>$userid), '`card`,`username`,`userid`')) {
			pc_base::load_app_class('card', 'admin', 0);
			if ($result = card::remove_card($data['card'])) {
					$this->db->update(array('card'=>''), array('userid'=>$userid));
					showmessage(L('the_binding_success'), '?m=admin&c=admin_manage&a=card&userid='.$userid);
			}
		} else {
			showmessage(L('users_were_not_found'));
		}
	}
	//添加修改用户 验证串验证
	private function check_admin_manage_code(){
		$admin_manage_code = $_POST['info']['admin_manage_code'];
		$pc_auth_key = md5(pc_base::load_config('system','auth_key').'adminuser');
		$admin_manage_code = sys_auth($admin_manage_code, 'DECODE', $pc_auth_key);	
		if($admin_manage_code==""){
			return false;
		}
		$admin_manage_code = explode("_", $admin_manage_code);
		if($admin_manage_code[0]!="adminuser" || $admin_manage_code[1]!=$_POST[pc_hash]){
			return false;
		}
		return true;
	}
	//添加修改用户 生成验证串
	private function get_admin_manage_code(){
		$pc_auth_key = md5(pc_base::load_config('system','auth_key').'adminuser');
		$code = sys_auth("adminuser_".$_GET[pc_hash]."_".time(), 'ENCODE', $pc_auth_key);
		return $code;
	}	
}
?>