<?php
/**
 * Created by PhpStorm.
 * User: gaffey
 * Date: 19-5-16
 * Time: 下午2:13
 */
namespace App\HttpController\Api;

use App\HttpController\BaseController;
use App\Service\Upload\ImageUpload;
use App\Service\Upload\VideoUpload;

class Upload extends BaseController
{
    public function file()
    {
        $request = $this->request();
        $type = $request->getRequestParam('type');
        //接受 type 参数来判断实例化哪个对象
        if (NULL === $type || !in_array($type, ['image', 'video'])) {
            return $this->errorJson("参数不合法");
        }

        $upload = $type === 'image' ? new ImageUpload($request) : new VideoUpload($request);
        try {
            $flag = $upload->upload();
            if ($flag === false) {
                return $this->errorJson("上传失败");
            }
        } catch (\Exception $e) {
            return $this->errorJson($e);
        }

        return $this->successJson(["url" => $flag]);
    }

}