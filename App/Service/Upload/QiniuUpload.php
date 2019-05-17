<?php
/**
 * Created by PhpStorm.
 * User: gaffey
 * Date: 19-5-17
 * Time: 下午2:33
 */
namespace App\Service\Upload;

use EasySwoole\Http\Request;
use Qiniu\Storage\UploadManager;

class QiniuUpload extends BaseUpload
{
    private $auth;
    private $bucketName;
    private $uploadKey;

    public function __construct(Request $request, $type = null)
    {
        parent::__construct($request, $type);
        $accessKey = \App\Common\Help::getConfig('Qiniu.ak');
        $secretKey = \App\Common\Help::getConfig('Qiniu.sk');
        $this->bucketName = \App\Common\Help::getConfig('Qiniu.bucket');
        $this->auth = new \Qiniu\Auth($accessKey, $secretKey);
        $this->uploadKey = $this->type.'/'. $this->getNewFileName();
    }

    public function upload()
    {
        $token = $this->auth->uploadToken($this->bucketName, $this->uploadKey);
        $uploadMgr = new UploadManager();
        try {
            list($ret, $err) = $uploadMgr->putFile($token, $this->uploadKey, $this->getUploadFile()->getTempName());
            if ($err !== null) {
                throw new \Exception($err);
            }
            return (string) \App\Common\Help::getConfig('Qiniu.domain') . '/' . $this->uploadKey;
        } catch (\Exception $e) {
            throw new \Exception($e);
        }

    }
}