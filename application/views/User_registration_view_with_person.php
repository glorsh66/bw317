


<div>
    <div>
        <div>
            <div>
                <h4>User Registration Form</h4>
            </div>
            <div class="panel-body">
                <?php  echo form_open('registration_with_person'); ?>
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

                <div>
                    <div>Супер секретные поля</div>
                <input name="reg_name" type="text" value="" />
                <input type="checkbox" name="reg_chbx_yes" value="yes" checked>
                <input type="checkbox" name="reg_chbx_no" value="no">
                </div>

               <div>
                <br>
                <br>
                <div>Формы персона</div>
<?php
//Выводим все поля для персоны
foreach ($person_forms as $k =>$pf)
{
    echo '<div>№: '.$k.' '.$pf->get_form_html().'</div>';
}
?>
                   <br>
                   <br>
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

                <div>
                    <button name="submit" type="submit" class="btn btn-default">Зарегестрироваться</button>
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


<script
        src="https://code.jquery.com/jquery-3.4.1.min.js"
        integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
        crossorigin="anonymous">
</script>


<script type="text/javascript">
    var global_regions_array = [];
    var global_cities_array = [];


    function fillSelections(firstform_this, secondform, geturl, global_array){
        glarray = global_array;

        if (!isNaN(firstform_this)) {
            var new_value = firstform_this;
            var region_url = geturl;
            var new_url = region_url + new_value;
            var current_idex = parseInt(firstform_this);

            function filltheselect() {
                var new_options = [];
                for (var i = 0; i < glarray[current_idex].length; ++i)
                {
                    new_options.push(new Option(glarray[current_idex][i][1],glarray[current_idex][i][0]));
                }

                $(secondform).append(new_options);
            }



            if(typeof glarray[current_idex] === 'undefined') {
                glarray[current_idex] = [];

                $.get(new_url, function (data) {
                    $(".result").html(data);
                    var splitted_array = data.split(";");
                    for (var i = 0; i < splitted_array.length; ++i) {
                        if (splitted_array[i].length > 0 && splitted_array[i].indexOf(',') > -1) {
                            var splitted_array_by_coma = splitted_array[i].split(",");
                            // console.log(splitted_array_by_coma);
                            glarray[current_idex].push(splitted_array_by_coma);

                        }
                    }
                    filltheselect();
                });
            }else
            {
                filltheselect();
            }

        }
    }

    //Для региона
    $('#p_person_country').on('change', function() {
        var thisvalue = this.value;
        $('#p_person_region').children('option:not(:first)').remove();
        $('#p_person_city').children('option:not(:first)').remove();
        fillSelections(thisvalue, "#p_person_region", "RegionParametrs/getRegion/", global_regions_array);
    });

    //Для города
    $('#p_person_region').on('change', function() {
        var thisvalue = this.value;
        $('#p_person_city').children('option:not(:first)').remove();
        fillSelections(thisvalue, "#p_person_city", "RegionParametrs/getCity/", global_cities_array);
    });

</script>