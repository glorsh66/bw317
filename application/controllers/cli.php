<?php
class cli extends CI_Controller {

        public function index()
        {
             $this->benchmark->mark('start');


             $arr[]='Земля';
             $arr[]='Луна';
             $arr[]='Громада';
             $arr[]='Солнце';
             $arr[]='Звезда';

             $nums_names[]='первую';
             $nums_names[]='вторую';
             $nums_names[]='третью';
             $nums_names[]='четвертую';
             $nums_names[]='пятую';
             $nums_names[]='шестую';
             $nums_names[]='седьмую';
             $nums_names[]='восьмую';
             $nums_names[]='девятую';
             $nums_names[]='десятую';

             //Определяем длинну массива в котором перечисленны проверяемые слова
             $arr_len = count($arr);

             //Определяем случайное слолово из массива
             $rand_element_of_array = rand(0,$arr_len-1);
             $cur_str = $arr[$rand_element_of_array];

             //Определеям длинну выбранного случайного слова
             mb_internal_encoding("UTF-8");
             $cur_str_len = mb_strlen($cur_str);

             //Выбираем позицию случайного
             $rand_chart_at = rand(0,$cur_str_len-1);
             $char = mb_substr($cur_str,$rand_chart_at,1);


             echo 'Строка: ' . $cur_str.'<br>';
             echo 'Символ на позиции: '. ($rand_chart_at+1). '  ввведите ' . $nums_names[$rand_chart_at] .' букву '.   '<br>';
             echo 'Символ: '. $char . '<br>';



            $this->benchmark->mark('stop');
            echo "Elapsed time: " . $this->benchmark->elapsed_time('start','stop');

            echo '<hr>';
            echo '<br> Вторая версия капчи в которой нужно вписать правильный ответ на вопрос';
            $a2[] =  new cap2('Как ты думаешь?','Это сойдет?');

            echo $a2[0]->q;
            echo '<br>';
            echo $a2[0]->a;


            $a3[] = ['q'=>'Привет как дела','a'=>'Отлично'];
            echo '<br>';
            echo $a3[0]['q'];
            echo '<br>';
            echo $a3[0]['a'];

            echo '<hr>';
            echo '<br> Третья версия капчи в которой нужно выбрать правильный ответ из нескольких';
        }


}

class cap2
{
public $q='';
public $a='';
    public function __construct($q,$a)
    {
         $this->q = $q;
         $this->a = $a;
    }
}
?>
