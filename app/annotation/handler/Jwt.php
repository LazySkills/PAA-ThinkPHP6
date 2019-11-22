<?php
declare (strict_types = 1);

namespace app\annotation\handler;

use app\common\authentication\Authentication;
use Doctrine\Common\Annotations\Annotation;
use think\annotation\handler\Handler;

class Jwt extends Handler
{

    public function func(\ReflectionMethod $refMethod, Annotation $annotation, \think\route\RuleItem &$rule)
    {
        if ($this->isCurrentMethod($rule)){
            $authentication = new Authentication();
            $authentication->check();
        }
    }

    public function isCurrentMethod(\think\route\RuleItem $rule){
        if (PHP_SAPI != 'cli'){
            if($rule->getRule() == trim($_SERVER['PATH_INFO'],'/')){
                return true;
            }
        }
        return false;
    }
}
