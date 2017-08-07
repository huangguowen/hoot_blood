<?php
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_class('admin', 'admin', 0);
pc_base::load_sys_class('form', '', 0);
class site extends admin {
	    //砖石价格对应表
		public $diamond_arr = array(
        '6' => 1000,
        '30' => 1001,
        '128' => 1002,
        '328' => 1003,
        '618' => 1004
      );
	private $db;
	public function __construct() {
		$this->account_db = pc_base::load_model('user_model');
		$this->player_db = pc_base::load_model('player_model');
		$this->changemoney_db = pc_base::load_model('changemoney_model');
		parent::__construct();
	}
	
	public function init() {
	if(isset($_GET['info'])){
		$start_time = isset($_GET['info']['start_time']) ? $_GET['info']['start_time'] : '';
		$end_time = isset($_GET['info']['end_time']) ? $_GET['info']['end_time'] :  date('Y-m-d', SYS_TIME);
		$type = isset($_GET['info']['type']) ? $_GET['info']['type'] : '';
		$status = isset($_GET['info']['status']) ? $_GET['info']['status'] : '';
		$keyword = isset($_GET['info']['keyword']) ? $_GET['info']['keyword'] : '';
		$fangka = isset($_GET['info']['fangka']) ? $_GET['info']['fangka'] : '';

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
			//玩家注册时间暂时不用了
			// $where .= "`regdate` BETWEEN '$where_start_time' AND '$where_end_time' AND ";

			//关键字
			if($keyword) {
					$where .= "and (b.nickname like '%$keyword%' or a.id = '$keyword' or a.account like '%$keyword%')";
			}
			//房卡数量消耗大于
			if($fangka) {
					$where .= "and (b.fangka_cnt >= $fangka)";
			}
			if($status) {
					$where .= "and (a.status = '$status')";
			}
			$where = "select a.*,b.* from t_dz_account a,t_dz_player b where a.id = b.id $where";
			$page = isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : 1;
			$list = $this->player_db->mylistinfo($where,$page);
			$pages = $this->player_db->pages;
			$show_dialog = true;
			include $this->admin_tpl('site_list');
			die;
	}
		$where = "select a.*,b.* from t_dz_account a,t_dz_player b where a.id = b.id";
		$page = isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : 1;
		$list = $this->player_db->mylistinfo($where,$page);
		$pages = $this->player_db->pages;
/*该段代码弃用
		$data = $this->player_db->get_player_data(1);
		$page = isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : 1;
		$pagesize = 10;
		$offset = ($page - 1) * $pagesize;
			$sql = "select t_dz_account.register_time,t_dz_player.id,t_dz_player.nickname,t_dz_account.account,t_dz_player.image,t_dz_player.diamond,t_dz_player.coins,t_dz_player.last_login_time,t_dz_player.last_login_ip,t_dz_account.status from t_dz_account,t_dz_player where t_dz_account.id = t_dz_player.id group by id order by t_dz_player.id desc limit $offset,$pagesize";
			$list = $this->player_db->get_array_by_sql($sql);
			$sqlnolimt = "select t_dz_account.register_time,t_dz_player.id,t_dz_player.nickname,t_dz_account.account,t_dz_player.image,t_dz_player.diamond,t_dz_player.coins,t_dz_player.last_login_time,t_dz_player.last_login_ip,t_dz_account.status from t_dz_account,t_dz_player where t_dz_account.id = t_dz_player.id group by id order by t_dz_player.id desc";
		$total = $this->player_db->get_array_by_sql_count($sqlnolimt);
		$pages = pages($total, $page, $pagesize);
*/
		$show_dialog = true;
		include $this->admin_tpl('site_list');
	}

	function edit(){
		//根据get的id查询 别名 和ID
		$userid = $_GET['userid'];
		$where = "id = '$userid'";
		$res = $this->player_db->get_one($where);
		include $this->admin_tpl('site_edit');
	}


