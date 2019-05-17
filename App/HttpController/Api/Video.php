<?php
/**
 * Created by PhpStorm.
 * User: gaffey
 * Date: 19-5-16
 * Time: 下午9:01
 */
namespace App\HttpController\Api;

use App\HttpController\BaseController;
use App\Models\VideoModel;
use EasySwoole\Validate\Validate;

class Video extends BaseController
{
    public function post()
    {
        $post = $this->request()->getRequestParam();

        $validata = new Validate();
        $validata->addColumn('name')->required('视频名称必填');
        $validata->addColumn('image')->required('视频封面必填');
        $validata->addColumn('cat_id')->required('视频分类必填');
        $validata->addColumn('url')->required('视频分类必填');
        $validata->addColumn('cat_id')->required('视频分类必填');

        if (!$this->validate($validata)) {
            return $this->errorJson($validata->getError()->__toString(), 'fail');
        }
        try {
            $baseData = ['create_time' => time(), 'update_time' => time()];
            $data = array_unique(array_merge($post, $baseData));
            $flag = (new VideoModel())->insertData($data);
        } catch (\Exception $e) {
            return $this->errorJson($e->getMessage());
        }

        if (!$flag) {
            return $this->errorJson("记录失败");
        }

        return $this->successJson(['id' => $flag]);
    }
}