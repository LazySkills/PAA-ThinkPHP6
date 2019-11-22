<?php
/** Created by 嗝嗝<china_wangyu@aliyun.com>. Date: 2019-11-20  */

namespace app\common\authentication\model;

use app\exception\AuthenticationException;
use Firebase\JWT\JWT as FirebaseJwt;

class Jwt
{
    private $key = 'PAA-thinkphp6';
    private $type = 'Bearer';
    private $uniqueId = null;
    private $signature = null;

    /**
     * @param string $key
     */
    public function setKey(string $key)
    {
        $this->key = $key;
    }


    public function encode(string $uniqueId, string $signature)
    {
        $this->uniqueId = $uniqueId;
        $this->signature = $signature;
        return [
            'access_token' => $this->create(),
            'refresh_token' => $this->create(false)
        ];
    }


    public function decode()
    {
        return $this->check();
    }

    public function check()
    {

        if ($authorization = request()->header('authorization')) {
            try {
                list($type, $token) = explode(' ', $authorization);
            } catch (\Exception $exception) {
                throw new AuthenticationException('authorization信息不正确');
            }

            if ($type !== $this->type) {
                throw new AuthenticationException('接口认证方式需为Bearer');
            }

            if (!$token) {
                throw new AuthenticationException();
            }

            try {
                return (array)\Firebase\JWT\JWT::decode($token, $this->getKey(), ['HS256']);
            } catch (\Firebase\JWT\SignatureInvalidException $exception) {  //签名不正确
                throw new AuthenticationException('令牌签名不正确');
            } catch (\Firebase\JWT\BeforeValidException $exception) {  // 签名在某个时间点之后才能用
                throw new AuthenticationException('令牌尚未生效');
            } catch (\Firebase\JWT\ExpiredException $exception) {  // token过期
                throw new AuthenticationException('令牌已过期，刷新浏览器重试');
            } catch (\UnexpectedValueException $exception) {
                throw new AuthenticationException('access_token不正确，' . $exception->getMessage());
            } catch (\Exception $exception) {  //其他错误
                throw new AuthenticationException($exception->getMessage());
            }
        }

        throw new AuthenticationException('请求header未携带authorization信息');


    }

    public function refresh()
    {
        $payload = $this->check();
        $this->uniqueId = $payload['uniqueId'];
        $this->signature = $payload['signature'];
        return $this->create();
    }


    /**
     * 创建JWT鉴权
     * @param bool $expire 是否会失效 true|false
     * @param int $time 过期时长
     * @return string
     */
    private function create(bool $expire = true,int $time = 7200){
        $payload = [
            'iss' => 'TRR', //签发者
            'iat' => time(), //什么时候签发的
            'exp' => time() + 7200 , //过期时间
            'uniqueId' => $this->uniqueId,
            'signature' => $this->signature
        ];
        $expire === true && $payload['exp'] = $payload['iat'] + $time;
        return FirebaseJwt::encode($payload, $this->key);
    }
}