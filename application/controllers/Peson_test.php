<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class peson_test extends CI_Controller {

    function __construct()
    {
        parent::__construct();

        $this->load->model('Usermodel');

        $this->load->library('simple_auth_lib');

    }


public function index()
{

    $this->load->library('form_validation');
    $this->load->helper('form');



$this->benchmark->mark('start');
$this->load->model('Personmodel');
$this->Personmodel->initialize(Personmodel::new);

$this->benchmark->mark('stop');


    //Устанавливаем правила FORM VALIDATION


    //Массив данных для передачи в VIEW
    $data['person_forms'] = $this->Personmodel->allFields;
    $ar = $this->Personmodel->allFields;






    if ($this->form_validation->run() == FALSE) //Если не прошли форма валидацию
    {

       $this->load->view('person_test_view',$data);
    }
    else // Успешно проши валидацию
    {
        //Выводим все значения. из массива
        foreach ($ar as $el)
        {
         $name = $el->get_name();
         $val = $this->input->post($name);

        if (is_array($val))
        {
            if (empty($val))
            {
                $st = "Данные для поля массив: " . $el->get_name() . 'пустой <br>';
            }
            else
            {
                $st = "Данные для поля массив: " . $el->get_name() . ' размер массива' . count ($val) .' ';

                foreach ($val as $item)
                {
                    $st.= 'значение: ' . 'Цифрой ' . $item .
                        ' Буквой: ' . $el->get_text_representation($item);
                }
            }
            echo $st;

        }
        else
        {
         if (is_numeric($val)) $val = (int)$val;
         else $val = 0;
            $st = "Данные для поля: " . $el->get_name() . 'значение: ' . 'Цифрой ' . $val = $this->input->post($name) .
                    ' Буквой: ' . $el->get_text_representation($val) . '<br>';
            echo $st;

        }
        }

    }
echo $this->benchmark->elapsed_time('start', 'stop');
}
}
?>