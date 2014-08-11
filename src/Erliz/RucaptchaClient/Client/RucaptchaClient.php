<?php
/**
 * @author Stanislav Vetlovskiy
 * @date   11.08.14
 */

namespace Erliz\RucaptchaClient\Client;


use Erliz\RucaptchaClient\Service\FileService;
use Erliz\RucaptchaClient\Service\SettingsService;
use GuzzleHttp\Client;

class RucaptchaClient
{
    /** @var SettingsService */
    private $settings;
    /** @var Client */
    private $browser;
    /** @var FileService */
    private $fileService;

    public function __construct($apiKey)
    {
        $this->settings = new SettingsService($apiKey);
        $this->browser = new Client();
        $this->fileService = new FileService();
    }

    /**
     * @param $url
     *
     * @return bool|string
     */
    public function getCaptchaAnswerByUrl($url)
    {
        $captchaId = $this->getCaptchaId($url);
        for ($i = 0; $i < $this->settings->getTryCount(); $i++) {
            if ($this->settings->getAnswerTimeout()) {
                sleep($this->settings->getAnswerTimeout());
            }
            if ($answer = $this->getAnswer($captchaId)) {
                return $answer;
            }
        }

        return false;
    }

    /**
     * @param $url
     *
     * @return string
     * @throws \RuntimeException
     */
    private function getCaptchaId($url)
    {
        $response = $this->browser->post(
            $this->settings->getUploadUrl(),
            array(
                'body' => array(
                    'key'    => $this->settings->getApiKey(),
                    'method' => $this->settings->getBase64MethodName(),
                    'body'   => $this->fileService->getBase64($url)
                )
            )
        );

        list($code, $captchaId) = explode($this->settings->getDelimiter(), $response->getBody());
        if (!$this->settings->isSuccessResponseCode($code)){
            throw new \RuntimeException(sprintf('Bad response answer "%s"', $code));
        }

        return $captchaId;
    }

    private function getAnswer($captchaId)
    {
        $response = $this->browser->get(
            $this->settings->getCheckUrl(),
            array(
                'query' => array(
                    'key'    => $this->settings->getApiKey(),
                    'action' => 'get',
                    'id'     => $captchaId
                )
            )
        );

        $responseBody = explode($this->settings->getDelimiter(), $response->getBody());
        $code = $responseBody[0];

        if (!$this->settings->isSuccessResponseCode($code)){
            if ($this->settings->isNotReadyResponseCode($code)) {
                return false;
            }
            throw new \RuntimeException(sprintf('Bad response answer "%s"', $code));
        }

        return $responseBody[1];
    }

} 