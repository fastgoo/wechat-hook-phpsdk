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
    'auth_key' => 'wechat-robot',
    'expire_time' => 30,
    "url" => "http://118.89.177.70:8090",
];

$hook = new WxHook($config);
$receive = $hook->receive->getParam();

switch ($receive->_data['type']) {
    case "login_success":

        break;
    case "login_out":

        break;
    case "event":
        if ($receive->_data['msg_type'] == 1) {//文字
            if ($receive->_data['msg_wxid'] == "5305302988@chatroom") {
                if ($receive->_data['isAtMe']) {
                    if (strpos($receive->_data['msg'], "文字测试") !== false) {
                        $hook->core->sendMsg($receive->_data['msg_wxid'], 1, "这是一条自动发出的文字信息");
                    }

                    if (strpos($receive->_data['msg'], "图片测试") !== false) {
                        $hook->core->sendMsg(
                            $receive->_data['msg_wxid'],
                            2,
                            ["url" => "http://cdnsource.9377.com/uploads/2015-12/02/5d882460adec7c2e.jpg", "name" => "高效图片.jpg"]
                        );
                    }
                    if (strpos($receive->_data['msg'], "文件测试") !== false) {
                        $hook->core->sendMsg(
                            $receive->_data['msg_wxid'],
                            3,
                            ["url" => "http://cdnsource.9377.com/uploads/2015-12/02/5d882460adec7c2e.jpg", "name" => "高效图片.jpg"]
                        );
                    }
                    if (strpos($receive->_data['msg'], "名片测试") !== false) {
                        $hook->core->sendMsg(
                            $receive->_data['msg_wxid'],
                            4,
                            ["wxid" => "wxid_k9jdv2j4n8cf12", "nickname" => "群主周大师"]
                        );
                    }
                    if (strpos($receive->_data['msg'], "链接测试") !== false) {
                        $hook->core->sendMsg(
                            $receive->_data['msg_wxid'],
                            5,
                            ["title" => "微信PC Hook项目地址", "content" => "麻烦点个start，谢谢了", "url" => "https://github.com/fastgoo/wechat-hook-phpsdk", "img" => "http://img2.niushe.com/upload/201304/19/14-22-31-71-26144.jpg"]
                        );
                    }
                    if (strpos($receive->_data['msg'], "发布公告") !== false) {
                        $msg = mb_substr($receive->_data['msg'], strpos($receive->_data['msg'], "发布公告") + 4);
                        $hook->core->roomSetAnnouncement($receive->_data['msg_wxid'], $msg);
                    }
                    if (strpos($receive->_data['msg'], "修改群名称") !== false) {
                        $msg = mb_substr($receive->_data['msg'], strpos($receive->_data['msg'], "修改群名称") + 5);
                        $hook->core->roomSetName($receive->_data['msg_wxid'], $msg);
                    }
                    if (strpos($receive->_data['msg'], "踢出") !== false) {
                        foreach ($receive->_data['at_user'] as $val) {
                            $hook->core->roomKickUser($receive->_data['msg_wxid'], $val);
                        }
                    }
                    if (strpos($receive->_data['msg'], "@我") !== false) {
                        $hook->core->sendMsgAtUser($receive->_data['msg_wxid'],$receive->_data['send_user_wxid'], "@你了，你觉得如何(目前没办法高效的获取到你的名称，所以就用你代替吧)");
                    }
                }
            }
            if ($receive->_data['from_type'] == 1) {
                if (strpos($receive->_data['msg'], "拉我进测试群1") !== false) {
                    $hook->core->roomAddUser($receive->_data['msg_wxid'], "5305302988@chatroom");
                }
                if (strpos($receive->_data['msg'], "拉我进测试群1") !== false) {
                    $hook->core->roomAddUser($receive->_data['msg_wxid'], "5305302988@chatroom");
                }
            }
        } else if ($receive->_data['msg_type'] == 3) {//图片
            $hook->core->sendMsg($receive->_data['msg_wxid'], 1, "收到图片消息");
        } else if ($receive->_data['msg_type'] == 34) {//语言
            $hook->core->sendMsg($receive->_data['msg_wxid'], 1, "收到语音消息");
        } else if ($receive->_data['msg_type'] == 42) {//名片
            $hook->core->sendMsg($receive->_data['msg_wxid'], 1, "收到名片消息");
        } else if ($receive->_data['msg_type'] == 43) {//视频
            $hook->core->sendMsg($receive->_data['msg_wxid'], 1, "收到视频消息");
        } else if ($receive->_data['msg_type'] == 48) {//定位
            $hook->core->sendMsg($receive->_data['msg_wxid'], 1, "收到定位消息");
        } else if ($receive->_data['msg_type'] == 50) {//视频语音通话
            $hook->core->sendMsg($receive->_data['msg_wxid'], 1, "收到视频通话事件");
        } else if ($receive->_data['msg_type'] == 4901) {//邀请进群

        } else if ($receive->_data['msg_type'] == 4902) {//微信转账
            $hook->core->sendMsg($receive->_data['msg_wxid'], 1, "收到转账消息");
        } else if ($receive->_data['msg_type'] == 4903) {//收款事件

        } else if ($receive->_data['msg_type'] == 4904) {//链接消息
            $hook->core->sendMsg($receive->_data['msg_wxid'], 1, "收到链接消息");
        } else if ($receive->_data['msg_type'] == 1001) {//成为新群主
            $hook->core->sendMsg($receive->_data['msg_wxid'], 1, $receive->_data['msg']);
        } else if ($receive->_data['msg_type'] == 1002) {//群收款消息
            $hook->core->sendMsg($receive->_data['msg_wxid'], 1, "收到群收款消息");
        } else if ($receive->_data['msg_type'] == 1003) {//红包消息
            $hook->core->sendMsg($receive->_data['msg_wxid'], 1, "收到红包消息");
        } else if ($receive->_data['msg_type'] == 1004) {//新人加入群聊
            $hook->core->sendMsg($receive->_data['msg_wxid'], 1, "欢迎新人加入群聊，开始发言吧");
        } else if ($receive->_data['msg_type'] == 1005) {//添加好友成功
            $hook->core->sendMsg($receive->_data['msg_wxid'], 1, "我们是好友了，开始装逼吧");
        } else if ($receive->_data['msg_type'] == 3701) {//收到添加好友通知
            $desc = $receive->_data['msg']['@attributes']['content'];
            if (strpos($desc, "测试") !== false || strpos($desc, "机器人") !== false) {
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