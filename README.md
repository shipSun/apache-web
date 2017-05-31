目录管理
===============

结合apache的htpasswd模块，实现目录管理.

 + 添加用户
 + 编辑用户
 + 删除用户
 + 队列

>运行php环境7.0以上。

## 使用说明

1、修改apache-web/application/config.php中url_domain_root为项目根域名（如：ship.com)

2、修改apache-web/application/redus.php中连接地址、端口、密码

3、修改apache-web/application/apache.php中apache安装目录、suffix执行shell后缀、product_path管理目录地址、path管理目录的目录名称

4、启动队列php think queue:work --daemon

5、客户端绑定host,服务器ip www.ship.com

.htaccess文件

AuthType "Basic"

AuthName "Password Required"

AuthUserFile "F:\WorkSpace\php\aaa\.passwd" #使用绝对路径

Require valid-user 


nginx配置

if (!-d $request_filename){
    set $rule_0 1$rule_0;
}

if (!-f $request_filename){
    set $rule_0 2$rule_0;
}

if ($rule_0 = "21"){
    rewrite ^/master/public(.*)$ /master/public/index.php/$1 last;
}

location ~ ^(.+\.php)(.*)$ {

    fastcgi_pass        127.0.0.1:9000;
    
    fastcgi_index       index.php;
    
    fastcgi_split_path_info ^(.+\.php)(.*)$;
    
    fastcgi_param PATH_INFO $fastcgi_path_info;
    
    fastcgi_param  SCRIPT_FILENAME $document_root$fastcgi_script_name;
    
    include             fastcgi_params;
    
}

虚拟机配置
location ^~ /www/ {
        index index.html;
        auth_basic "TEST-Login";
        auth_basic_user_file /var/www/www/.passwd;
}