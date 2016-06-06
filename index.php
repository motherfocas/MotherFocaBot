<?php

//--------------------------------------------------------------------------
//	Core
//--------------------------------------------------------------------------

include '../core/core.php';

include $core->getLibrary('AltoRouter');
include $core->getLibrary('vendor/autoload');

use Telegram\Bot\Api;

$router = new AltoRouter();

$router->addRoutes(array(
    array( 'GET', '/telegram/'.$core->getTelegram('webhook', 'telegram').'/', 'webhook'),
    array( 'POST', '/telegram/'.$core->getTelegram('webhook', 'telegram').'/', 'telegram'),
    array( 'POST', '/telegram/'.$core->getTelegram('webhook', 'sendMessage').'/', 'sendMessage'),
    array( 'POST', '/telegram/'.$core->getTelegram('webhook', 'github').'/', 'github'),
));

$match = $router->match();

$output = array();

if( $match ) {

    // Create the bot

    $telegram = new Telegram\Bot\Api($core->getTelegram('token'));

    if (file_exists('controllers/'.$match['target'].'.php')) {
        include 'controllers/'.$match['target'].'.php';
    }
    else {
        $output['status'] = 0;
        $output['code'] = 'no-controller';
        $output['return'] = 'Internal error, unable to find the controller';
    }
} else {
    $output['status'] = 0;
    $output['code'] = 'no-found';
    $output['return'] = 'This option cant be found';
}

if ($output) echo json_encode($output);