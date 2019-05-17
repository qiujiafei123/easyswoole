<?php
/**
 * Created by PhpStorm.
 * User: gaffey
 * Date: 19-5-14
 * Time: 下午6:13
 */
namespace App\HttpController;
use EasySwoole\Http\AbstractInterface\Controller;

/**
 * 这里直接集成 easyswoole 的 Controoler
 * 以后的业务层 Controller 就继承 BaseController
 * Class BaseController
 * @package App\HttpController
 */
class BaseController extends Controller
{
    /**
     * 根据父类的抽象方法必须实现这个index方法
     */
    function index()
    {
    }

    /**
     * 封装一个成功的返回json
     * @param string $data
     * @param string $message
     * @param int $code
     * @return bool
     */
    public function successJson($data = '', string $message = 'OK', int $code = 200)
    {
        //我将这里的writeJson方法里的参数调换了位置，符合主流的状态码，成功与否，结果集顺序
        return $this->writeJson($code, $message, $data);
    }

    /**
     * 封装一个错误的返回json
     * @param string $data
     * @param string $message
     * @param int $code
     * @return bool
     */
    public function errorJson($data = '', string $message = 'error', int $code = 500)
    {
        return $this->writeJson($code, $message, $data);
    }

    /**
     * 我在这里覆盖了父类的 onException 方法
     * @param \Throwable $throwable
     * @throws \Throwable
     */
    public function onException(\Throwable $throwable): void
    {
        //通过获取 dev.php 或 producede.php 的配置来判断是生产环境还是开发环境
        $instance = \EasySwoole\EasySwoole\Config::getInstance();
        //如果是dev环境，则输出全部错误信息,方便代码调试
        if (true === $instance->getConf('DISPLAY_ERROR')) {
            parent::onException($throwable);
        }
        //这里将不是dev环境的启动，将错误输出为 json 格式，防止前端解析失败以及暴露服务器内部信息
        $this->writeJson(500, '服务器内部异常');
    }
}