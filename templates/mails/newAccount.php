<?php
require_once $_SERVER['DOCUMENT_ROOT'] . './init.php';

define("LINK", '%s://%s:%s/public/scripts/activateAccount.php?email=%s&nick=%s&key=%s');
define("HREF", '<a href="%s">%s</a>');

$session = new \App\Session\Session();
$href = sprintf(LINK,
            $_SERVER['REQUEST_SCHEME'],
            $_SERVER['SERVER_NAME'],
            $_SERVER['SERVER_PORT'],
            urlencode($user->getEmail()),
            urlencode($user->getNick()),
            urlencode($user->getKey())
        );
$link = sprintf(HREF,
            $href,
            "click here"
        );
return
    "Thanks for using our service :)" . "\r\n" .
    "To activate account please {$link}";

