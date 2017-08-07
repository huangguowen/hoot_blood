
<?php
defined('IN_ADMIN') or exit('No permission resources.');
$show_validator = true;
include $this->admin_tpl('header');?>
<script type="text/javascript">
<!--
// $(function(){
// 	$.formValidator.initConfig({autotip:true,formid:"myform",onerror:function(msg){}});

// 	$("#play_id").formValidator({onshow:"<?php echo L('input').L('play_id')?>",onfocus:"<?php echo L('play_id').L('between_1_to_20')?>"}).inputValidator({min:1,max:20,onerror:"<?php echo L('play_id').L('between_1_to_20')?>"}).ajaxValidator({
// 	    type : "get",
// 		url : "",
// 		data :"m=admin&c=admin_manage&a=public_checkplay_id_ajx",
// 		datatype : "html",
// 		async:'false',
// 		success : function(data){	
//             if( data == "0" )
// 			{
//                 return false;
// 			}
//             else
// 			{
// 				 var divshow = $("#username_p");
// 				//将account放到 id = play_idTip
// 				divshow.text('玩家名为：'+data)
//                 return true;
//                 // alert('你将为该玩家添加代理功能:'+data)
// 			}
// 		},
// 		buttons: $("#dosubmit"),
// 		onerror : "不存在该玩家用户！",
// 		onwait : "<?php echo L('connecting_please_wait')?>"
// 	});
// 	$("#code").formValidator({onshow:"<?php echo L('input').L('code')?>",onfocus:"<?php echo L('code').L('between_4_to_20')?>"}).inputValidator({min:4,max:20,onerror:"<?php echo L('code').L('between_4_to_20')?>"});
// 	$("#password").formValidator({onshow:"<?php echo L('input').L('password')?>",onfocus:"<?php echo L('password').L('between_6_to_20')?>"}).inputValidator({min:6,max:20,onerror:"<?php echo L('password').L('between_6_to_20')?>"});

// 	$("#bank_name").formValidator({onshow:"<?php echo L('input').L('bank_name')?>",onfocus:"<?php echo '请输入您的银行名称'?>"}).inputValidator({min:2,max:20,onerror:"<?php echo '请输入您的银行名称'?>"});

// 	$("#bank_code").formValidator({onshow:"<?php echo L('input').L('bank_code')?>",onfocus:"<?php echo '请输入您的银行卡号'?>"}).inputValidator({min:10,max:30,onerror:"<?php echo '请输入您的银行卡号'?>"});

// 	$("#bank_place").formValidator({onshow:"<?php echo L('input').L('bank_place')?>",onfocus:"<?php echo '请输入您的开户地';?>"}).inputValidator({min:2,max:40,onerror:"<?php echo '请输入您的开户地'?>"});

// 	$("#pwdconfirm").formValidator({onshow:"<?php echo L('input').L('cofirmpwd')?>",onfocus:"<?php echo L('input').L('passwords_not_match')?>",oncorrect:"<?php echo L('passwords_match')?>"}).compareValidator({desid:"password",operateor:"=",onerror:"<?php echo L('input').L('passwords_not_match')?>"});

// 	$("#phone").formValidator({onshow:"<?php echo L('input').L('phone')?>",onfocus:"<?php echo L('phone').L('between_2_to_20')?>"}).inputValidator({min:11,max:11,onerror:"<?php echo L('phone').L('between_11_to_11')?>"});
// })
//-->
</script>
<div class="pad_10">
<div class="common-form">
<form name="myform" action="?m=admin&c=admin_manage&a=fanli&menuid=54" method="post" id="myform">
<table width="100%" class="table_form contentWrap">
<tr>
<td width="80">总代获取二代返佣比例</td> 
<td><input type="test" name="info[play_id]"  class="input-text" id="play_id"></input></td>
</tr>

<input type="hidden" name="info[username]" value="test">
<input type="hidden" name="info[url]" value="<?php echo $_GET['menuid']?>">



<tr>
<td>总代获取一代返佣比例</td>
<td>
<input type="text" name="info[bank_name]" value="" class="input-text" id="bank_name" size="30"></input>
</td>
</tr>

<tr>
<td>总代获取一代返佣比例</td>
<td>
<input type="text" name="info[bank_code]" value="" class="input-text" id="bank_code" size="30"></input>
</td>
</tr>

</table>
    <div class="bk15"></div>
    <input type="hidden" name="info[admin_manage_code]" value="<?php echo $admin_manage_code?>" id="admin_manage_code"></input>
    <input name="dosubmit" type="submit" value="<?php echo L('submit')?>" class="button">
</form>
</div>
</div>
</body>
</html>

