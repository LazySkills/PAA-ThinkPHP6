<?php
// +----------------------------------------------------------------------
// | 控制台配置
// +----------------------------------------------------------------------
return [
    // 指令定义
    'commands' => [
        \think\annotation\command\make\Annotation::class,
        \think\annotation\command\make\Handler::class,
    ],
];
