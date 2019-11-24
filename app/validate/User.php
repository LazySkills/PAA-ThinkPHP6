<?php
declare (strict_types = 1);

namespace app\validate;


class User extends BaseValidate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	public $rule = [
	    'sex' => 'require|in:1,2',
        'px' => 'require|in:500,999'
    ];

    public $scene = [
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
