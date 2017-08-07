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
	<link href="<?php echo base_url().'/static';?>/css/bootstrap.min.css" rel="stylesheet" media="screen">
	<link href="<?php echo base_url().'/static';?>/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
    <link rel="stylesheet" href="<?php echo base_url().'/static';?>/css/uploadify.css">
	<link rel="stylesheet" href="<?php echo base_url().'/static';?>/css/main.css">
	</head>
	<body>
		<div class="top info clearfix">
			<div class="fl"><a href="#"><span>热血德州</span></a></div>
			<div class="fm">后台管理系统</div>
			<div class="fr">
				<a href="#" class="headpic"><img src="<?php echo $_SESSION['image'];?>" alt=""></a>
				<div class="info clearfix">
					<a href="#" class="name">欢迎，<?php echo $_SESSION['login_name'];?></a>
				</div>
				<a href="#" class="refresh">刷新</a>
				<a href="<?php echo base_url('user/logout');?>" class="quit">退出</a>
				<!--<a href="login.html" class="login">登录</a>-->
			</div>
		</div>
		<div class="containter">
			<!-- 左侧 -->
			<div class="left">					
				<div class="nav">
					<ul>
						<li class="current"><a class="a_index" href="<?php echo base_url('user/homepage');?>" target="open_frame">首页</a></li>
						<li><a class="a_code sub" href="javascript:;" target="open_frame">用户管理</a><i></i></li>
						<div class="sub_nav" style="display: block;">
							<a href="<?php echo base_url('user/userlist');?>" target="open_frame">玩家列表</a>
							<a href="<?php echo base_url('user/adminlist');?>" target="open_frame">管理员列表</a>
							<a href="<?php echo base_url('user/adduser');?>" target="open_frame">添加玩家用户</a>
							<a href="<?php echo base_url('user/add_bsuser');?>" target="open_frame">添加后台用户</a>
						</div>
						<li><a class="a_wm sub" href="javascript:;" target="open_frame">财务管理</a><i></i></li>
						<div class="sub_nav" style="display: block;">
							<a href="<?php echo base_url('finanace/index');?>" target="open_frame">充值管理</a>
							<a href="<?php echo base_url('finanace/diamond');?>" target="open_frame">钻石消费记录</a>
							<a href="<?php echo base_url('finanace/coin');?>" target="open_frame">金币消费记录</a>
							<a href="<?php echo base_url('finanace/give');?>" target="open_frame">赠送管理</a>
						</div>
						<li><a class="a_finance sub" href="javascript:;" target="open_frame">俱乐部管理</a><i></i></li>
						<div class="sub_nav" style="display: block;">
							<a href="<?php echo base_url('club/index');?>" target="open_frame">俱乐部列表</a>
							<a href="<?php echo base_url('club/table');?>" target="open_frame">当前牌桌情况</a>
						</div>		
						<li><a src="<?php echo base_url().'/static';?>/images/log.png" href="javascript:;" target="open_frame">日志</a><i></i></li>
						<div class="sub_nav" style="display: block;">
							<a href="<?php echo base_url('log/gift_logs');?>" target="open_frame">操作日志</a>
						</div>	
						<li><a class="a_code sub" href="javascript:;" target="open_frame">权限管理</a><i></i></li></li>	
						<div class="sub_nav" style="display: block;">
							<a href="<?php echo base_url('user/jurisdiction');?>" target="open_frame">部门功能</a>
						</div>					
					</ul>
				</div>
			</div>
			<!-- 右侧 -->
			<div class="right">
				<!--<div class="site"><a href="index.html">网站主后台</a>&gt;<span>会员首页</span></div>-->
				<div class="main">
					<iframe id="main_frame" name="open_frame" border="0" frameboder="0" scrolling="auto" width="100%" height="100%" src="<?php echo base_url('user/homepage');?>"></iframe>
				</div>
			</div>	
		</div>
		<script src="<?php echo base_url().'/static';?>/js/jquery.min.js"></script>
		<script src="<?php echo base_url().'/static';?>/js/layer/layer.js"></script>
		<script src="<?php echo base_url().'/static';?>/js/bootstrap-datetimepicker.min.js"></script>
		<script src="<?php echo base_url().'/static';?>/js/datetimepicker.js"></script>
		<script src="<?php echo base_url().'/static';?>/js/cropbox.js"></script>
		<script src="<?php echo base_url().'/static';?>/js/main.js"></script>		
	</body>
</html>