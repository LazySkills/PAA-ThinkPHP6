<?php
declare (strict_types = 1);

namespace app\annotation\handler;

use Doctrine\Common\Annotations\Annotation;
use think\annotation\handler\Handler;

class Param extends Handler
{

    public function func(\ReflectionMethod $refMethod, Annotation $annotation, \think\route\RuleItem &$rule)
    {
        // TODO: 完成方法注解的操作
        $rules = [];
        $validateRules = $rule->getOption('validate');
        $annotationRule = $this->getAnnotationValidateRule($annotation);
        if (empty($validateRules[0])){
            $rules = $annotationRule;
        }elseif (is_string($validateRules[0])){
            $validateModel = new $validateRules[0]();
            if (isset($validateModel->rule)){
                $rules = array_merge($annotationRule,$validateRules);
            }else{
                $rules = $annotationRule;
            }
        }elseif (is_array($validateRules[0])){
            $rules = array_merge($annotationRule,$validateRules[0]);
        }
        $rule->validate($rules);
    }

    protected function getAnnotationValidateRule(Annotation $annotation){
        return [
            $annotation->value.(empty($annotation->doc) ? '' : '|'.$annotation->doc) => join('|',$annotation->rule)
        ];
    }
}
