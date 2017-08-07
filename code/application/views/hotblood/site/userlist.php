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
			<!-- <div class="section"> -->
				<!-- <div class="item clearfix">
					<label>用户类型：</label>
					<select class="address box-sizing">
						<?php echo $ostype; ?>
					</select>
					<label>状态：</label>
					<select class="address box-sizing">
						<?php echo $status; ?>
					</select>
					<label>充值：</label>
					<select class="address box-sizing">
						<?php echo $recharge1; ?>
					</select>
					<label>充值：</label>
					<select class="address box-sizing">
							<?php echo $recharge2; ?>
					</select>
				</div> -->
				<div class="time_section">
				<form action="<?php echo base_url('user/searchuser');?>" method='post'>
					<label>关键字：</label>
					
					<input class="keyw" type="text" placeholder="请输入会员ID、用户名或者昵称" name="keywords"/>

					<label>日期：</label>
				<input type="text" class="datetime" id="datePrev" value="" placeholder="请选择开始时间" name="starttime" />
				-
				<input type="text" class="datetime" id="dateNext" value="" placeholder="请选择结束时间" name="endtime" />
					<!-- <a class="search_btn" href="javascript:;" type="submit">搜索</a> -->
					<input type="submit" class="search_btn" value="搜索">
					</form>
				</div>
			<!-- </div> -->
			<div class="listBox">
				<a href="javascript:;" class="resetAllPic_btn">头像重置</a>
				<div class="datatable">
					<table border="0" cellpadding="0" cellspacing="0">
						<tr>
							<th class="tool"><input type="checkbox" class="checkboxAll"/></th>
							<th>ID</th>
							<th>用户名</th>
							<th>昵称</th>
							<th>头像</th>
							<th>充值情况(元)</th>
							<th>钻石余额</th>
							<th>金币余额</th>
							<th>赠送金币</th>
							<th>注册时间</th>
							<th>最后登录时间</th>
							<th>最后登录IP</th>
							<th>状态</th>
							<th>操作</th>
						</tr>
						<?php 
							foreach($data as $k=>$v){
						 ?>
						<tr>
							<td class="tool"><input type="checkbox" class="checkboxItem"/></td>
							<td><?php echo $v['id'];?></td>
							<td><?php echo $v['account'];?></td>
							<td><?php echo $v['nickname'];?></td>
							<td><img src="<?php echo $v['image']; ?>" alt="" /></td>
							<td><?php echo ($this->usermodel->getrecharge($v['id']))?></td>
							<td><?php echo $v['diamond'];?></td>
							<td><?php echo $v['coins'];?></td>
							<td><?php //echo ($this->usermodel->getgift_coins($v['id']));?></td>
							<td><?php echo $v['register_time'];?></td>
							
							<td><?php echo $v['last_login_time'];?></td>
							<td><?php echo $v['last_login_ip'];?></td>
							<td><?php if($v['status'] == 1){echo '正常';}?></td>
							<td class="operation">
								<a href="<?php echo base_url('user/updateuser/'.$v['id']);?>">编辑</a>
								<a href="javascript:;">头像重置</a>
								<a href="javascript:;">加入观察</a>
								<a href="javascript:;">锁定</a>
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
	if(!isset($_SESSION['userwhere'])){
     $page_action = base_url() . "user/" . 'getuserlistpage' . "/";
}else{
	   $page_action = base_url() . "user/" . 'searchpage' . "/";
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