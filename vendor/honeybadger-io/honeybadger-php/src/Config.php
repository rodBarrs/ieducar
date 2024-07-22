<?php

namespace Honeybadger;

use Honeybadger\Exceptions\ServiceException;
use Honeybadger\Support\Repository;

class Config extends Repository
{
    /**
     * @param  array  $config
     */
    public function __construct($config = [])
    {
        $this->items = $this->mergeConfig($config);
        $checkinsRaw = $this->get('checkins') ?? [];
        $checkins = array_map(function ($checkin) {
            return new CheckIn($checkin);
        }, $checkinsRaw);
        $this->set('checkins', $checkins);
    }

    /**
     * @param array $config
     *
     * @return array
     */
    private function mergeConfig($config = []): array
    {
        $result = array_merge([
            'api_key' => null,
            'personal_auth_token' => null,
            'endpoint' => Honeybadger::API_URL,
            'notifier' => [
                'name' => 'honeybadger-php',
                'url' => 'https://github.com/honeybadger-io/honeybadger-php',
                'version' => Honeybadger::VERSION,
            ],
            'environment_name' => 'production',
            'report_data' => true,
            'service_exception_handler' => function (ServiceException $e) {
                throw $e;
            },
            'environment' => [
                'filter' => [],
                'include' => [],
            ],
            'request' => [
                'filter' => [],
            ],
            'version' => '',
            'hostname' => gethostname(),
            'project_root' => '',
            'handlers' => [
                'exception' => true,
                'error' => true,
                'shutdown' => true,
            ],
            'client' => [
                'timeout' => 15,
                'proxy' => [],
                'verify' => true,
            ],
            'excluded_exceptions' => [],
            'capture_deprecations' => false,
            'vendor_paths' => [
                'vendor\/.*',
            ],
            'breadcrumbs' => [
                'enabled' => true,
            ],
            'checkins' => [],
            'events' => [
                'enabled' => false,
                'bulk_threshold' => BulkEventDispatcher::BULK_THRESHOLD,
                'dispatch_interval_seconds' => BulkEventDispatcher::DISPATCH_INTERVAL_SECONDS
            ],
        ], $config);

        if (!isset($result['handlers']['shutdown'])) {
            $result['handlers']['shutdown'] = false;
        }

        return $result;
    }
}
