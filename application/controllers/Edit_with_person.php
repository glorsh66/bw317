<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class edit_with_person extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model("PMmodel");
        $this->load->model('Usermodel');
        $this->load->model('Personmodel'); //Грузим для того что бы можно было еще данные из person загрузить.
        $this->load->library('simple_auth_lib');
        $this->load->helper('url');
        $this->config->set_item('language', 'russian');
        $this->load->library('form_validation');
        $this->load->helper('form');

    }

    public function index()
    {
        //Если пользователь залогинен, то ему регистрироваться не нужно, и редиректим на страницу с логином.
        if ($this->simple_auth_lib->check_if_user_is_loggined() !== TRUE) {
            redirect('/login/', 'refresh');
        }

        $data['user'] = $this->simple_auth_lib->user_data;

        $this->Personmodel->initialize(Personmodel::edit);
        $data['person_forms'] = $this->Personmodel->editableFields;
        $data['person_data_db'] = $this->Personmodel->getDataPerson($this->simple_auth_lib->user_data['id']);

        $data['all_forms'] = $this->Personmodel->allFields;

        $id = $this->simple_auth_lib->user_data['id'];
        $ar = $this->Personmodel->editableFields;
        $this->Personmodel->person_data_table = $data['person_data_db'];


        $this->Personmodel->makeValidationRules();


        if ($this->form_validation->run() )
        { //Если проверка на  шаблоны прошла успешно то

            //Берем данны только с тех полей который которые можно в принципе редактировать
            /**
            * @var form_filed $el
            */
            foreach ($this->Personmodel->editableFields as $el)
            {
                if ($this->Personmodel->person_data_table[$el->getMysqlName()]!=$this->input->post($el->get_name())) // Что бы не перезаписывать лишнюю фигню
                $person_changed_data[$el->getMysqlName()] =$this->input->post($el->get_name());
            }

            if (!empty($person_changed_data)) {
                $this->Personmodel->updatePersonData($id, $person_changed_data);
                echo "Данные обновлены.";
            }
            else
            {
               echo "Вы ничего не поменяли";
            }



        }
        else //Сюда заходим только если форма не отправлена, либо не прошла валидацию.
        {

            $this->load->view('Edit_person',$data);
        }
        //Проводим проверку полей




    }




}
?>
