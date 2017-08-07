<?php
/**
  * wechat php test
  */

//define your token
define("TOKEN", "weixin");
$wechatObj = new wechatCallbackapiTest();
// $wechatObj->valid();
$wechatObj->responseMsg();
class wechatCallbackapiTest
{
	public function valid()
    {
        $echoStr = $_GET["echostr"];

        //valid signature , option
        if($this->checkSignature()){
        	echo $echoStr;
        	exit;
        }
    }

		  //自动回复接口
    public function responseMsg()
    {
        //get post data, May be due to the different environments
        //功能与$_POST类似，专门用于接收XML数据
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

        //extract post data
        //判断接收到的XML数据是否为空
        if (!empty($postStr)){
                /* libxml_disable_entity_loader is to prevent XML eXternal Entity Injection,
                   the best way is to check the validity of xml by yourself */
                //解析XML时不解析实体，防止XXE攻击
                libxml_disable_entity_loader(true);
                
                //把接收到的xml数据以simplexml模型进行解析
                $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
                //手机端微信，openid，每个手机都对应一个唯一的值
                $fromUsername = $postObj->FromUserName;
                //微信公众平台
                $toUsername = $postObj->ToUserName;
                
                //定义一个变量，接收MsgType节点（严格区分大小写）
                $msgType = $postObj->MsgType;
                
                //定义一个变量，用于接收语音识别消息
                $rec = $postObj->Recognition;
                
                //定义两个变量，接收经纬度信息
                $latitude = $postObj->Location_X;
                $longitude = $postObj->Location_Y;
                
                //获取用户发送的文本数据
                $keyword = trim($postObj->Content);
                //获取当前时间的时间戳
                $time = time();
                
                //定义文本回复模板
                $textTpl = "<xml>
                            <ToUserName><![CDATA[%s]]></ToUserName>
                            <FromUserName><![CDATA[%s]]></FromUserName>
                            <CreateTime>%s</CreateTime>
                            <MsgType><![CDATA[%s]]></MsgType>
                            <Content><![CDATA[%s]]></Content>
                            <FuncFlag>0</FuncFlag>
                            </xml>"; 
                //定义音乐回复模板
                $musicTpl = "<xml>
                             <ToUserName><![CDATA[%s]]></ToUserName>
                             <FromUserName><![CDATA[%s]]></FromUserName>
                             <CreateTime>%s</CreateTime>
                             <MsgType><![CDATA[%s]]></MsgType>
                             <Music>
                                 <Title><![CDATA[%s]]></Title>
                                 <Description><![CDATA[%s]]></Description>
                                 <MusicUrl><![CDATA[%s]]></MusicUrl>
                                 <HQMusicUrl><![CDATA[%s]]></HQMusicUrl>
                              </Music>
                              </xml>";
                //定义图文消息模板
                $newsTpl = "<xml>
                            <ToUserName><![CDATA[%s]]></ToUserName>
                            <FromUserName><![CDATA[%s]]></FromUserName>
                            <CreateTime>%s</CreateTime>
                            <MsgType><![CDATA[%s]]></MsgType>
                            <ArticleCount>%s</ArticleCount>
                            %s
                            </xml>";
                         
                //判断$msgType的消息类型
                if($msgType=='text') {
                    //判断用户发送的关键词是否为空
                    if(!empty( $keyword ))
                    {
                        if($keyword=='?' || $keyword=='？') {
                            //① 定义相关变量
                            $msgType = 'text';
                            //② 定义回复内容
                            $contentStr = "【1】特种服务号码\n【2】通讯服务号码\n【3】银行服务号码\n【4】用户反馈";
                            //③ 格式化字符串
                            $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                            //④ 输出与返回
                            echo $resultStr;
                        }elseif (strstr($keyword,'苹果')) {
                              //① 定义相关变量
                            $msgType = 'text';
                            //② 定义回复内容
                            $contentStr = "您是指苹果7吗？";
                            //③ 格式化字符串
                            $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                            //④ 输出与返回
                            echo $resultStr;
                        } elseif($keyword=='1') {
                            //① 定义相关变量
                            $msgType = 'text';
                            //② 定义回复内容
                            $contentStr = "常用特种服务号码：\n匪警：110\n火警：119\n急救中心：120";
                            //③ 格式化字符串
                            $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                            //④ 输出与返回
                            echo $resultStr;
                        } elseif($keyword=='4') {
                            //① 定义相关变量
                            $msgType = 'text';
                            //② 定义回复内容
                            $contentStr = "尊敬的用户，为了更好的为您服务，请将系统的不足之处反馈给我们。\n反馈格式：@+建议内容\n例如：@希望增加***号码";
                            //③ 格式化字符串
                            $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                            //④ 输出与返回
                            echo $resultStr;
                        } elseif(strpos($keyword, '@')===0) {
                            //① 定义相关变量
                            $msgType = 'text';
                            //② 定义回复内容
                            $contentStr = "http://callback.rxpkapp.com/wxpay/";
                            //③ 格式化字符串
                            $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                            //④ 输出与返回
                            echo $resultStr;
                        } elseif($keyword=='音乐') {
                            //① 定义相关变量
                            $msgType = 'music';
                            //定义音乐四个基本因素
                            $title = '冰雪奇缘';
                            $desc = '冰雪奇缘原声大碟';
                            $url = 'http://callback.rxpkapp.com/music.mp3';
                            $hqurl = 'http://callback.rxpkapp.com/music.mp3';
                            //② 格式化字符串
                            $resultStr = sprintf($musicTpl, $fromUsername, $toUsername, $time, $msgType, $title, $desc, $url, $hqurl);
                            //③ 返回并输出
                            echo $resultStr;
                        } elseif($keyword=='单图文') {
                            //① 定义相关变量
                            $msgType = 'news';
                            //定义文章数量
                            $count = 1;
                            //定义图文节点
                            $str = '<Articles>';
                            for($i=1;$i<=$count;$i++) {
                                $str .= "<item>
                                            <Title><![CDATA[微信开发]]></Title> 
                                            <Description><![CDATA[和国文哥学习微信开发...]]></Description>
                                            <PicUrl><![CDATA[http://callback.rxpkapp.com/images/{$i}.jpg]]></PicUrl>
                                            <Url><![CDATA[http://callback.rxpkapp.com/index.php]]></Url>
                                         </item>";
                            }
                            $str .= '</Articles>';
                            //② 格式化XML模板
                            $resultStr = sprintf($newsTpl, $fromUsername, $toUsername, $time, $msgType, $count, $str);
                            //③ 返回与输出
                            echo $resultStr;
                        } elseif($keyword=='多图文') {
                            //① 定义相关变量
                            $msgType = 'news';
                            //定义文章数量
                            $count = 4;
                            //定义图文节点
                            $str = '<Articles>';
                            for($i=1;$i<=$count;$i++) {
                                $str .= "<item>
                                <Title><![CDATA[微信开发]]></Title>
                                <Description><![CDATA[和国文哥学习微信开发...]]></Description>
                                <PicUrl><![CDATA[http://callback.rxpkapp.com/images/{$i}.jpg]]></PicUrl>
                                <Url><![CDATA[http://callback.rxpkapp.com/index.php]]></Url>
                                </item>";
                            }
                            $str .= '</Articles>';
                            //② 格式化XML模板
                            $resultStr = sprintf($newsTpl, $fromUsername, $toUsername, $time, $msgType, $count, $str);
                            //③ 返回与输出
                            echo $resultStr;
                        } else {
                            //如果用户输入的关键词与上面的关键词都不匹配时，自动执行此接口
                            //定义一个url地址
                            $url = "http://www.tuling123.com/openapi/api?key=9009fc44f168cfc7055c8a469821ce9b&info={$keyword}";
                            //模拟发送get请求
                            $json = file_get_contents($url);
                            //解析里面的数据
                            $std = json_decode($json);
                            
                            //① 定义相关变量
                            $msgType = 'text';
                            //② 定义回复内容
                            $contentStr = str_replace('<br>', "\n", $std->text);
                            //③ 格式化字符串
                            $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                            //④ 输出与返回
                            echo $resultStr;
                        }
                    }else{
                        echo "Input something...";
                    }
                } elseif($msgType=='image') {
                    //定义回复类型，text以文本形式回复
                    $msgType = "text";
                    //定义回复内容
                    $contentStr = "您发送的是图片消息，美女真漂亮!";
                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                    echo $resultStr;
                } elseif($msgType=='voice') {
                    //定义一个url地址
                    $url = "http://www.tuling123.com/openapi/api?key=9009fc44f168cfc7055c8a469821ce9b&info={$rec}";
                    //模拟发送get请求
                    $json = file_get_contents($url);
                    //解析里面的数据
                    $std = json_decode($json);
                    
                    //① 定义相关变量
                    $msgType = 'text';
                    //② 定义回复内容
                    $contentStr = str_replace('<br>', "\n", $std->text);
                    //③ 格式化字符串
                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                    //④ 输出与返回
                    echo $resultStr;
                } elseif($msgType=='video' || $msgType=='shortvideo') {
                    //定义回复类型，text以文本形式回复
                    $msgType = "text";
                    //定义回复内容
                    $contentStr = "您发送的是视频消息";
                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                    echo $resultStr;
                } elseif($msgType=='location') {
                    //定义回复类型，text以文本形式回复
                    $msgType = "text";
                    
                    //第三方接口开发（百度API）
                    $url = "http://api.map.baidu.com/telematics/v3/reverseGeocoding?location={$longitude},{$latitude}&coord_type=gcj02&output=json&ak=2pReiGS2nQV9Gi7tslO9r2UZ";
                    //模拟http中的get请求
                    $json = file_get_contents($url);
                    //调试输出$json数据
                    $std = json_decode($json);
                    //输出描述内容
                    $addr = $std->description;
                    
                    //定义回复内容
                    $contentStr = "您发送的是地理位置消息，您位于{$addr}！";
                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                    echo $resultStr;
                } elseif($msgType=='link') {
                    //定义回复类型，text以文本形式回复
                    $msgType = "text";
                    //定义回复内容
                    $contentStr = "您发送的是链接消息，图文分享";
                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                    echo $resultStr;
                }
        }else {
            echo "";
            exit;
        }
    }
        
	private function checkSignature()
	{
        // you must define TOKEN by yourself
        if (!defined("TOKEN")) {
            throw new Exception('TOKEN is not defined!');
        }
        
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        		
		$token = TOKEN;
		$tmpArr = array($token, $timestamp, $nonce);
        // use SORT_STRING rule
		sort($tmpArr, SORT_STRING);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );
		
		if( $tmpStr == $signature ){
			return true;
		}else{
			return false;
		}
	}
}

?>