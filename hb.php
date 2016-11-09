<?php
$code=$_GET['code'];
$appid='';
$appsecret='';
$json=file_get_contents('https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$appid'.&secret='.$appsecret.'='.$code.'&grant_type=authorization_code');

$arr=json_decode($json,true);
$openid=$arr['openid'];//获取用户OPENID

  $re = sendredpack($openid);//普通红包，直接调用就可用
//  $re = sendgroupredpack($openid);//裂变红包
//  var_dump($re);//调试模式
   //自行替换 $mch_id = '8888888888';$send_name = "商户名称";  $total_amount ='100';//红包金额     
    // 现金红包
    function sendredpack($openid){
       
            $url = "https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack";
           
            $mch_billno = '8888888888' . date ( "YmdHis", time () ) . rand ( 1000, 9999 );      //商户订单号
            $mch_id = '8888888888';                         //微信支付分配的商户号
            $wxappid = 'wxappID';        //公众账号appid
            $send_name = "商户名称";                          //商户名称
            $re_openid = $openid;         //用户openid
            $total_amount ='100';                              // 付款金额，单位分
            $total_num = 1;                                          //红包发放总人数
            $wishing = "恭喜发财";                             //红包祝福语
            $client_ip = "127.0.0.1";                //Ip地址
            $act_name = "鸿宇家超市店庆四周年";                         //活动名称
            $remark = "测试";                                      //备注
            $apikey = "微信支付秘钥";   // key 商户后台设置的  微信商户平台(pay.weixin.qq.com)-->账户设置-->API安全-->密钥设置
            $nonce_str =  md5(rand());                                  //随机字符串，不长于32位
            $m_arr = array (
                    'mch_billno' => $mch_billno,
                    'mch_id' => $mch_id,
                    'wxappid' => $wxappid,
                    'send_name' => $send_name,
                    're_openid' => $re_openid,
                    'total_amount' => $total_amount,
                    'total_num' => $total_num,
                    'wishing' => $wishing,
                    'client_ip' => $client_ip,
                    'act_name' => $act_name,
                    'remark' => $remark,
                    'nonce_str'=> $nonce_str
            );
            array_filter ( $m_arr ); // 清空参数为空的数组元素
            ksort ( $m_arr ); // 按照参数名ASCII码从小到大排序
                   
            $stringA = "";
            foreach ( $m_arr as $key => $row ) {
                $stringA .= "&" . $key . '=' . $row;
            }
            $stringA = substr ( $stringA, 1 );
            // 拼接API密钥：
            $stringSignTemp = $stringA."&key=" . $apikey;
            $sign = strtoupper ( md5 ( $stringSignTemp ) );         //签名
       
            $textTpl = '<xml>
                        <sign><![CDATA[%s]]></sign>
                        <mch_billno><![CDATA[%s]]></mch_billno>
                        <mch_id><![CDATA[%s]]></mch_id>
                        <wxappid><![CDATA[%s]]></wxappid>
                        <send_name><![CDATA[%s]]></send_name>
                        <re_openid><![CDATA[%s]]></re_openid>
                        <total_amount><![CDATA[%s]]></total_amount>
                        <total_num><![CDATA[%s]]></total_num>
                        <wishing><![CDATA[%s]]></wishing>
                        <client_ip><![CDATA[%s]]></client_ip>
                        <act_name><![CDATA[%s]]></act_name>
                        <remark><![CDATA[%s]]></remark>
                        <nonce_str><![CDATA[%s]]></nonce_str>
                        </xml>';
     $resultStr = sprintf($textTpl, $sign, $mch_billno, $mch_id, $wxappid, $send_name,$re_openid,$total_amount,$total_num,$wishing,$client_ip,$act_name,$remark,$nonce_str);
      return curl_post_ssl($url, $resultStr);
    }
    //裂变红包
    function sendgroupredpack($openid)
    {
			$mch_billno = '8888888888' . date ( "YmdHis", time () ) . rand ( 1000, 9999 );      //商户订单号
            $mch_id = '8888888888';                         //微信支付分配的商户号
            $wxappid = 'wxappID';        //公众账号appid
            $send_name = "商户名称";                          //商户名称
            $re_openid = $openid;         //用户openid
            $total_amount = "100";                              //付款金额，单位分
            $total_num = 1;                                          //红包发放总人数
            $amt_type = "ALL_RAND";                      //红包金额设置方式 ALL_RAND—全部随机,商户指定总金额和红包发放总人数，由微信支付随机计算出各红包金额
            $wishing = "恭喜发财";                             //红包祝福语
            $act_name = "关注有礼";                         //活动名称
            $remark = "测试";                                      //备注
            $apikey = "微信支付秘钥";   // key 商户后台设置的  微信商户平台(pay.weixin.qq.com)-->账户设置-->API安全-->密钥设置
            $nonce_str =  md5(rand());                                  //随机字符串，不长于32位
            $m_arr = array (
                    'mch_billno' => $mch_billno,
                    'mch_id' => $mch_id,
                    'wxappid' => $wxappid,
                    'send_name' => $send_name,
                    're_openid' => $re_openid,
                    'total_amount' => $total_amount,
                    'total_num' => $total_num,
                    'amt_type' => $amt_type,
                    'wishing' => $wishing,
                    'act_name' => $act_name,
                    'remark' => $remark,
                    'nonce_str'=> $nonce_str
            );
            array_filter ( $m_arr ); // 清空参数为空的数组元素
            ksort ( $m_arr ); // 按照参数名ASCII码从小到大排序
                   
            $stringA = "";
            foreach ( $m_arr as $key => $row ) {
                $stringA .= "&" . $key . '=' . $row;
            }
            $stringA = substr ( $stringA, 1 );
            // 拼接API密钥：
            $stringSignTemp = $stringA."&key=" . $apikey;
            $sign = strtoupper ( md5 ( $stringSignTemp ) );         //签名
       
            $textTpl = '<xml>
                        <sign><![CDATA[%s]]></sign>
                        <mch_billno><![CDATA[%s]]></mch_billno>
                        <mch_id><![CDATA[%s]]></mch_id>
                        <wxappid><![CDATA[%s]]></wxappid>
                        <send_name><![CDATA[%s]]></send_name>
                        <re_openid><![CDATA[%s]]></re_openid>
                        <total_amount><![CDATA[%s]]></total_amount>
                        <amt_type><![CDATA[%s]]></amt_type>
                        <total_num><![CDATA[%s]]></total_num>
                        <wishing><![CDATA[%s]]></wishing>
                        <act_name><![CDATA[%s]]></act_name>
                        <remark><![CDATA[%s]]></remark>
                        <nonce_str><![CDATA[%s]]></nonce_str>
                        </xml>';
            $resultStr = sprintf($textTpl, $sign, $mch_billno, $mch_id, $wxappid, $send_name,$re_openid,$total_amount,$amt_type,$total_num,$wishing,$act_name,$remark,$nonce_str);
            $url = "https://api.mch.weixin.qq.com/mmpaymkttransfers/sendgroupredpack";
            return curl_post_ssl($url, $resultStr);
    }
function curl_post_ssl($url, $vars, $second=30,$aHeader=array())
{
    $ch = curl_init();
    //超时时间
    curl_setopt($ch,CURLOPT_TIMEOUT,$second);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
    //这里设置代理，如果有的话
    //curl_setopt($ch,CURLOPT_PROXY, '10.206.30.98');
    //curl_setopt($ch,CURLOPT_PROXYPORT, 8080);
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
    curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);
   
    //以下两种方式需选择一种
   
    //第一种方法，cert 与 key 分别属于两个.pem文件
    //默认格式为PEM，可以注释
    curl_setopt($ch,CURLOPT_SSLCERTTYPE,'PEM');
    curl_setopt($ch,CURLOPT_SSLCERT,getcwd().'/zhengshu/apiclient_cert.pem');
    // 默认格式为PEM，可以注释
    curl_setopt($ch,CURLOPT_SSLKEYTYPE,'PEM');
    curl_setopt($ch,CURLOPT_SSLKEY,getcwd().'/zhengshu/apiclient_key.pem');
   
    //第二种方式，两个文件合成一个.pem文件
    //curl_setopt($ch,CURLOPT_SSLCERT,getcwd().'/all.pem');
 
    if( count($aHeader) >= 1 ){
        curl_setopt($ch, CURLOPT_HTTPHEADER, $aHeader);
    }
 
    curl_setopt($ch,CURLOPT_POST, 1);
    curl_setopt($ch,CURLOPT_POSTFIELDS,$vars);
    $data = curl_exec($ch);
    if($data){
        curl_close($ch);
        return $data;
    }
    else {
        $error = curl_errno($ch);
        echo "call faild, errorCode:$error\n";
        curl_close($ch);
        return false;
    }
}
?>
