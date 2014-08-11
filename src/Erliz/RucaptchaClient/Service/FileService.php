<?php
/**
 * @author Stanislav Vetlovskiy
 * @date   11.08.14
 */

namespace Erliz\RucaptchaClient\Service;


class FileService
{
    /**
     * @param $url
     *
     * @return string Image in base64 type
     * @throws \InvalidArgumentException
     */
    public function getBase64($url)
    {
        if (filter_var($url, FILTER_VALIDATE_URL) === FALSE) {
            throw new \InvalidArgumentException('Not valid url');
        }

        $fileData = file_get_contents($url);
        return $base64Data = base64_encode($fileData);
    }
} 