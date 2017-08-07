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
	<form action="<?php echo base_url('user/change_diamonds_form') ?>" method="post">
		<div class="f_diamond">
			<div class="item clearfix">
				<label>用户名：</label>
				<span><input name="account" value="<?php echo $account; ?>" readonly="readonly"></input></span>
			</div>
			<div class="item clearfix">
				<label>选项：</label>
				<input class="diamondSet" name="diamondset" type="radio" value="1" checked="checked" />
				<span>增加</span>
				<!-- <input class="diamondSet" name="diamondset" type="radio" value="-1" />
				<span>扣除</span> -->
			</div>
			<div class="item clearfix">
				<label>选择砖石数目: </label>
				<select name="diamond">
					<?php echo $select; ?>
				</select>
			</div>
			<!-- <div class="item clearfix">
				<label>钻石：</label>
				<input type="text" class="nickname" name="diamond" />
			</div> -->
			<div class="item clearfix">
				<label>说明：</label>
				<textarea class="autograph" name="content"></textarea>
			</div>
			<input type="hidden" name="id" value="<?php echo $id;?>">
			<div class="item clearfix">
				<input class="save_btn" type="submit" value="确定" onclick="return confirm(this.form);"/>
			</div>
		</div>
		</form>
	</div>
	<script src="<?php echo base_url().'static/'?>js/jquery.min.js"></script>
	<script src="<?php echo base_url().'static/'?>js/layer/layer.js"></script>
	<script src="<?php echo base_url().'static/'?>js/bootstrap-datetimepicker.min.js"></script>
	<script src="<?php echo base_url().'static/'?>js/datetimepicker.js"></script>
	<script src="<?php echo base_url().'static/'?>js/cropbox.js"></script>
	<script src="<?php echo base_url().'static/'?>js/main.js"></script>
</body>
</html>
<script>

function isNumber(value) {
    var patrn = /^[0-9]*$/;
    if (patrn.exec(value) == null || value == "") {
        return false
    } else {
        return true
    }
}

         function confirm(form) {
         	diamond = $("select[name='diamond']").val()
          if( diamond== '' || isNumber(diamond)==false) {
                alert("请输入正确的砖石数量!");
                return false;
           }
       
          // document.myform.submit();

}


</script>