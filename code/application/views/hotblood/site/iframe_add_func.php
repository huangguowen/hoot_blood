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
	<link href="<?php echo base_url().'static/'?>css/bootstrap.min.css" rel="stylesheet" media="screen">
	<link href="<?php echo base_url().'static/'?>css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
    <link rel="stylesheet" href="<?php echo base_url().'static/'?>css/uploadify.css">
	<link rel="stylesheet" href="<?php echo base_url().'static/'?>css/main.css">
</head>
<body class="iframebody f_diamondC">
	<div class="picSettingCnt clearfix f_diamondC">
	<form action="<?php echo base_url('user/change_func_form') ?>" method="post">
		<div class="f_diamond">
			<div class="item clearfix">
				<label>选项一项操作类型：</label>
				<input class="diamondSet" name="change_type" type="radio" value="1" checked="checked" />
				<span>增加</span>
				<input class="diamondSet" name="change_type" type="radio" value="-1" />
				<span>减少</span>
			</div>
			<div class="item clearfix">
				<label>选择一项功能: </label>
				<select name="func">
				<?php foreach($func as $k=>$v){ ?>
					<option value="<?php echo $v['id'];?>"><?php echo $v['func_name']; ?></option>
					<?php } ?>
				</select>
			</div>
		<input type="hidden" name="dept_id" value="<?php echo $dept_id; ?>">
	
			<div class="item clearfix">
				<input class="save_btn" type="submit" value="确定" onclick=""/>
			</div>
		</div>
		</form>
			<div align="center"><font size="4">已有功能</font> </div>
			<div style="height: 40px;"></div>
			<?php foreach($in_func as $k=>$v){ ?>
				<font size="2" color="red">	<?php echo $v['func_name'].','; ?></font>
			<?php } ?>
	</div>
	<script src="<?php echo base_url().'static/'?>js/jquery.min.js"></script>
	<script src="<?php echo base_url().'static/'?>js/layer/layer.js"></script>
	<script src="<?php echo base_url().'static/'?>js/bootstrap-datetimepicker.min.js"></script>
	<script src="<?php echo base_url().'static/'?>js/datetimepicker.js"></script>
	<script src="<?php echo base_url().'static/'?>js/cropbox.js"></script>
	<script src="<?php echo base_url().'static/'?>js/main.js"></script>
</body>
</html>
