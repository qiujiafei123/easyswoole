<?php
/**
 * Created by PhpStorm.
 * User: gaffey
 * Date: 19-5-16
 * Time: 下午4:15
 */
namespace App\Service\Upload;

use EasySwoole\Http\Request;

class BaseUpload
{
    /**
     * 判断上传的文件类型
     * @var string
     */
    protected $type = '';

    /**
     * 当前文件类型和允许上传的文件类型
     * @var string
     */
    protected $fileMain = '';   //文件主类型
    protected $fileSuffix = ''; //文件后缀
    protected $allowFileType = [];
    /**
     * Request对象
     * @var Request
     */
    protected $request;



    protected $size;

    /**
     * 最大上传大小
     * @var
     */
    protected $maxSize;

    protected $fileName = '';

    /**
     * 初始化参数
     * BaseUpload constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
        $files = $request->getSwooleRequest()->files;
        $this->type = current(array_keys($files));

    }

    /**
     * 上传逻辑
     * @return bool
     */
    public function upload()
    {
        $file = $this->request->getUploadedFile($this->type);
        $this->size = $file->getSize();
        $this->fileName = $file->getClientFilename();
        $mediaType = explode('/', $file->getClientMediaType());
        if (!isset($mediaType[0]) || !isset($mediaType[1])) {
            throw new \InvalidArgumentException('未知的文件类型');
        }
        $this->fileMain = $mediaType[0];
        $this->fileSuffix = $mediaType[1];

        //检查上传的文件类型是否是预先定义好的
        if (!in_array($this->fileSuffix, $this->allowFileType)) {
            return false;
        }

        //检查文件大小是否超过最大设置
        if ($this->size > $this->maxSize) {
            return false;
        }
        $dirSuffix = "/" . $this->fileMain . date('Y-m', time());
        $dir = EASYSWOOLE_ROOT . "/webroot/" . $dirSuffix;
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        //移动文件
        $newFileName = $this->getNewFileName();
        $flag = $file->moveTo($dir . "/" . $newFileName);
        if ($flag === true) {
            return $dirSuffix . "/" . $newFileName;
        } else {
            return false;
        }
    }

    public function getNewFileName()
    {
        return uniqid().$this->fileName;
    }
}