<?php
/**
 * Created by PhpStorm.
 * User: gaffey
 * Date: 19-5-16
 * Time: 下午8:49
 */
namespace App\Models;

use function FastRoute\TestFixtures\empty_options_cached;

class VideoModel extends BaseModel
{
    protected $tablename = 'video';
    protected $pageSize;

    /**
     * 插入方法
     * @param array $data
     * @return mixed
     */
    public function insertData(array $data)
    {
        if (is_array(current($data))) {
            foreach ($data as $val) {
                $result = $this->db->insert($this->tablename, $val);
            }
        } else {
            $result = $this->db->insert($this->tablename, $data);
        }
        return $result;
    }

    /**
     * 获取数据带分页方法
     * @param $page
     * @param int $pageSize
     * @return mixed
     */
    public function getPaginationData($page, $condition = [] , $pageSize = 3)
    {
        $this->pageSize = $pageSize;
        if (!empty($condition['cat_id'])) {
            $this->db->where('cat_id', $condition['cat_id']);
        }
        $total = $this->db->count($this->tablename);
        $data['total_page'] = ceil($total/$this->pageSize);
        $data['page_size'] = $this->pageSize;
        $data['count'] = $total;
        if (!empty($condition['cat_id'])) {
            $this->db->where('cat_id', $condition['cat_id']);
        }
        $data['lists'] = $this->db->orderBy('id', 'desc')->get($this->tablename,[($page-1)*$this->pageSize,$this->pageSize],'*');
        return $data;
    }

    /**
     * 获取单条数据
     * @param $id
     * @return mixed
     */
    public function getOne($condition = [])
    {
        if (!empty($condition)) {
            $value = current($condition);
            $key = array_search($value, $condition);
            $this->db->where($key, $value);
        }
        return $this->db->getOne($this->tablename);
    }

    public function cacheVideo($condition = [], $pageSize = 3)
    {
        $this->pageSize = $pageSize;
        if (!empty($condition['cat_id'] && $condition['cat_id'] !== 0)) {
            $this->db->where('cat_id', $condition['cat_id']);
        }
        $total = $this->db->count($this->tablename);
        $data['total_page'] = ceil($total/$this->pageSize);
        $data['page_size'] = $this->pageSize;
        $data['count'] = $total;
        if (!empty($condition['cat_id'])) {
            $this->db->where('cat_id', $condition['cat_id']);
        }
        $data['lists'] = $this->db->orderBy('id', 'desc')->get($this->tablename,[(1-1)*$this->pageSize,$this->pageSize],'*');
        return $data;
    }
}