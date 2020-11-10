<?php

use SmCrunch\Aggregator;
use SmCrunch\Client;
use SmCrunch\Env;
use SmCrunch\Proxy\Credentials;
use SmCrunch\Processors\AvgLengthPerMonth;
use SmCrunch\Processors\AvgMonthlyPostsPerUser;
use SmCrunch\Processors\MaxLengthPerMonth;
use SmCrunch\Processors\TotalByWeek;
use SmCrunch\Proxy\Proxy;
use SmCrunch\Proxy\Settings;

require_once 'vendor/autoload.php';

$env = new Env( __DIR__ . '/.env' );

$settings = new Settings();
$settings->caPath = $env->get( 'CA_PATH' );

$creds = new Credentials( $env->get( 'CLIENT_ID' ), $env->get( 'EMAIL' ), $env->get( 'NAME' ) );
$proxy = new Proxy( $env->get( 'ENDPOINT' ), $creds, $settings );
$client = new Client( $proxy );

$aggregator = new Aggregator();
$aggregator->addProcessor( 'avg-length', new AvgLengthPerMonth() );
$aggregator->addProcessor( 'longest-posts', new MaxLengthPerMonth() );
$aggregator->addProcessor( 'total-by-week', new TotalByWeek() );
$aggregator->addProcessor( 'avg-length-per-user', new AvgMonthlyPostsPerUser() );

try {
    $pageNo = 0;
    while ( ( $posts = $client->retrievePosts( ++$pageNo ) ) !== [] ) {
        $aggregator->aggregate( $posts );
    }
} catch ( \Exception $e ) {
    echo "Failed to collect post statistics.\n" . $e->getMessage() . "\n";
    exit();
}

echo json_encode( $aggregator->report() ) . PHP_EOL;
