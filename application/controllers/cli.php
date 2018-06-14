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


             //Сравниваем введенную и сохраненную букву




             echo 'Строка: ' . $cur_str.'<br>';
             echo 'Символ на позиции: '. ($rand_chart_at+1). '  ввведите ' . $nums_names[$rand_chart_at] .' букву '.   '<br>';
             echo 'Символ: '. $char . '<br>';



            $this->benchmark->mark('stop');
            echo "Elapsed time: " . $this->benchmark->elapsed_time('start','stop');

        }
}
?>
