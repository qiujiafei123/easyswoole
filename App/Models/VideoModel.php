<?php
/**
 * Created by PhpStorm.
 * User: gaffey
 * Date: 19-5-16
 * Time: 下午8:49
 */
namespace App\Models;

class VideoModel extends BaseModel
{
    protected $tablename = 'video';

    public function insertData(array $data)
    {
        if (is_array(current($data))) {
            foreach ($data as $val) {
                $data = $this->db->insert($this->tablename, $val);
            }
        } else {
            $data = $this->db->insert($this->tablename, $data);
        }
        return $data;
    }

    public function getAll()
    {
        return $this->db->get($this->tablename);//获取表的数据
    }
}