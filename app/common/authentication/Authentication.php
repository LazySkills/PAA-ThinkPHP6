<?php
/** Created by 嗝嗝<china_wangyu@aliyun.com>. Date: 2019-11-20  */

namespace app\common\authentication;


use app\common\authentication\model\Jwt;

class Authentication
{
    const JWT_MODEL = Jwt::class;

    /**
     * @Enum("Authentication::JWT_MODEL")
     */
    protected $authentication = Jwt::class;

    /**
     * @return mixed
     */
    public function getAuthentication()
    {
        return $this->authentication;
    }

    /**
     * @param mixed $authentication
     */
    public function setAuthentication($authentication): void
    {
        $this->authentication = $authentication;
    }

    /**
     * Authentication constructor.
     * @param string $key
     */
    public function __construct(string $key = '')
    {
        $this->setAuthentication(self::JWT_MODEL);
        !empty($key) && $this->authentication->setKey($key);
    }

    public function encode(string $uniqueId){
        $this->authentication->setUniqueId($uniqueId);
        return $this->authentication->encode();
    }

    public function check(){
        $this->authentication->check();
    }

    public function refresh(){
        return $this->authentication->refresh();
    }


    public function decode(){
        return $this->authentication->decode();
    }


}