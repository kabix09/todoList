<?php

use App\Service\Config\{Config, Constants};

?>
<!-- recaptcha -->
<script src="https://www.google.com/recaptcha/api.js?render=<?=Config::init()::module(Constants::RECAPTCHA)::get("publicKey")[0]?>"></script>
<script>
    grecaptcha.ready(function () {
        grecaptcha.execute('<?=Config::init()::module(Constants::RECAPTCHA)::get("publicKey")[0]?>', { action: 'submit' }).then(function (token) {
            var recaptchaResponse = document.getElementById('recaptchaResponse');
            recaptchaResponse.value = token;
        });
    });
</script>