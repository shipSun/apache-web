<?php
/*
 * @author shipSun <543999860@qq.com>
 */
return  [
		'connector' => 'Redis',
		'host'         => '127.0.0.1', // redis主机
        'port'         => 6379, // redis端口
        'password'     => '', // 密码
        'select'       => 0, // 操作库
        'expire'       => 0, // 有效期(秒)
        'timeout'      => 0, // 超时时间(秒)
        'persistent'   => false, // 是否长连接
];