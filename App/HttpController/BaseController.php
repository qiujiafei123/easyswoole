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
        $this->writeJson(500, $throwable->getMessage(), '服务器内部异常');
    }
}