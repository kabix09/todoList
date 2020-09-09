<?php
    include '../init.php';

    if(!isset($_SESSION['user']))
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
        <?php echo $_SESSION['user']->getStatus(); ?>
    </h2>
    <?php if($_SESSION['user']->getStatus() === 'inactive' ||
    (
        $_SESSION['user']->getStatus() === 'banned' &&
        (
                (new \DateTime($_SESSION['user']->getEndBan()))->format(User::DATE_FORMAT) <
                date(User::DATE_FORMAT)
        )
    )): ?>
    <?= (new \DateTime($_SESSION['user']->getEndBan()))->format(User::DATE_FORMAT); ?>
    <a href="../scripts/activateAccount.php">Active Account</a>
    <?php endif; ?>
</body>
</html>