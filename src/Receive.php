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

        if (!empty($data['type']) && in_array($data['type'], ["login_success", "login_out"])) {
            $data['msg_time'] = $data['time'];
        }

        if (!empty($this->_config['auth_key'])) {
            if (empty($data['sign']) || empty($data['msg_time'])) {
                exit("无法获取客户端发送的sign、time字段");
            }
            if ($data['sign'] != md5($this->_config['auth_key'] . $data['msg_time'])) {
                exit("签名数据校验失败，请核对认证key");
            }
            if ($data['msg_time'] < time() - $this->_config['expire_time']) {
                exit("数据已过有效期");
            }
        }
        /** 格式化消息里面的xml代码 */
        if (!empty($data['msg_type']) && $data['msg_type'] != 1) {
            if (strpos($data['msg'], "<") !== false && strpos($data['msg'], ">") !== false) {
                $data['msg'] = json_decode(json_encode(simplexml_load_string(str_replace(["\n", "\t"], "", $data['msg']))), true);
            }
        }
        /** 格式化消息里面的xml代码 */
        if (!empty($data['at_user'])) {
            $data['at_user'] = explode(",", $data['at_user']);
            if ($data['at_user']) {
                $my_key = array_search($data['g_wxid'], $data['at_user']);
                if ($my_key >= 0 && $my_key !== false) {
                    unset($data['at_user'][$my_key]);
                }
            }
        }
        $this->_data = $data;
        return $this;
    }

}