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
		<div class="f_userList">
			<div class="time_section">
				<b>消费类型：</b>
				<select class="address box-sizing">
					<option value="全部">全部</option>
				</select>
				<b>日期：</b>
				<input type="text" class="datetime" id="datePrev" value="" placeholder="请选择开始时间" />
				-
				<input type="text" class="datetime" id="dateNext" value="" placeholder="请选择结束时间" />
				<b>关键字：</b>
				<input class="keyw" type="text" placeholder="请输入会员ID、用户名、订单号" />
				<a href="javascript:;" class="search_btn">搜索</a>
				<a href="javascript:;" class="download_btn">导出</a>
			</div>
			<div class="listBox">
				<div class="datatable">
					<table border="0" cellpadding="0" cellspacing="0">
						<tr>
							<th>ID</th>
							<th>消费类型</th>
							<th>用户名</th>
							<th>昵称</th>
							<th>消耗钻石</th>
							<th>操作时间</th>
						</tr>
						<tr>
							<td>000001</td>
							<td>兑换金币</td>
							<td>qq_1497408324888</td>
							<td>手机用户9947</td>
							<td>0</td>
							<td>2017-06-13 22:05:00</td>
						</tr>
						<tr>
							<td>000001</td>
							<td>兑换金币</td>
							<td>qq_1497408324888</td>
							<td>手机用户9947</td>
							<td>0</td>
							<td>2017-06-13 22:05:00</td>
						</tr>
					</table>
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