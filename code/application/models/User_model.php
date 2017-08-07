<?php 
	/**
	* user finanace club 通用
	*/
	class User_model extends BaseModel
	{
	 var $this_CI; // 在类库中为了调用CI系统的东西，特意记录CI的实例
   var $mydb;
	function __construct()
  {
             parent::__construct();
             $this->this_CI = & get_instance();
             $this->mydb = $this->this_CI->load->database('api', true);
  }
  	function getusers(){
  		 $sql = "select nickname,sum_score from t_dz_player";
        $res = $this->getRows($sql);
      return $res;
  	}
  	//根据id查询在t_dz_consume_history 查询是否有充值记录。如果没有
  	function getrecharge($id){
 		$sql = "select sum(product_price) from t_dz_consume_history where player_id = $id";
        $res = $this->getOne($sql);
        return $res;
  	}
  	//根据id获取昵称
  	function getnickbyid($id){ 
  		$sql = "select nickname from t_dz_player where id = $id";
  		 $res = $this->getOne($sql);
      return $res;
  	}

        //根据昵称获取id
    function getidbynick($nickname){ 
      $sql = "select id from t_dz_player where nickname = '$nickname'";
       $res = $this->getOne($sql);
      return $res;
    }

        //根据昵称获取id
    function getidbyusername($account){ 
      $sql = "select id from t_dz_account where account = '$account'";
       $res = $this->getOne($sql);
      return $res;
    }


	//根据id获取用户名
	function getusernamebyid($id){
		$sql = "select account from t_dz_account where id = $id";
	 $res = $this->getOne($sql);
      return $res;
	}
    //根据id获取性别
  function getsexbyid($id){ 
    $sql = "select sex from t_dz_player where id = $id";
   $res = $this->getOne($sql);
      return $res;
  }
      //根据id获取图片
  function getimagebyid($id){
    
    $sql = "select image from t_dz_player where id = $id";
   $res = $this->getOne($sql);
      return $res;
  }
//根据userid查他的历史充值记录(不含内部订单)
  function getrechargebyid($id){
        $this->mydb->select("sum(product_price)");
        $this->mydb->from('t_dz_consume_history');
        $this->mydb->where('player_id',$id);
        $result = $this->mydb->get();
        if ($result->num_rows() > 0) {
          $data = $result->row_array();
        }
          return $data['sum(product_price)'];
  }


  //查询今日充值总额
  function getallrecharge_today($table,$today,$tomorrow){
   $sql = "select sum(product_price) from $table where order_time>=('$today') and order_time<=('$tomorrow')";
     $data = $this->getOne($sql);
   return $data;
  }

  //查询昨日充值总额
  function getallrecharge_yesterday($table,$today,$yesterday){
   $sql = "select sum(product_price) from $table where order_time>=('$yesterday') and order_time<=('$today')";
   $data = $this->getOne($sql);
   return $data;
  }
  //今日单数
  function getallcount_today($table,$today,$tomorrow){
$sql = "select count(id) from $table where order_time>=('$today') and order_time<=('$tomorrow')";
 $data = $this->getOne($sql);
   return $data;
  }

    //今日单数
  function getregister_today($table,$today,$tomorrow){
$sql = "select count(id) from $table where register_time>=('$today') and register_time<=('$tomorrow')";
 $data = $this->getOne($sql);
   return $data;
  }

  //昨日单数
  function getallcount_yesterday($table,$today,$yesterday){
$sql = "select count(id) from $table where order_time>=('$yesterday') and order_time<=('$today')";
 $data = $this->getOne($sql);
   return $data;
  }
  //充值来源
  function getpaystyle($table,$pay_type){

      $sql = "select sum(product_price) from $table where pay_type = $pay_type";
       $data = $this->getOne($sql);
   return $data;
  }

  //查询一条最后充值记录
  function getmaxrechargebyid($id){
        $sql = "select max(order_time) from t_dz_consume_history where player_id = $id";
        // echo $sql;die;
        $res = $this->getOne($sql);
        return $res;
  }

  function getdiamondsbyid($id){
    $this->mydb->select("diamond");
        $this->mydb->from('t_dz_player');
        $this->mydb->where('id',$id);
        $result = $this->mydb->get();
        if ($result->num_rows() > 0) {
          $data = $result->row_array();
        }
          return $data['diamond'];
  }

    function getcoinsbyid($id){
    $this->mydb->select("coins");
        $this->mydb->from('t_dz_player');
        $this->mydb->where('id',$id);
        $result = $this->mydb->get();
        if ($result->num_rows() > 0) {
          $data = $result->row_array();
        }
          return $data['coins'];
  }

      function getregister_time_byid($id){
    $this->mydb->select("register_time");
        $this->mydb->from('t_dz_account');
        $this->mydb->where('id',$id);
        $result = $this->mydb->get();
        if ($result->num_rows() > 0) {
          $data = $result->row_array();
        }
          return $data['register_time'];
  }
  function getlast_login_time_byid($id){
    $this->mydb->select("last_login_time");
        $this->mydb->from('t_dz_player');
        $this->mydb->where('id',$id);
        $result = $this->mydb->get();
        if ($result->num_rows() > 0) {
          $data = $result->row_array();
        }
          return $data['last_login_time'];
  }
   function getlast_login_ip_byid($id){
    $this->mydb->select("last_login_ip");
        $this->mydb->from('t_dz_player');
        $this->mydb->where('id',$id);
        $result = $this->mydb->get();
        if ($result->num_rows() > 0) {
          $data = $result->row_array();
        }
          return $data['last_login_ip'];
  }
  //根据p_id 查询 change_money是否存在
  function ischange($id){
        $this->mydb->from('t_dz_change_money'); 
          $this->mydb->where('player_id',$id); 
        return $this->mydb->count_all_results();  
  }
  //增加
  function filedchange_add($filed,$table,$where,$diamond){
    $this->mydb->where($where);
    $this->mydb->set("$filed","$filed + $diamond",FALSE);
    $this->mydb->update("$table");  
  }

    //减少
  function filedchange_reduce($filed,$table,$where,$diamond){
    $this->mydb->where($where);
    $this->mydb->set("$filed","$filed - $diamond",FALSE);
    $this->mydb->update("$table");  
    // echo $this->mydb->last_query();die;
  }
  //获取赠送的金币BYID
  function getgift_coins($id){
        $this->mydb->select("sum(coins)");
        $this->mydb->from('t_dz_change_money');
        $this->mydb->where('player_id',$id);
        $this->mydb->where('change_type',1);
        $result = $this->mydb->get();
        if ($result->num_rows() > 0) {
          $data = $result->row_array();
        }
          $add = $data['sum(coins)'];


           $this->mydb->select("sum(coins)");
        $this->mydb->from('t_dz_change_money');
        $this->mydb->where('player_id',$id);
        $this->mydb->where('change_type',-1);
        $result = $this->mydb->get();
        if ($result->num_rows() > 0) {
          $data = $result->row_array();
        }
          $reduce = $data['sum(coins)'];

          return ($add-$reduce);
  }
  //更新个人信息
  function setuser_data($data,$id){
     $this->mydb->where('id',$id);
    $this->mydb->update("t_dz_player",$data); 
     return $this->mydb->affected_rows();
  }
//根据club_id查询座子个数
  function get_table_count_byid($id){
      $this->mydb->from('t_dz_gametable'); 
          $this->mydb->where('club_id',$id); 
             $this->mydb->where('table_state',1); 
        return $this->mydb->count_all_results();  
  }

  function get_bsusername_byid($id){
      $sql = "select username from t_bs_user where id = $id";
    $res = $this->getOne($sql);
      return $res;
  }
  function get_bsimage_byid($id){
      $sql = "select image from t_bs_user where id = $id";
   $res = $this->getOne($sql);
      return $res;
  }

  function get_online_data($id){
     $sql = "select diamond from t_dz_online_player where id = $id";
   $res = $this->getOne($sql);
      return $res;
  }
// login
  function login_sql($where){
 $this->mydb->select("id");
 $this->mydb->from('t_bs_user');
 foreach($where as $k=>$v){
 $this->mydb->where("$k",$v);
}
$r = $this->mydb->get();
   if ($r->num_rows() > 0) {
          $data = $r->row_array();
        }
       return $data['id'];
  } 
  
}
 ?>