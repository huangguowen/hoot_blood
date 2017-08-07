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
		<div class="f_index">
			<ul class="clearfix">
				<li>
					<div class="item">
						<div class="tt">会员统计</div>
						<div class="cnt">
							<p>注册量：<span><?php echo $playerscount;?></span>   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;今日注册用户数量: <span><?php echo $today_register;?></span></p>
							<p>俱乐部数量：<span><?php echo $clubscount;?></span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;昨日日注册用户数量: <span><?php echo $yesterday_register;?></span></p>
							<p>当前牌局数量：<span><?php echo $onlinetablecount;?></span></p>
							<p>当前在线用户数量：<span><?php echo $onlineplayers;?></span></p>
							<p></p>
						</div>
					</div>
				</li>
				<li>
					<div class="item">
						<div class="tt">数据充值</div>
						<div class="cnt">
							<p>今日充值金额：<span><?php echo $todayrecharge;?></span>共<span><?php echo $todayrechargecount;?></span>单</p>
							<p>昨日充值金额：<span><?php echo $yestadaycharge;?></span>共<span><?php echo $yestadayrechargecount;?></span>单</p>
						</div>
					</div>
				</li>
				<li>
					<div class="item">
						<div class="tt">充值来源</div>
						<div class="cnt">
							<p>微信：<span><?php echo $paytypewx;?></span></p>
							<p>支付宝：<span><?php echo $paytypeali;?></span></p>
							<p>苹果支付：<span><?php echo $paytypeap?></span></p>
							<p>后台添加  : <span><?php echo $paytypepc?></span></p>
						</div>
					</div>
				</li>
				<li>
					<div class="item">
						<div class="tt">代理审核(待定)</div>
						<div class="cnt">
							<p>待审核代理：<span>4302</span></p>
						</div>
					</div>
				</li>
				<li>
					<div class="item">
						<div class="tt">赠送数据</div>
						<div class="cnt">
							<p>赠送金币：<span>4</span></p>
						</div>
					</div>
				</li>
				<li>
					<div class="item">
						<div class="tt">热门俱乐部</div>
						<div class="cnt">
						</div>
					</div>
				</li>
			</ul>
		</div>
		<script src="<?php echo base_url().'/static';?>/js/jquery.min.js"></script>
		<script src="<?php echo base_url().'/static';?>/js/layer/layer.js"></script>
		<script src="<?php echo base_url().'/static';?>/js/bootstrap-datetimepicker.min.js"></script>
		<script src="<?php echo base_url().'/static';?>/js/datetimepicker.js"></script>
		<script src="<?php echo base_url().'/static';?>/js/cropbox.js"></script>
		<script src="<?php echo base_url().'/static';?>/js/main.js"></script>		
	</body>
</html>