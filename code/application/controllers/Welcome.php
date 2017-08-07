<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends MY_Controller{

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

    //用户账号及密码暂时写死
    var $loginer = array(
            'admin' => 'admin888',
            'bobo' => 'bobo3.145',
               'wangxiang' => 'wx3.145',
                  'dafei' => 'dafei3.145',
        );
	function __construct(){
		parent::__construct();
	

	 	$this->load->library('session');

	}
	public function index()
	{
		$this->load->helper('captcha');
		 $this->load->model('User_model','user');
		$this->load->view('hotblood/login');
	}


	public function Validation(){
          $this->load->model('User_model','usermodel');
        $username = $_POST['userName'];
        if($username == "' or 1=1#"){
           $this->go(3,base_url(),'请不要做傻事了！');
        }
        $password = md5($_POST['password']);
                $where = array('username'=>$username,'password'=>$password);
       @$user_id = $this->usermodel->login_sql($where);
       if(!$user_id){
         $this->go(3,base_url(),'请核对你的账号密码正确性并能正确输入验证码');
       }

         if(isset($user_id) and $_SESSION['verify'] == $_POST['verify']){
                 $_SESSION['user_id'] = $user_id;
                 $_SESSION['login_name'] = $this->usermodel->get_bsusername_byid($user_id);
                 $_SESSION['image'] = $this->usermodel->get_bsimage_byid($user_id);
            $this->log_user_id = $user_id;
            redirect(base_url('user/index'));
         }
         /* 暂时写死
        $uid = 0;
        $users = $this->loginer;


        foreach ($users as $key => $value) {
            $uid = $uid+1;
            if($key == $_POST['userName'] and $value == $_POST['password'] and $_SESSION['verify'] == $_POST['verify']){

            $_SESSION['user_id'] = $uid;
            $_SESSION['login_name'] = $key;
            $this->log_user_id = $uid;
            redirect(base_url('user/index'));
            }
        }
        */
        $this->go(3,base_url(),'请核对你的账号密码正确性并能正确输入验证码');
	}

	 function go($time, $url, $text)
    {
        header("refresh:$time;url=$url");
        $html = "<body style=' background: url(" . base_url() . "images/error_bg.jpg) repeat-x scroll 0 0 #67ACE4;'>
                    <div id='container' style='margin: 0 auto;padding-top: 50px;text-align: center;width: 560px'>
                        <img style='border: medium none;margin-bottom: 50px' class='png' src='" . base_url() . "static/image/404.gif' />
                          <div align='center' style='margin-top:100px;background-color:##FFFEE6;color:red;font-size:18px;font-weight:bold;'>" . $text . "&nbsp&nbsp请稍等...该页将在" . $time . "秒后自动跳转!</div>
                          
                    </div>
                    <div style='background: url(" . base_url() . "images/error_cloud.png) repeat-x scroll 0 0 transparent;bottom:0;height:170px;position:absolute;width:100%' class='png'></div>
                </body>";
        echo $html;

    }

      function captshow()  
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
          
        $_SESSION['verify']=$randcode;  

        /*绘图结束*/  
        Imagegif($im);  
        ImageDestroy($im);  
        /*绘图结束*/  
    }  

    //验证码在前端显示
    function verify_image() {  
  
    $conf['name'] = 'verify_code'; //作为配置参数  
    		//验证码config信息
		 if($conf!="")  
        {  
            foreach($conf as $key=>$value)  
            {  
                $this->$key=$value;  
            }  
        }  

    
    $this->captshow();  
    $yzm_session = $this->session->userdata('verify_code');  
    echo $yzm_session;  
} 
	
}
