<?php 

	/**
	* 
	*/
	class Club extends MY_Controller
	{
		
		function __construct()
		{
			parent::__construct();
       if(!isset($_SESSION['user_id'])) {
            redirect('', 'location', 301);
        }
             $this->load->library('session');
		}
		function index(){
		unset($_SESSION['clubwhere']);
               $sql = "select t_club.*,t_dz_account.account,t_dz_player.image from t_club,t_dz_account,t_dz_player where t_club.player_id = t_dz_account.id and t_dz_player.id =t_club.player_id and t_club.state = 1 group by id limit 0,10";
     
            $data = array();
              $this->load->model('User_model','usermodel');
           
                      //如果没有筛选条件的话
                      $count = $this->usermodel->count('t_club');
                      $_SESSION['clubscount'] = $count;

          $pageSize = 10;
        $page = 1;
          // $sql = "select t_club.*,t_dz_account.account from t_club,t_dz_account where t_club.player_id = t_dz_account.id group by id limit 0,10";
        
           // echo $sql;die;
             $data['data'] = $this->usermodel->getRows($sql);           

       $data['pageInfo'] = $this->usermodel->getPageInfo($_SESSION['clubscount'],$page,$pageSize);
        $maxPage = intval(($_SESSION['clubscount'] + $pageSize) / $pageSize);
        $data['maxPage'] = $maxPage;
        $data['pageList'] = $this->usermodel->getPageList($maxPage, $page);
                 
            $this->load->view('hotblood/site/iframe_clublist',$data);
     
			
		}

        //查询
        function searchclub(){
            //删除查询变量， 删除查询或默认页数
           unset($_SESSION['clubwhere']);
            unset($_SESSION['clubscount']);
            @$where = $_POST['keywords'];
            $_SESSION['clubwhere'] = $where;
       if(isset($where)){
        $where = "(t_dz_player.id = '$where' or t_dz_account.account = '$where' or t_club.nickname = '$where' or t_club.mingpian = '$where')";
           $sql = "select t_club.*,t_dz_account.account,t_dz_player.image from t_club,t_dz_account,t_dz_player where t_club.player_id = t_dz_account.id and t_dz_player.id =t_club.player_id and $where and t_club.state = 1 group by id limit 0,10";
             $sqlnolimit = "select t_club.*,t_dz_account.account,t_dz_player.image from t_club,t_dz_account,t_dz_player where t_club.player_id = t_dz_account.id and t_dz_player.id =t_club.player_id and $where and t_club.state = 1 group by id";
       }else{
               $sql = "select t_club.*,t_dz_account.account,t_dz_player.image from t_club,t_dz_account,t_dz_player where t_club.player_id = t_dz_account.id and t_dz_player.id =t_club.player_id and t_club.state = 1 group by id limit 0,10";
                 $sqlnolimit = "select t_club.*,t_dz_account.account,t_dz_player.image from t_club,t_dz_account,t_dz_player where t_club.player_id = t_dz_account.id and t_dz_player.id =t_club.player_id and t_club.state = 1 group by id";
       }
     
            $data = array();
              $this->load->model('User_model','usermodel');
           
                      //有筛选
                      $all = $this->usermodel->getrows($sqlnolimit);
                      $count = count($all);
                      //总数写入session
                      $_SESSION['clubscount'] = $count;

          $pageSize = 10;
        $page = 1;
          // $sql = "select t_club.*,t_dz_account.account from t_club,t_dz_account where t_club.player_id = t_dz_account.id group by id limit 0,10";
        
           // echo $sql;die;
             $data['data'] = $this->usermodel->getRows($sql);           

       $data['pageInfo'] = $this->usermodel->getPageInfo($_SESSION['clubscount'],$page,$pageSize);
        $maxPage = intval(($_SESSION['clubscount'] + $pageSize) / $pageSize)-1;
        $data['maxPage'] = $maxPage;
        $data['pageList'] = $this->usermodel->getPageList($maxPage, $page);
                 
            $this->load->view('hotblood/site/iframe_clublist',$data);
     
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

    $count = $_SESSION['clubscount'];
    $pageSize = 10;
       $data['pageInfo'] = $this->usermodel->getPageInfo($count,$page,$pageSize);

        $maxPage = intval(($count + $pageSize) / $pageSize);

        $data['maxPage'] = $maxPage;
        $data['pageList'] = $this->usermodel->getPageList($maxPage, $page);
        $offset = ($page-1)*$pageSize;

     if(isset($_SESSION['clubwhere'])){
        $where = $_SESSION['clubwhere'];
        $where = "(t_dz_player.id = '$where' or t_dz_account.account = '$where' or t_club.nickname = '$where' or t_club.mingpian = '$where')";
           $sql = "select t_club.*,t_dz_account.account,t_dz_player.image from t_club,t_dz_account,t_dz_player where t_club.player_id = t_dz_account.id and t_dz_player.id =t_club.player_id and $where and t_club.state = 1 group by id limit $offset,10";
       }else{
               $sql = "select t_club.*,t_dz_account.account,t_dz_player.image from t_club,t_dz_account,t_dz_player where t_club.player_id = t_dz_account.id and t_dz_player.id =t_club.player_id and t_club.state = 1 group by id limit $offset,10";
       }
$data['data'] = $this->usermodel->getRows($sql);    
      
            
         $this->load->view('hotblood/site/iframe_clublist',$data);
        }

        //默认分页
        function getclublistpage(){
                    $this->load->model('User_model','usermodel');
            $data = array();
         $param = $this->uri->segment(3); //获取uri的第3段
          if (!isset($param) || strlen($param) < 1) {
            $page = 1;
        } else {
            $page = $param;
        }
         $maxPage = $_SESSION['clubscount'];


         //用户未筛选信息
        

        $count = $maxPage;
        //分页制作(总页数)
         $pageSize = 10;
       $data['pageInfo'] = $this->usermodel->getPageInfo($count,$page,$pageSize);

        $maxPage = intval(($count + $pageSize) / $pageSize);

        $data['maxPage'] = $maxPage;
        $data['pageList'] = $this->usermodel->getPageList($maxPage, $page);
        $offset = ($page-1)*$pageSize;
 
         
                $sql = "select t_club.*,t_dz_account.account,t_dz_player.image from t_club,t_dz_account,t_dz_player where t_club.player_id = t_dz_account.id and t_dz_player.id =t_club.player_id and t_club.state = 1 group by id limit $offset,10";
           
               $data['data'] = $this->usermodel->getrows($sql);

            
         $this->load->view('hotblood/site/iframe_clublist',$data);
        }


    function table(){
                unset($_SESSION['tablecount']);
                unset($_SESSION['tablewhere']);

               $sql = "select table_id,table_name,player_name,player_id,player_num,table_code,table_state from t_dz_gametable where table_state != 4 order by table_id limit 0,10";
     
            $data = array();
              $this->load->model('User_model','usermodel');
           
                      //如果没有筛选条件的话
                      $sqlnolimit = "select count(table_id) from t_dz_gametable where table_state != 4 order by table_id";
                     $count = $this->usermodel->getOne($sqlnolimit);
                      $_SESSION['tablecount'] = $count;

          $pageSize = 10;
        $page = 1;
          // $sql = "select t_club.*,t_dz_account.account from t_club,t_dz_account where t_club.player_id = t_dz_account.id group by id limit 0,10";
        
           // echo $sql;die;
             $data['data'] = $this->usermodel->getRows($sql);           

       $data['pageInfo'] = $this->usermodel->getPageInfo($_SESSION['tablecount'],$page,$pageSize);
        $maxPage = intval(($_SESSION['tablecount'] + $pageSize) / $pageSize);
        $data['maxPage'] = $maxPage;
        $data['pageList'] = $this->usermodel->getPageList($maxPage, $page);
                 
            $this->load->view('hotblood/site/iframe_tablelist',$data);
     
      
    }
            //默认分页
        function gettablelistpage(){
                    $this->load->model('User_model','usermodel');
            $data = array();
         $param = $this->uri->segment(3); //获取uri的第3段
          if (!isset($param) || strlen($param) < 1) {
            $page = 1;
        } else {
            $page = $param;
        }
         $maxPage = $_SESSION['tablecount'];


         //用户未筛选信息
        

        $count = $maxPage;
        //分页制作(总页数)
         $pageSize = 10;
       $data['pageInfo'] = $this->usermodel->getPageInfo($count,$page,$pageSize);

        $maxPage = intval(($count + $pageSize) / $pageSize);

        $data['maxPage'] = $maxPage;
        $data['pageList'] = $this->usermodel->getPageList($maxPage, $page);
        $offset = ($page-1)*$pageSize;
 
         
                $sql = "select table_id,table_name,player_name,player_id,player_num,table_code,table_state from t_dz_gametable table_state != 4 order by table_id limit $offset,10";
           
               $data['data'] = $this->usermodel->getrows($sql);

            
         $this->load->view('hotblood/site/iframe_tablelist',$data);
        }

           //查询
        function searchtable(){
           $this->load->model('User_model','usermodel');
            //删除查询变量， 删除查询或默认页数
           unset($_SESSION['tablecount']);
           unset($_SESSION['tablewhere']);

            $where = $_POST['keywords'];
            $_SESSION['tablewhere'] = $where;
             @$account = $this->usermodel->getidbyusername($where);
       if(isset($where)){
        $where = "(table_code = '$where' or player_name = '$where' or player_id = '$account' or player_id = '$where' or table_name = '$where')";
           $sql = "select table_id,table_name,player_name,player_id,player_num,table_code,table_state from t_dz_gametable where $where and table_state != 4 order by table_id limit 0,10";
             $sqlnolimit = "select count(table_id) from t_dz_gametable where $where and table_state != 4 order by table_id";
       }else{
               $sql = "select table_id,table_name,player_name,player_id,player_num,table_code,table_state from t_dz_gametable where table_state != 4 order by table_id limit 0,10";
                  $sqlnolimit = "select count(table_id) from t_dz_gametable where table_state != 4 order by table_id";
       }

            $data = array();
              $this->load->model('User_model','usermodel');
           
                      //计算count
                     $count = $this->usermodel->getOne($sqlnolimit);
                      //总数写入session
                      $_SESSION['tablecount'] = $count;


          $pageSize = 10;
        $page = 1;
          // $sql = "select t_club.*,t_dz_account.account from t_club,t_dz_account where t_club.player_id = t_dz_account.id group by id limit 0,10";
        
           // echo $sql;die;
             $data['data'] = $this->usermodel->getRows($sql);           

       $data['pageInfo'] = $this->usermodel->getPageInfo($_SESSION['tablecount'],$page,$pageSize);
        $maxPage = intval(($_SESSION['tablecount'] + $pageSize) / $pageSize)-1;
        $data['maxPage'] = $maxPage;
        $data['pageList'] = $this->usermodel->getPageList($maxPage, $page);
                 
            $this->load->view('hotblood/site/iframe_tablelist',$data);
     
        }
           //搜索分页
        function gettablelistpage_s(){
            $this->load->model('User_model','usermodel');
            $data = array();
         $param = $this->uri->segment(3); //获取uri的第3段
          if (!isset($param) || strlen($param) < 1) {
            $page = 1;
        } else {
            $page = $param;
        }

    $count = $_SESSION['tablecount'];
    $pageSize = 10;
       $data['pageInfo'] = $this->usermodel->getPageInfo($count,$page,$pageSize);

        $maxPage = intval(($count + $pageSize) / $pageSize);

        $data['maxPage'] = $maxPage;
        $data['pageList'] = $this->usermodel->getPageList($maxPage, $page);
        $offset = ($page-1)*$pageSize;

 if(isset($_SESSION['tablewhere'])){
  $where = $_SESSION['tablewhere'];
        $where = "(table_code = '$where' or player_name = '$where' or player_id = '$account' or player_id = '$where' or table_name = '$where')";
           $sql = "select table_id,table_name,player_name,player_id,player_num,table_code,table_state from t_dz_gametable where $where and table_state != 4 order by table_id limit $offset,10";
             $sqlnolimit = "select count(table_id) from t_dz_gametable where $where and table_state != 4 order by table_id";
       }else{
               $sql = "select table_id,table_name,player_name,player_id,player_num,table_code,table_state from t_dz_gametable where table_state != 4 order by table_id limit $offset,10";
                  $sqlnolimit = "select count(table_id) from t_dz_gametable where table_state != 4 order by table_id";
       }
$data['data'] = $this->usermodel->getRows($sql);    
      
            
         $this->load->view('hotblood/site/iframe_tablelist',$data);
        }

        function recommend(){
           $this->load->model('User_model','usermodel');
              $id = $_GET['id'];
              $sql = "select * from t_club_recommend where club_id = $id";
              $data = $this->usermodel->getRows($sql);
              if(count($data) == 0){
                $data['data'] = '该俱乐部还未加入推荐！';           
              }else{
                $data['data'] = $this->getclub_recommend($data);
              }
                $data['id'] = $id;
             $this->load->view('hotblood/site/iframe_club_recommend',$data);
        }
        //俱乐部的列表
        function getclub_recommend($data){

        // <font size="2" color="red"> echo $v['func_name'].','; </font>
        $html = "<div align='center'>当前俱乐部的关键字如下</div>";
          foreach($data as $k=>$v){
            $html .= "<span class=$v[id]><font size=2 color=red> $v[keywords] </font><span onclick=deletes('$v[id]') style=cursor:pointer>删除</span>&nbsp&nbsp&nbsp</span>";
          }
        return $html;
        }
        //insert
        function club_recommend_form(){
           $this->load->model('User_model','usermodel');
        //insert t_bs_funnum
        $keywords = $_POST['keywords'];
        $club_id = $_POST['club_id'];
        $data = array(
            'club_id' => $club_id,
            'keywords' => $keywords
          );
         $id = $this->usermodel->insert($data,'t_club_recommend',true);
         if($id){
          echo "<div align='center' style='color:#F00 ; font-size:40px ; height=70px' >添加关键字成功!<div>";
         }else{
          echo "<div align='center' style='color:#F00 ; font-size:40px ; height=70px' >操作失败！<div>";
         }
        }
        //del ajax
        function del_recommend(){
            $param = $this->uri->segment(3); //获取uri的第3段
            $this->load->model('User_model','usermodel');
             $where = array('id' => $param);
          $id = $this->usermodel->delete($where,'t_club_recommend',true);
        }

	}
 ?>