<div>
    <div>
        <div>
            <div>
                <h4>User Registration Form</h4>
            </div>
            <div class="panel-body">
                <?php  echo form_open('registration'); ?>
                <!-- User name -->
                <div>
                    <label for="reg_username">User Name</label>
                    <input name="reg_username" placeholder="Your username" type="text" value="<?php echo set_value('reg_username'); ?>" />
                    <span><?php echo form_error('reg_username'); ?></span>
                </div>

                <!-- User email -->
                <div>
                    <label for="reg_email">Email ID</label>
                    <input name="reg_email" placeholder="YOUR@E-MAIL.COM" type="text" value="<?php echo set_value('reg_email'); ?>" />
                    <span><?php echo form_error('reg_email'); ?></span>
                </div>

                <!-- User password -->
                <div>
                    <label for="reg_password">Password</label>
                    <input name="reg_password" placeholder="Password" type="password" value="<?php echo set_value('reg_password'); ?>" />
                    <span><?php echo form_error('reg_password'); ?></span>
                </div>

                <!-- User password verify -->
                <div>
                    <label for="reg_cpassword">Confirm Password</label>
                    <input name="reg_cpassword" placeholder="Confirm Password" type="password" value="<?php echo set_value('reg_cpassword'); ?>" />
                    <span ><?php echo form_error('reg_cpassword'); ?></span>
                </div>

                <!-- Username -->
                <input name="reg_name" type="text" value="" />
                <input type="checkbox" name="reg_chbx_yes" value="yes" checked>
                <input type="checkbox" name="reg_chbx_no" value="no">
                <div>
                    <button name="submit" type="submit" class="btn btn-default">Зарегестрироваться</button>
                </div>
                <div>
                    Докажи что ты не робот!
                    <div>
                    <label for="reg_captcha1">
                        <?php
                       echo 'В слове <b>' . $captcha1['word'] . '</b> Определи и напииши в поле снизу <b>' . $captcha1['pos_char_word']
                       . '</b> по счету букву';
                        ?></label>
                    <input name="reg_captcha1" type="text" placeholder="captcha" value="" />
                        <span><?php echo form_error('reg_captcha1'); ?></span>
                    </div>
                </div>
                   <div>
                    <label for="reg_captcha2">
                        <?php
                       echo 'Ответь правильно на вопрос: '. $captcha2;
                        ?>
                    </label>
                    <input name="reg_captcha2" type="text" placeholder="Ответ на вопрос" value="" />
                        <span><?php echo form_error('reg_captcha2'); ?></span>
                    </div>
                </div>



                <?php echo form_close(); ?>
                <div style="color: red">
                    <?php echo validation_errors();?>
                    <? $registration_error ?>

                </div>
            </div>
        </div>
    </div>
</div>
