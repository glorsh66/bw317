<div>
    <div>
        <div>
            <div>
                <h4>User person edit mode</h4>
            </div>

            <div>Id текущего пользователя: <b><?php echo $user['id'] ?></b></div>
            <div>Текущий пользователь: <b><?php echo $user['user_name'] ?></b></div>
            <div>Почта пользователя: <b><?php echo $user['user_email'] ?></b></div>
            <div>Id текущей персоны: <b><?php echo  $person_data_db['id'] ?></b></div>
            <div>Выыодим текущие данные для персоны:
            <br>
<?php
//Выводим все поля для персоны
/** @var form_filed $el  */
foreach ( $all_forms as $k =>$el)
{



    echo $el->get_name(). " " .  $el->param->selectable . " ". "Данные: " . $person_data_db[$el->getMysqlName()] . "<br>";

//
//    if ($el->param->selectable)
//        echo $el->get_name() . " " . "Значение из базы цифрой: " . $person_data_db[$el->getMysqlName()] . " Название значения: ".
//            $el->get_text_representation($person_data_db[$el->getMysqlName()])."<br>";
//    else echo $el->get_name() . " " . "Значение из базы цифрой: " . $person_data_db[$el->getMysqlName()] . " Название значения: " ."<br>";

}
?>

<?php  echo form_open('edit_with_person'); ?>
                <div>Формы персона</div>
<?php
//Выводим все поля для персоны
foreach ($person_forms as $k =>$pf)
{
    echo '<div>№: '.$k.' '.$pf->get_form_html().'</div>';
}
?>

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
