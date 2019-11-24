<?php
declare (strict_types = 1);

namespace app\annotation;

use Doctrine\Common\Annotations\Annotation;

/**
 * class Param
 * @package app\annotation
 * @Annotation
 * @Target({"METHOD"}) # 不需要进行类注解去掉"CLASS"，不需要方法注解去掉"METHOD"
 */
class Param extends Annotation
{
    /**
     * @var array
     * @Enum(
     *     "require","number","integer","float","bool","email","email","accepted","date",
     *     "alpha","alphaNum","alphaDash","chs","chsAlpha","chsAlphaNum","chsDash","cntrl",
     *     "graph","print","lower","upper","space","xdigit","activeUrl","url","ip",
     *     "dateFormat:format","mobile","idCard","macAddr","zip","in:1,2,...","notIn:1,2,...",
     *     "between:1,10","notBetween:1,10","length:num1,num2","length:num1","max:number",
     *     "min:number","after:日期","before:日期","expire:开始时间,结束时间","allowIp:allow1,allow2,...",
     *     "denyIp:allow1,allow2,...","confirm","different","eq:value","=:value",
     *     "same:value","egt:value","gt:value",">:value","elt:value","<=:value","lt:value",
     *     "<:value","filter:validate_ip","file","image:width,height,type","fileExt:允许的文件后缀",
     *     "fileMime:允许的文件类型","fileSize:允许的文件字节大小","token:表单令牌名称","unique:table,field,except,pk",
     *     "requireIf:field,value","requireWith:field","requireWithout:field","requireCallback:callable",
     *     ""
     * )
     */
    public $rule=[];

    /**
     * @var string
     */
    public $doc;

    /**
     * @Enum("file","header","post","get","put","delete","options")
     * @var string
     */
    public $type = null;
}
