<?php
declare (strict_types = 1);

namespace app\annotation\handler;

use Doctrine\Common\Annotations\Annotation;
use think\annotation\handler\Handler;
use think\Validate;

final class Param extends Handler
{

    public function func(\ReflectionMethod $refMethod, Annotation $annotation, \think\route\RuleItem &$rule)
    {
        $rules = [];
        $validateRules = $rule->getOption('validate');
        $annotationRule = $this->getAnnotationValidateRule($annotation);
        if (empty($validateRules[0])){
            $rules = $annotationRule;
        }elseif (is_string($validateRules[0])){
            $validateModel = new $validateRules[0]();
            $validateModelRules = $this->getAnnotationValidateSceneRule($annotationRule,$validateRules[1],$validateModel);
            if (!$validateModelRules){
                $rules = $annotationRule;
            }else{
                $rules = $validateModelRules;
            }
        }elseif (is_array($validateRules[0])){
            $rules = array_merge($annotationRule,$validateRules[0]);
        }
        $rule->validate($rules);
    }

    protected function getAnnotationValidateSceneRule($annotationRule,$scene,Validate $validateModel){
        $rules = [];
        $getValidateModelRule = function (){
            return $this->rule;
        };
        $validateModelRules = $getValidateModelRule->call($validateModel);
        $getValidateModelScene = function (){
            return $this->scene;
        };
        $validateModelScenes = $getValidateModelScene->call($validateModel);
        if (isset($validateModelScenes[$scene])){
            foreach ($validateModelRules as $key => $item){
                $ruleItem = explode('|',$key);
                if (in_array($ruleItem[0],$validateModelScenes[$scene])){
                    $rules[$key] = $item;
                }else{
                    continue;
                }
            }
            $rules = array_merge($annotationRule,$rules);
        }else{
            $rules = array_merge($annotationRule,$validateModelRules);
        }
        return $rules;
    }

    protected function getAnnotationValidateRule(Annotation $annotation){
        return [
            $annotation->value.(empty($annotation->doc) ? '' : '|'.$annotation->doc) => join('|',$annotation->rule)
        ];
    }
}
