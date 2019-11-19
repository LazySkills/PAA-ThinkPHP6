<?php

namespace app\controller;

use app\annotation\Param;
use app\BaseController;
use think\annotation\route\Route;

/**
 * Class Index
 * @package app\controller
 */
class Index extends BaseController
{
    public function index()
    {
        return '<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} a{color:#2E5CD5;cursor: pointer;text-decoration: none} a:hover{text-decoration:underline; } body{ background: #fff; font-family: "Century Gothic","Microsoft yahei"; color: #333;font-size:18px;} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.6em; font-size: 42px }</style><div style="padding: 24px 48px;"> <h1>:) </h1><p> ThinkPHP V6<br/><span style="font-size:30px">13载初心不改 - 你值得信赖的PHP框架</span></p></div><script type="text/javascript" src="https://tajs.qq.com/stats?sId=64890268" charset="UTF-8"></script><script type="text/javascript" src="https://e.topthink.com/Public/static/client.js"></script><think id="eab4b9f840753f8e7"></think>';
    }


    public function hello($name = 'ThinkPHP6')
    {
        return 'hello,' . $name;
    }

    /**
     * @Route(value="/test",method="GET")
     * @Param(value="name",rule={"require","number","alphaDash"})
     * @Param(value="age",rule={"require","number"})
     */
    public function test()
    {
        return "<style type=\"text/css\">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} a{color:#2E5CD5;cursor: pointer;text-decoration: none} a:hover{text-decoration:underline; } body{ background: #fff; font-family: \"Century Gothic\",\"Microsoft yahei\"; color: #333;font-size:18px;} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.6em; font-size: 42px }</style><div style=\"padding: 24px 48px;\"> <h1>:) </h1><h1 style='font-size: 45px'>源于<a href=\"https://github.com/LazySkills/TRR-tp6\">TRR</a>的美好生活💑</h1>
<p>TRR Based On ThinkPHP V6<br/><span style=\"font-size:30px\">初心不改 - 🥰幸福感满满的PHP框架</span></p></div>";
    }
}
