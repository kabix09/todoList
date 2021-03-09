<!DOCTYPE html>
<html lang="en">
<head>
    <title>Send email fail</title>
    <meta name="author" content="kabix09"/>
    <meta name="description" content="Remote task list! With our service you gain access to the list of tasks where and when you want."/>
    <meta http-equiv = "Content-Type" content = "text/html; charset = UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href=<?=$_SERVER['REQUEST_SCHEME'] . "://" .$_SERVER['HTTP_HOST']?>/public/style/main.css>

</head>
<body>
<span>Sending the email failed!!!</span><br>
<span>Your account was created successfully but we couldn't sent verification email to you</span><br>
<span>Occurred unexpected error, please contact with administrator</span><br>
<span>Or</span><br>
<a href="#TODO-sendEmail">Send email again</a><small>Yet don't work ;)</small>
</body>
</html>