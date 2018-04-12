项目介绍
=======
2018-4-12 项目功能迁到 https://github.com/fastgoo/padchat-php 

微信PC Hook工具 PHP-SDK 
目前这个2.5的版本已经不可用了，等待新版本出来发布通知。记得随时关注github的更新动态

Composer 安装
------------
```bash
新建composer.json文件，引入包如下：
 
{
  "require-dev": {
    "fastgoo/wechat-hook-phpsdk": "dev-master"
  }
} 

composer install
```

依赖扩展
-------
1、日志系统依赖 Redis扩展 和 Redis服务器

项目结构
-------

```bash
src/
    
    WxHook.php  核心管理器，管理Core、Receive
    Core.php 核心类 HTTP请求、功能接口
    Receive.php 接受数据，请求安全数据过滤，以及数据格式化
```

文档地址
-------

https://github.com/fastgoo/wechat-hook-phpsdk/wiki


作者
-------
QQ：773729704 记得备注github

微信：huoniaojugege  记得备注github

需要做维系机器人管理系统的可以联系我
