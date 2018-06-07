Добро пожаловать на наш сайт. <br>
Введите Ваш логин (или адрес электронной почты) и пароль <br>
<div id="login_form_div">
<?= form_open('login');?>

<!-- User name or email -->
<div class="form-group">
    <label for="auth_username_or_email">User Name or Email</label><br>
    <input class="form-control" name="auth_username_or_email" placeholder="Your username or Email" type="text" value="<?php echo set_value('auth_username_or_email'); ?>" />
    <span class="text-danger"><?php echo form_error('auth_username_or_email'); ?></span>
</div>

<!-- password -->
<div class="form-group">
    <label for="auth_password">Password</label><br>
    <input class="form-control" name="auth_password" placeholder="Password" type="password" value="" />
    <span class="text-danger"><?php echo form_error('auth_password'); ?></span>
</div>

<!-- check box -->
<div class="form-group">
    <label for="auth_remember_me">Запомнить меня?</label>
    <input type="checkbox" name="auth_remember_me" value="1" checked/>
</div>

<!-- errors -->
<div class="form-group" id="errors" style="color: red">
        <?= $auth_errors;?>
</div>


<!-- button -->
<div class="form-group">
    <input type="submit" name="formSubmit" value="Submit" />
</div>
<?=form_close();?>
</div>

Прошло времени <?= $elapsed_time?>