**HRSRC**是一个基于SRCMS·轻响应框架二次开发的安全响应平台




##框架首次运行配置说明：
在进行下面的步骤之前，请您确保本地或服务器有运行PHP程序的环境（推荐环境:Apache+PHP5.3及以上[暂不能完美兼容PHP7]+MySQL），接下来您需要：

* 第一步：通过Github代码托管页面将项目源代码下载

* 第二步：打开./Application/Common/Conf/db.php，按照实际情况调整本地数据库连接相关配置。

* 第三步：在phpmyadmin建立一个名为hrsrc（可以自定义，但是请参照第二步，调整数据库连接设置）的数据库，然后将./DB目录下HRSRC_db.sql文件导入刚刚创建的数据库。

* 第四步：打开./Application/Common/Conf/config.php，按照文件中绿色注释文字的提示,填写网站找回密码、后台登陆报警所需要使用到SMTP服务的相关配置。

* 第五步：打开./Application/Admin/Controller/LoginController.class.php,修改第59行sendMail方法内初始值邮箱地址为您自己的安全邮箱。配置完成后，每次管理员登陆后台，该邮箱都将会接收到登陆日志。

* 配置完成：基础配置全部完成，下面您就可以通过在浏览器内输入相应地址体验SRCMS（轻响应）了。


---
##使用帮助说明
如果您在使用hrsrc，搭建自己的安全应急响应中心时遇到问题，非常欢迎通过QQ或邮件咨询我：491463471(@qq.com)

后台帐号admin  密码123456

十分感谢martinzhou2015大神!我只是在他的基础上借花献佛!

---
**以下是原作者的github和srcms链接

【martinzhou2015】:https://github.com/martinzhou2015

【SRCMS·轻响应框架源码地址】:https://github.com/martinzhou2015/SRCMS
