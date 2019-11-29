<?php
declare (strict_types = 1);

namespace app\annotation\handler;

use Doctrine\Common\Annotations\Annotation;
use think\annotation\handler\Handler;

class Doc extends Handler
{
    protected $path = '/public/annotation.json';

    public function func(\ReflectionMethod $refMethod, Annotation $annotation, \think\route\RuleItem &$rule)
    {
        $data = json_decode(file_get_contents(root_path().$this->path),true);
        if (!empty($annotation->group)){
            $annotationGroup = explode('.',$annotation->group);
            if (isset($annotationGroup[1])){
                $data[$annotationGroup[0]][$annotationGroup[1]][$annotation->value] = $this->getRuleData(
                    $data[$annotationGroup[0]][$annotationGroup[1]][$annotation->value] ?? [],
                    $rule
                );
                $data[$annotationGroup[0]][$annotationGroup[1]][$annotation->value]['hide'] = $annotation->hide == 'false' ? false : true ;
            }else{
                $data[$annotationGroup[0]][$annotation->value] =  $this->getRuleData(
                    $data[$annotationGroup[0]][$annotation->value] ?? [],
                    $rule
                );
                $data[$annotationGroup[0]][$annotation->value]['hide'] = $annotation->hide == 'false' ? false : true ;
            }
        }else{
            $data[$annotation->group][$annotation->value] =  $this->getRuleData(
                $data[$annotation->group][$annotation->value] ?? [],
                $rule
            );
            $data[$annotation->group][$annotation->value]['hide'] = $annotation->hide == 'false' ? false : true ;
        }

        file_put_contents(root_path().$this->path,json_encode($data,FILE_USE_INCLUDE_PATH));
    }




    protected function getRuleData(array $api, \think\route\RuleItem $rule){
        return [
            'rule' => $rule->getRule(),
            'route' => $rule->getRoute(),
            'method' => $rule->getMethod(),
            'validate' => $rule->getOption('validate'),
            'success' => $api['success'] ?? [],
            'error' => $api['error'] ?? [],
            'return_params' => $api['return_params'] ?? [],
        ];
    }
}
