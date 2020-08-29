<?php
define('DB_CONFIG_FILE', __DIR__ . "/config/db.config.php");

require_once __DIR__ . "/vendor/autoload.php";

use App\Connection\Connection;

use App\Repository\UserRepository;
use App\Entity\ {User, Base};

$repo = new UserRepository(new Connection(include DB_CONFIG_FILE));

/*      first example - find with criteria       */
$date = [
    "where" => NULL,
    "IN" => ["status", ["'inactive'"]]
];

$date2 = [
    "where" => NULL,
    "like" => ["status", "'%in%'"],
    "AND" => "nick = 'kabix09'"
];

echo '<pre>';
foreach ($repo->find(array(), $date) as $item){
    var_dump($item);
}

foreach ($repo->find(array(), $date2) as $item){
    var_dump($item);
}
echo '</pre>';

/*      second example - insert       */
$user = new User();
$user->setNick("bogWojny");
$user->setEmail("bogWojny@gmail.com");
$user->setPassword("qwerty");
$user->setLastLoginDate("2020-08-29 23:35:05");
$user->setCreateAccountDate("2020-08-29 23:35:05");
$user->setStatus("inactive");

$repo->insert($user);

/*      third example - update       */
$user->setStatus("active");
$date3 = [
    "where" => ["nick", "= '{$user->getNick()}'"]
];

$repo->update($user, $date3);

/*      fourth example - remove few records       */
$date4 = [
    "where" => ["status", "= 'active'"]
];
$repo->remove($date4);





