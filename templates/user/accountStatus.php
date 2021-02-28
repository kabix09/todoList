<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . './vendor/autoload.php';
    use App\Session\Session;
    define("DB_CONFIG", __DIR__ . '/../config/db.config.php');
    $session = new Session();

    if(!isset($session['user']))
    {
        header("Location: ../index.php");
        exit();
    }
    use App\Entity\User;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Account Status</title>
</head>
<body>
    <h2>Your account is
        <?php echo $session['user']->getStatus(); ?>
    </h2>
    <?php if($session['user']->getStatus() === 'inactive' ||
    (
        $session['user']->getStatus() === 'banned' &&
        (
                (new \DateTime($session['user']->getEndBan()))->format(User::DATE_FORMAT) <
                date(User::DATE_FORMAT)
        )
    )): ?>
    <?= (new \DateTime($session['user']->getEndBan()))->format(User::DATE_FORMAT); ?>
    <a href="<?=$_SERVER['REQUEST_SCHEME'] . "://" .$_SERVER['HTTP_HOST']?>/public/scripts/activateAccount.php">Active Account</a>
    <?php endif; ?>
</body>
</html>