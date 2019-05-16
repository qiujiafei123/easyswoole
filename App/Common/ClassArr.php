<?php
/**
 * Created by PhpStorm.
 * User: gaffey
 * Date: 19-5-16
 * Time: 下午7:51
 */
namespace App\Common;

class ClassArr
{
    /**
     * 对象映射表
     * @var array
     */
     protected static $classMap = [
        'image' => \App\Service\Upload\ImageUpload::class,
        'video' => \App\Service\Upload\VideoUpload::class,
    ];

    /**
     * 利用反射机制找到映射表的对应关系进行实例化
     * @param $key
     * @param array $params
     * @param bool $need
     * @return mixed|object
     * @throws \ReflectionException
     */
    public static function initClass($key, $params = [], $need = true)
    {
        if (!is_array($params)) {
            throw new \InvalidArgumentException((string)$params. "参数不是一个数组");
        }
        if (!array_key_exists($key, self::$classMap)) {
            throw new \InvalidArgumentException($key . "类型不可实例化");
        }

        $className = self::$classMap[$key];
        return $need === true ? (new \ReflectionClass($className))->newInstanceArgs($params) : $className;
    }
}