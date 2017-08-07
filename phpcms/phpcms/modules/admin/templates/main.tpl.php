<?php
defined('IN_ADMIN') or exit('No permission resources.');
include PC_PATH.'modules'.DIRECTORY_SEPARATOR.'admin'.DIRECTORY_SEPARATOR.'templates'.DIRECTORY_SEPARATOR.'header.tpl.php';
?>

<div id="main_frameid" class="pad-10 display" style="_margin-right:-12px;_width:98.9%;">
<script type="text/javascript">
$(function(){if ($.browser.msie && parseInt($.browser.version) < 7) $('#browserVersionAlert').show();}); 
</script>
<div class="explain-col mb10" style="display:none" id="browserVersionAlert">
<?php echo L('ie8_tip')?></div>
<?php 
	if($_SESSION['userid'] == 1){
 ?>
<div class="col-2 lf mr10" style="width:48%">
	<h6>今日数据</h6>
	<div class="content">
		新增玩家:<?php echo $todaycount; ?><br />
	当前在线:<?php echo $onlineplayers['count']; ?> <br />
	消耗房卡:<br />
	售出砖石:<?php echo $diamond[0]['product_price'] * 10 ?><br />
	兑换房卡: <br />
	盈利收入: <?php echo $diamond[0]['product_price']?><br />
	</div>
</div>
<div class="col-2 col-auto">
	<h6>昨日数据</h6>
	<div class="content">
	新增玩家:<?php echo $yesterdaycount; ?><br />
	当前在线: <br />
	消耗房卡:<br />
	售出砖石:<?php echo $diamondyes[0]['product_price']*10; ?><br />
	兑换房卡: <br />
	盈利收入:<?php echo $diamondyes[0]['product_price']; ?> <br />
	</div>
</div>

<!-- <div class="col-2 lf mr10" style="width:48%">
	<h6>近一周数据</h6>
	<div class="content">
		新增玩家:<br />
	当前在线: <br />
	消耗房卡:<br />
	售出砖石:<br />
	兑换房卡: <br />
	盈利收入: <br />
	</div>
</div>
<div class="col-2 col-auto">
	<h6>近一月数据</h6>
	<div class="content">
		新增玩家:<br />
	当前在线: <br />
	消耗房卡:<br />
	售出砖石:<br />
	兑换房卡: <br />
	盈利收入: <br />
	</div> -->
<?php } ?>
</body></html>
