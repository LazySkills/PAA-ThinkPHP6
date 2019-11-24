<?php
/** Created by 嗝嗝<china_wangyu@aliyun.com>. Date: 2019-11-22  */

namespace app\validate;


use think\Validate;

class BaseValidate extends Validate
{
    public $rule;

    public $scene;
}