<?php

return [
    'inject' => [
        'enable'     => true,
        'namespaces' => [],
    ],
    'route'  => [
        'enable'      => true,
        'controllers' => [],
    ],
    'ignore' => [],
    'management' => true,
    'custom' => [
        # 格式：注解类 => 注解操作类
        \app\annotation\Param::class => \app\annotation\handler\Param::class,
        \app\annotation\Jwt::class => \app\annotation\handler\Jwt::class,
        \app\annotation\Doc::class => \app\annotation\handler\Doc::class,
    ]
];