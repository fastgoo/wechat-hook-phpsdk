<?php
/**
 * Created by PhpStorm.
 * User: Mr.Zhou
 * Date: 2017/11/15
 * Time: 下午1:21
 */

namespace WechatHook;

class Core
{

    public function __construct($config)
    {
        $this->config = $config;
    }


    /**
     * 访问网页
     * @param string $url 请求网址
     * @param string $data 请求数据，非空时使用POST方法
     * @param string $cookies 可空
     * @param array $headers
     * @param string $proxy 代理地址，可空
     * @param int $time 超时时间，单位：秒。默认10秒
     * @return string 执行结果
     */
    protected function getHttpData($url = '', $data = '', $cookies = '', $headers = array(), $proxy = '', $time = 8)
    {
        $ch = curl_init($url); //初始化 CURL 并设置请求地址
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //设置获取的信息以文件流的形式返回
        if ($data) curl_setopt($ch, CURLOPT_POST, 1); //设置 post 方式提交
        if ($data) curl_setopt($ch, CURLOPT_POSTFIELDS, $data); //设置 post 数据
        if (is_array($cookies) && $cookies) {
            foreach ($cookies as $array) $data .= $array;
            $cookies = $data;
        }
        if ($cookies) curl_setopt($ch, CURLOPT_COOKIE, $cookies);   //设置Cookies
        if ($headers) curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        if ($proxy) curl_setopt($ch, CURLOPT_USERAGENT, $proxy);
        curl_setopt($ch, CURLOPT_TIMEOUT, $time);   //只需要设置一个秒的数量就可以
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // 对认证证书来源的检查
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); // 从证书中检查SSL加密算法是否存在

        $data = curl_exec($ch); //执行命令
        curl_close($ch); //关闭 CURL

        return $data;
    }

    public function send($data)
    {
        $data['time'] = (string)time();
        $data['sign'] = md5($this->config['auth_key'] . $data['time']);
        $response = $this->getHttpData(
            $this->config['url'],
            "<<" . json_encode($data, JSON_UNESCAPED_UNICODE) . ">>",
            '',
            '',
            '',
            $this->config['timeout']);
        var_dump($response);
        var_dump("<<" . json_encode($data) . ">>");
    }

    /**
     * 发送消息
     * @param $wxid 微信ID
     * @param $type 1文字 2图片 3语音 4文件 5名片 6链接
     * @param $msg 名片{"nickname":"","wxid":""}  链接{"title":"","content":"","url":"","img":""}
     */
    public function sendMsg($wxid, $type, $msg)
    {
        $data = [
            "function" => "sendMsg",
            "wxid" => $wxid,
            'type' => "$type"
        ];
        switch ($type) {
            case 1:
                $data['msg'] = $msg;
                break;
            case 2:
                $data['imgName'] = $msg['name'];
                $data['imgUrl'] = $msg['url'];
                break;
            case 3:
                $data['fileName'] = $msg['name'];
                $data['fileUrl'] = $msg['url'];
                break;
            case 4:
                $data['data']['wxid'] = $msg['wxid'];
                $data['data']['nickname'] = $msg['nickname'];
                break;
            case 5:
                $data['data']['title'] = $msg['title'];
                $data['data']['content'] = $msg['content'];
                $data['data']['url'] = $msg['url'];
                $data['data']['img'] = $msg['img'];
                break;
            default:
                exit("消息类型错误");
        }
        $this->send($data);
    }

    /**
     * 邀请好友加入群聊
     * @param $wxid
     * @param $group_wxid
     * @param int $invite_type
     */
    public function roomAddUser($wxid, $group_wxid, $invite_type = 1)
    {
        $data = [
            "function" => "roomAddUser",
            "wxid" => $wxid,
            'group_wxid' => $group_wxid,
            "invite_type" => (string)$invite_type
        ];
        $this->send($data);
    }

    /**
     * 踢出群聊
     * @param $wxid
     * @param $group_wxid
     */
    public function roomKickUser($group_wxid, $wxid)
    {
        $data = [
            "function" => "roomKickUser",
            "wxid" => $wxid,
            'group_wxid' => $group_wxid
        ];
        $this->send($data);
    }

    /**
     * 设置群名称
     * @param $group_wxid
     * @param $name
     */
    public function roomSetName($group_wxid, $name)
    {
        $data = [
            "function" => "roomSetName",
            "group_wxid" => $group_wxid,
            'name' => $name
        ];
        $this->send($data);
    }

    /**
     * 设置群公告
     * @param $group_wxid
     * @param $msg
     */
    public function roomSetAnnouncement($group_wxid, $msg)
    {
        $data = [
            "function" => "roomSetAnnouncement",
            "group_wxid" => $group_wxid,
            'msg' => $msg
        ];
        $this->send($data);
    }

    /**
     * 退出群聊
     * @param $group_wxid
     */
    public function roomExit($group_wxid)
    {
        $data = [
            "function" => "roomExit",
            "group_wxid" => $group_wxid,
        ];
        $this->send($data);
    }

    /**
     * 创建群聊
     * @param array $wxidArr
     * @param $roomName 设置群名称
     * @param $msg 创建成功的欢迎语
     */
    public function roomCreate(Array $wxidArr, $roomName, $msg)
    {
        $data = [
            "function" => "roomCreate",
            "wxidArr" => $wxidArr,
            "room" => [
                "name" => $roomName,
                "msg" => $msg
            ]
        ];
        $this->send($data);
    }

    /**
     * 发送at消息
     * @param $group_wxid
     * @param $at_user_wxid
     * @param $msg
     */
    public function sendMsgAtUser($group_wxid, $at_user_wxid, $msg)
    {
        $data = [
            "function" => "sendMsgAtUser",
            "group_wxid" => $group_wxid,
            "at_wxid" => $at_user_wxid,
            "msg" => $msg,
        ];
        $this->send($data);
    }

    /**
     * 接受好友申请
     * @param $v1
     * @param $v2
     */
    public function acceptFriend($v1, $v2)
    {
        $data = [
            "function" => "acceptFriend",
            "v1" => $v1,
            'v2' => $v2
        ];
        $this->send($data);
    }

    /**
     * 添加好友
     * @param $wxid
     * @param $desc
     */
    public function addFriend($wxid, $desc)
    {
        $data = [
            "function" => "addFriend",
            "wxid" => $wxid,
            'desc' => $desc,
        ];
        $this->send($data);
    }

    /**
     * 删除好友
     * @param $wxid
     */
    public function deleteFriend($wxid)
    {
        $data = [
            "function" => "deleteFriend",
            "wxid" => $wxid,
        ];
        $this->send($data);
    }

    /**
     * 获取好友列表
     */
    public function getFriendList()
    {
        $data = [
            "function" => "getFriendList",
        ];
        $this->send($data);
    }

    /**
     * 获取群成员列表
     * @param $group_wxid
     */
    public function getMembers($group_wxid)
    {
        $data = [
            "function" => "getMembers",
            "group_wxid" => $group_wxid,
        ];
        $this->send($data);
    }
}