 //表单提交
      public function change_diamonds_form(){
        // print_r($_POST);die;
        $table = 't_dz_change_money';

        $this->load->model('User_model','usermodel');
       
        $data  = array('diamond'=>$_POST['diamond'],'content'=>$_POST['content'],'coins'=>0,'player_id'=>$_POST['id'],'change_time'=>date('Y-m-d H:i:s',time()),'change_type'=>$_POST['diamondset'],'change_user'=>$_SESSION['login_name']);
      
          //insert t_dz_change_money 记录表
          $inert_diamond = $this->usermodel->insert($data,$table,true);
          //update t_dz_player diamond filed
          /* 不用更新数据库了
          if($_POST['diamondset'] == -1){
             $update_player = $this->usermodel->filedchange_reduce('diamond','t_dz_player',array('id'=>$_POST['id']),$_POST['diamond']);
          }
            else{
             $update_player = $this->usermodel->filedchange_add('diamond','t_dz_player',array('id'=>$_POST['id']),$_POST['diamond']);
          }
          */
         //改为直接增加到unfinest
         //支付类型 2代表apple 14代表微信
         $nickname = $this->usermodel->getnickbyid($_POST['id']);
         $ip = getIp();
         $sql = "select max(id) from t_dz_consume_history";
         $pid = $this->usermodel->getOne($sql);
         $pay_type = 99;
         $order_time = date('Y-m-d H:i:s',time());
         $product_id = get_arrvalue_bykey($this->diamond_arr,$_POST['diamond']);

         //订单号规则
         $consume_num = date('Ymd',time()).'-'.$pay_type.'-'.'PC-'.($pid+1);
         
         $data_order = array('consume_number'=>$consume_num,'player_id'=>$_POST['id'],'nickname'=>$nickname,'ip'=>$ip,'channel'=>'PC','pay_type'=>$pay_type,'order_time'=>$order_time,'pay_status'=>2,'deliver_status'=>1,'product_id'=>$product_id,'product_price'=>$_POST['diamond'],'product_count'=>1,'pay'=>$_POST['diamond'],'coins_amount'=>0);
         //添加到unfine table 2S 后更新到正式表中
         $inert_diamond = $this->usermodel->insert($data_order,'t_dz_unfinished_consume',true);
         echo "<div align='center' style='color:#F00 ; font-size:40px ; height=70px' >更新成功！请重新载入该页面<div>";
      }

	//获取clientIP
    function getIp() { 
    if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown")) $ip = getenv("HTTP_CLIENT_IP"); 
    else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown")) $ip = getenv("HTTP_X_FORWARDED_FOR"); 
    else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown")) $ip = getenv("REMOTE_ADDR"); 
    else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown")) $ip = $_SERVER['REMOTE_ADDR']; 
    else $ip = "unknown"; 
    return ($ip); 
} 

	//根据键值返回建民
	function get_arrvalue_bykey($arr,$key){
			foreach($arr as $k=>$v){
				if($key == $k){
					return $v;
				}
			}
	}

	function edit_form(){
		$data  = array('diamond'=>$_POST['num'],'content'=>$_POST['comment'],'coins'=>0,'player_id'=>$_POST['id'],'change_time'=>date('Y-m-d H:i:s',time()),'change_type'=>1,'change_user'=>'admin');
		$id = $this->changemoney_db->insert($data);
		if($id == 1){
				$this->changemoney_db = pc_base::load_model('unfinished_model');
			$sql = "id = '$_POST[id]'";
			$res = $this->player_db->get_one($sql);
			$nickname = $res['nickname'];
			$ip = $this->getIp();

		 $sql = "select max(id) as max from t_dz_consume_history";
         $pid = $this->player_db->get_one_by_sql($sql);
        $pid = $pid['max'];
         $pay_type = 99;
         $order_time = date('Y-m-d H:i:s',time());
         $product_id = $this->get_arrvalue_bykey($this->diamond_arr,$_POST['num']);

         	 //订单号规则
         $consume_num = date('Ymd',time()).'-'.$pay_type.'-'.'PC-'.($pid+1);
         $data_order = array('consume_number'=>$consume_num,'player_id'=>$_POST['id'],'nickname'=>$nickname,'ip'=>$ip,'channel'=>'PC','pay_type'=>$pay_type,'order_time'=>$order_time,'pay_status'=>2,'deliver_status'=>1,'product_id'=>$product_id,'product_price'=>$_POST['num'],'product_count'=>1,'pay'=>$_POST['num'],'coins_amount'=>0);

         $id = $this->changemoney_db->insert($data_order);

		if($id == 1){
			showmessage('添加成功!', HTTP_REFERER, '', 'init');die;
		}else{
			showmessage('添加失败!', HTTP_REFERER, '', 'init');die;
		}
		}
	}

