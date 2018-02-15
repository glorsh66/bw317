<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CodeIgniter User Registration Form Demo</title>
    <link href="<?php echo base_url("/css/bootstrap.css"); ?>" rel="stylesheet" type="text/css" />
</head>
<body>
	<div class="container">

<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <?php echo $this->session->flashdata('verify_msg'); ?>
    </div>
</div>
<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4>User authentication Form</h4>
            </div>
            <div class="panel-body">
                <?php $attributes = array("name" => "userauthform");
                echo form_open(base_url().'user/auth',$attributes);?>
                <div class="form-group">
                    <label for="auth_username">User Name or Email</label>
                    <input class="form-control" name="auth_username" placeholder="Your username or Email" type="text" value="<?php echo set_value('auth_username'); ?>" />
                    <span class="text-danger"><?php echo form_error('auth_username'); ?></span>

                    <?php if ($wrong_name_or_email): ?>
                    <span class="text-danger">You have entered wrong name or e-mail. Check it twice, or you will be blocked for some time</span>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="auth_password">Password</label>
                    <input class="form-control" name="auth_password" placeholder="Password" type="auth_password" value="<?php echo set_value('auth_password'); ?>" />
                    <span class="text-danger"><?php echo form_error('auth_password'); ?></span>
                    <?php if ($wrong_password): ?>
                    <span class="text-danger">You have entered wrong password. Check it twice, or you will be blocked for some time</span>
                    <?php endif; ?>
                </div>


                <?php if ($show_captcha): ?>
                  <div class="form-group">
                  <input class="form-control" name="captcha_tf" placeholder="Enter number on the captcha" type="number" value="<?php echo set_value('captcha_tf'); ?>" />
                  <img src="<?php echo (base_url());?>Dogecaptcha/user_auth_captcha/"  id="captcha_img"/>
                  <img src="<?php echo (base_url());?>images/flat_restart_icon50.png" id="captcha_restart_img">
                  <span class="text-danger"><?php echo form_error('captcha_tf'); ?></span>
                  </div>
                <?php endif; ?>


                <div class="form-group">
                    <button name="submit" type="submit" class="btn btn-default">Signup</button>
                </div>
                <?php echo form_close(); ?>
                <?php echo $this->session->flashdata('msg'); ?>

                <?php
                echo "Test_field";
                if (isset ($user_exist)) echo $user_exist;
                if (isset ($pawrod_is_not_correct)) echo $pawrod_is_not_correct;
                ?>



            </div>
        </div>
    </div>
</div>
</div>
<script type="text/javascript" src="<?php echo(base_url());?>js/jquery-3.2.1.min.js"></script>
<script type="text/javascript" src="<?php echo (base_url());?>js/user_registration_view.js"></script>
</body>
</html>
