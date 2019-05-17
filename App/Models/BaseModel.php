<?php
/**
 * Created by PhpStorm.
 * User: gaffey
 * Date: 19-5-16
 * Time: 下午8:46
 */
namespace App\Models;

use App\Utility\Pool\MysqlPool;
use EasySwoole\Component\Pool\PoolManager;

class BaseModel
{
    protected $db;
    public function __construct()
    {
        //如果没有表名，报错
        if (empty($this->tablename)) {
            throw new \Exception('table error');
        }
        //如果db已经被实例化了，那么就不再获取
        if (null === $this->db) {
            $this->db = PoolManager::getInstance()->getPool(MysqlPool::class)->getObj();
        }
    }

    /**
     * 自动回收
     */
    public function __destruct()
    {
        PoolManager::getInstance()->getPool(MysqlPool::class)->recycleObj($this->db);
    }
}