		/**
	 * 会员搜索
	 */
	function search() {
print_r($_GET);
		//搜索框
		$start_time = isset($_GET['start_time']) ? $_GET['start_time'] : '';
		$end_time = isset($_GET['end_time']) ? $_GET['end_time'] :  date('Y-m-d', SYS_TIME);
		$type = isset($_GET['type']) ? $_GET['type'] : '';
		$status = isset($_GET['status']) ? $_GET['status'] : '';
		$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
		$fangka = isset($_GET['fangka']) ? $_GET['fangka'] : '';
		//站点信息
		// $sitelistarr = getcache('sitelist', 'commons');
		// $siteid = isset($_GET['siteid']) ? intval($_GET['siteid']) : '0';
		// foreach ($sitelistarr as $k=>$v) {
		// 	$sitelist[$k] = $v['name'];
		// }
		
		
		//会员所属模型		
		// $modellistarr = getcache('member_model', 'commons');
		// foreach ($modellistarr as $k=>$v) {
		// 	$modellist[$k] = $v['name'];
		// }
				
		if (isset($_GET['search'])) {
			
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
			//玩家注册时间暂时不用了
			// $where .= "`regdate` BETWEEN '$where_start_time' AND '$where_end_time' AND ";

			//关键字
			if($keyword) {
					$where .= "`b.nickname` like '%$keyword%' or 'a.id' = '$keyword' ";
			}
			

echo $where;die;
		$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$memberlist = $this->player_db->listinfo($where, 'userid DESC', $page, 15);
		//查询会员头像
		foreach($memberlist as $k=>$v) {
			$memberlist[$k]['avatar'] = get_memberavatar($v['phpssouid']);
		}
		$pages = $this->player_db->pages;
		$big_menu = array('?m=member&c=member&a=manage&menuid=879', L('member_research'));
		include $this->admin_tpl('member_list');
	}
}
	
	public function add() {
		header("Cache-control: private"); 
		if (isset($_GET['show_header'])) $show_header = 1;
		if (isset($_POST['dosubmit'])) {
			$name = isset($_POST['name']) && trim($_POST['name']) ? trim($_POST['name']) : showmessage(L('site_name').L('empty'));
			$dirname = isset($_POST['dirname']) && trim($_POST['dirname']) ? strtolower(trim($_POST['dirname'])) : showmessage(L('site_dirname').L('empty'));
			$domain = isset($_POST['domain']) && trim($_POST['domain']) ? trim($_POST['domain']) : '';
			$site_title = isset($_POST['site_title']) && trim($_POST['site_title']) ? trim($_POST['site_title']) : '';
			$keywords = isset($_POST['keywords']) && trim($_POST['keywords']) ? trim($_POST['keywords']) : '';
			$description = isset($_POST['description']) && trim($_POST['description']) ? trim($_POST['description']) : '';
			$release_point = isset($_POST['release_point']) ? $_POST['release_point'] : '';
			$template = isset($_POST['template']) && !empty($_POST['template']) ? $_POST['template'] : showmessage(L('please_select_a_style'));
			$default_style = isset($_POST['default_style']) && !empty($_POST['default_style']) ? $_POST['default_style'] : showmessage(L('please_choose_the_default_style'));			   
			if ($this->db->get_one(array('name'=>$name), 'siteid')) {
				showmessage(L('site_name').L('exists'));
			} 
			if (!preg_match('/^\\w+$/i', $dirname)) {
				showmessage(L('site_dirname').L('site_dirname_err_msg'));
			}
			if ($this->db->get_one(array('dirname'=>$dirname), 'siteid')) {
				showmessage(L('site_dirname').L('exists'));
			}
			if (!empty($domain) && !preg_match('/http:\/\/(.+)\/$/i', $domain)) {
				showmessage(L('site_domain').L('site_domain_ex2'));
			}
			if (!empty($domain) && $this->db->get_one(array('domain'=>$domain), 'siteid')) {
				showmessage(L('site_domain').L('exists'));
			}
			if (!empty($release_point) && is_array($release_point)) {
				if (count($release_point) > 4) {
					showmessage(L('release_point_configuration').L('most_choose_four'));
				}
				$s = '';
				foreach ($release_point as $key=>$val) {
					if($val) $s.= $s ? ",$val" : $val;
				}
				$release_point = $s;
				unset($s);
			} else {
				$release_point = '';
			}
			if (!empty($template) && is_array($template)) {
				$template = implode(',', $template);
			} else {
				$template = '';
			}
			$_POST['setting']['watermark_img'] = IMG_PATH.'water/'.$_POST['setting']['watermark_img'];
			$setting = trim(array2string($_POST['setting']));
			if ($this->db->insert(array('name'=>$name,'dirname'=>$dirname, 'domain'=>$domain, 'site_title'=>$site_title, 'keywords'=>$keywords, 'description'=>$description, 'release_point'=>$release_point, 'template'=>$template,'setting'=>$setting, 'default_style'=>$default_style))) {
				$class_site = pc_base::load_app_class('sites');
				$class_site->set_cache();
				showmessage(L('operation_success'), '?m=admin&c=site&a=init', '', 'add');
			} else {
				showmessage(L('operation_failure'));
			}
		} else {
			$release_point_db = pc_base::load_model('release_point_model');
			$release_point_list = $release_point_db->select('', 'id, name');
			$show_validator = $show_scroll = $show_header = true;
			$template_list = template_list();
			include $this->admin_tpl('site_add');
		}
	}
	
