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
                <h4>User Registration Form</h4>
            </div>
            <div class="panel-body">
                <?php $attributes = array("name" => "registrationform");
                echo form_open(base_url().'user/registration',$attributes);?>
                <div class="form-group">
                    <label for="reg_username">User Name</label>
                    <input class="form-control" name="reg_username" placeholder="Your username" type="text" value="<?php echo set_value('reg_username'); ?>" />
                    <span class="text-danger"><?php echo form_error('reg_username'); ?></span>
                </div>


                <div class="form-group">
                    <label for="reg_email">Email ID</label>
                    <input class="form-control" name="reg_email" placeholder="YOUR@E-MAIL.COM" type="text" value="<?php echo set_value('reg_email'); ?>" />
                    <span class="text-danger"><?php echo form_error('reg_email'); ?></span>
                </div>

                <div class="form-group">
                    <label for="reg_password">Password</label>
                    <input class="form-control" name="reg_password" placeholder="Password" type="reg_password" value="<?php echo set_value('reg_password'); ?>" />
                    <span class="text-danger"><?php echo form_error('reg_password'); ?></span>
                </div>

                <div class="form-group">
                    <label for="reg_cpassword">Confirm Password</label>
                    <input class="form-control" name="reg_cpassword" placeholder="Confirm Password" type="reg_cpassword" value="<?php echo set_value('reg_cpassword'); ?>" />
                    <span class="text-danger"><?php echo form_error('reg_cpassword'); ?></span>
                </div>

                <div class="form-group">
                    <label for="captcha_tf">Confirm Password</label>
                    <input class="form-control" name="captcha_tf" placeholder="Enter number on the captcha" type="number" value="<?php echo set_value('captcha_tf'); ?>" />
                    <img src="<?php echo (base_url());?>Dogecaptcha/user_registration_captcha/"  id="captcha_img"/>
					<img src="<?php echo (base_url());?>images/flat_restart_icon50.png" id="captcha_restart_img">
					<span class="text-danger"><?php echo form_error('captcha_tf'); ?></span>
                </div>
                <div class="form-group">
                    <button name="submit" type="submit" class="btn btn-default">Signup</button>
                </div>
                
                <?php echo form_close(); ?>
                <?php echo $this->session->flashdata('msg'); ?>
            </div>
        </div>
    </div>
</div>
</div>
<script type="text/javascript" src="<?php echo(base_url());?>js/jquery-3.2.1.min.js"></script>
<script type="text/javascript" src="<?php echo (base_url());?>js/user_registration_view.js"></script>
</body>
</html>
