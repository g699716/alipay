<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class AlipayController extends Controller
{
    public function pay()
    {
    	$data=[
            'order_id'=>20,
            'order_amount'=>300,
            'add_time'=>133151367,
            'user_id'=>233
        ];
        ksort($data);
        $string='';
        foreach ($data as $k => $v) {
            $string.=$k.'='.$v.'&';
        }
        $str=rtrim($string,'&');
        openssl_sign($str,$sign,openssl_get_privatekey('file://'.storage_path('keys/pri.pem')));
        $data['sign']=base64_encode($sign);
        $client=new Client();
        $url='http://www.lm.com/rsa3';
        // $response=$client->request('post',$url,['form_params'=>$data]);
        // echo $response->getBody();
    }
}
