目录管理
===============

结合apache的htpasswd模块，实现目录管理.

 + 添加用户
 + 编辑用户
 + 删除用户
 + 队列

>运行php环境7.0以上。

## 使用说明

1、修改apache-web/application/config.php中url_domain_root为项目域名或ip;
2、修改apache-web/application/redus.php中连接地址、端口、密码;
3、修改apache-web/application/apache.php中apache安装目录、suffix执行shell后缀、product_path管理目录地址、path管理目录的目录名称;
4、启动队列php think queue:work --daemon;

.htaccess文件

AuthType "Basic"  
AuthName "Password Required" 
AuthUserFile "F:\WorkSpace\php\aaa\.passwd" #使用绝对路径 
Require valid-user 
