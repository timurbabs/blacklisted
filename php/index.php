<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = new Slim\App();

function check_blacklist($con, $ip) {
    $query = "SELECT ip FROM blacklisted"; 

    $rs = pg_query($con, $query) or die("Cannot execute query: $query\n");

    while ($ro = pg_fetch_object($rs)) {
        if (trim($ro->ip) == trim($ip)) {
            return true;
        }
    }

    return false;
}

$app->GET('/blacklisted', function($request, $response, $args) {

    $db = getenv('DATABASE');

    $host = getenv('PRIMARY_HOST');
    $user = getenv('USER');
    $pass = getenv('PASS');

    $slave_host = getenv('SLAVE_HOST');

    $slave_con = pg_connect("host=$slave_host dbname=$db user=$user password=$pass")
        or die ("Could not connect to server\n"); 

    $ip = isset($_SERVER['REMOTE_ADDR']) ? trim($_SERVER['REMOTE_ADDR']) : '';
    $date = date('Y-m-d H:i:s');

    if (check_blacklist($slave_con, $ip)) {
        $response->write('You are forbidden');
        pg_close($slave_con); 

        return $response->withStatus(403);
    }

    pg_close($slave_con); 

    $con = pg_connect("host=$host dbname=$db user=$user password=$pass")
        or die ("Could not connect to server\n"); 

    $query = "INSERT INTO blacklisted VALUES('$ip', '$date')"; 
    pg_query($con, $query) or die("Cannot execute query: $query\n"); 
    pg_close($con); 

    return $response->withStatus(444);
});


$app->GET('/', function($request, $response, $args) {

    $db = getenv('DATABASE');

    $user = getenv('USER');
    $pass = getenv('PASS');

    $slave_host = getenv('SLAVE_HOST');

    $slave_con = pg_connect("host=$slave_host dbname=$db user=$user password=$pass")
        or die ("Could not connect to server\n"); 

    $ip = isset($_SERVER['REMOTE_ADDR']) ? trim($_SERVER['REMOTE_ADDR']) : '';

    if (check_blacklist($slave_con, $ip)) {
        $response->write('You are forbidden');
        return $response->withStatus(403);
    }

    pg_close($slave_con); 

    $queryParams = $request->getQueryParams();
    if(array_key_exists("n", $queryParams ))
    {
        $n = $queryParams['n'];
        $response->write($n * $n);
        return $response->withStatus(200);
    }            

    return $response->withStatus(200);
});

$app->run();
