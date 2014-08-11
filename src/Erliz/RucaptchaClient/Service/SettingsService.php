<?php
/**
 * @author Stanislav Vetlovskiy
 * @date   11.08.14
 */

namespace Erliz\RucaptchaClient\Service;


use Erliz\RucaptchaClient\Entity\SettingsEntity;

class SettingsService
{
    /** @var SettingsEntity */
    private $settings;

    public function __construct($apiKey)
    {
        $this->settings = new SettingsEntity();
        $this->settings->setApiKey($apiKey);
    }

    public function getBase64MethodName()
    {
        return SettingsEntity::API_BASE64_METHOD;
    }

    /**
     * @return string
     */
    public function getUploadUrl()
    {
        return sprintf(
            '%s%s',
            SettingsEntity::API_DOMAIN,
            SettingsEntity::API_UPLOAD_ACTION
        );
    }

    /**
     * @return string
     */
    public function getCheckUrl()
    {
        return sprintf(
            '%s%s',
            SettingsEntity::API_DOMAIN,
            SettingsEntity::API_CHECK_ACTION
        );
    }

    /**
     * @return string
     */
    public function getApiKey()
    {
        return $this->settings->getApiKey();
    }

    /**
     * @param string $code
     *
     * @return bool
     */
    public function isSuccessResponseCode($code)
    {
        return $code == SettingsEntity::API_SUCCESS_CODE;
    }

    /**
     * @param $code
     *
     * @return bool
     */
    public function isNotReadyResponseCode($code)
    {
        return $code == SettingsEntity::API_ANSWER_NOT_READY;
    }

    /**
     * @return string
     */
    public function getDelimiter()
    {
        return SettingsEntity::API_RESPONSE_DELIMITER;
    }

    /**
     * @return int
     */
    public function getAnswerTimeout()
    {
        return SettingsEntity::API_ANSWER_TIMEOUT;
    }

    public function getTryCount()
    {
        return SettingsEntity::API_ANSWER_TRY_COUNT;
    }
} 