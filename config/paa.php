<?php
/** Created by 嗝嗝<china_wangyu@aliyun.com>. Date: 2019-12-17  */
return [
    'jwt' => [
        'key' => 'PAA-ThinkPHP6', // 授权 key
        'type' => 'Bearer', // 授权类型
        'request' => 'header', // 请求方式
        'param' => 'authorization', // 授权名称
        'time' => 7200, //token有效时长
        'payload' => [
            'iss' => 'PAA-ThinkPHP6', //签发者
            'iat' => '', //什么时候签发的
            'exp' => '' , // 过期时间
            'uniqueId' => '',
            'signature' => ''
        ]
    ]
];