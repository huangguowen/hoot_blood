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
			
			<div class="listBox">
				<div class="datatable">
				<form action="<?php echo base_url('club/club_recommend_form') ?>" method="post">
					<table border="0" cellpadding="0" cellspacing="0">
						<tr>
						
							<th>推荐关键字</th>
					
						</tr>

						<tr>
							
							<td><input type="text" name="keywords"></td>
						</tr>
						<input type="hidden" name="club_id" value="<?php echo $id;?>">
<div><?php echo $data; ?></div>
					</table>
					<div class="item clearfix" align="center">
				<input class="save_btn" type="submit" value="确定" onclick=""  />
			</div>
					</form>
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
<script>
		function deletes(param){
			  var url = "<?php echo base_url().'club/del_recommend/'?>"+param
        $.ajax({
            type: "GET",
            url: url,
            success: function (html) {
               parent = $('.'+param);
                parent.remove()
            }
        });
		}

</script>
