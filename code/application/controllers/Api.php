<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller{



    var $configlib;
    var $userlib;
    var $log_user_id;
    var $vals = array(
    'word'      => 'Random word',
    'img_path'  => './captcha/',
    'img_url'   => 'http://localhost/code/captcha/',
    'font_path' => './path/to/fonts/texb.ttf',
    'img_width' => '183',
    'img_height'    => 46,
    'expiration'    => 7200,
    'word_length'   => 8,
    'font_size' => 32,
    'img_id'    => 'Imageid',
    'pool'      => '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',

    // White background and border, black text and red grid
    'colors'    => array(
        'background' => array(255, 255, 255),
        'border' => array(255, 255, 255),
        'text' => array(0, 0, 0),
        'grid' => array(255, 40, 40)
    )
);


    function __construct(){
        parent::__construct();
        $this->load->library('session');
        $this->api = $this->load->database('online',TRUE);
        $this->niuniu = $this->load->database('niuniu',TRUE);
    }
    function apiinterface()
    {
        header("Content-type:text/html;charset=utf-8");
$output = array();
$appid = @$_POST['appid'] ? $_POST['appid'] : '0';
$time = @$_POST['time'] ? $_POST['time'] : 0;
$money = @$_POST['money'] ? $_POST['money'] : 0;
$orderid = @$_POST['orderid'] ? $_POST['orderid'] : '20170619-2-default-254999999944';
$paytype = @$_POST['paytype'] ? $_POST['paytype'] : 0;
$attach = @$_POST['attach'] ? $_POST['attach'] : 0;
$sign = @$_POST['sign'] ? $_POST['sign'] : 0;


 //检查用户
  if (!isset($appid)) {
$output = array('data'=>NULL, 'info'=>'The appid is null!', 'code'=>-401);
exit(json_encode($output));
  }
  

  $sql = "select * from t_dz_unfinished_consume where consume_number = '$orderid' and pay_status = 1";
  $res = $this->api->query($sql);
  $res = $res->result_array();
  if(empty($res)){
    $output = array('data'=>NULL, 'info'=>'The orderid is null!', 'code'=>-401);
exit(json_encode($output));
}
$array = array();
if(count($res)>0){
$sqll ="update t_dz_unfinished_consume set pay_status = 2 , pay = $money where consume_number = '$orderid'";
$this->api->query($sqll);
}
//写入到日志
 $this->writeLog($res[0]['consume_number'].'-'.$res[0]['pay'].'-'.$res[0]['nickname']);


//输出数据
  $output = array(
'data' => array(
  'userInfo' => $res,
), 
'info' => 'success',
'code' => 200,
  );
  exit(json_encode($output));
    }

        //已经弃用
    function logfile($str,$log='debug'){
        $log.=date('Ymd');
        $logfile = fopen(base_url().'logs/'.$log.'.log','a+');
        // var_dump(base_url());die;
        if(is_array($str)){
            $str = print_r($str,TRUE);
        }
        fwrite($logfile,"\r\n [".date("Y-m-d H:i:s",time())."] ".$str);
        fclose($logfile);
    }
//文件写入

    function writeLog($msg){

         $dir = ('apilogs');
        if (!file_exists($dir)){
            mkdir ($dir,0777,true);
        }
 $logFile = 'apilogs'.'/'.date('Y-m-d').'.txt';
 $msg = date('Y-m-d H:i:s').' >>> '.$msg."\r\n";
 file_put_contents($logFile,$msg,FILE_APPEND );
}

    // 根据id 
    function get_club_data(){
//         $data = array();
// $callback = $_GET['callback'];
// echo $callback.'('.json_encode($data).')';
// exit;
            $output = array();
            $club_id = @$_GET['club_id'] ? $_GET['club_id'] : '1';
             $sql = "select * from t_club where mingpian = '$club_id'";
              $res = $this->api->query($sql);
              $res = $res->result_array();
              if(empty($res)){
                $output = array('club_data'=>NULL, 'info'=>'The club_id is null!', 'code'=>-401);
                $callback = @$_GET['callback'];
             echo $callback.'('.json_encode($output).')';die;
            }

            //输出数据
              $output = array(
            'data' => array(
              'club_data' => $res,
            ), 
            'info' => 'success',
            'code' => 200,
              );

               $callback = @$_GET['callback'];
             echo $callback.'('.json_encode($output).')';die;
    }
    //点击 获取短信验证码API
    function get_verification(){
        //这里先验证码图像验证码的正确性
        $captshow =  @$_GET['captshow'] ? $_GET['captshow'] : '0';
        $account =  @$_GET['account'] ? $_GET['account'] : 'asfasfas';

            /* 获取图片验证码 从memcache读取 */
         $this->load->library('memcache_api', '', 'memca');
        $captcha_mem = $this->memca->get_captshow($account);
        if($captshow != $captcha_mem){
              $output = array('data'=>NULL, 'info'=>'图片验证码错误!', 'code'=>-201);
              $callback = @$_GET['callback'];
              echo $callback.'('.json_encode($output).')';die;
        }
      
//验证通过发送请求给 短信验证 url  生成一个$_SESSION['duanxin']
        $ms = $this->send_ms($account);
      // 短息验证码存入到 memcache
       $duanxin_mem = $this->memca->set_duanxin($account,$ms);

    $output = array('data'=>$captcha_mem, 'info'=>'success', 'code'=>200);

              $callback = @$_GET['callback'];
             echo $callback.'('.json_encode($output).')';die;
    }

    function register(){
        header("Content-type:text/html;charset=utf-8");
            $output = array();
            $club_id = @$_GET['club_id'] ? $_GET['club_id'] : '0';
            $account = @$_GET['account'] ? $_GET['account'] : '0';
            //提交过来的加密密码
            $password = @$_GET['password'] ? $_GET['password'] : '0';
            $nickname = @$_GET['nickname'] ? $_GET['nickname'] : '0';
            $sex = @$_GET['sex'] ? $_GET['sex'] : '0';
            //短信验证码
            $verification = @$_GET['verification'] ? $_GET['verification'] : '0';
            $ip =  @$_GET['ip'] ? $_GET['ip'] : '0';
         
            //处理加密过的密码
            $password = $this->base_pass($password);

$this->load->library('memcache_api', '', 'memca');
 $duanxin_mems = $this->memca->get_duanxin($account);
//短信验证码是否一致(前端验证)
if($verification != $duanxin_mems){
            $output = array('data'=>NULL, 'info'=>'verification error', 'code'=>-201);
             $callback = @$_GET['callback'];
             echo $callback.'('.json_encode($output).')';die;
}

           

        $this->load->model('User_model','usermodel');
//查询是否已经注册
        $sql = "select count(*) from t_dz_account where account = $account";
        $res = $this->usermodel->getOne($sql);

   if($res == 1){
            $output = array('data'=>NULL, 'info'=>'已存在该用户', 'code'=>-301);
              $callback = @$_GET['callback'];
             echo $callback.'('.json_encode($output).')';die;
}
        //入库t_dz_account 以及 t_dz_player
        $dataaccount = array(
                'account'=>"$account",
                'register_time'=>date('Y-m-d H:i:s',time()),
                'register_ip'=>$ip,
                'status'=>1,
                'password'=>md5($password),
                'platform'=>'PC',
                'device'=>'win64',
            );
    $cid = $this->usermodel->insert($dataaccount,'t_dz_account',true);
    if($cid){
        $externID = $this->get_exprentid();
        $dataplayer = array(
                'id'=>$cid,
                'nickname'=>"$nickname",
                'sex'=>$sex,
                 'coins'=>2000,
                'diamond'=>50,
                'extern_id'=>$externID,
                'vip_grade'=>1
            );
     $pid = $this->usermodel->insert($dataplayer,'t_dz_player',true);
 }
     if(isset($pid) and isset($cid)){
        $r_time = date('Y-m-d H:i:s',time());
          //根据名片查询club_id
        $sql = "select id from t_club where mingpian = '$club_id'";
        $club_ids = $this->api->query($sql);
         $club_ids = $club_ids->result_array();
         $club_id = $club_ids[0]['id'];
        //俱乐部是否满员 
         $sql = "select player_curr,player_num from t_club where id = '$club_id'";
        $manyuan = $this->api->query($sql);
         $manyuan = $manyuan->result_array();
         if($manyuan[0]['player_curr'] == $manyuan[0]['player_num']){
            //满了
             unset($_SESSION["pic_verify"]);
            unset($_SESSION["duanxin"]);
             $output = array('data'=>$pid, 'info'=>'添加成功,俱乐部满员', 'code'=>200);
              $callback = @$_GET['callback'];
             echo $callback.'('.json_encode($output).')';die;
         }

        //加入俱乐部
          $club_mes = array(
                'club_id'=>$club_id,
                'player_type'=>3,
                'player_id'=>$cid,
                'state'=>1,
                 'apply_msg'=>'I am from api',
                'look_state'=>2,
                'create_time'=>date('Y-m-d H:i:s',time())
            );
           $clubid = $this->usermodel->insert($club_mes,'t_club_player',true);
          //加入俱乐部后 删除这个验证码的session 以及图片 session
            unset($_SESSION["pic_verify"]);
            unset($_SESSION["duanxin"]);
            $output = array('data'=>$pid, 'info'=>'添加成功', 'code'=>200);
              $callback = @$_GET['callback'];
             echo $callback.'('.json_encode($output).')';die;
 }else{
            $output = array('data'=>NULL, 'info'=>'insert error', 'code'=>-409);
              $callback = @$_GET['callback'];
             echo $callback.'('.json_encode($output).')';die;
 }

}
    function get_exprentid(){
     $this->load->model('User_model','usermodel');
      $data = array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25);
      foreach($data as $k=>$v){
            $string[$k] = $this->haha();
            $num[$k] = $string[$k]['num'];
            $str[$k] = $string[$k]['str'];
            if(strpos($string[$k]['str'],'1')){
            }else{
              $sql = "select count(*) from t_dz_player where extern_id = $num[$k]";
                     $res = $this->usermodel->getOne($sql);
                 if($res == 1){
                      }else{
                        return $string[$k]['num'];die;
                      }
            }
          }
             // if(strpos($string,'1')){
             //       $this->haha();
             //    }else{
             //         $sql = "select count(*) from t_dz_player where extern_id = $num";
             //         $res = $this->usermodel->getOne($sql);
             //     if($res == 1){
             //            $this->haha();
             //        }else{
             //       return $num;
             //   }
             //    }
    }
    //连号顺号
    function haha(){
       $num = $this->unique_rand(2000000,5000000,1);
         $num = $num[0];
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
    $data = array();
    $data['str'] = implode('', $haha);
    $data['num'] = $num;
    return $data;
    
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


      function captshow($account)  
    {  
       
 // header("Content-type: text/html; charset=utf-8");      
        /*  
        * 初始化  
        */  
        $border = 0; //是否要边框 1要:0不要  
        $how = 4; //验证码位数  
        $w = 183; //图片宽度  
        $h = 46; //图片高度  
        $fontsize = 8; //字体大小  
        $alpha = "abcdefghijkmnopqrstuvwxyz"; //验证码内容1:字母  
        $number = "023456789"; //验证码内容2:数字  
        $randcode = ""; //验证码字符串初始化  
        srand((double)microtime()*1000000); //初始化随机数种子  
          
        $im = ImageCreate($w, $h); //创建验证图片  
          
        /*  
        * 绘制基本框架  
        */  
        $bgcolor = ImageColorAllocate($im, 255, 255, 255); //设置背景颜色  
        ImageFill($im, 0, 0, $bgcolor); //填充背景色  
        if($border)  
        {  
            $black = ImageColorAllocate($im, 0, 0, 0); //设置边框颜色  
            ImageRectangle($im, 0, 0, $w-1, $h-1, $black);//绘制边框  
        }  
          
        /*  
        * 逐位产生随机字符  
        */  
        for($i=0; $i<$how; $i++)  
        {     
            $alpha_or_number = mt_rand(0, 1); //字母还是数字  
            $str = $alpha_or_number ? $alpha : $number;  
            $which = mt_rand(0, strlen($str)-1); //取哪个字符  
            $code = substr($str, $which, 1); //取字符  
            $j = !$i ? 4 : $j+15; //绘字符位置  
            $color3 = ImageColorAllocate($im, mt_rand(0,100), mt_rand(0,100), mt_rand(0,100)); //字符随即颜色  
            ImageChar($im, $fontsize, $j, 3, $code, $color3); //绘字符  
            $randcode .= $code; //逐位加入验证码字符串  
        }  
          
        /*  
        * 添加干扰  
        */  
        for($i=0; $i<5; $i++)//绘背景干扰线  
        {     
            $color1 = ImageColorAllocate($im, mt_rand(0,255), mt_rand(0,255), mt_rand(0,255)); //干扰线颜色  
            ImageArc($im, mt_rand(-5,$w), mt_rand(-5,$h), mt_rand(20,300), mt_rand(20,200), 55, 44, $color1); //干扰线  
        }     
        for($i=0; $i<$how*15; $i++)//绘背景干扰点  
        {     
            $color2 = ImageColorAllocate($im, mt_rand(0,255), mt_rand(0,255), mt_rand(0,255)); //干扰点颜色   
            ImageSetPixel($im, mt_rand(0,$w), mt_rand(0,$h), $color2); //干扰点  
        }  
          
        //把验证码字符串写入session  
          
        //$this->session->set_userdata(array($this->name=>$randcode));  
          
        //$_SESSION['pic_verify']=$randcode;  
        
        //验证码memcache 存储
         $memcache_cap = $this->setmem_captshow($account,$randcode);

        /*绘图结束*/  
        Imagegif($im);  
        ImageDestroy($im);  
        /*绘图结束*/  
    }  


    function connectmemcache($host="127.0.0.1")
    {

        $this->memcache = new Memcache;
        $ret = $this->memcache->connect($host, 11211);
        if($ret==false)
        {
            for($i=0;$i<count($this->allHosts);$i++)
            {
                $ret = $this->memcache->connect($this->allHosts[$i], 11211);
                if($ret==true)
                {
                    $this->host = $this->allHosts[$i];
                    return $ret;
                }
            }
        }
            
        if($ret==false)
        {    
            echo ("Could not connect Memcached server!!!");
        }   
        return $ret;
    }

      //验证码在前端显示 (图片验证码接口)
    function verify_image() {  
        $account = @$_GET['account'];
    $conf['name'] = 'verify_code'; //作为配置参数  
            //验证码config信息
         if($conf!="")  
        {  
            foreach($conf as $key=>$value)  
            {  
                $this->$key=$value;  
            }  
        }  

    
    $this->captshow($account);  
    $yzm_session = $this->session->userdata('verify_code'); 
    echo $yzm_session;  
} 

function base_pass($password){
    header('Content-Type:text/html;Charset=utf-8;');  
  //密码 password
$password = isset($password) ? $password : 'ObMBe+x/XsU7WQwL4LMx8w==';  

$pk = "123454536f667445454d537973576562";
$iv = substr("1234577290ABCDEF1264147890ACAE45", 0, 16);

$c= trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $pk,base64_decode($password), MCRYPT_MODE_CBC, $iv));
 
 return $c;
}
function send_ms($account){
    $this->load->library('sms_send', '', 'duanxin');
    $code = $this->duanxin->send($account);
    return $code;
}
//存session
function set_sessions(){
      //密码 password
$session['pic_verify'] = isset($_POST['pic_verify']) ? $_POST['pic_verify'] : '0';  
}
//图形验证码memcache 存储
function setmem_captshow($account,$captshow_code){
     $this->load->library('memcache_api', '', 'memca');
    $code = $this->memca->set_captshow($account,$captshow_code);
    return $code;
}
//短信验证码memcache 存储
function setmem_duanxin($account,$duanxin_code){
     $this->load->library('memcache_api', '', 'memca');
    $ms = $this->memca->set_duanxin($account,$duanxin_code);
    return $ms;
}


 function finish_pay()
    {

       //存储微信的回调
        $xml = $GLOBALS['HTTP_RAW_POST_DATA']; 
        $xta  = $this->xml_to_array($xml);  
        $order_id = $xta['out_trade_no'];

//写入到日志
$this->writeLog_niuniu('订单号：'.$order_id);

  $sql = "select * from t_dz_unfinished_consume where consume_number = '$order_id' and pay_status = 1";
  $res = $this->niuniu->query($sql);
    $res = $res->result_array();

  if(empty($res)){
   $this->return_xml_success('failed');
}

       //修改订单状态
   $sqll = "update t_dz_unfinished_consume set pay_status = 2 where consume_number = '$order_id'";
  $this->niuniu->query($sqll);

//写入到日志
$this->writeLog_niuniu('订单号：'.$res[0]['consume_number'].'-支付金额'.$res[0]['pay'].'-别名'.$res[0]['nickname']);

  //返回成功
  $this->return_xml_success('ok');

    }

    //文件写入

    function writeLog_niuniu($msg){

         $dir = ('apilogs_niuniu');
        if (!file_exists($dir)){
            mkdir ($dir,0777,true);
        }
 $logFile = 'apilogs_niuniu'.'/'.date('Y-m-d').'.txt';
 $msg = date('Y-m-d H:i:s').' >>> '.$msg."\r\n";
 file_put_contents($logFile,$msg,FILE_APPEND );
}

function set_xml($args)
    {
        $xml = "<xml>";
        foreach ($args as $key=>$val)
        {
            if (is_numeric($val)){
                $xml.="<".$key.">".$val."</".$key.">";
            }else{
                $xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
            }
        }
        $xml.="</xml>";
        return $xml; 
}
function return_xml_success($msg){
        $arr = array(
            'return_code'   =>  'SUCCESS',
            'return_msg'    =>  $msg
        );
        $xml = $this->set_xml($arr);
        echo $xml;
        exit();
 }



function xml_to_array($xml)                              
{                                                        
//禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        $values = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $values;                                         
} 
    
}
