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
	<?php 
$this->load->helper('form');
	 ?>
	 <?php echo form_open_multipart('user/adduserform');?>
		<div class="f_addUser">
			<div class="item clearfix">
				<label>手机号</label>
				<input class="nickname" type="text" name="account" />
				<em>*</em>
			</div>
			<div class="item clearfix">
				<label>密码</label>
				<input class="nickname" type="text" name="password" />
				<em>*</em>
			</div>
			<div class="item clearfix">
				<label>昵称</label>
				<input class="nickname" type="text" name="nickname" />
				<em>*</em>
			</div>
			<div class="item clearfix">
				<label>头像/封面</label>
				<!-- <img id="avatar_img" src="images/loading.jpg" default-src="images/loading.jpg" alt="头像/封面" class="pic" onclick="showLayerIframe('设置新头像','iframe_changePic.html','600px','480px')" title="修改头像/封面" />
				<a href="javascript:;" class="resetPic_btn">取消图片</a> -->

<input type="file" name="userfile" size="20" />
			</div>
			<div class="item clearfix">
				<label>性别</label>
				<input class="sex" name="sex" type="radio" value="0" checked="checked" />
				<span>男</span>
				<input class="sex" name="sex" type="radio" value="1" />
				<span>女</span>
			</div>
			<div class="item clearfix">
				<label>个性签名</label>
				<textarea class="autograph" name="sign"></textarea>
			</div>
			<div class="item clearfix">
				<label>状态</label>
				<input class="state" name="status" type="radio" value="1" />
				<span>开启</span>
				<input class="state" name="status" type="radio" value="0" checked="checked" />
				<span>禁用</span>
			</div>
			<div class="item clearfix">
				<input class="save_btn" type="submit" value="添加" onclick="return confirm(this.form);"/>
				<a class="cancel_btn" href="javascript:;">返回</a>
			</div>
		</div>
		</form>
		<script src="<?php echo base_url().'/static';?>/js/jquery.min.js"></script>
		<script src="<?php echo base_url().'/static';?>/js/layer/layer.js"></script>
		<script src="<?php echo base_url().'/static';?>/js/bootstrap-datetimepicker.min.js"></script>
		<script src="<?php echo base_url().'/static';?>/js/datetimepicker.js"></script>
		<script src="<?php echo base_url().'/static';?>/js/cropbox.js"></script>
		<script src="<?php echo base_url().'/static';?>/js/main.js"></script>		
	</body>
</html>

<script type="text/javascript">
         function confirm(form) {

          if(form.account.value=='') {
                alert("请输入用户帐号!");
                // form.userId.focus();
                return false;
           }
       if(form.password.value==''){
                alert("请输入密码!");
        
                return false;
         }
            if(form.nickname.value==''){
                alert("请输入昵称!");
        
                return false;
         }
      
          document.myform.submit();
}
</script>