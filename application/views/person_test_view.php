<?php  echo form_open('peson_test'); ?>
<?php echo $f1->get_form();?>
<br>
<?php echo $f2->get_form();?>
<br>
<?php echo $f3->get_form();?>
   <div>
       <button name="submit" type="submit" class="btn btn-default">Зарегестрироваться</button>
   </div>
<?php echo form_close(); ?>
