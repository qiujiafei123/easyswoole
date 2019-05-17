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
    protected $pageSize = 3;

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

    public function getPaginationData($page)
    {
        $page_size=3;
        $total = $this->db->count($this->tablename);
        $data['totalPage'] = ceil($total/$this->pageSize);
        $data['nextPage'] = $page+1;
        $data['lastPage'] = $page-1;
        $data['data'] = $this->db->get($this->tablename,[($page-1)*$page_size,$page_size],'*');
        //$sql = $this->db->getLastQuery();
        return $data;
    }
}