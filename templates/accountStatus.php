<?php
    require_once '../vendor/autoload.php';
    use App\Session\Session;

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
    <a href="../scripts/activateAccount.php">Active Account</a>
    <?php endif; ?>
</body>
</html>