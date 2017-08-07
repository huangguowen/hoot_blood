<?php 
// print_r($data);die;
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
		<form action="<?php echo base_url('finanace/search_recharge'); ?>" method="post">
			<div class="time_section">
				<b>订单状态：</b>
				<select class="address box-sizing" name="pay_status">
					<option value="全部">全部</option>
					<?php echo $pay_status_option; ?>
				</select>
				<b>日期：</b>
				<input type="text" class="datetime" id="datePrev" value="" placeholder="请选择开始时间" name="starttime" />
				-
				<input type="text" class="datetime" id="dateNext" value="" placeholder="请选择结束时间" name="endtime" />
				<b>关键字：</b>
				<input class="keyw" type="text" placeholder="请输入会员ID、用户名、订单号" name="keywords" />
				<!-- <a href="javascript:;" class="search_btn">搜索</a> -->
				<input type="submit" class="search_btn" value="搜索">
				<a href="<?php echo base_url('finanace/get_recharge_excel'); ?>" class="download_btn">导出</a>
				<p>人民币金额统计：<?php echo @$data['sumprice'];?> 元</p>
			</div>
			</form>
			<div class="listBox">
				<div class="datatable">
					<table border="0" cellpadding="0" cellspacing="0">
						<tr>
							<th>ID</th>
							<th>用户名</th>
							<th>昵称</th>
							<th>人民币</th>
							<th>金额</th>
							<th>赠送钻石数</th>
							<th>商户订单号</th>
							<th>支付类型</th>
							<th>订单状态</th>
							<th>提交时间</th>
						</tr>
					<!-- 	<tr>
							<td>000001</td>
							<td>qq_1497408324888</td>
							<td>手机用户9947</td>
							<td>30.00</td>
							<td>3000</td>
							<td>0</td>
							<td>12495_12495_0613220500_7576</td>
							<td>支付宝</td>
							<td>未支付</td>
							<td>2017-06-13 22:05:00</td>
						</tr> -->
						<?php unset($data['sumprice']); ?>
						<?php foreach($data as $k=>$v){ ?>
						<tr>
							<td><?php echo $k+1;?></td>
					
							<td><?php echo ($this->usermodel->getusernamebyid($v['player_id'])); ?></td>

							<td><?php echo ($this->usermodel->getnickbyid($v['player_id']));?></td>

							<td><?php echo @$v['product_price']; ?></td>
							<td><?php echo @$v['product_price']*10; ?></td>
							<td>0</td>
							<td><?php echo $v['consume_number'] ?></td>
							<td><?php if($v['pay_type'] == 14){echo '中润付';}elseif($v['pay_type'] == 2){echo '苹果支付';}elseif($v['pay_type'] == 13){echo '微信支付';}elseif($v['pay_type'] == 99){echo '后台操作';}else{echo '默认支付';} ?></td>
							<td><?php if($v['pay_status'] == 1){echo '未支付';}elseif($v['pay_status'] == 2){echo '支付成功';}else{echo '支付失败';} ?></td>
							<td><?php echo $v['order_time']; ?></td>
						</tr>
						<?php } ?>
					</table>
				</div>	
			</div>
		</div>
	<?php
if ($pageInfo['count'] > 1) {
	if(!isset($_SESSION['recharge_where'])){
    $page_action = base_url() . "finanace/" . 'getrechargepage' . "/";
}else{
	  $page_action = base_url() . "finanace/" . 'searchpage' . "/";
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