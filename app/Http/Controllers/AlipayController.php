<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class AlipayController extends Controller
{
    public function pay()
    {
        return view('alipay/go');
    }

    public function go()
    {
        $order_no=mt_rand(1000,9999).time();
        $biz_content=[
            'subject'=>'测试订单：'.$order_no,
            'out_trade_no'=>$order_no,
            'total_amount'=>mt_rand(1,100)/100,
            'product_code'=>'QUICK_WAP_WAY'
        ];
        $data=[
            'app_id'=>2016092700605422,
            'method'=>'alipay.trade.wap.pay',
            'charset'=>'utf-8',
            'sign_type'=>'RSA2',
            'timestamp'=>date('Y-m-d H:i:s'),
            'version'=>'1.0',
            'biz_content'=>json_encode($biz_content, JSON_UNESCAPED_UNICODE)
        ];
        ksort($data);
        $string='?';
        foreach ($data as $k => $v) {
            $string.=$k.'='.$v.'&';
        }
        $str=rtrim($string,'&');
        $pri_key=openssl_get_privatekey('file://'.storage_path('keys/alipay_pri.pem'));
        openssl_sign($str,$sign,$pri_key,OPENSSL_ALGO_SHA256);
//        $sign=hash('sha256',$str);
        $data['sign']=base64_encode($sign);
<<<<<<< HEAD
        $client=new Client();
        $url='http://www.pay.com/rsa3';
        echo 111;
        // $response=$client->request('post',$url,['form_params'=>$data]);
        // echo $response->getBody();
=======
        $param_str='?';
        foreach ($data as $k => $v){
            $param_str.=$k.'='.urlencode($v).'&';
        }
        $param=rtrim($param_str,'&');
        $url='https://openapi.alipaydev.com/gateway.do'.$param;
        header('refresh:2;url='.$url);
>>>>>>> c07b05e7e71db8d629415689afe9b2da8f1ffcd9
    }

    public function rsa3()
    {
        $data=$_POST;
        $sign=$data['sign'];
        unset($data['sign']);
        $string='';
        foreach ($data as $k => $v) {
            $string.=$k.'='.$v.'&';
        }
        $str=rtrim($string,'&');
        $res=openssl_verify($str,base64_decode($sign),openssl_get_publickey('file://'.storage_path('key/pub.key')));
    }

    public function pay1()
    {
        $order_no=mt_rand(1000,9999).time();
        $biz_content=[
            'subject'=>'测试订单：'.$order_no,
            'out_trade_no'=>$order_no,
            'total_amount'=>200,
            'product_code'=>'QUICK_WAP_WAY'
        ];
        $data=[
            'app_id'=>2016092700605422,
            'method'=>'alipay.trade.wap.pay',
            'charset'=>'utf-8',
            'sign_type'=>'RSA2',
            'timestamp'=>date('Y-m-d H:i:s'),
            'version'=>1.0,
            'biz_content'=>json_encode($biz_content, JSON_UNESCAPED_UNICODE)
        ];
        ksort($data);
        $string='';
        foreach ($data as $k => $v) {
            $string.=$k.'='.$v.'&';
        }
        $str=rtrim($string,'&');
        $pri_key=openssl_get_privatekey('file://'.storage_path('key/alipay_pri.pem'));
        openssl_sign($str,$sign,$pri_key,OPENSSL_ALGO_SHA256);
//        $sign=hash('sha256',$str);
        $data['sign']=base64_encode($sign);
        $url='https://openapi.alipaydev.com/gateway.do';
        $res=Wechat::curlPost($url,$data);
        echo $res;
    }
}
