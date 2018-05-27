<?php
require 'translate.php';

/**
 * Created by PhpStorm.
 * User: liurongxing
 * Date: 2018/5/6
 * Time: 上午12:06
 */

class User {
    private $userid;
    private $url;
    public function __construct($userid,$url) {
        $this->doit($userid,$url);
    }

    public function doit($userid,$url){

        $curlobj = curl_init();
        curl_setopt($curlobj, CURLOPT_URL, $url );
        curl_setopt($curlobj, CURLOPT_RETURNTRANSFER, true);

        date_default_timezone_get('PRC');   //使用cookies时，必须先设置时区
        curl_setopt($curlobj, CURLOPT_SSL_VERIFYPEER, 0);  //终止从服务端验证

        $output = curl_exec($curlobj);  //执行获取内容
        curl_close($curlobj);          //关闭curl


        if ($output) {
            echo $userid;
        }

        $explodeStr = 'data-aria-label-part="0">';
        $explodeEnd = '</p>
</div>';
        $explodeArr = (explode($explodeStr,$output));


        $explodeTime = 'tweet-timestamp js-permalink js-nav js-tooltip" title="';
        $explodeTimeEnd = '"  data-conversation-id';
        $explodeTimeArr = explode($explodeTime,$output);



        for ($i=(count($explodeArr)-1);$i >=1 ;$i--){

            $explodeTimeArrEnd = explode($explodeTimeEnd,$explodeTimeArr[$i]);
            $explodeTimeContent = $explodeTimeArrEnd[0];

            $explodeArrEnd = (explode($explodeEnd,$explodeArr[$i]));
            $content = ($explodeArrEnd[0]);

            $realContent = $this->notag($content)."\n";
            $explodeTimeContent = $explodeTimeContent."\n";
            $chinese = $this->translateUse($realContent)."\n"."\n";
            unset($explodeArrEnd);

            $postData = array(
                'english'=>$realContent,
                'zhongwen'=>$chinese,
                'itime'=>$explodeTimeContent,
                'user_id' => $userid
            );
//            echo $postData;
            $this->sendMessage($postData);
        }
    }

    //调用翻译
    function translateUse($str){
        $translation = translate($str,"EN","zh-CHS");
        return $translation['translation'][0];
    }


    //去除标签
    public function notag($str){
        $one = explode('<a',$str);
        for ($i=1;$i < count($one);$i++){
            $two = explode('</a>',$one[$i]);
            $arr1[] = $two[1];
        }

        $three = explode('<s>',$str);
        for ($i=1;$i < count($three);$i++){
            $four = explode('</s>',$three[$i]);
            $arr2[] = $four[0];
        }

        $five = explode('<b>',$str);
        for ($i=1;$i < count($five);$i++){
            $six = explode('</b>',$five[$i]);
            $arr3[] = $six[0];
        }
        $strr = $one[0];
        for($i=0;$i < count($three);$i++){
            $strr .= $arr1[$i-1].$arr2[$i].$arr3[$i];
        }
        return $strr;
    }
//去除标签end

    function sendMessage($postData){
        $url = '服务器接收用的';
        $postUrl = $url;
        $postData = http_build_query($postData);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $postUrl);
        curl_setopt($curl, CURLOPT_USERAGENT,'Opera/9.80 (Windows NT 6.2; Win64; x64) Presto/2.12.388 Version/12.15');
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // stop verifying certificate
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
        curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        $r = curl_exec($curl);
        curl_close($curl);

    echo $r;
    }


}

class userFactory {
    static public function createUser($properties = []) {
        return new User($properties['userid'],$properties['url']);
    }
}

$employers = [
    ['userid' => 1, 'url' => "https://twitter.com/realDonaldTrump"],
    ['userid' => 2, 'url' => "https://twitter.com/BBCBreaking"],
//    ['userid' => 3, 'url' => "https://twitter.com/VitalikButerin"],
    ['userid' => 4, 'url' => "https://twitter.com/JokesAndMemes"],
    ['userid' => 5, 'url' => "https://twitter.com/NBATV"],
];


$user = userFactory::createUser($employers[3]);

