# Swoft JSON RCP Client (TCP) for Yii2

## Install

- composer command

```bash
composer require curtis18/yii2-swoft-rpc
```

## Usage

- Please make sure Swoft RPC Server created before using this extension in Yii2.

~~~php
use yii\Swoft\JsonRpc\Client;

public function actionRpcTest()
{
    $rpc = new Client("tcp://127.0.0.1:8001", \App\Rpc\Lib\TestInterface::class, "1.7");
    $result = $rpc->echoRpc("Hello World");
    echo $result;
}
~~~

## Resources

* [Documentation of Swoft](https://swoft.org/docs)
* [Report Issues][issues] and [Send Pull Requests][pulls] in the [Main Swoft JSON RCP Client (TCP) for Yii2 Repository][repository]

[pulls]: https://github.com/curtis18/yii2-swoft-rpc/pulls
[repository]: https://github.com/curtis18/yii2-swoft-rpc
[issues]: https://github.com/curtis18/yii2-swoft-rpc/issues

## LICENSE

The Component is open-sourced software licensed under the [Apache license](LICENSE).