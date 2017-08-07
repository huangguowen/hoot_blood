<?php 
	$this->load->model('User_model','usermodel');
 ?>
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
				<div class="tt">牌桌列表</div>
				<div class="item clearfix">
					<label>关键字：</label>
					<form action="<?php echo base_url('club/searchtable');?>" method='post'>
					<input class="keyw" type="text" placeholder="会员ID、用户名或者昵称,桌子名称,编号" name="keywords" style="width: 360px" />
					<!-- <a class="search_btn" href="javascript:;" type="submit">搜索</a> -->
					<input type="submit" class="search_btn" value="搜索">
					</form>
				</div>
			</div>
			<div class="listBox">
				<div class="datatable">
					<table border="0" cellpadding="0" cellspacing="0">
						<tr>
							<th class="tool"><input type="checkbox" class="checkboxAll"/></th>
							<th>牌局ID</th>
							<th>牌局名称</th>
							<th>创建人</th>
							<th>头像</th>
							<th>桌子编号</th>
							<th>桌子状态</th>
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
							<td class="tool"><input type="checkbox" class="checkboxItem"/></td>
							<td><?php echo $v['table_id']; ?></td>
							<td><?php echo $v['table_name']; ?></td>
							<td><?php echo $v['player_name']; ?></td>
							<td><img src="<?php echo @$this->usermodel->getimagebyid($v['table_id']); ?>" alt="" /></td>
							<td><?php echo $v['table_code']; ?></td>
							<td><?php if($v['table_state'] == 1){echo '无人在牌局';}elseif($v['table_state'] == 2){echo '游戏中';}else{echo '游戏暂停';} ?></td>
						</tr>
						<?php } ?>
					</table>
				</div>	
			</div>
									<?php
if ($pageInfo['count'] > 1) {
	if(!isset($_SESSION['tablewhere'])){
    $page_action = base_url() . "club/" . 'gettablelistpage' . "/";
}else{
	   $page_action = base_url() . "club/" . 'gettablelistpage_s' . "/";
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
                    <a href="<?php echo($page_action . $pageInfo['prep']); ?>"><img
                            src="<?php echo base_url() . 'static/images/pre.gif'; ?>"/></a>
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
