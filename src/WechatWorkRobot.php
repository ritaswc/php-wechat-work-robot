<?php

namespace Ritaswc\WechatWorkRobot;

use Ritaswc\WechatWorkRobot\Exception\InvalidFileException;

class WechatWorkRobot
{
    private $key = '';
    const URL = 'https://qyapi.weixin.qq.com/cgi-bin/webhook/send?key=';

    public function __construct($key)
    {
        $this->key = (string)$key;
    }

    /**
     * Send Text Message
     * @param string $text
     * @param array $mentionedList
     * @param array $mentionedMobileList
     * @return array|bool|null
     */
    public function sendText($text, $mentionedList = [], $mentionedMobileList = [])
    {
        $data = [
            'msgtype' => 'text',
            'text'    => [
                'content' => (string)$text,
            ],
        ];
        if (count($mentionedList)) {
            $data['mentioned_list'] = $mentionedList;
        }
        if (count($mentionedMobileList)) {
            $data['mentioned_mobile_list'] = $mentionedMobileList;
        }
        return $this->request($data);
    }

    /**
     * Send Markdown Message
     * @param string $markdownText
     * @return array|bool|null
     */
    public function sendMarkDown($markdownText)
    {
        return $this->request([
            'msgtype'  => 'markdown',
            'markdown' => [
                'content' => $markdownText,
            ],
        ]);
    }

    /**
     * Send Articles, title picture with link
     * @param array $articles
     * @return array|bool|null
     */
    public function sendNews($articles = [])
    {
        return $this->request([
            'msgtype' => 'news',
            'news' => [
                'articles' => $articles,
            ],
        ]);
    }

    /**
     * Send Image Message
     * @param string $image /local/path/image.png https://blogoss.yinghualuo.cn/blog/2019/05/wechatpay.png
     * @return array|bool|null
     */
    public function sendImage($image)
    {
        if (strpos($image, '://') > 0) {
            return $this->sendUrlImage($image);
        }
        return $this->sendFileImage($image);
    }

    private function sendFileImage($fileName)
    {
        if (!is_readable($fileName)) {
            throw new InvalidFileException("'{$fileName}' is not available");
        }
        return $this->request([
            'msgtype' => 'image',
            'image'   => [
                'base64' => base64_encode(file_get_contents($fileName)),
                'md5'    => md5_file($fileName),
            ],
        ]);
    }

    private function sendUrlImage($image)
    {
        $tmpFileName = $this->tmpFileName();
        $this->downloadFile($image, $tmpFileName);
        $r = $this->sendFileImage($tmpFileName);
        @unlink($tmpFileName);
        return $r;
    }

    private function downloadFile($url, $targetFileName)
    {
        @unlink($targetFileName);
        $handle = fopen($url, 'rb');
        if (null === $handle) {
            throw new InvalidFileException("'{$url}' is not available");
        }
        while ($buffer = fread($handle, 1024 * 1024)) {
            file_put_contents($targetFileName, $buffer, FILE_APPEND);
        }
        fclose($handle);
    }

    private function tmpFileName()
    {
        return sys_get_temp_dir() . DIRECTORY_SEPARATOR . uniqid('url_image_');
    }

    private function url()
    {
        return static::URL . $this->key;
    }

    /**
     * @param $data
     * @return null|bool|array
     */
    private function request($data)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url());
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json;charset=utf-8']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $body = curl_exec($ch);
        curl_close($ch);
        return json_decode($body, true);
    }
}