<?php
/**
 * Created by PhpStorm.
 * User: Mr.Zhou
 * Date: 2017/11/15
 * Time: 下午1:21
 */

namespace WechatHook;

class Receive
{

    private $_config;
    public $_data;

    public function __construct($config)
    {
        $this->_config = $config;
    }

    public function getParam()
    {
        $data = $_POST;
        if (!empty($this->_config['auth_key'])) {
            if (empty($data['sign']) || empty($data['time'])) {
                exit("无法获取客户端发送的sign、time字段");
            }
            if ($data['sign'] != md5($this->_config['auth_key'] . $data['time'])) {
                exit("签名数据校验失败，请核对认证key");
            }
            if ($data['time'] < time() - $this->_config['expire_time']) {
                exit("数据已过有效期");
            }
        }

        /** 判断消息ID是群组还是好友 */
        if (!empty($data['msg_wxid'])) {
            if (strpos($data['msg_wxid'], "@chatroom") === false) {
                $data['msg_from'] = 1;
            } else {
                $data['msg_from'] = 2;
                $arr = explode(":", $data['msg']);
                $data['group_wxid'] = $data['msg_wxid'];
                $data['msg_wxid'] = $arr[0];
                $data['msg'] = str_replace($data['msg_wxid'] . ":", $data['msg']);
            }
        }
        /** 格式化消息里面的xml代码 */
        if (!empty($data['msg_type']) && $data['msg_type'] != 1) {
            if (strpos($data['msg'], "<") !== false && strpos($data['msg'], ">") !== false) {
                $data['msg'] = json_encode((array)simplexml_load_string($data['msg']));
            }
        }
        $this->_data = $data;
        return $this;
    }

}