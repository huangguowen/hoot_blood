<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
	<meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="description" content="热血德州">
    <meta name="keywords" content="热血德州">
	<meta name="applicable-device" content="pc">
	<meta http-equiv="Cache-Control" content="no-transform" />
	<meta http-equiv="Cache-Control" content="no-siteapp"/>
	<meta name="format-detection" content="telephone=no">
    <title>热血德州</title>
	<link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
	<link href="css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
    <link rel="stylesheet" href="static/css/uploadify.css">
	<link rel="stylesheet" href="static/css/main.css">
	</head>
	<body>
		<div class="loginCnt">
			<div class="loginBox">
				<div class="logo"></div>
				<form action="<?php echo base_url('Welcome/Validation'); ?>" class="layui-form" method="post">
				<div class="itemBox">
					<div class="tt">后台登录</div>
					<div class="item">
						<p>账号</p>
						<input class="input_b" type="text" name="userName"/>
					</div>
					<div class="item">
						<p>密码</p>
						<input class="input_b" type="password" name="password" />
					</div>
					<div class="item">
						<!-- <p>验证码</p>
						<input class="input_s" type="text"  name="verify" />
						<a class="qcode" href="javascript:;" title="看不清，换一张"><img src="images/code.jpg" /></a>
					</div> -->

				 
          <p>验证码<?php //echo $_SESSION['verify'];?></p>
          
           <input class="input_s" type="text"  name="verify"/>
           <a class="qcode" href="javascript:;" title="看不清，换一张"><img src="<?php echo base_url('Welcome/verify_image') ?>" id='verify_code' onclick="changeCode()"/>

           </a>
           
      

					<!-- <div class="notice">提示信息</div> -->
					<div class="item">
						<input class="input_login" type="submit" value="登录" />
					</div>
				</div>
				</form>
			</div>
		</div>
		<script src="static/js/jquery.min.js"></script>
		<script src="static/js/layer/layer.js"></script>
		<script src="static/js/bootstrap-datetimepicker.min.js"></script>
		<script src="static/js/datetimepicker.js"></script>
		<script src="static/js/cropbox.js"></script>
		<script src="static/js/main.js"></script>		
	</body>
</html>
<script type="text/javascript">  
    function changeCode(){  
    	
    
         $("#verify_code").attr('src',"<?php echo base_url('Welcome/verify_image');?>");  
    }  
</script>  