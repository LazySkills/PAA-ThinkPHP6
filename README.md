# PAA-ThinkPHP6
🐘 A Simple and Practical Annotation Routing RESTful API architecture Implemented by ThinkHP 6.0

## 简介

`PAA-ThinkPHP6` 专注注解完成工作。

目前内置功能：

- `thinkphp6` 无缝衔接
- 单个参数注解验证器，`app/annotation/Param.php`
- 接口文档注解器，`app/annotation/Doc.php`
- 接口JWT注解器，`app/annotation/JWT.php`
- 接口管理平台


## 运行

开发运行，推荐使用`tinkphp6`内置服务器.

命令：

```php
php think run
```

运行效果

```bash
> php think run

ThinkPHP Development server is started On <http://127.0.0.1:8000/>
You can exit with `CTRL-C`
Document root is: PAA-thinkphp6\public
```

运行成功，浏览器访问

```bash
http://127.0.0.1:8000/
```

## 接口管理平台

浏览器访问地址

```bash
http://127.0.0.1:8000/apidoc/
```

### 账户

- 开发账户: `admin`
- 密码: `supper`

> 开发具有操作权限

- 浏览者账户: `web`
- 密码: `123456`

> 浏览者只有访问权限

### 暂时演示地址：

- 欢迎页：
    ```
      http://m.ibs3.top:8080/
    ```
- 接口管理：
    ```
      http://m.ibs3.top:8080/apidoc
    ```
    
## 感谢

`PAA-ThinkPHP6` 由以下项目组成和依赖。

万分感谢，以下排名不分先后

- [ThinkPHP v6](https://github.com/top-think/framework) 文档: https://www.kancloud.cn/manual/thinkphp6_0
- [firebase/php-jwt](https://packagist.org/packages/firebase/php-jwt) php-jwt验证器
- [LazySkills/think-annotation](https://github.com/LazySkills/think-annotation) 优秀注解扩展
- [sentsin/layui](https://github.com/sentsin/layui) 优秀注解扩展

> 再次感谢，希望大家能帮这几个项目点点`star`。

## 维护与提问

### 更新
由于目前PAA目前还处在不断迭代更新阶段，ThinkPHP6版本也在持续跟进，所以本项目的内容也会随着适配的进度而增加或者调整。

### 完善
局限于个人技术水平和写作能力，如果文档中有哪些地方读者觉得不对或者看不懂需要再讲仔细些可以随时提出。

### 催更、提问
读者对本教程或者GitHub项目有任何疑问、建议都可以在作者GitHub仓库提个issues

### 交流
加【PAA 官方群】QQ群: 860613750

### 请我喝茶
如果你觉得本项目帮助到你，想请作者[喝杯茶](https://camo.githubusercontent.com/7c3599367e8cde0a4ea6d0cd97103794a4e18af1/68747470733a2f2f6368696e612d77616e6779752e6769746875622e696f2f2f5452522fe68993e8b58f2f616c697061792e6a7067) , 请扫码打赏任意金额

## 下阶段开发计划
[ ] 优化代码

## 版权信息
PAA-ThinkPHP6 遵循 MIT 开源协议发布，并提供免费使用。

本项目包含的第三方源码和二进制文件之版权信息另行标注。

版权所有Copyright © 2006-2019 by PAA-ThinkPHP6

All rights reserved。