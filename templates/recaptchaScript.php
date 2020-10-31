<!-- recaptcha -->
<script src="https://www.google.com/recaptcha/api.js?render=<?=(include(RECAPTCHA))["publicKey"]?>"></script>
<script>
    grecaptcha.ready(function () {
        grecaptcha.execute('<?=(include(RECAPTCHA))["publicKey"]?>', { action: 'submit' }).then(function (token) {
            var recaptchaResponse = document.getElementById('recaptchaResponse');
            recaptchaResponse.value = token;
        });
    });
</script>