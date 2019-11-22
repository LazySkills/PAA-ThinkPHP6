<?php
declare (strict_types = 1);

namespace app\annotation\handler;

use app\validate\BaseValidate;
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
                $rules = $this->getAnnotationValidateSceneRule($annotationRule,$validateRules[1],$validateModel);
            }else{
                $rules = $annotationRule;
            }
        }elseif (is_array($validateRules[0])){
            $rules = array_merge($annotationRule,$validateRules[0]);
        }
        $rule->validate($rules);
    }

    protected function getAnnotationValidateSceneRule($annotationRule,$scene,BaseValidate $validateModel){
        $rules = [];
        if (isset($validateModel->scene[$scene])){
            foreach ($validateModel->rule as $key => $item){
                $ruleItem = explode('|',$key);
                if (in_array($ruleItem[0],$validateModel->scene[$scene])){
                    $rules[$key] = $item;
                }else{
                    continue;
                }
            }
            $rules = array_merge($annotationRule,$rules);
        }else{
            $rules = array_merge($annotationRule,$validateModel->rule);
        }
        return $rules;
    }

    protected function getAnnotationValidateRule(Annotation $annotation){
        return [
            $annotation->value.(empty($annotation->doc) ? '' : '|'.$annotation->doc) => join('|',$annotation->rule)
        ];
    }
}
