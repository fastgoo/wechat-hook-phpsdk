<?php
/**
 * Created by PhpStorm.
 * User: Mr.Zhou
 * Date: 2017/11/15
 * Time: 下午1:21
 */
namespace WechatHook;

class WxHook
{

    public $receive;
    public $core;

    public function __construct($config)
    {
        $this->receive = new Receive($config);
        $this->core = new Core($config);
    }




}