<?php
declare (strict_types = 1);

namespace app\validate;


use think\Validate;

class User extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     * @var array
     */	
	protected $rule = [
	    'sex' => 'require|in:1,2',
        'px' => 'require|in:500,999'
    ];

    protected $scene = [
	    'add' => ['sex','px']
    ];
    
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [];
}
