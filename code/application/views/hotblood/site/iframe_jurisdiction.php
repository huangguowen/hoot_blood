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
			<div class="section">
				<div class="tt" align="center">部门分布情况</div>
			</div>
			<div class="listBox">
				<div class="datatable">
					<table border="0" cellpadding="0" cellspacing="0">
						<tr>
							<th>id</th>
							<th>部门名称</th>
							<th>操作</th>
						</tr>
					<!-- 	<tr>
							<td class="tool"><input type="checkbox" class="checkboxItem"/></td>
							<td>000001</td>
							<td>qq_1497408324888</td>
							<td>手机用户9947</td>
							<td><img src="images/tx.jpg" alt="" /></td>
							<td>10/60</td>
							<td>1/3</td>
						</tr> -->
						<?php foreach($data as $k=>$v){ ?>
						<tr>
							<td><?php echo $v['id']; ?></td>
							<td><?php echo $v['dept_name']; ?></td>
							<td>
							<?php if($v['id'] != 1){ ?>

							<?php}else{  ?>
							<a href="javascript:showLayerIframe('增/扣钻石','change_func?dept_id=<?php echo $v['id'];?>','600px','480px');"><font  color="blue">增/减功能</font></a>
							<?php } ?>
							</td>
						</tr>
						<?php } ?>
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
<script>

 function onGoPage(page) {
        var url = "<?php echo $page_action;?>" + page;
        location.href = url;
    }
</script>
