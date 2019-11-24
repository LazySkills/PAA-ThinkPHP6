<?php
declare (strict_types = 1);

namespace app\annotation;

use Doctrine\Common\Annotations\Annotation;

/**
 * class Doc
 * @package app\annotation
 * @Annotation
 * @Target({"METHOD","CLASS"}) # 不需要进行类注解去掉"CLASS"，不需要方法注解去掉"METHOD"
 */
class Doc extends Annotation
{
    /**
     * @var string
     */
    public $group = null;

    /**
     * @var bool
     */
    public $hide = false;
}
