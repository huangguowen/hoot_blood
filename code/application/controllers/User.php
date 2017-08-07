<?php 

	/**
	* 
	*/
	class User extends MY_Controller
	{
    //砖石价格对应表
		var $diamond_arr = array(
        '6' => 1000,
        '30' => 1001,
        '128' => 1002,
        '328' => 1003,
        '618' => 1004
      );

    //部门分布（后期要写入数据库中）key(dept_name) value(dept_id)
    var $depts = array(
      //admin
        'admin' => 1,
        //1；增加砖石
        '市场部' => 2,
        '财务部' => 3,
        '人事部' => 4,
        '技术部' => 5
      );

    //部分功能(后期写入到数据库)
    var $func = array(
        '1' => 'add_diamond'
      );

		function __construct()
		{
			parent::__construct();
       if(!isset($_SESSION['user_id'])) {
            redirect('', 'location', 301);
        }
             $this->load->helper(array('form', 'url'));
             $this->load->helper('funshelp');
             $this->load->library('session');
		}

      //增加用户bs
    function add_bsuser(){
      if($this->log_user_id != 1){
          $this->maintenance('您没有这个权限...');die;
      }
        $this->load->model('User_model','usermodel');
          $data = $this->usermodel->get_deptid();
        $data['data'] = $data;
        $this->load->view('hotblood/site/iframe_bs_addUser',$data);
    }
    //form
    function add_bsuser_form(){
            $this->load->model('User_model','usermodel');
     //TUPIAN
         $config['upload_path']      = './uploads/';
        $config['allowed_types']    = 'gif|jpg|png';
        $config['max_size']     = 100;
        $config['max_width']        = 1024;
        $config['max_height']       = 768;

        $this->load->library('upload', $config);

        if ( ! $this->upload->do_upload('userfile'))
        {
           $img = false;
        }
        else
        {
              $data['upload_data']=$this->upload->data();  //文件的一些信息
             $img=$data['upload_data']['file_name'];  //取得文件名

            // $data = array('upload_data' => $this->upload->data());

          
        }
        if(isset($img)){
      $user_data['image'] = base_url().'/uploads/'.$img;
        }
      $user_data['username'] = $_POST['username'];
      $user_data['password'] = md5($_POST['password']);
      if(isset($_POST['sex'])){$user_data['sex'] = 0;};
      $user_data['dept_id'] = $_POST['dept_id'];
      $user_data['status'] = $_POST['status'];
      $user_data['register_time'] = date('Y-m-d H:i:s',time());

      $id = $this->usermodel->insert($user_data,'bs_user',true);

       redirect(base_url('user/add_bsuser'));
    }
  
		function index(){
			if(isset($_SESSION['user_id'])) {
            $this->load->view('hotblood/index');
            // $this->homepage();
        }else{
        	 redirect('', 'location', 301);
        }
			
		}

		 function logout()
    {
        $user_id = $_SESSION['user_id'];
        $type = "out";
        $ip = $this->input->ip_address();
        $logData = array(
            'user_id' => $user_id,
            'login_time' => date("Y-m-d H:i:s"),
            'type' => $type,
            'ip' => $ip,
        );
        //后期可以写入到登出日志
        // $this->userlib->addLoginLog($logData);

        unset($_SESSION['user_id']);
        // print_r($_SESSION);die;
        redirect('', 'location', 301);
    }

    function userinfo(){
        $this->load->view('hotblood/index');
       $this->load->view('hotblood/site/online');
    }


		//默认进来的视图页面
    function homepage(){
      //完成的表
        $fin = 't_dz_consume_history';
        //仅仅生成订单的表
         // $unfin = 't_dz_unfinished_consume'; //不用了


        $this->load->model('User_model','usermodel');
        $data = array();

       
        //玩家注册量
        $sql = 'select id from t_dz_account';
        $players = $this->usermodel->getrows($sql);
        $data['playerscount'] = count($players);
        //俱乐部数量
        $sql = 'select id from t_club';
        $clubs = $this->usermodel->getrows($sql);
        $data['clubscount'] = count($clubs);
        //当前牌局数量
        $sql = 'select table_id from t_dz_gametable where table_state = 2';
        $onlinetable = $this->usermodel->getrows($sql);
        $data['onlinetablecount'] = count($onlinetable);
        //当前在线用户
        $sql = 'select id from t_dz_online_player';
        $onlineplayers = $this->usermodel->getrows($sql);
        $data['onlineplayers'] = count($onlineplayers);
        //今日充值金额以及单数
        $today = date('Y-m-d 00:00:00',time());
        $yesterday = date("Y-m-d 00:00:00",strtotime("-1 day"));
        $tomorrow = date("Y-m-d 00:00:00",strtotime("+1 day")); 
         //今日注册量
        $data['today_register'] = $this->usermodel->getregister_today('t_dz_account',$today,$tomorrow);
        //昨日注册量
        $data['yesterday_register'] = $this->usermodel->getregister_today('t_dz_account',$yesterday,$today);

         $charge_todayfin = $this->usermodel->getallrecharge_today($fin,$today,$tomorrow);
           //首页不显示内部订单金额
        // $charge_todayunfin = $this->usermodel->getallrecharge_today($unfin,$today,$tomorrow);
        // echo $sql;die;
        //今日充值总额
        $data['todayrecharge'] = $charge_todayfin;
        if($data['todayrecharge'] == ''){
          $data['todayrecharge'] = 0;
        }
      
        //今日充值单数
        $count_todayfin = $this->usermodel->getallcount_today($fin,$today,$tomorrow);
        //首页不显示内部订单数量
        // $count_todayunfin = $this->usermodel->getallcount_today($unfin,$today,$tomorrow);

        $data['todayrechargecount'] = $count_todayfin;
        
        //昨日的
         $charge_yesfin = $this->usermodel->getallrecharge_yesterday($fin,$today,$yesterday);
          //首页不显示内部订单金额
        // $charge_yesunfin = $this->usermodel->getallrecharge_yesterday($unfin,$today,$yesterday);
         //昨日充值总额
        $data['yestadaycharge'] = $charge_yesfin;
         if($data['yestadaycharge'] == ''){
          $data['yestadaycharge'] = 0;
        }
        //昨日充值单数
               $count_yesfin = $this->usermodel->getallcount_yesterday($fin,$today,$yesterday);
                 //首页不显示内部订单的数量
        // $count_tyesunfin = $this->usermodel->getallcount_yesterday($unfin,$today,$yesterday);

        $data['yestadayrechargecount'] = $count_yesfin;

       //充值来源(默认是所有的)微信
       $data['paytypewx'] = $this->usermodel->getpaystyle($fin,13);
         // $data['paytypewx_unfin'] = $this->usermodel->getpaystyle($unfin,13);

         // $data['paytypewx'] = $data['paytypewx_fin']+  $data['paytypewx_unfin'];//不用了
           if($data['paytypewx'] == ''){
          $data['paytypewx'] = 0;
        }

        //支付宝
    
        $data['paytypeali'] = $this->usermodel->getpaystyle($fin,10);
      
         // $data['paytypeali_unfin'] = $this->usermodel->getpaystyle($unfin,10);
         // $data['paytypeali'] = $data['paytypeali_fin']+  $data['paytypeali_unfin'];//不用了
          if($data['paytypeali'] == ''){
          $data['paytypeali'] = 0;
        }
      
        //苹果支付
         $data['paytypeap'] = $this->usermodel->getpaystyle($fin,2);
         // $data['paytypeap_unfin'] = $this->usermodel->getpaystyle($unfin,2); 

         // $data['paytypeap'] = $data['paytypeap_fin']+  $data['paytypeap_unfin'];//不用了
          if($data['paytypeap'] == ''){
          $data['paytypeap'] = 0;
        }

          //后台操作
         $data['paytypepc'] = $this->usermodel->getpaystyle($fin,99);
         // $data['paytypeap_unfin'] = $this->usermodel->getpaystyle($unfin,2); 

         // $data['paytypeap'] = $data['paytypeap_fin']+  $data['paytypeap_unfin'];//不用了
          if($data['paytypeap'] == ''){
          $data['paytypeap'] = 0;
        }
    

        //代理审核 
        //赠送数据
        //热门俱乐部
        
        //IP
       $this->load->helper('funshelp');
        $data['ip'] = getIp();
    
    	 $this->load->view('hotblood/site/home',$data);
    }

    //用户列表
    function userlist(){
                    //删除查询变量， 删除查询或默认页数
            unset($_SESSION['userwhere']);

         $this->load->model('User_model','usermodel');
            $data = array();

        //搜索
        //1:用户类型（操作系统platform:默认ios,android）
        $data['ostype'] = "<option value='ios'>ios</option>"."<option value='android'>android</option>";
        //2:用户状态（status字段默认都是1）
        $data['status'] = "<option value='1'>1</option>"."<option value='2'>2</option>"."<option value='0'>0</option>";
        //3:充值1（）
        $data['recharge1'] = "<option value='recharge1'>全部</option>";
        //4:充值2（）
        $data['recharge2'] = "<option value='recharge2'>全部</option>";

        //用户未筛选信息
       
        $sql = "select t_dz_player.id from t_dz_account,t_dz_player where t_dz_account.id = t_dz_player.id group by id";
        //查询当前人是否有充值记录
    // echo $sql;die;
        
        $data = $this->usermodel->getRows($sql);

        $count = count($data);
       
        $_SESSION['userlists'] = $count;
        //分页制作(总页数)
         $pageSize = 10;
        $page = 1;
       $data['pageInfo'] = $this->usermodel->getPageInfo($count,$page,$pageSize);
        $maxPage = intval(($count + $pageSize) / $pageSize);
        $data['maxPage'] = $maxPage;
        $data['pageList'] = $this->usermodel->getPageList($maxPage, $page);

         $sql = "select t_dz_account.register_time,t_dz_player.id,t_dz_player.nickname,t_dz_account.account,t_dz_player.image,t_dz_player.diamond,t_dz_player.coins,t_dz_player.last_login_time,t_dz_player.last_login_ip,t_dz_account.status from t_dz_account,t_dz_player where t_dz_account.id = t_dz_player.id group by id order by t_dz_player.id desc limit 0,$pageSize";
          $data['data'] = $this->usermodel->getRows($sql);
          /*暂时不用了(充值金额)
        foreach($data['data'] as $k=>$v){   
                // $data['data'][$k]['product_price'] = $this->usermodel->getrecharge($v['id']);
                $data['data'][$k]['gift_coins'] = $this->usermodel->getgift_coins($v['id']);
        }
        */
         $this->load->view('hotblood/site/userlist',$data);

    }

    //用户列表分页获取
    function getuserlistpage(){
         $this->load->model('User_model','usermodel');
            $data = array();
         $param = $this->uri->segment(3); //获取uri的第3段
          if (!isset($param) || strlen($param) < 1) {
            $page = 1;
        } else {
            $page = $param;
        }
         $maxPage = $_SESSION['userlists'];
         //用户未筛选信息
    

        $count = $maxPage;
        //分页制作(总页数)
         $pageSize = 10;
       $data['pageInfo'] = $this->usermodel->getPageInfo($count,$page,$pageSize);

        $maxPage = intval(($count + $pageSize) / $pageSize);
        $data['maxPage'] = $maxPage;
        $data['pageList'] = $this->usermodel->getPageList($maxPage, $page);
        $offset = ($page-1)*$pageSize;

         $sql = "select t_dz_account.register_time,t_dz_player.id,t_dz_player.nickname,t_dz_account.account,t_dz_player.image,t_dz_player.diamond,t_dz_player.coins,t_dz_player.last_login_time,t_dz_player.last_login_ip,t_dz_account.status from t_dz_account,t_dz_player,t_dz_unfinished_consume where t_dz_account.id = t_dz_player.id group by id order by id desc limit $offset,$pageSize";
          $data['data'] = $this->usermodel->getRows($sql);
          /* 赠送金额暂时不用了
           foreach($data['data'] as $k=>$v){   
                $data['data'][$k]['product_price'] = $this->usermodel->getrecharge($v['id']);
        }
           */
       
         $this->load->view('hotblood/site/userlist',$data);
    }

    // //目前在线人数
    // function online(){

    // 	$this->load->model('User_model','usermodel');
    //     $sql = "select nickname,sum_score from t_dz_player";
    // 	//返回行数
    // 	$data['data'] = $this->usermodel->getrows($sql);

    //     $pageSize = 5;
    //     $data['page_size'] = $pageSize;

    //     //总页数
    //     $count = count($data['data']);
    //     $data['count'] = $count;
    //     $page = 1;
    //      @$data['data'] = $this->usermodel->page_array($pageSize,$page,$data['data'],1);
        

    //     $data['pageInfo'] = $this->usermodel->getPageInfo($count,$page,$pageSize);
    //     $maxPage = intval(($data['count'] + $pageSize) / $pageSize)-1;
    //     $data['pageList'] = $this->usermodel->getPageList($maxPage, $page);
       
    // 	$this->load->view('hotblood/site/online',$data);
    // }
    // function onlinelist(){
    //     $param = $this->uri->segment(3); //获取uri的第三段
    //     if (!isset($param) || strlen($param) < 1) {
    //         $page = 1;
    //     } else {
    //         $page = $param;
    //     }
    //     echo $page;
    //     $this->load->model('User_model','usermodel');
    //     $sql = "select nickname,sum_score from t_dz_player";
    //     //返回行数
    //     $data['data'] = $this->usermodel->getrows($sql);
    //       $pageSize = 5;
    //     $data['page_size'] = $pageSize;

    //     //总页数
    //     $count = count($data['data']);
    //     $data['count'] = $count;
    //      @$data['data'] = $this->usermodel->page_array($pageSize,$page,$data['data'],1);
        

    //     $data['pageInfo'] = $this->usermodel->getPageInfo($count,$page,$pageSize);
    //     $maxPage = intval(($data['count'] + $pageSize) / $pageSize)-1;
    //     $data['pageList'] = $this->usermodel->getPageList($maxPage, $page);

    //     $this->load->view('hotblood/site/online', $data);
    // }

    //增加用户
    function adduser(){
          $this->load->model('User_model','usermodel');
        //验证权限
         $this->usermodel->permission_valid('user/adduser',$this->log_user_id);
        $this->load->view('hotblood/site/iframe_addUser');
    }

    function adduserform(){
    $this->load->model('User_model','usermodel');
         $config['upload_path']      = './uploads/';
        $config['allowed_types']    = 'gif|jpg|png';
        $config['max_size']     = 100;
        $config['max_width']        = 1024;
        $config['max_height']       = 768;

        $this->load->library('upload', $config);

        if ( ! $this->upload->do_upload('userfile'))
        {
           //头像上传失败则 默认空白头像
           $img = '';
        }
        else
        {
              $data['upload_data']=$this->upload->data();  //文件的一些信息
             $img=base_url().'/uploads/'.$data['upload_data']['file_name'];  //取得文件名

            // $data = array('upload_data' => $this->upload->data());

          
        }
        // echo $img;
        //入库t_dz_account 以及 t_dz_player
        $dataaccount = array(
                'account'=>$_POST['account'],
                'register_time'=>date('Y-m-d H:i:s',time()),
                'register_ip'=>getIp(),
                'status'=>$_POST['status'],
                'password'=>md5($_POST['password']),
                'platform'=>'PC',
                'device'=>'win64',
            );
    $cid = $this->usermodel->insert($dataaccount,'t_dz_account',true);
    if($cid){
        $externID = $this->get_exprentid();
        $dataplayer = array(
                'id'=>$cid,
                'nickname'=>$_POST['nickname'],
                'sex'=>$_POST['sex'],
                'image'=>$img,
                'signature'=>$_POST['sign'],
                  'coins'=>2000,
                'diamond'=>50,
                'extern_id'=>$externID,
                'vip_grade'=>1
            );
     $pid = $this->usermodel->insert($dataplayer,'t_dz_player',true);
 }
     if(isset($pid) and isset($cid)){
     echo "<script l>alert('添加成功！！');location.href='adduser';</script>";
    // redirect(base_url('user/adduser'));
 }else{
      echo "<script>alert('添加失败！！')</script>";
     redirect(base_url('user/adduser'));
 }

    }
        function get_exprentid(){
        $num = $this->unique_rand(2000000,5000000,1);
         $num = $num[0];
            $string = $this->haha($num);
             if(in_array('1', $string)){
                   $this->get_exprentid();
                }else{
                     $sql = "select count(*) from t_dz_player where extern_id = $num";
                     $res = $this->usermodel->getOne($sql);
                 if($res == 1){
                        $this->get_exprentid();
                    }else{
                   return $num;
               }
                }
    }
       //连号顺号
    function haha($num){
    $arr = str_split($num);
    $haha = array();
    // print_r($arr);die;
    foreach ($arr as $key => $value) {
        if(@$arr[$key] != @$arr[$key+1]){
            if((@$arr[$key]+1 == @$arr[$key+1] and @$arr[$key+2] == @$arr[$key+1]+1) or (@$arr[$key] == @$arr[$key+1]+1 and @$arr[$key+2] == @$arr[$key+1]-1)){
               $haha[$key] = 1;
            }else{
                $haha[$key] = 0;
            }
        }elseif(@$arr[$key+2] == @$arr[$key]){
             $haha[$key] = 1;
        }
    }
    return $haha;
    
  }
  //生成随机数
  function unique_rand($min, $max, $num) {  
    $count = 0;  
    $return = array();  
    while ($count < $num) {  
        $return[] = mt_rand($min, $max);  
        $return = array_flip(array_flip($return));  
        $count = count($return);  
    }  
    shuffle($return);  
    return $return;  
}
    //搜索条件查询
    function searchuser(){
            //删除查询变量， 删除查询或默认页数
            unset($_SESSION['userwhere']);
            unset($_SESSION['userlists']);

              $keywords = $_POST['keywords'];
              $starttime = $_POST['starttime'];
              $endtime = $_POST['endtime'];

                 if($_POST['starttime'] != ''){
                $starttime = explode('.',$_POST['starttime']);
                @$starttime = $starttime[0].'-'.$starttime[1].'-'.$starttime[2];
            }

      if($_POST['endtime'] != ''){
                $endtime = explode('.',$_POST['endtime']);
                @$endtime = $endtime[0].'-'.$endtime[1].'-'.$endtime[2];
            }

              $time = 0;
            if($starttime != '' and $endtime != ''){
              $time = 1;
            }

            // @$where = $_POST['keywords'];
            $_SESSION['userwhere']['keywords'] = $keywords;
            $_SESSION['userwhere']['starttime'] = $starttime;
            $_SESSION['userwhere']['endtime'] = $endtime;
       if(!empty($keywords) and $time == 0){
        $where = "(t_dz_player.id = '$keywords' or t_dz_account.account = '$keywords' or t_dz_player.nickname like '%$keywords%')";

            $sql = "select t_dz_account.register_time,t_dz_player.id,t_dz_player.nickname,t_dz_account.account,t_dz_player.image,t_dz_player.diamond,t_dz_player.coins,t_dz_player.last_login_time,t_dz_player.last_login_ip,t_dz_account.status from t_dz_account,t_dz_player,t_dz_unfinished_consume where t_dz_account.id = t_dz_player.id and $where group by id order by id limit 0,10";

              $sqlnolimit = "select t_dz_player.id from t_dz_account,t_dz_player,t_dz_unfinished_consume where t_dz_account.id = t_dz_player.id and $where group by id";
       }elseif(!empty($keywords) and $time == 1){
        $where = "(t_dz_player.id = '$keywords' or t_dz_account.account = '$keywords' or t_dz_player.nickname like '%$keywords%') and (t_dz_account.register_time <= '$endtime' and t_dz_account.register_time >= '$starttime')";

            $sql = "select t_dz_account.register_time,t_dz_player.id,t_dz_player.nickname,t_dz_account.account,t_dz_player.image,t_dz_player.diamond,t_dz_player.coins,t_dz_player.last_login_time,t_dz_player.last_login_ip,t_dz_account.status from t_dz_account,t_dz_player,t_dz_unfinished_consume where t_dz_account.id = t_dz_player.id and $where group by id order by id limit 0,10";

              $sqlnolimit = "select t_dz_player.id from t_dz_account,t_dz_player,t_dz_unfinished_consume where t_dz_account.id = t_dz_player.id and $where group by id";
       }elseif(empty($keywords) and $time == 1){
         $where = "(t_dz_account.register_time <= '$endtime' and t_dz_account.register_time >= '$starttime')";

            $sql = "select t_dz_account.register_time,t_dz_player.id,t_dz_player.nickname,t_dz_account.account,t_dz_player.image,t_dz_player.diamond,t_dz_player.coins,t_dz_player.last_login_time,t_dz_player.last_login_ip,t_dz_account.status from t_dz_account,t_dz_player,t_dz_unfinished_consume where t_dz_account.id = t_dz_player.id and $where group by id order by id limit 0,10";

              $sqlnolimit = "select t_dz_player.id from t_dz_account,t_dz_player,t_dz_unfinished_consume where t_dz_account.id = t_dz_player.id and $where group by id";
       }
       else{
              $sql = "select t_dz_account.register_time,t_dz_player.id,t_dz_player.nickname,t_dz_account.account,t_dz_player.image,t_dz_player.diamond,t_dz_player.coins,t_dz_player.last_login_time,t_dz_player.last_login_ip,t_dz_account.status from t_dz_account,t_dz_player,t_dz_unfinished_consume where t_dz_account.id = t_dz_player.id group by id order by id limit 0,10";

                $sqlnolimit = "select t_dz_player.id from t_dz_account,t_dz_player,t_dz_unfinished_consume where t_dz_account.id = t_dz_player.id group by id";
       }
     // echo $sql;die;
            $data = array();
              $this->load->model('User_model','usermodel');
           
                      //有筛选
                      $all = $this->usermodel->getrows($sqlnolimit);
                      $count = count($all);
                      //总数写入session
                      $_SESSION['userlists'] = $count;

          $pageSize = 10;
        $page = 1;
          // $sql = "select t_club.*,t_dz_account.account from t_club,t_dz_account where t_club.player_id = t_dz_account.id group by id limit 0,10";
        
           // echo $sql;die;
             $data['data'] = $this->usermodel->getRows($sql);  

               $data['data'] = $this->usermodel->getRows($sql);
               /*赠送金额暂时不用了
                foreach($data['data'] as $k=>$v){   
                $data['data'][$k]['product_price'] = $this->usermodel->getrecharge($v['id']);
        }  
                */
              

       $data['pageInfo'] = $this->usermodel->getPageInfo($_SESSION['userlists'],$page,$pageSize);
        $maxPage = intval(($_SESSION['userlists'] + $pageSize) / $pageSize);
        $data['maxPage'] = $maxPage;
        $data['pageList'] = $this->usermodel->getPageList($maxPage, $page);
                 


            $this->load->view('hotblood/site/userlist',$data);
    }

        //搜索分页
        function searchpage(){
            $this->load->model('User_model','usermodel');
            $data = array();
         $param = $this->uri->segment(3); //获取uri的第3段
          if (!isset($param) || strlen($param) < 1) {
            $page = 1;
        } else {
            $page = $param;
        }

    $count = $_SESSION['userlists'];
    $pageSize = 10;
       $data['pageInfo'] = $this->usermodel->getPageInfo($count,$page,$pageSize);

        $maxPage = intval(($count + $pageSize) / $pageSize);

        $data['maxPage'] = $maxPage;
        $data['pageList'] = $this->usermodel->getPageList($maxPage, $page);
        $offset = ($page-1)*$pageSize;

       @$keywords = $_SESSION['userwhere']['keywords'];
       @$starttime = $_SESSION['userwhere']['starttime'];
       @$endtime = $_SESSION['userwhere']['endtime'];
           $time = 0;
            if($starttime != '' and $endtime != ''){
              $time = 1;
            }

       if(!empty($keywords) and $time == 0){
        $where = "(t_dz_player.id = '$keywords' or t_dz_account.account = '$keywords' or t_dz_player.nickname = '$keywords')";

            $sql = "select t_dz_account.register_time,t_dz_player.id,t_dz_player.nickname,t_dz_account.account,t_dz_player.image,t_dz_player.diamond,t_dz_player.coins,t_dz_player.last_login_time,t_dz_player.last_login_ip,t_dz_account.status from t_dz_account,t_dz_player,t_dz_unfinished_consume where t_dz_account.id = t_dz_player.id and $where group by id order by id limit $offset,10";

              $sqlnolimit = "select t_dz_player.id from t_dz_account,t_dz_player,t_dz_unfinished_consume where t_dz_account.id = t_dz_player.id and $where group by id";
       }elseif(!empty($keywords) and $time == 1){
        $where = "(t_dz_player.id = '$keywords' or t_dz_account.account = '$keywords' or t_dz_player.nickname = '$keywords') and (t_dz_account.register_time <= '$endtime' and t_dz_account.register_time >= '$starttime')";

            $sql = "select t_dz_account.register_time,t_dz_player.id,t_dz_player.nickname,t_dz_account.account,t_dz_player.image,t_dz_player.diamond,t_dz_player.coins,t_dz_player.last_login_time,t_dz_player.last_login_ip,t_dz_account.status from t_dz_account,t_dz_player,t_dz_unfinished_consume where t_dz_account.id = t_dz_player.id and $where group by id order by id limit $offset,10";

              $sqlnolimit = "select t_dz_player.id from t_dz_account,t_dz_player,t_dz_unfinished_consume where t_dz_account.id = t_dz_player.id and $where group by id";
       }elseif(empty($keywords) and $time == 1){
         $where = "(t_dz_account.register_time <= '$endtime' and t_dz_account.register_time >= '$starttime')";

            $sql = "select t_dz_account.register_time,t_dz_player.id,t_dz_player.nickname,t_dz_account.account,t_dz_player.image,t_dz_player.diamond,t_dz_player.coins,t_dz_player.last_login_time,t_dz_player.last_login_ip,t_dz_account.status from t_dz_account,t_dz_player,t_dz_unfinished_consume where t_dz_account.id = t_dz_player.id and $where group by id order by id limit $offset,10";

              $sqlnolimit = "select t_dz_player.id from t_dz_account,t_dz_player,t_dz_unfinished_consume where t_dz_account.id = t_dz_player.id and $where group by id";
       }
       else{
              $sql = "select t_dz_account.register_time,t_dz_player.id,t_dz_player.nickname,t_dz_account.account,t_dz_player.image,t_dz_player.diamond,t_dz_player.coins,t_dz_player.last_login_time,t_dz_player.last_login_ip,t_dz_account.status from t_dz_account,t_dz_player,t_dz_unfinished_consume where t_dz_account.id = t_dz_player.id group by id order by id limit $offset,10";

                $sqlnolimit = "select t_dz_player.id from t_dz_account,t_dz_player,t_dz_unfinished_consume where t_dz_account.id = t_dz_player.id group by id";
       }

$data['data'] = $this->usermodel->getRows($sql);    
      
            
         $this->load->view('hotblood/site/userlist',$data);
        }

      //用户编辑
      function updateuser(){

        $this->load->model('User_model','usermodel');
            $data = array();
         $param = $this->uri->segment(3); //获取uri的第3段
          if (!isset($param) || strlen($param) < 1) {
            $id = 3;
        } else {
            $id = $param;
        }

        $data['account'] = $this->usermodel->getusernamebyid($id);        //姓名
        $data['id'] = $id;
        $data['nickname'] = $this->usermodel->getnickbyid($id);        //昵称
        $data['sex'] = $this->usermodel->getsexbyid($id);      //性别
        $data['image'] = $this->usermodel->getimagebyid($id);         //头像
        $data['recharge'] = $this->usermodel->getrechargebyid($id);          //总值总金额
        $data['maxorder_time'] = $this->usermodel->getmaxrechargebyid($id); //最后充值的一单
        //如果在线从online table读取
        $res = $this->usermodel->get_online_data($id);
         if(!empty($res) and $res != 50){
         $data['diamonds'] = $res[0]['diamond'];
        }else{
        $data['diamonds'] = $this->usermodel->getdiamondsbyid($id); //获取砖石
      }
        $data['coins'] = $this->usermodel->getcoinsbyid($id); //获取金币
        $data['register_time'] = $this->usermodel->getregister_time_byid($id);//注册
        $data['last_login_time'] = $this->usermodel->getlast_login_time_byid($id);//最后登录
        $data['last_login_ip'] = $this->usermodel->getlast_login_ip_byid($id);//最后登录IP
        $data['gift_coins'] = $this->usermodel->getgift_coins($id);

      
      //充值是否为空
        $data['recharge'] = ($data['recharge'] != '')?$data['recharge']:'0';
        $this->load->view('hotblood/site/iframe_setUser',$data);
      }

      //增加|| 减少砖石
      function change_diamonds(){
        $this->load->model('User_model','usermodel');
        //验证权限
         $this->usermodel->permission_valid('user/change_diamonds',$this->log_user_id);
        //增加砖石select option td_product
        $data['select'] = "<option value='6'>60砖石</option>
                            <option value='30'>300砖石</option>
                            <option value='128'>1280砖石</option>
                            <option value='328'>3280砖石</option>
                            <option value='618'>6180砖石</option>
        ";

          $data['account'] = $_GET['account'];
          $data['id'] = $_GET['id'];
          $this->load->view('hotblood/site/iframe_diamond',$data);
      }

      //表单提交
      function change_diamonds_form(){
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

      //赠送砖石的载入 ajax页面 change_diamonds_form 执行后调用更新
      function diamon_refush(){
  $this->load->model('User_model','usermodel');
            $data = array();
         $param = $this->uri->segment(3); //获取uri的第3段
          if (!isset($param) || strlen($param) < 1) {
            $id = 3;
        } else {
            $id = $param;
        }
            $diamond = $this->usermodel->getdiamondsbyid($id); //获取砖石
            echo $diamond;
      }

      //增加金币
         function change_coins(){
          $data['account'] = $_GET['account'];
          $data['id'] = $_GET['id'];
          $this->load->view('hotblood/site/iframe_coins',$data);
      }



      //表单提交
      function change_coins_form(){

        $table = 't_dz_change_money';

        $this->load->model('User_model','usermodel');
       
        $data  = array('coins'=>$_POST['coins'],'content'=>$_POST['content'],'diamond'=>0,'player_id'=>$_POST['id'],'change_time'=>date('Y-m-d H:i:s',time()),'change_type'=>$_POST['coinsset']);
      
          //insert t_dz_change_money 记录表
          $inert_diamond = $this->usermodel->insert($data,$table,true);
          //update t_dz_player diamond filed
          
          if($_POST['coinsset'] == -1){
             $update_player = $this->usermodel->filedchange_reduce('coins','t_dz_player',array('id'=>$_POST['id']),$_POST['coins']);
          }
            else{
             $update_player = $this->usermodel->filedchange_add('coins','t_dz_player',array('id'=>$_POST['id']),$_POST['coins']);
          }

         echo "<div align='center' style='color:#F00 ; font-size:40px ; height=70px' >更新成功！请重新载入该页面<div>";
      }

      //setuser表单提交
      function updateuser_form(){
        //post date:Array ( [nickname] => 我是西红柿h [image] => http://localhost/code/static/images/loading.jpg [sex] => 1 id=32)
        $this->load->model('User_model','usermodel');
        $id = $_POST['id'];
        $data = array('nickname'=>$_POST['nickname'],'image'=>$_POST['image'],'sex'=>$_POST['sex']);
        $row = $this->usermodel->setuser_data($data,$id);
        if(isset($row)){
          redirect('user/updateuser/'.$id);
        }
      }
      //权限管理部门功能
      function jurisdiction(){
        if($this->log_user_id == 1){
           $this->load->model('User_model','usermodel');
           $sql = "select * from t_bs_dept";
           $data['data'] = $this->usermodel->getrows($sql);
        
          $this->load->view('hotblood/site/iframe_jurisdiction',$data);
        }else{
           if($this->log_user_id != 1){
          $this->maintenance('您没有这个权限...');die;
      }
        }
      }

      //部门增/减功能 
      function change_func(){
          $dept_id = $_GET['dept_id'];
           $this->load->model('User_model','usermodel');
           $sql = "select id,func_name,func_url from t_bs_func";
          $data['func'] = $this->usermodel->getRows($sql);
          $data['dept_id'] = $dept_id;
          //部门已有功能
          $sql = "SELECT a.func_name FROM t_bs_func a,t_bs_funcnum b WHERE b.dept_id = $dept_id AND b.func_id = a.id";
          $data['in_func'] = $this->usermodel->getRows($sql);
          $this->load->view('hotblood/site/iframe_add_func',$data);
      }
      //增减 form
      function change_func_form(){
        $this->load->model('User_model','usermodel');
        //insert t_bs_funnum
        $change_type = $_POST['change_type'];
        $func = $_POST['func'];
        $dept_id = $_POST['dept_id'];
        $data = array(
            'dept_id' => $dept_id,
            'func_id' => $func
          );
        if($change_type == 1){
      $id = $this->usermodel->insert($data,'t_bs_funcnum',true);
    }else{
      $where = array('dept_id' => $dept_id,'func_id'=>$func);
      $id = $this->usermodel->delete($where,'t_bs_funcnum',true);
    }
       echo "<div align='center' style='color:#F00 ; font-size:40px ; height=70px' >权限操作成功！<div>";
      }
//管理员列表
      function adminlist(){


         $this->load->model('User_model','usermodel');
          
       
        $sql = "select * from t_bs_user";

        
        $data = $this->usermodel->getRows($sql);

        $count = count($data);
       
        $_SESSION['userlists'] = $count;
        //分页制作(总页数)
         $pageSize = 10;
        $page = 1;
       $data['pageInfo'] = $this->usermodel->getPageInfo($count,$page,$pageSize);
        $maxPage = intval(($count + $pageSize) / $pageSize);
        $data['maxPage'] = $maxPage;
        $data['pageList'] = $this->usermodel->getPageList($maxPage, $page);

         $sql = "select * from t_bs_user limit 0,$pageSize";
          $data['data'] = $this->usermodel->getRows($sql);

         $this->load->view('hotblood/site/adminlist',$data);

      }

      function getadminlistpage(){
  $this->load->model('User_model','usermodel');
            $data = array();
         $param = $this->uri->segment(3); //获取uri的第3段
          if (!isset($param) || strlen($param) < 1) {
            $page = 1;
        } else {
            $page = $param;
        }
         $maxPage = $_SESSION['userlists'];
         //用户未筛选信息
    

        $count = $maxPage;
        //分页制作(总页数)
         $pageSize = 10;
       $data['pageInfo'] = $this->usermodel->getPageInfo($count,$page,$pageSize);

        $maxPage = intval(($count + $pageSize) / $pageSize);
        $data['maxPage'] = $maxPage;
        $data['pageList'] = $this->usermodel->getPageList($maxPage, $page);
        $offset = ($page-1)*$pageSize;

         $sql = "select * from t_bs_user limit $offset,$pageSize";
          $data['data'] = $this->usermodel->getRows($sql);

       
         $this->load->view('hotblood/site/adminlist',$data);
      }
	}
 ?>