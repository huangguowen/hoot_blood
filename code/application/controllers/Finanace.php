<?php 

	/**
	* 
	*/
	class Finanace extends MY_Controller
	{
	
		function __construct()
		{
			# code...
				parent::__construct();
                      if(!isset($_SESSION['user_id'])) {
            redirect('', 'location', 301);
        }   
             $this->load->library('session');
		}
		function index(){
			//删除总页数 以及 查询条件
			
			     unset($_SESSION['rechargelist']);//无筛选总页数 
                unset($_SESSION['recharge_where']);// 筛选条件
                unset($_SESSION['searech_count']);//有筛选总页数
			 $this->load->model('User_model','usermodel');
			$data = array();
			//订单状态 pay_status=1 未处理 2：成功 3：失败
			$arr = array('成功'=>2,'失败'=>3,'未处理'=>1);
			 $this->load->helper('funshelp');
			$data['pay_status_option'] = setoption('',$arr);

			$count_1 = $this->usermodel->count('t_dz_consume_history');
            /*
             由于性能以及部分问题，只要求生成订单表  
             $count_2 = $this->usermodel->count('t_dz_unfinished_consume');
            $_SESSION['rechargelist'] = $count_2+$count_1;
             */
		 $_SESSION['rechargelist'] = $count_1;
			   //分页制作(总页数)
         $pageSize = 10;
        $page = 1;
       $data['pageInfo'] = $this->usermodel->getPageInfo($_SESSION['rechargelist'],$page,$pageSize);
        $maxPage = intval(($_SESSION['rechargelist'] + $pageSize) / $pageSize);
        $data['maxPage'] = $maxPage;
        $data['pageList'] = $this->usermodel->getPageList($maxPage, $page);


                //查询所有金额(完成的订单)
                $sql = "select sum(product_price) from t_dz_consume_history";
                $sumprice_fin = $this->usermodel->getOne($sql);
                //未完成的订单
                $sql = "select sum(product_price) from t_dz_unfinished_consume";
                $sumprice_unfin = $this->usermodel->getOne($sql);


			//只是生成订单表 t_dz_consume_history
			 $sql = "select id,consume_number,product_price,pay_type,player_id,order_time,pay_status from t_dz_consume_history order by id desc limit 0,10";
			  $data['data'] = $this->usermodel->getrows($sql);
              /*
                由于性能以及部分问题，只要求生成订单表  
            
			 //只是支付中
			  $sql = "select id,consume_number,product_price,pay_type,player_id,order_time,pay_status from t_dz_unfinished_consume limit 0,10";
			   $data['unfinished'] = $this->usermodel->getrows($sql);

			   $data['data'] = array_merge($data['history'],$data['unfinished']);

               */
			   foreach($data['data'] as $k=>$v){
                //该段代码大于0.55秒（后期看能否优化）
			   		// @$data['data'][$k]['username'] = $this->usermodel->getusernamebyid($v['player_id']);
			   		// @$data['data'][$k]['nickname'] = $this->usermodel->getnickbyid($v['player_id']);
                     @$data['data']['sumprice'] += $v['product_price'];
			   }
                            
			   // print_r($data['data']);die;
			$this->load->view('hotblood/site/rechargeList',$data);

		}
		 //用户列表分页获取
    function getrechargepage(){
         $this->load->model('User_model','usermodel');
            $data = array();
         $param = $this->uri->segment(3); //获取uri的第3段
          if (!isset($param) || strlen($param) < 1) {
            $page = 1;
        } else {
            $page = $param;
        }
         $maxPage = $_SESSION['rechargelist'];


         $arr = array('成功'=>2,'失败'=>3,'未处理'=>1);
             $this->load->helper('funshelp');
            $data['pay_status_option'] = setoption('',$arr);
         //用户未筛选信息
    

        $count = $maxPage;
        //分页制作(总页数)
         $pageSize = 10;
       $data['pageInfo'] = $this->usermodel->getPageInfo($count,$page,$pageSize);

        $maxPage = intval(($count + $pageSize) / $pageSize);
        $data['maxPage'] = $maxPage;
        $data['pageList'] = $this->usermodel->getPageList($maxPage, $page);
      $offset = ($page-1)*$pageSize;

 $sql = "select id,consume_number,product_price,pay_type,player_id,order_time,pay_status from t_dz_consume_history order by id desc limit $offset,$pageSize";
			  $data['data'] = $this->usermodel->getrows($sql);
              /*
                    由于性能以及部分问题，只要求生成订单表  
			 //只是支付中
			  $sql = "select id,consume_number,product_price,pay_type,player_id,order_time,pay_status from t_dz_unfinished_consume limit $offset,$pageSize";
			   $data['unfinished'] = $this->usermodel->getrows($sql);
 
			   $data['data'] = array_merge($data['history'],$data['unfinished']);

               */
			   foreach($data['data'] as $k=>$v){
			   		// @$data['data'][$k]['username'] = $this->usermodel->getusernamebyid($v['player_id']);
			   		// @$data['data'][$k]['nickname'] = $this->usermodel->getnickbyid($v['player_id']);

             //人民币总
               @$data['data']['sumprice'] += $v['product_price'];
			   }
         $this->load->view('hotblood/site/rechargeList',$data);
    }
    function diamond(){

    	$this->maintenance('暂未开放...');
    }
     function coin(){

        $this->maintenance('暂未开放...');
    }
     function give(){

        $this->maintenance('暂未开放...');
    }
    //充值导出excel
    
    function get_recharge_excel(){
        $this->load->library('excelxml');
        $count_datas = array();
        $count_data = array();
        $count_data = $this->customerssignlib->getallformforsearchdaochu();
        $j = 0;
  //     print_r($count_data);die;
        for ($i = 0; $i < count($count_data); $i++) {
            if ($i == 0) {
                $data['data'][$j]['a'] = "ID";
                $data['data'][$j]['b'] = "用户名";
                $data['data'][$j]['c'] = "昵称";
                $data['data'][$j]['d'] = "金额";
                $data['data'][$j]['e'] = "赠送钻石数";
                $data['data'][$j]['f'] = "商户订单号";
                $data['data'][$j]['g'] = "支付类型";
                $data['data'][$j]['h'] = "订单状态";
                $data['data'][$j]['j'] = " 提交时间";
                $j++;
            }
            $data['data'][$j]['0'] = $count_data[$i]['0'];
            $data['data'][$j]['1'] = $count_data[$i]['1'];
            $data['data'][$j]['2'] = $count_data[$i]['2'];
            $data['data'][$j]['3'] = $count_data[$i]['3'];
            $data['data'][$j]['4'] = $count_data[$i]['4'];
            $data['data'][$j]['5'] = $count_data[$i]['5'];
            $data['data'][$j]['6'] = $count_data[$i]['6'];
            $data['data'][$j]['7'] = $count_data[$i]['7'];
             $data['data'][$j]['7'] = $count_data[$i]['8'];
            $j++;
        }
        //print_r($data);exit;
        $xls = new Excelxml('GB2312', false, 'Check Info Sheet');
        $xls->addArray($data['data']);
        $xls->generateXML("客户协议单");
    }

     //搜索条件查询
    function search_recharge(){
            //删除查询变量， 删除查询或默认页数
            unset($_SESSION['recharge_where']);
            unset($_SESSION['rechargelist']);
            unset($_SESSION['searech_count']);
            //POST = Array ( [pay_status] => 全部 [starttime] => 2017.06.01 [endtime] => 2017.06.02 [keywords] => sd )
        
            $_SESSION['recharge_where'] = $_POST;
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

 		
              $this->load->model('User_model','usermodel');
            $data = array();
		$arr = array();

          /*
                    由于性能以及部分问题，只要求生成订单表  

                         //只是支付中
              $sql = "select id,consume_number,product_price,pay_type,player_id,order_time,pay_status from t_dz_unfinished_consume";
               $data['unfinished'] = $this->usermodel->getrows($sql);
                 $data['data'] = array_merge($data['history'],$data['unfinished']);

                    */
		
         
			 $keywords = $_POST['keywords'];
             $pay_status = $_POST['pay_status'];

             @$player_id = $this->usermodel->getidbynick($keywords);

		$where = '';
    if($_POST['keywords'] != ''){
        $where = "(t_dz_player.id = '$keywords' or t_dz_consume_history.consume_number = '$keywords' or t_dz_consume_history.player_id = '$player_id')";
    }
     if($_POST['pay_status'] != '全部'){
         if($where == ''){
             $where .= "(t_dz_consume_history.pay_status = $pay_status)";
         }else{
        $where .= "(and t_dz_consume_history.pay_status = $pay_status)";
    }
    }
     if(($endtime != '' and $starttime != '')){
         if($where == ''){
        $where .= "(t_dz_consume_history.order_time <= '$endtime' and t_dz_consume_history.order_time >= '$starttime')";
         }else{
        $where .= "and (t_dz_consume_history.order_time <= '$endtime' and t_dz_consume_history.order_time >= '$starttime')";
    }
    }		 
    if($where != ''){
        $sqlnolimit = "select t_dz_consume_history.id,t_dz_consume_history.consume_number,t_dz_consume_history.product_price,t_dz_consume_history.pay_type,t_dz_consume_history.player_id,t_dz_consume_history.order_time,t_dz_consume_history.pay_status from t_dz_consume_history,t_dz_player where $where group by t_dz_consume_history.id";

     $sql = "select t_dz_consume_history.id,t_dz_consume_history.consume_number,t_dz_consume_history.product_price,t_dz_consume_history.pay_type,t_dz_consume_history.player_id,t_dz_consume_history.order_time,t_dz_consume_history.pay_status from t_dz_consume_history,t_dz_player where $where group by t_dz_consume_history.id order by id desc limit 0,10 ";
    }else{
          $sqlnolimit = "select t_dz_consume_history.id,t_dz_consume_history.consume_number,t_dz_consume_history.product_price,t_dz_consume_history.pay_type,t_dz_consume_history.player_id,t_dz_consume_history.order_time,t_dz_consume_history.pay_status from t_dz_consume_history,t_dz_player group by t_dz_consume_history.id";

         $sql = "select t_dz_consume_history.id,t_dz_consume_history.consume_number,t_dz_consume_history.product_price,t_dz_consume_history.pay_type,t_dz_consume_history.player_id,t_dz_consume_history.order_time,t_dz_consume_history.pay_status from t_dz_consume_history,t_dz_player group by t_dz_consume_history.id  order by id desc limit 0,10 ";
    }

    $all = $this->usermodel->getrows($sqlnolimit);
    $count = count($all);
    $_SESSION['searech_count'] = $count;
 $data['data'] = $this->usermodel->getrows($sql);

 /*以前的总数居筛选
			   foreach($data['data'] as $k=>$v){
			   		@$data['data'][$k]['username'] = $this->usermodel->getusernamebyid($v['player_id']);
			   		@$data['data'][$k]['nickname'] = $this->usermodel->getnickbyid($v['player_id']);
			   			if($_POST['keywords'] != ''){
			   				
			   		if($data['data'][$k]['username'] != $_POST['keywords'] and $data['data'][$k]['nickname'] != $_POST['keywords'] and $data['data'][$k]['player_id'] != $_POST['keywords'] and $data['data'][$k]['consume_number'] != $_POST['keywords']){
			   			unset($data['data'][$k]);
			   		}
			   	}
			   		if($_POST['pay_status'] != '全部' and @$data['data'][$k]['pay_status'] != $_POST['pay_status']){
			   			unset($data['data'][$k]);
			   		}

			   		  if( ($endtime != '' and $endtime < date('Y-m-d',strtotime($v['order_time'])) or ($starttime != '' and $starttime > date('Y-m-d',strtotime($v['order_time']))) )){
		
			   			unset($data['data'][$k]);
			   		}
			   		// if (@$starttime != '' and $starttime != date('Y-m-d',strtotime($v['order_time']))) {
			   			
			   		// 		unset($data['data'][$k]);
			   		// }
            //人民币总
           @$data['data']['sumprice'] += $data['data'][$k]['product_price'];
			   	
			   }
           $sumprice_search = $data['data']['sumprice'];

               foreach($data['data'] as $k=>$v){
                 $param = $this->uri->segment(3); //获取uri的第3段
          if (!isset($param) || strlen($param) < 1) {
            $page = 1;
        } else {
            $page = $param;
        }
                 $pageSize = 10;
                 $count = count($data['data'])-1;

                $data['pageInfo'] = $this->usermodel->getPageInfo($count,$page,$pageSize);

                $maxPage = intval(($count + $pageSize) / $pageSize);
                $data['maxPage'] = $maxPage;
                $data['pageList'] = $this->usermodel->getPageList($maxPage, $page);
                unset($data['data']['sumprice']);
               $data['data'] = $this->usermodel->page_array($pageSize,$page,$data['data'],1); 
               $data['data']['sumprice'] = $sumprice_search;
*/

 foreach($data['data'] as $k=>$v){
       //人民币总
           @$data['data']['sumprice'] += $data['data'][$k]['product_price'];
 }

       
			   
				// $data['data'] = $arr;
				//订单状态 pay_status=1 未处理 2：成功 3：失败
				$option = array('成功'=>2,'失败'=>3,'未处理'=>1);
 				$this->load->helper('funshelp');
				$data['pay_status_option'] = setoption('',$option);

   if (!isset($param) || strlen($param) < 1) {
            $page = 1;
        } else {
            $page = $param;
        }
                 $pageSize = 10;
                

                $data['pageInfo'] = $this->usermodel->getPageInfo($count,$page,$pageSize);

                $maxPage = intval(($count + $pageSize) / $pageSize);
                $data['maxPage'] = $maxPage;
                $data['pageList'] = $this->usermodel->getPageList($maxPage, $page);

         $this->load->view('hotblood/site/rechargeList',$data);
    }
    //搜索分页
         function searchpage(){
            
           $starttime = $_SESSION['recharge_where']['starttime'];
            $endtime = $_SESSION['recharge_where']['endtime'];
            if($starttime != ''){
                    $starttime = explode('.',$starttime);
                    @$starttime = $starttime[0].'-'.$starttime[1].'-'.$starttime[2];
            }

            if($endtime != ''){
                    $endtime = explode('.',$endtime);
                    @$endtime = $endtime[0].'-'.$endtime[1].'-'.$endtime[2];
            }

        
              $this->load->model('User_model','usermodel');
                /*
            $data = array();
        $arr = array();
        $sql = "select id,consume_number,product_price,pay_type,player_id,order_time,pay_status from t_dz_consume_history";
              $data['data'] = $this->usermodel->getrows($sql);

                /*
                    由于性能以及部分问题，只要求生成订单表  
 $sql = "select id,consume_number,product_price,pay_type,player_id,order_time,pay_status from t_dz_unfinished_consume";
               $data['unfinished'] = $this->usermodel->getrows($sql);

               $data['data'] = array_merge($data['history'],$data['unfinished']);
             

                    */

             //只是支付中
             /*  

               foreach($data['data'] as $k=>$v){
                    @$data['data'][$k]['username'] = $this->usermodel->getusernamebyid($v['player_id']);
                    @$data['data'][$k]['nickname'] = $this->usermodel->getnickbyid($v['player_id']);
                        if($_SESSION['recharge_where']['keywords'] != ''){
                            
                    if($data['data'][$k]['username'] != $_SESSION['recharge_where']['keywords'] and $data['data'][$k]['nickname'] != $_SESSION['recharge_where']['keywords'] and $data['data'][$k]['player_id'] != $_SESSION['recharge_where']['keywords'] and $data['data'][$k]['consume_number'] != $_SESSION['recharge_where']['keywords']){
                        unset($data['data'][$k]);
                    }
                }
                    if($_SESSION['recharge_where']['pay_status'] != '全部' and @$data['data'][$k]['pay_status'] != $_SESSION['recharge_where']['pay_status']){
                        unset($data['data'][$k]);
                    }

                      if( ($endtime != '' and $endtime < date('Y-m-d',strtotime($v['order_time'])) or ($starttime != '' and $starttime > date('Y-m-d',strtotime($v['order_time']))) )){
                        
                            unset($data['data'][$k]);
                    }
            //人民币总
           @$data['data']['sumprice'] += $data['data'][$k]['product_price'];
                
               }
           $sumprice_search = $data['data']['sumprice'];

                    $param = $this->uri->segment(3); //获取uri的第3段
          if (!isset($param) || strlen($param) < 1) {
            $page = 1;
        } else {
            $page = $param;
        }
                 $pageSize = 10;
                 $count = count($data['data'])-1;

                $data['pageInfo'] = $this->usermodel->getPageInfo($count,$page,$pageSize);

                $maxPage = intval(($count + $pageSize) / $pageSize);
                $data['maxPage'] = $maxPage;
                $data['pageList'] = $this->usermodel->getPageList($maxPage, $page);
                unset($data['data']['sumprice']);

               $data['data'] = $this->usermodel->page_array($pageSize,$page,$data['data'],1); 
                $data['data']['sumprice'] = $sumprice_search;
               */
          
        

           
                 $param = $this->uri->segment(3); //获取uri的第3段
          if (!isset($param) || strlen($param) < 1) {
            $page = 1;
        } else {
            $page = $param;
        }
                 $pageSize = 10;
              

                $data['pageInfo'] = $this->usermodel->getPageInfo($_SESSION['searech_count'],$page,$pageSize);

                $maxPage = intval(($_SESSION['searech_count'] + $pageSize) / $pageSize);
                $data['maxPage'] = $maxPage;
                $data['pageList'] = $this->usermodel->getPageList($maxPage, $page);
                $offset = ($page-1)*$pageSize;

                 
             $where = '';
             $pay_status = $_SESSION['recharge_where']['pay_status'];
             $keywords =    $_SESSION['recharge_where']['keywords'];
             $pay_status = $_SESSION['recharge_where']['pay_status'];
              @$player_id = $this->usermodel->getidbynick($keywords);

    if($_SESSION['recharge_where']['keywords'] != ''){
        $where = "(t_dz_player.id = '$keywords' or t_dz_consume_history.consume_number = '$keywords' or t_dz_consume_history.player_id = '$player_id')";
    }
     if($pay_status != '全部'){
        if($where == ''){
        $where .= "(t_dz_consume_history.pay_status = '$pay_status')";
        }else{
        $where .= "(and t_dz_consume_history.pay_status = '$pay_status')";
    }
    }
     if(($endtime != '' and $starttime != '')){
        if($where == ''){
        $where .= "(t_dz_consume_history.order_time <= '$endtime' and t_dz_consume_history.order_time >= '$starttime')";
        }else{
        $where .= "and (t_dz_consume_history.order_time <= '$endtime' and t_dz_consume_history.order_time >= '$starttime')";
    }
    }        
    if($where != ''){
     $sql = "select t_dz_consume_history.id,t_dz_consume_history.consume_number,t_dz_consume_history.product_price,t_dz_consume_history.pay_type,t_dz_consume_history.player_id,t_dz_consume_history.order_time,t_dz_consume_history.pay_status from t_dz_consume_history,t_dz_player where $where group by t_dz_consume_history.id order by id desc limit $offset,10";
    }else{
         $sql = "select t_dz_consume_history.id,t_dz_consume_history.consume_number,t_dz_consume_history.product_price,t_dz_consume_history.pay_type,t_dz_consume_history.player_id,t_dz_consume_history.order_time,t_dz_consume_history.pay_status from t_dz_consume_history,t_dz_player group by t_dz_consume_history.id order by id desc limit $offset,10";
    }
// echo $sql;
 $data['data'] = $this->usermodel->getrows($sql);

               
             foreach($data['data'] as $k=>$v){
                    // @$data['data'][$k]['username'] = $this->usermodel->getusernamebyid($v['player_id']);
                    // @$data['data'][$k]['nickname'] = $this->usermodel->getnickbyid($v['player_id']);

             //人民币总
               @$data['data']['sumprice'] += $v['product_price'];
               }
               
                // $data['data'] = $arr;
                //订单状态 pay_status=1 未处理 2：成功 3：失败
                $option = array('成功'=>2,'失败'=>3,'未处理'=>1);
                $this->load->helper('funshelp');
                $data['pay_status_option'] = setoption('',$option);


         $this->load->view('hotblood/site/rechargeList',$data);
        }
}
 ?>