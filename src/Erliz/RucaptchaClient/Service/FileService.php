<?php
/**
 * @author Stanislav Vetlovskiy
 * @date   11.08.14
 */

namespace Erliz\RucaptchaClient\Service;


use Erliz\RucaptchaClient\Exception\CaptchaDownloadException;
use Erliz\RucaptchaClient\Exception\InvalidArgumentException;

class FileService
{
    /**
     * @param $url
     *
     * @throws CaptchaDownloadException
     * @throws InvalidArgumentException
     *
     * @return string Image in base64 type
     */
    public function getBase64($url)
    {
        if (filter_var($url, FILTER_VALIDATE_URL) === FALSE) {
            throw new InvalidArgumentException('Not valid url');
        }

        $fileData = file_get_contents($url);
        if (!$fileData) {
            throw new CaptchaDownloadException();
        }
        return $base64Data = base64_encode($fileData);
    }
}
