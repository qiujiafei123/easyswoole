<?php
/**
 * Created by PhpStorm.
 * User: gaffey
 * Date: 19-5-16
 * Time: 下午5:29
 */
namespace App\Service\Upload;

class VideoUpload extends BaseUpload
{
    protected $allowFileType = [
        'mp4'
    ];

    protected $maxSize = 999999999999;
}