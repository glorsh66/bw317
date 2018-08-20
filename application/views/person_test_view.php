<?php  echo form_open('peson_test');
foreach ($person_forms as $k =>$pf)
{
    echo '<div>№: '.$k.' '.$pf->get_form_html().'</div>';
}
?>
   <div>
       <button name="submit" type="submit" class="btn btn-default">Зарегестрироваться</button>
   </div>
<?php echo form_close(); ?>
