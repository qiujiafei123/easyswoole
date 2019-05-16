<?php
/**
 * Created by PhpStorm.
 * User: gaffey
 * Date: 19-5-16
 * Time: 下午2:13
 */
namespace App\HttpController\Api;

use App\Common\ClassArr;
use App\HttpController\BaseController;

class Upload extends BaseController
{
    public function file()
    {
        $request = $this->request();
        $files = $request->getSwooleRequest()->files;
        $type = current(array_keys($files));

        if (empty($type)) {
            return $this->errorJson('文件不合法');
        }
        try {
            $uploadObj = ClassArr::initClass($type, [$request, $type]);
            $flag = $uploadObj->upload();
            if ($flag === false) {
                return $this->errorJson("上传失败");
            }
        } catch (\Throwable $e) {
            return $this->errorJson($e->getMessage());
        }

        return $this->successJson(["url" => $flag]);
    }

}