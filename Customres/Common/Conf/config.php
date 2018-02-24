<?php

return array(
    //'配置项'=>'配置值'
    'DEFAULT_TIMEZONE' => 'Asia/Shanghai', //设置时区 
    'DB_TYPE' => 'mysql', // 数据库类型
    'DB_HOST' => 'localhost', // 服务器地址
    'DB_NAME' => 'customres', // 数据库名
    'DB_USER' => 'customres', // 用户名
    'DB_PWD' => 'customres', // 密码
    'DB_PORT' => 3306, // 端口
    'DB_CHARSET' => 'utf8', // 字符集
    'DB_PREFIX' => 'm_', // 数据库表前缀
    'PUB_SALT' => 'f*5-H#>9K:#;aj:O', //非常重要的字段，不能修改
//    'AUTOLOAD_NAMESPACE' => array(//手动增加重要命名空间
//        'OaLibs' => '/var/www/ThinkAPP/Oas/OaLibs',
//    ),
    'SHOW_PAGE_TRACE' => 0,
);
