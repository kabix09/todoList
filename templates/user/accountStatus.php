<?php
    require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'init.php';
    use App\Service\Session\Session;

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
    <a href="<?=$_SERVER['REQUEST_SCHEME'] . "://" .$_SERVER['HTTP_HOST']?>/scripts/user/activateAccount.php">Active Account</a>
    <?php endif; ?>
</body>
</html>