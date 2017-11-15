<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/15
 * Time: 16:11
 */

include __DIR__ . "/vendor/autoload.php";


use WechatHook\WxHook;

$config = [
    'auth_key' => '',
    'expire_time' => 30,
    "url" => "http://118.89.177.70:8090",
];

$hook = new WxHook($config);
$receive = $hook->receive->getParam();


switch ($receive->_data['type']) {
    case "login_success":


        break;
    case "event":
        if ($receive->_data['msg_type'] == 1) {//文字
            if ($receive->_data['msg_wxid'] == "5305302988@chatroom") {
                if (strpos("文字测试", $receive->_data['msg']) !== false) {
                    $hook->core->sendMsg($receive->_data['msg_wxid'], 1, "这是一段测试文字");
                }
                if (strpos("名片测试", $receive->_data['msg']) !== false) {
                    $hook->core->sendMsg($receive->_data['msg_wxid'], 5, ['nickname' => '机器人的主人', 'wxid' => 'wxid_k9jdv2j4n8cf12']);
                }
                if (strpos("链接测试", $receive->_data['msg']) !== false) {
                    $hook->core->sendMsg($receive->_data['msg_wxid'], 6, [
                        'title' => '测试标题',
                        'content' => '测试内容',
                        'url' => 'http://www.baidu.com',
                        'img' => 'http://wx.qlogo.cn/mmhead/ver_1/TgsuHvVASh7gEBkPqJhiaAMp0vSMFUJicfJPGZrdicevUwtYElJRFMibIVsuQEOB74HJo6wFHYboISrNDsN1BbUQ1GJbL44NFeLJfg2EqVEQ9Hs/0'
                    ]);
                }
            }
            if ($receive->_data['msg_from'] == 1) {
                if (strpos("拉我进测试群", $receive->_data['msg']) !== false) {
                    $hook->core->roomAddUser($receive->_data['msg_wxid'], "5305302988@chatroom");
                }
            }

        } else if ($receive->_data['msg_type'] == 3) {//图片

        } else if ($receive->_data['msg_type'] == 34) {//语言

        } else if ($receive->_data['msg_type'] == 42) {//名片

        } else if ($receive->_data['msg_type'] == 43) {//视频

        } else if ($receive->_data['msg_type'] == 48) {//定位

        } else if ($receive->_data['msg_type'] == 4901) {//微信好友转账消息

        } else if ($receive->_data['msg_type'] == 4902) {//微信二维码收款到账消息

        } else if ($receive->_data['msg_type'] == 10001) {//新加好友

        } else if ($receive->_data['msg_type'] == 10002) {//群成员增加

        } else if ($receive->_data['msg_type'] == 10003) {//群成员邀请用户

        } else if ($receive->_data['msg_type'] == 10004) {//群成员减少

        } else if ($receive->_data['msg_type'] == 10005) {//微信红包消息

        } else if ($receive->_data['msg_type'] == 3701) {//收到添加好友通知
            $desc = $receive->_data['msg']['@attributes']['content'];
            if (strpos("测试", $desc) !== false || strpos("robot", $desc) !== false || strpos("机器人", $desc) !== false) {
                $hook->core->acceptFriend($receive->_data['msg']['@attributes']['encryptusername'], $receive->_data['msg']['@attributes']['ticket']);
            }
        } else {

        }

        break;
    default:
        echo 1;
}


$file = file_get_contents("./test.log");
file_put_contents("./test.log", $file . "\n" . date("H:i:s") . "  " . json_encode($receive->_data));