<?php

namespace app\controller;

use app\BaseController;
use app\exception\AuthenticationException;
use think\annotation\route\Group;
use app\annotation\Doc;
use app\annotation\Jwt;
use app\annotation\Param;
use think\annotation\route\Route;
use think\annotation\route\Validate;
use app\validate\User;

/**
 * Class Index
 * @Group("/app")
 * @package app\controller
 */
class App extends BaseController
{

    /**
     * @Route(value="test",method="GET")
     * @Param(value="name",doc="名称",rule={"require","number","alphaDash"})
     * @Param(value="age",doc="年纪",rule={"require","number"})
     * @Jwt()
     * @Doc(value="测试应用",group="管理.应用",hide="false")
     */
    public function test()
    {
        return 'PAA';
    }



}
