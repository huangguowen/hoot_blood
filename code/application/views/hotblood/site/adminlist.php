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
					<table border="0" cellpadding="0" cellspacing="0">
						<tr>
							<!-- <th class="tool"><input type="checkbox" class="checkboxAll"/></th> -->
							<th>ID</th>
							<th>用户名</th>
							<th>部门</th>
							<th>头像</th>
							<!-- <th>操作</th> -->
						</tr>
						<?php 
							foreach($data as $k=>$v){
						 ?>
						<tr>
							<!-- <td class="tool"><input type="checkbox" class="checkboxItem"/></td> -->
							<td><?php echo $v['id'];?></td>
							<td><?php echo $v['username'];?></td>
							<td><?php if($v['dept_id'] == 2){echo '市场部';}elseif($v['dept_id'] == 3){echo '技术部';}else{echo '管理员';};?></td>
							<td><img src="<?php echo $v['image']; ?>" alt="" /></td>
							<!-- <td><a href="<?php echo base_url('user/updateadmin/'.$v['id']);?>">编辑</a></td> -->
							<?php } ?>
					</table>

				</div>	
			</div>
		</div>
								<?php
if ($pageInfo['count'] > 1) {
	if(!isset($_SESSION['userwhere'])){
     $page_action = base_url() . "user/" . 'getadminlistpage' . "/";
}else{
	   $page_action = base_url() . "user/" . 'getadminlistpage' . "/";
}
   
    ?>
    <table class="table table-bordered">
        <tr height="40">

            <td>
                <?php if ($pageInfo['now'] != $pageInfo['prep']) { ?>
                    <a href="<?php echo($page_action . $pageInfo['start']); ?>">首页</a>
                <?php } ?>
            </td>

            <td>
                <?php if ($pageInfo['now'] != $pageInfo['prep']) { ?>
                    <a href="<?php echo($page_action . $pageInfo['prep']); ?>">
                    <img src="<?php echo base_url() . 'static/images/pre.gif'; ?>" /></a>
                           
                <?php } ?>
            </td>

            <td>
                <?php if ($pageInfo['now'] != $pageInfo['next']) { ?>
                    <a href="<?php echo($page_action . $pageInfo['next']); ?>"><img
                            src="<?php echo base_url() . 'static/images/next.gif'; ?>"/></a>
                <?php } ?>
            </td>

            <td>
                <?php if ($pageInfo['now'] != $pageInfo['end']) { ?>
                    <div class="tdinfo"><a href="<?php echo($page_action . $pageInfo['end']); ?>">尾页</a></div>
                <?php } ?>
            </td>

            <td>
                跳<select class="input-mini" name="<?php echo "ShipmentPageSelect"; ?>"
                         onchange="onGoPage(this.value);"><?php echo $pageList; ?></select>页
            </td>

        </tr>
    </table>
<?php } else {
    $page_action = "#"; ?>
    <table class="table table-bordered" width="800">
        <tr height="40">
            <td>
            </td>
            <td></td>
            <td></td>
        </tr>
    </table>
<?php } ?>
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