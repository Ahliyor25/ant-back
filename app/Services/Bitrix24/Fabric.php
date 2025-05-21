<?php


namespace App\Services\Bitrix24;

use Bitrix24\SDK\Core\Batch;
use Bitrix24\SDK\Core\BulkItemsReader\BulkItemsReaderBuilder;
use Bitrix24\SDK\Core\Contracts\BulkItemsReaderInterface;
use Bitrix24\SDK\Core\Contracts\CoreInterface;
use Bitrix24\SDK\Core\CoreBuilder;
use Bitrix24\SDK\Core\Credentials\Credentials;
use Bitrix24\SDK\Core\Credentials\WebhookUrl;
use Bitrix24\SDK\Core\Exceptions\InvalidArgumentException;
use Bitrix24\SDK\Services\ServiceBuilder;
use Exception;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\IntrospectionProcessor;
use Monolog\Processor\MemoryUsageProcessor;
use Psr\Log\LoggerInterface;


/**
 * Class Fabric
 *
 * @package App\Services\Bitrix24\Fabric
 */
class Fabric
{
    protected static Credentials $credentials;

    /**
     * @return ServiceBuilder
     * @throws InvalidArgumentException
     */
    public static function getServiceBuilder(): ServiceBuilder
    {
        return new ServiceBuilder(self::getCore(), self::getBatchService(), self::getBulkItemsReader(), self::getLogger());
    }

    /**
     * @return BulkItemsReaderInterface
     * @throws InvalidArgumentException
     */
    public static function getBulkItemsReader(): BulkItemsReaderInterface
    {
        return (new BulkItemsReaderBuilder(self::getCore(), self::getBatchService(), self::getLogger()))->build();
    }

    /**
     * @param Credentials|null $credentials
     * @return CoreInterface
     * @throws InvalidArgumentException
     */
    public static function getCore(): CoreInterface
    {
        return (new CoreBuilder())
            ->withLogger(self::getLogger())
            ->withCredentials(
                self::$credentials ?? self::getDefaultCredentials()
            )
            ->build();
    }

    /**
     * @return LoggerInterface
     */
    public static function getLogger(): LoggerInterface
    {
        $log = new Logger("bitrix24");
        $log->pushHandler(new StreamHandler(
            config("bitrix.log.dir"),
            config("bitrix.log.level")
        ));
        $log->pushProcessor(new MemoryUsageProcessor(true, true));
        $log->pushProcessor(new IntrospectionProcessor());

        return $log;
    }

    /**
     * @return Batch
     * @throws InvalidArgumentException
     */
    public static function getBatchService(): Batch
    {
        return new Batch(self::getCore(), self::getLogger());
    }

    /**
     * Creates default credentials object
     *
     * @return Credentials
     * @throws InvalidArgumentException|Exception
     */
    public static function getDefaultCredentials(): Credentials
    {
        switch ($type = config("bitrix.credentials.type")) {
            case "webhook":
                return Credentials::createFromWebhook(
                    new WebhookUrl(config("bitrix.credentials.url"))
                );
            default:
                throw new Exception("Undefined auth Bitrix24 auth type '$type'");
        }
    }

    /**
     * Sets credentials for fabric
     *
     * @param Credentials|null $credentials Credentials. null to set default
     * @throws InvalidArgumentException|Exception
     */
    public static function setCredentials(Credentials $credentials = null)
    {
        self::$credentials = $credentials ?? self::getDefaultCredentials();
    }
}
