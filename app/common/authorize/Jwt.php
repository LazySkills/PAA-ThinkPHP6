<?php
/** Created by 嗝嗝<china_wangyu@aliyun.com>. Date: 2019-11-20  */

namespace app\common\authorize;

use app\exception\AuthenticationException;
use Firebase\JWT\JWT as FirebaseJwt;

class Jwt
{
    protected static $signature;
    protected static $uniqueId;
    protected static $data;

    public static function encode(string $uniqueId, string $signature)
    {
        self::$uniqueId = $uniqueId;
        self::$signature = $signature;
        return [
            'access_token' => static::create(),
            'refresh_token' => static::create(false)
        ];
    }


    public static function decode(string $token = '')
    {
        if (empty($token)){
            static::check();
        }else{
            static::$data = (array)FirebaseJwt::decode($token,config('paa.jwt.key'),['HS256']);
        }
        return static::$data;
    }

    public static function getHeaderAuthorization(){
        $authorization = request()->header(config('paa.jwt.param'));
        if (empty($authorization)){
            throw new AuthenticationException();
        }
        try {
            list($type, $token) = explode(' ', $authorization);
        } catch (\Exception $exception) {
            throw new AuthenticationException('authorization信息不正确');
        }
        if ($type !== config('paa.jwt.type')) {
            throw new AuthenticationException('接口认证方式需为'.config('paa.jwt.param'));
        }

        if (!$token) {
            throw new AuthenticationException();
        }
        return [$type, $token];
    }


    public static function check():void
    {
        list($type, $token) = static::getHeaderAuthorization();
        try {
            static::$data = (array)\Firebase\JWT\JWT::decode($token, config('paa.jwt.key'), ['HS256']);
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

    public static function refresh()
    {
        $payload = static::decode();
        self::$uniqueId = $payload['uniqueId'];
        self::$signature = $payload['signature'];
        return ['access_token'=>static::create()];
    }


    /**
     * 创建JWT鉴权
     * @param bool $expire 是否会失效 true|false
     * @return string
     * @throws AuthenticationException
     */
    private static function create(bool $expire = true){
        $payload = config('paa.jwt.payload');
        if (empty($payload)){
            throw new AuthenticationException('请检查paa配置文件中jwt选项是否正确');
        }
        $payload['iat'] = time();
        $payload['uniqueId'] = static::$uniqueId;
        $payload['signature'] = static::$signature;
        if ($expire === true) {
            $payload['exp'] = $payload['iat'] + config('paa.jwt.time');
        }else{
            unset($payload['exp']);
        }
        return FirebaseJwt::encode($payload, config('paa.jwt.key'), 'HS256');
    }
}