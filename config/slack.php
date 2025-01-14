<?php

return [
    'driver' => 'slack',
    'endpoint' => env('SLACK_HOST'),
    'channels' => [
        'failed_jobs' => 'failed-jobs',
        'db_issues' => 'database-issues',
        'service_issues' => 'service-issues',
        'redis_issues' => 'redis-issues',
    ],
    'icon' => ':crossed_swords:',
    'username' => 'Slack Messenger',
];
