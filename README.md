# php-wechat-work-robot
Easy to send WechatWork robot message

## Usage
```shell script
composer require ritaswc/wechat-work-robot
```

## Send Message
```php
$key = 'a45bxxxx-xxxx-xxxx-xxxx-fbf0a09cxxxx';
$robot = new \Ritaswc\WechatWorkRobot\WechatWorkRobot($key);

$robot->sendText('PHP is the best language in the world!', ['huixinchen', '@all']);
$robot->sendText('PHP is the best language in the world!', [], ['199****1322', '@all']);

$robot->sendImage('wechatpay.png');
$robot->sendImage('https://blogoss.yinghualuo.cn/blog/2019/05/wechatpay.png');

$robot->sendMarkDown("## 欢迎使用支付宝赞助
## 欢迎使用微信赞助");

$robot->sendNews([
    [
        'title'       => 'Charles的小星球',
        'description' => '一个PHP码农的自述',
        'url'         => 'https://blog.yinghualuo.cn',
        'picurl'      => 'https://blog.yinghualuo.cn/wp-content/themes/twentyseventeen/assets/images/header.jpg',
    ],
    [
        'title'       => 'Charles的小星球1',
        'description' => '一个PHP码农的自述1',
        'url'         => 'https://blog.yinghualuo.cn',
        'picurl'      => 'https://blog.yinghualuo.cn/wp-content/themes/twentyseventeen/assets/images/header.jpg',
    ]
]);

```

## Author Blog
[Charles的小星球](https://blog.yinghualuo.cn)

## Dingtalk Document
[自定义机器人开发](https://work.weixin.qq.com/help?person_id=1&doc_id=13376)

## License
MIT

## Sponsor
![wechatpay](https://raw.githubusercontent.com/ritaswc/zx-ip-address/master/wechatpay.png)

![alipay](https://raw.githubusercontent.com/ritaswc/zx-ip-address/master/alipay.png)