<?php
/**
 * Created by PhpStorm.
 * User: gaffey
 * Date: 19-5-17
 * Time: 下午12:20
 */
namespace App\Common;

class HelpFunction
{

    public static function getConfig(string $config)
    {
        $instance = \EasySwoole\EasySwoole\Config::getInstance();
        return $instance->getConf($config);
    }
}