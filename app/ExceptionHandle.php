<?php
namespace app;

use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\Handle;
use think\exception\HttpException;
use think\exception\HttpResponseException;
use think\exception\ValidateException;
use think\Response;
use Throwable;

/**
 * 应用异常处理类
 */
class ExceptionHandle extends Handle
{
    /**
     * 不需要记录信息（日志）的异常类列表
     * @var array
     */
    protected $ignoreReport = [
        HttpException::class,
        HttpResponseException::class,
        ModelNotFoundException::class,
        DataNotFoundException::class,
        ValidateException::class,
    ];

    /**
     * 记录异常信息（包括日志或者其它方式记录）
     *
     * @access public
     * @param  Throwable $exception
     * @return void
     */
    public function report(Throwable $exception): void
    {
        // 使用内置的方式记录异常日志
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @access public
     * @param \think\Request   $request
     * @param Throwable $e
     * @return Response
     */
    public function render($request, Throwable $e): Response
    {
        dump($e);die;
        // 开启debug开启时，走系统错误
        if (env('APP_DEBUG')){
            // 其他错误交给系统处理
            return parent::render($request, $e);
        }

        // 添加自定义异常处理机制
        return \response([
            'msg' => $e->getMessage() ?? '服務器內部錯誤，不想告訴你',
            'error_code' => method_exists($e,'getErrorCode') ? $e->getErrorCode() : 999,
            'request_url' => $request->method(). ' ' .$request->url()
        ],
            $e->getCode() ?? 500,
            [],
            'json');
    }
}