	public function del() {
		$siteid = isset($_GET['siteid']) && intval($_GET['siteid']) ? intval($_GET['siteid']) : showmessage(L('illegal_parameters'), HTTP_REFERER);
		if($siteid==1) showmessage(L('operation_failure'), HTTP_REFERER);
		if ($this->db->get_one(array('siteid'=>$siteid))) {
			if ($this->db->delete(array('siteid'=>$siteid))) {
				$class_site = pc_base::load_app_class('sites');
				$class_site->set_cache();
				showmessage(L('operation_success'), HTTP_REFERER);
			} else {
				showmessage(L('operation_failure'), HTTP_REFERER);
			}
		} else {
			showmessage(L('notfound'), HTTP_REFERER);
		}
	}
	
	// public function edit() {
	// 	$siteid = isset($_GET['siteid']) && intval($_GET['siteid']) ? intval($_GET['siteid']) : showmessage(L('illegal_parameters'), HTTP_REFERER);
	// 	if ($data = $this->db->get_one(array('siteid'=>$siteid))) {
	// 		if (isset($_POST['dosubmit'])) {
	// 			$name = isset($_POST['name']) && trim($_POST['name']) ? trim($_POST['name']) : showmessage(L('site_name').L('empty'));
	// 			$dirname = isset($_POST['dirname']) && trim($_POST['dirname']) ? strtolower(trim($_POST['dirname'])) : ($siteid == 1 ? '' :showmessage(L('site_dirname').L('empty')));
	// 			$domain = isset($_POST['domain']) && trim($_POST['domain']) ? trim($_POST['domain']) : '';
	// 			$site_title = isset($_POST['site_title']) && trim($_POST['site_title']) ? trim($_POST['site_title']) : '';
	// 			$keywords = isset($_POST['keywords']) && trim($_POST['keywords']) ? trim($_POST['keywords']) : '';
	// 			$description = isset($_POST['description']) && trim($_POST['description']) ? trim($_POST['description']) : '';
	// 			$release_point = isset($_POST['release_point']) ? $_POST['release_point'] : '';
	// 			$template = isset($_POST['template']) && !empty($_POST['template']) ? $_POST['template'] : showmessage(L('please_select_a_style'));
	// 			$default_style = isset($_POST['default_style']) && !empty($_POST['default_style']) ? $_POST['default_style'] : showmessage(L('please_choose_the_default_style'));	
	// 			if ($data['name'] != $name && $this->db->get_one(array('name'=>$name), 'siteid')) {
	// 				showmessage(L('site_name').L('exists'));
	// 			}
	// 			if ($siteid != 1) {
	// 				if (!preg_match('/^\\w+$/i', $dirname)) {
	// 					showmessage(L('site_dirname').L('site_dirname_err_msg'));
	// 				}
	// 				if ($data['dirname'] != $dirname && $this->db->get_one(array('dirname'=>$dirname), 'siteid')) {
	// 					showmessage(L('site_dirname').L('exists'));
	// 				}
	// 			} 
				
