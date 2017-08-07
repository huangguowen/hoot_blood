<?php 

	/**
	* 日志
	*/
	class Log extends MY_Controller
	{
		
		function __construct()
		{
			parent::__construct();
		}

		function gift_logs(){
			unset($_SESSION['loglist']);
			$this->load->model('Log_model','logmodel');
			$this->load->model('User_model','usermodel');
			$count = $this->logmodel->count("t_dz_change_money");
			$_SESSION['loglist'] = $count;
			   //分页制作(总页数)
         $pageSize = 10;
     	   $page = 1;
      	 $data['pageInfo'] = $this->logmodel->getPageInfo($count,$page,$pageSize);
        $maxPage = intval(($count + $pageSize) / $pageSize);
        $data['maxPage'] = $maxPage;
        $data['pageList'] = $this->logmodel->getPageList($maxPage, $page);

			$data['data'] = $this->logmodel->get_gift_log(10,1);
			foreach($data['data'] as $k=>$v){
				$data['data'][$k]['nickname'] = $this->usermodel->getnickbyid($v['player_id']);
				$data['data'][$k]['username'] = $this->usermodel->getusernamebyid($v['player_id']);
			}
			$this->load->view('hotblood/site/gift_logs',$data);
		}
		function gift_logs_page(){

 $this->load->model('Log_model','logmodel');
 	$this->load->model('User_model','usermodel');
            $data = array();
         $param = $this->uri->segment(3); //获取uri的第3段
          if (!isset($param) || strlen($param) < 1) {
            $page = 1;
        } else {
            $page = $param;
        }
         $maxPage = $_SESSION['loglist'];
       
        $count = $maxPage;
        //分页制作(总页数)
         $pageSize = 10;
          $maxPage = intval(($count + $pageSize) / $pageSize);
       $data['pageInfo'] = $this->logmodel->getPageInfo($count,$page,$pageSize);
        $data['maxPage'] = $maxPage;
        $data['pageList'] = $this->logmodel->getPageList($maxPage, $page);
       $data['data'] = $this->logmodel->get_gift_log(10,$page);
        foreach($data['data'] as $k=>$v){
				$data['data'][$k]['nickname'] = $this->usermodel->getnickbyid($v['player_id']);
				$data['data'][$k]['username'] = $this->usermodel->getusernamebyid($v['player_id']);
			}
         $this->load->view('hotblood/site/gift_logs',$data);
		}
	}
 ?>