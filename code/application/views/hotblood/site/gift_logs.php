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
						
							<th>操作成员</th>
							<th>玩家名称</th>
							<th>玩家昵称</th>
							<th>操作时间</th>
							<th>备注信息</th>
							<th>操作记录</th>
						</tr>
						<?php 
							foreach($data as $k=>$v){
						 ?>
						<tr>
							<td><?php if($v['change_user'] == ''){echo 'admin';}else{echo $v['change_user'];}?></td>
							<td><?php echo $v['username'];?></td>
							<td><?php echo $v['nickname'];?></td>
							<td><?php echo $v['change_time'];?></td>
							<th><?php echo $v['content']; ?></th>
							<td>
<?php if($v['change_type'] == 1 and $v['coins'] != 0){echo '增加'.$v['coins'].'金币';} ?>
		  <?php if($v['change_type'] == -1 and $v['coins'] != 0){echo '扣除'.$v['coins'].'金币';} ?>
		  		  <?php if($v['change_type'] == 1 and $v['diamond'] != 0){echo '增加'.($v['diamond']*10).'砖石';} ?>
		  		  		  <?php if($v['change_type'] == -1 and $v['diamond'] != 0){echo '扣除'.$v['diamond'].'砖石';} ?>
							</td>
							
					
						</tr>
						<?php 
						} ?>
					</table>

				</div>	
			</div>
		</div>
								<?php
if ($pageInfo['count'] > 1) {
	
	   $page_action = base_url() . "log/" . 'gift_logs_page' . "/";

   
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