<?php
/** Created by 嗝嗝<china_wangyu@aliyun.com>. Date: 2019-11-20  */

namespace app\exception;


use Throwable;

class BaseException extends \Exception
{
    protected $message = '服務器內部錯誤，不想告訴你';

    protected $code = 500;

    protected $error_code = 1000;

    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct(
            empty($message) ? $this->message : $message,
            empty($code) ? $this->code : $code,
            $previous);
    }

    /**
     * @return int
     */
    public function getErrorCode(): int
    {
        return $this->error_code;
    }
}