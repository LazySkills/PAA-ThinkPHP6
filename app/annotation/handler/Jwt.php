<?php
declare (strict_types = 1);

namespace app\annotation\handler;

use Doctrine\Common\Annotations\Annotation;
use think\annotation\handler\Handler;

class Jwt extends Handler
{

    public function func(\ReflectionMethod $refMethod, Annotation $annotation, \think\route\RuleItem &$rule)
    {
        if ($this->isCurrentMethod($rule)){
            (new \app\common\authentication\Jwt())->check();
        }
    }

    public function isCurrentMethod(\think\route\RuleItem $rule){
        if (PHP_SAPI != 'cli'){
            if($rule->getRule() == trim(explode('?',$_SERVER['REQUEST_URI'])[0],'/')){
                return true;
            }
        }
        return false;
    }
}
