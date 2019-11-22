<?php
/** Created by 嗝嗝<china_wangyu@aliyun.com>. Date: 2019-11-20  */

namespace app\common\authentication\model;


interface AuthenticationHandler
{

    /** 加密 */
    public function encode();

    /** 解密 */
    public function decode();

    public function refresh();

    public function check();

}