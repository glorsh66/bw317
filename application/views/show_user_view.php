Вы зашли как пользователь с именем <?= htmlentities($user_name)?> и почтой <?= htmlentities($user_email)?>
</br>
Вы можете выйти из данной учетной записи, нажав на ссылку ниже
<br>
<a href="<?=site_url('login/logout')?>" class="button">Выйти из учетной записи</a>

<style>
    a.button {
        -webkit-appearance: button;
        -moz-appearance: button;
        appearance: button;

        text-decoration: none;
        color: initial;
    }
</style>
