<?php

namespace app\controller;


use paa\annotation\{
    Doc,Jwt
};
use think\annotation\route\{
    Group,Route,Validate
};
use app\validate\User;
/**
 * Class Index
 * @Group("app")
 * @package app\controller
 */
class App
{

    /**
     * @Route(value="test",method="GET")
     * @Validate(value=User::class)
     * @Jwt()
     * @Doc(value="测试应用",group="管理.应用",hide="false")
     */
    public function test()
    {
        return 'PAA';
    }



}
