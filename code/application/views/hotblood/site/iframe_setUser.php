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

		<div class="f_setUser">
		<form action="<?php echo base_url('user/updateuser_form') ?>" method="post">
			<div class="section">
				<div class="tt">用户修改</div>
				<div class="item clearfix">
					<label>ID：</label>
					<span><?php echo $id; ?></span>
				</div>
				<div class="item clearfix">
					<label>用户名：</label>
					<span><?php echo $account; ?></span>
				</div>
				<div class="item clearfix">
					<label>昵称：</label>
						<input type="text" name="nickname" value="<?php echo $nickname;?>">
				</div>
				<input type="hidden" name="image" value="">
				<input type="hidden" name="id" value="<?php echo $id; ?>">
				<div class="item clearfix">
					<label>性别</label>
					<input class="sex" name="sex" type="radio" value="0" <?php if($sex == 0){echo "checked='checked'";}?> />
					<span>男</span>
					<input class="sex" name="sex" type="radio" value="1" <?php if($sex == 1){echo "checked='checked'";}?>/>
					<span>女</span>
				</div>
				<div class="item clearfix">
					<label>头像/封面</label>
					<img id="avatar_img" src="<?php echo $image;?>" default-src="<?php echo base_url('static/')?>images/loading.jpg" alt="头像/封面" class="pic" onclick="showLayerIframe('设置新头像',<?php echo base_url('user/setimage');?>,'600px','480px')" title="修改头像/封面"/>
					<a href="javascript:;" class="resetPic_btn">重置默认头像</a>
				</div>
				<div class="item clearfix">
					<label>充值情况：</label>
					<span><b><?php echo $recharge;?>元</b>（累计）</span>
				</div>
				<div class="item clearfix">
					<label>最后充值时间：</label>
					<span><?php echo $maxorder_time?></span>
				</div>
				<div class="item clearfix">
					<label>钻石余额：</label>
					<span>
					<b id="diamond_sh"><?php echo $diamonds; ?></b>
				<!-- 	<b><div src="<?php echo base_url('user/diamon_refush/'.$id);?>"></div></b> -->
					<a href="javascript:showLayerIframe('增/扣钻石','change_diamonds?account=<?php echo $account?>&id=<?php echo $id; ?>','600px','480px');">增加钻石</a></span>
				</div>

				<div class="item clearfix">
					<label>金币余额：</label>
					<span><b><?php echo $coins; ?></b>（包含赠送部分金币）
				<!-- 	<a href="javascript:showLayerIframe('增/扣金币','change_coins?account=<?php echo $account?>&id=<?php echo $id; ?>','600px','480px');">增/扣金币</a> -->
					</span>
				</div>

				<div class="item clearfix">
					<label>赠送金币：</label>
					<span><b><?php echo $gift_coins; ?></b></span>
				</div>
				<div class="item clearfix">
					<label>注册时间：</label>
					<span><?php echo $register_time; ?></span>
				</div>
				<div class="item clearfix">
					<label>最后登录时间：</label>
					<span><?php echo $last_login_time; ?></span>
				</div>
				<div class="item clearfix">
					<label>最后登录IP：</label>
					<span><?php echo $last_login_ip; ?></span>
				</div>
				<div class="item clearfix">
					<input class="save_btn" type="submit" value="修改"  onclick="getimgurl()"/>
					<a class="cancel_btn" href="javascript:;">返回</a>
				</div>
			</div>
			</form>
		</div>
		<script src="<?php echo base_url().'/static';?>/js/jquery.min.js"></script>
		<script src="<?php echo base_url().'/static';?>/js/layer/layer.js"></script>
		<script src="<?php echo base_url().'/static';?>/js/bootstrap-datetimepicker.min.js"></script>
		<script src="<?php echo base_url().'/static';?>/js/datetimepicker.js"></script>
		<script src="<?php echo base_url().'/static';?>/js/cropbox.js"></script>
		<script src="<?php echo base_url().'/static';?>/js/main.js"></script>		
	</body>
</html>

<script>
 
    function getimgurl(){
    	img = $("#avatar_img").attr('src')
    	imageput = $("input[name='image']").attr('value',img);
    }
</script>