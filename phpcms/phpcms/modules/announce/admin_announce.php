<?php
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_class('admin','admin',0);

class admin_announce extends admin {

	private $db; public $username;
	public function __construct() {
		parent::__construct();
		//if (!module_exists(ROUTE_M)) showmessage(L('module_not_exists'));
		$this->username = param::get_cookie('admin_username');
		$this->db = pc_base::load_model('announce_model');
	}
	
	public function init() {
		// 公告列表
		$sql = '';
		$page = max(intval($_GET['page']), 1);
		$data = $this->db->listinfo($sql, '`event_id` DESC', $page);
		$big_menu = array('javascript:window.top.art.dialog({id:\'add\',iframe:\'?m=announce&c=admin_announce&a=add\', title:\''.L('announce_add').'\', width:\'700\', height:\'500\', lock:true}, function(){var d = window.top.art.dialog({id:\'add\'}).data.iframe;var form = d.document.getElementById(\'dosubmit\');form.click();return false;}, function(){window.top.art.dialog({id:\'add\'}).close()});void(0);', L('announce_add'));
		include $this->admin_tpl('announce_list');
	}
	
	/**
	 * 添加公告
	 */
	public function add() {
		if(isset($_POST['dosubmit'])) {
			$_POST['announce'] = $this->check($_POST['announce']);
			//删除默认的一些提交字段
			$_POST['announce']['addtime'] = date('Y-m-d H:i:s',time());
			unset($_POST['announce']['siteid']);
			unset($_POST['announce']['username']);
			// print_r($_POST);die;
			if($this->db->insert($_POST['announce'])) showmessage(L('announcement_successful_added'), HTTP_REFERER, '', 'add');
		} else {
			//获取站点模板信息
			pc_base::load_app_func('global', 'admin');
			$siteid = $this->get_siteid();
			$template_list = template_list($siteid, 0);
			$site = pc_base::load_app_class('sites','admin');
			$info = $site->get_by_id($siteid);
			foreach ($template_list as $k=>$v) {
				$template_list[$v['dirname']] = $v['name'] ? $v['name'] : $v['dirname'];
				unset($template_list[$k]);
			}
			$show_header = $show_validator = $show_scroll = 1;
			pc_base::load_sys_class('form', '', 0);
			include $this->admin_tpl('announce_add');
		}
	}
	
	/**
	 * 修改公告
	 */
	public function edit() {
		if(isset($_POST['dosubmit'])) {	
				$_POST['announce'] = $this->check($_POST['announce'],$a='edit');
			//删除默认的一些提交字段
			$_POST['announce']['addtime'] = date('Y-m-d H:i:s',time());
			unset($_POST['announce']['siteid']);
			unset($_POST['announce']['username']);
			if($this->db->update($_POST['announce'], array('event_id' => $_POST['announce']['event_id']))){ showmessage('更新成功', HTTP_REFERER, '', 'edit');}else{echo '失败';die;}
		} else {
			// $where = array('aid' => $_GET['aid']);
			// $an_info = $this->db->get_one($where);
			// pc_base::load_sys_class('form', '', 0);
			// //获取站点模板信息
			// pc_base::load_app_func('global', 'admin');
			// $template_list = template_list($this->siteid, 0);
			// foreach ($template_list as $k=>$v) {
			// 	$template_list[$v['dirname']] = $v['name'] ? $v['name'] : $v['dirname'];
			// 	unset($template_list[$k]);
			// }
			// $show_header = $show_validator = $show_scroll = 1;
			$announce['event_id'] = $_GET['aid'];
			//event_id 查询 notice具体
			$data = $this->db->get_one(array('event_id' => $announce['event_id']));
			pc_base::load_sys_class('form', '', 0);
			include $this->admin_tpl('announce_edit');
		}
	}
	
	/**
	 * ajax检测公告标题是否重复
	 */
	public function public_check_title() {
		if (!$_GET['title']) exit(0);
		if (CHARSET=='gbk') {
			$_GET['title'] = iconv('UTF-8', 'GBK', $_GET['title']);
		}
		$title = $_GET['title'];
		if ($_GET['aid']) {
			$r = $this->db->get_one(array('id' => $_GET['aid']));
			if ($r['title'] == $title) {
				exit('1');
			}
		} 
		$r = $this->db->get_one(array('siteid' => $this->get_siteid(), 'title' => $title), 'aid');
		if($r['aid']) {
			exit('0');
		} else {
			exit('1');
		}
	}
	
	/**
	 * 批量修改公告状态 使其成为审核、未审核状态
	 */
	public function public_approval($aid = 0) {
		if((!isset($_POST['aid']) || empty($_POST['aid'])) && !$aid) {
			showmessage(L('illegal_operation'));
		} else {
			if(is_array($_POST['aid']) && !$aid) {
				array_map(array($this, 'public_approval'), $_POST['aid']);
				showmessage(L('announce_passed'), HTTP_REFERER);
			} elseif($aid) {
				$aid = intval($aid);
				$this->db->update(array('passed' => $_GET['passed']), array('aid' => $aid));
				return true;
			}
		}
	}
	
	/**
	 * 删除公告
	 */
	public function delete() {
				$id = intval($_GET['id']);
				$res = $this->db->delete(array('event_id' => $id));
				echo $res;die;
	}
	
	/**
	 * 验证表单数据
	 * @param  		array 		$data 表单数组数据
	 * @param  		string 		$a 当表单为添加数据时，自动补上缺失的数据。
	 * @return 		array 		验证后的数据
	 */
	private function check($data = array(), $a = 'add') {
		if($data['title']=='') showmessage(L('title_cannot_empty'));
		if($data['recommended']=='') showmessage(L('announcements_cannot_be_empty'));
		$r = $this->db->get_one(array('title' => $data['title']));
		if (strtotime($data['endtime'])<strtotime($data['starttime'])) {
			$data['endtime'] = '';
		}
		if ($a=='add') {
			if (is_array($r) && !empty($r)) {
				showmessage(L('announce_exist'), HTTP_REFERER);
			}
			$data['siteid'] = $this->get_siteid();
			$data['addtime'] = SYS_TIME;
			$data['username'] = $this->username;
			if ($data['starttime'] == '') $announce['starttime'] = date('Y-m-d');
		} 
		return $data;
	}
}
?>