	// 			if (!empty($domain) && !preg_match('/http:\/\/(.+)\/$/i', $domain)) {
	// 				showmessage(L('site_domain').L('site_domain_ex2'));
	// 			}
	// 			if (!empty($domain) && $data['domain'] != $domain && $this->db->get_one(array('domain'=>$domain), 'siteid')) {
	// 				showmessage(L('site_domain').L('exists'));
	// 			}
	// 			if (!empty($release_point) && is_array($release_point)) {
	// 				if (count($release_point) > 4) {
	// 					showmessage(L('release_point_configuration').L('most_choose_four'));
	// 				}
	// 				$s = '';
	// 				foreach ($release_point as $key=>$val) {
	// 					if($val) $s.= $s ? ",$val" : $val;
	// 				}
	// 				$release_point = $s;
	// 				unset($s);
	// 			} else {
	// 				$release_point = '';
	// 			}
	// 			if (!empty($template) && is_array($template)) {
	// 				$template = implode(',', $template);
	// 			} else {
	// 				$template = '';
	// 			}
	// 			$_POST['setting']['watermark_img'] = 'statics/images/water/'.$_POST['setting']['watermark_img'];
	// 			$setting = trim(array2string($_POST['setting']));
	// 			$sql = array('name'=>$name,'dirname'=>$dirname, 'domain'=>$domain, 'site_title'=>$site_title, 'keywords'=>$keywords, 'description'=>$description, 'release_point'=>$release_point, 'template'=>$template, 'setting'=>$setting, 'default_style'=>$default_style);
	// 			if ($siteid == 1) unset($sql['dirname']);
	// 			if ($this->db->update($sql, array('siteid'=>$siteid))) {
	// 				$class_site = pc_base::load_app_class('sites');
	// 				$class_site->set_cache();
	// 				showmessage(L('operation_success'), '', '', 'edit');
	// 			} else {
	// 				showmessage(L('operation_failure'));
	// 			}
	// 		} else {
	// 			$show_validator = true;
	// 			$show_header = true;
	// 			$show_scroll = true;
	// 			$template_list = template_list();
	// 			$setting = string2array($data['setting']);
	// 			$setting['watermark_img'] = str_replace('statics/images/water/','',$setting['watermark_img']);
	// 			$release_point_db = pc_base::load_model('release_point_model');
	// 			$release_point_list = $release_point_db->select('', 'id, name');
	// 			include $this->admin_tpl('site_edit');
	// 		}
	// 	} else {
	// 		showmessage(L('notfound'), HTTP_REFERER);
	// 	}
	// }
	
	public function public_name() {
		$name = isset($_GET['name']) && trim($_GET['name']) ? (pc_base::load_config('system', 'charset') == 'gbk' ? iconv('utf-8', 'gbk', trim($_GET['name'])) : trim($_GET['name'])) : exit('0');
		$siteid = isset($_GET['siteid']) && intval($_GET['siteid']) ? intval($_GET['siteid']) : '';
 		$data = array();
		if ($siteid) {
			
			$data = $this->db->get_one(array('siteid'=>$siteid), 'name');
			if (!empty($data) && $data['name'] == $name) {
				exit('1');
			}
		}
		if ($this->db->get_one(array('name'=>$name), 'siteid')) {
			exit('0');
		} else {
			exit('1');
		}
	}
	
	public function public_dirname() {
		$dirname = isset($_GET['dirname']) && trim($_GET['dirname']) ? (pc_base::load_config('system', 'charset') == 'gbk' ? iconv('utf-8', 'gbk', trim($_GET['dirname'])) : trim($_GET['dirname'])) : exit('0');
		$siteid = isset($_GET['siteid']) && intval($_GET['siteid']) ? intval($_GET['siteid']) : '';
		$data = array();
		if ($siteid) {
			$data = $this->db->get_one(array('siteid'=>$siteid), 'dirname');
			if (!empty($data) && $data['dirname'] == $dirname) {
				exit('1');
			}
		}
		if ($this->db->get_one(array('dirname'=>$dirname), 'siteid')) {
			exit('0');
		} else {
			exit('1');
		}
	}

	private function check_gd() {
		if(!function_exists('imagepng') && !function_exists('imagejpeg') && !function_exists('imagegif')) {
			$gd = L('gd_unsupport');
		} else {
			$gd = L('gd_support');
		}
		return $gd;
	}
}