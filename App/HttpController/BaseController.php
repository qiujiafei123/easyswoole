<?php
/**
 * Created by PhpStorm.
 * User: gaffey
 * Date: 19-5-14
 * Time: 下午6:13
 */
namespace App\HttpController;
use EasySwoole\Http\AbstractInterface\Controller;

class BaseController extends Controller
{
    function index()
    {
    }

    public function successJson($data = '', string $message = 'ok', int $code = 200)
    {
        return $this->writeJson($code, $data, $message);
    }

    public function errorJson($data = '', string $message = 'error', int $code = 500)
    {
        return $this->writeJson($code, $data, $message);
    }

    public function onException(\Throwable $throwable): void
    {
        $instance = \EasySwoole\EasySwoole\Config::getInstance();
        //如果是dev环境，则输出全部错误信息
        if (true === $instance->getConf('DISPLAY_ERROR')) {
            parent::onException($throwable);
        }
        $this->writeJson(500, $throwable->getMessage(), '服务器内部异常');
    }
}