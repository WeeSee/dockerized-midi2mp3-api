<?php 

// Define APP_ROOT and autoloader
define('APP_ROOT',dirname(__DIR__));
require_once APP_ROOT . '/vendor/autoload.php';
require_once APP_ROOT . '/lib/midi2mp3.php';

// Includes env defined constant (generated through docker image)
//@require_once APP_ROOT . '/lib/const.php';

// Creates app
$app = new \Slim\App();


/*
 * Endpoind /info
 */
 $app->get('/info', function ($request, $response, $args) {
    $midi2mp3 = new Midi2Mp3();
    $result = $midi2mp3->info();
    return $response->withJson($result,200);
});


/*
 * Endpoind /convert
 * Test with curl inside container:
 * $ curl -X POST -H "Content-Type: application/json" --data "{\"midiData\":\"hello world\"}" localhost/convert
 */
$app->post('/convert', function ($request, $response, $args) {
    $midiData = $request->getParsedBody()['midiData'];
    $midi2mp3 = new Midi2Mp3();
    $result = $midi2mp3->convert($midiData);
	return $response->withJson($result,200);

});

/*
 * SLIM framework application start
 */
$app->run();
