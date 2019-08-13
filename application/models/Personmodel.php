<?php
class Personmodel extends CI_Model
{

    /**
     * @var string название текущей таблицы. По дефолту должно быть, person.     *
     */
    private $t = 'person'; //название текущей таблицы, что бы менять в одном месте
    
    // Публичные переменные
    /** @var array массив объектов form_filed */
    public $allFields;
    /** @var array массив объектов form_filed */
    public $ar;
    /**@var array массив объектов form_filed */
    public $searchebleFields;
    /**@var array массив объектов form_filed */
    public $editableFields;

    /**
     * @var array масив вернувшихся значений из базы данных
     */
    public $person_data_table=FALSE;

    //Поля (устанавливаются в завимисомти от того в каком режиме работает наш PersonModel).
    //Может быть: New Person, Editable, Search
    /**
     * @var form_params объект форм params - для radio button. Он по умолчанию Required;
     */
    private $fp_radio_req;
    /**
     * @var form_params объект форм params - для select. Который Required;
     */
    private $fp_sel_req;
    /**
     * @var form_params объект форм params - для обычного select. Который не Required;
     */
    private $fp_sel_not_req;

    /** @var form_params  */
    private $fp_text_not_req;
    /** @var form_params  */
    private $fp_text_req;
    /** @var form_params  */
    private $fp_edit_text_not_req;
    /** @var form_params  */
    private $fp_edit_text_req;
    /** @var form_params  */
    private $fp_sel_country;
    /** @var form_params  */
    private $fp_sel_region;
    /** @var form_params  */
    private $fp_sel_city;





    /**
     * @var form_params объект форм params - для multi-select. На текущий момент не используется.;
     */
    private $fp_sel_multi;



    //Переменные типов работы Person Model. На основании этого будет решено, что будет использовано.
    public const new=1;
    public const edit=2;
    public const search=3;

    //Текущее состояние. С каким PersonModel был инициализирован.
    public $currentType=1;

    
    //Поля (доступные формы)
    function __construct()
    {
        parent::__construct();
//Грузим нужные либы
$this->load->library('form_validation');
$this->load->model('Regionmodel');
$this->load->helper('form');
    }



function initialize(int $type)
{
    $this->currentType=$type;

    if ($type===Personmodel::new) { //Формы для создания нового пользователя
        //Определяем наборы параметров для форм (типа RADIO BUTTON, SELECT BOX и т.д.)
        $this->fp_radio_req = new form_params(form_params::radio, 'Не указано', array('required', 'numeric'), 'radio_required', 'error_line', 'p_', TRUE, "Это поле обязательно нужно выбрать.", ['numeric' => 'Значение должно быть числом.', 'required' => 'Это поле обязательно нужно выбрать.']);
        $this->fp_sel_req = new form_params(form_params::select, 'Не указано', array('required', 'numeric'), 'select_not_required', 'error_line', 'p_', TRUE, "Вы выбрали неправильный ответ. Попробуйте снова.", ['numeric' => 'Значение должно быть числом.']);
        $this->fp_sel_not_req = new form_params(form_params::select, 'Не указано', array('numeric'), 'select_not_required', 'error_line', 'p_', TRUE, "Вы выбрали неправильный ответ. Попробуйте снова.", ['numeric' => 'Значение должно быть числом.']);
        $this->fp_sel_multi = new form_params(form_params::select_multiple, 'Не указано', array('numeric'), 'select_not_required', 'error_line', 'p_', TRUE, "Вы выбрали неправильный ответ. Попробуйте снова.", ['numeric' => 'Значение должно быть числом.']);
        $this->fp_edit_text_not_req = new form_params(form_params::text, 'Введите сюда текст', array('min_length[10]','max_length[21000]'), 'text_not_required', 'error_line', 'p_', TRUE, "ошибка поля.", ['numeric' => 'Значение должно быть числом.','min_length' => 'Это поле не может быть меньше 10 символов']);
        $this->fp_edit_text_req =new form_params(form_params::text, 'Введите сюда текст', array('required','min_length[10]','max_length[21000]'), 'text_not_required', 'error_line', 'p_', TRUE, "ошибка поля.", ['numeric' => 'Значение должно быть числом.']);
        $this->fp_sel_country =new form_params(form_params::country, 'Введите сюда текст', array('required','min_length[10]','max_length[21000]'), 'text_not_required', 'error_line', 'p_', TRUE, "ошибка поля.", ['numeric' => 'Значение должно быть числом.']);
        $this->fp_sel_region =new form_params(form_params::region, 'Введите сюда текст', array('required','min_length[10]','max_length[21000]'), 'text_not_required', 'error_line', 'p_', TRUE, "ошибка поля.", ['numeric' => 'Значение должно быть числом.']);
        $this->fp_sel_city =new form_params(form_params::city, 'Введите сюда текст', array('required','min_length[10]','max_length[21000]'), 'text_not_required', 'error_line', 'p_', TRUE, "ошибка поля.", ['numeric' => 'Значение должно быть числом.']);

    }
    elseif ($type===Personmodel::edit) //Формы для редактирования
    {
        //Определяем наборы параметров для форм (типа RADIO BUTTON, SELECT BOX и т.д.)
        $this->fp_sel_req = new form_params(form_params::edit_select, 'Не указано', array('required', 'numeric'), 'select_not_required', 'error_line', 'p_', TRUE, "Вы выбрали неправильный ответ. Попробуйте снова.", ['numeric' => 'Значение должно быть числом.']);
        $this->fp_sel_not_req = new form_params(form_params::edit_select, 'Не указано', array('numeric'), 'select_not_required', 'error_line', 'p_', TRUE, "Вы выбрали неправильный ответ. Попробуйте снова.", ['numeric' => 'Значение должно быть числом.']);
        //TODO: Не реализованны еще
        $this->fp_edit_text_not_req = new form_params(form_params::edit_text, 'Введите сюда текст', array('min_length[10]','max_length[25000]'), 'text_not_required', 'error_line', 'p_', TRUE, "ошибка поля.", ['numeric' => 'Значение должно быть числом.']);
        $this->fp_edit_text_req =new form_params(form_params::edit_text, 'Введите сюда текст', array('required','min_length[10]','max_length[25000]'), 'text_not_required', 'error_line', 'p_', TRUE, "ошибка поля.", ['numeric' => 'Значение должно быть числом.']);
    }
    elseif ($type===Personmodel::search) //Для поиска
    {
        //Определяем наборы параметров для форм (типа RADIO BUTTON, SELECT BOX и т.д.)
        $this->fp_radio_req = new form_params(form_params::radio, 'Не указано', array('required', 'numeric'), 'radio_required', 'error_line', 'p_', TRUE, "Это поле обязательно нужно выбрать.", ['numeric' => 'Значение должно быть числом.', 'required' => 'Это поле обязательно нужно выбрать.']);
        $this->fp_sel_req = new form_params(form_params::select, 'Не указано', array('required', 'numeric'), 'select_not_required', 'error_line', 'p_', TRUE, "Вы выбрали неправильный ответ. Попробуйте снова.", ['numeric' => 'Значение должно быть числом.']);
        $this->fp_sel_not_req = new form_params(form_params::select, 'Не указано', array('numeric'), 'select_not_required', 'error_line', 'p_', TRUE, "Вы выбрали неправильный ответ. Попробуйте снова.", ['numeric' => 'Значение должно быть числом.']);
        $this->fp_sel_multi = new form_params(form_params::select_multiple, 'Не указано', array('numeric'), 'select_not_required', 'error_line', 'p_', TRUE, "Вы выбрали неправильный ответ. Попробуйте снова.", ['numeric' => 'Значение должно быть числом.']);
    }
    else die('Выбран неправильный тип инициплизации класса PersonModel'); // В случае если не выбран никакой типа поля.

    //Создаем формы (в зависимости от разного типа переменных используются разные объекты - form_params)
    $this->ar=$this->makeFormsFromJSON(); //Делаем формочки при помощи файла JSON
}

    public function getDataPerson(int $id)
    {
            $this->db->where('id',$id);
			$query = $this->db->get($this->t);
			if ($query->num_rows() > 0)
			{
			    $this->person_data_table = $query->row_array(); //Сохраняем в Personmodel строки для текущего пользователя
				return $this->person_data_table;
			} else
            {
					return FALSE;
            }
    }


    public function makeFormsFromJSON()
    {

    //Определяем наборы параметров для форм (типа RADIO BUTTON, SELECT BOX и т.д.)
    $fp_radio_req = $this->fp_radio_req;
    $fp_sel_req =  $this->fp_sel_req;
    $fp_sel_not_req = $this->fp_sel_not_req;
    $fp_sel_multi =  $this->fp_sel_multi;
    $fp_edit_text_not_req = $this->fp_edit_text_not_req;
    $fp_edit_text_req = $this->fp_edit_text_req;
    $fp_sel_country = $this->fp_sel_country;
    $fp_sel_region = $this->fp_sel_region;
    $fp_sel_city = $this->fp_sel_city;



    //Грузим JSON
    $json_file = file_get_contents('JSON/forms.json');
    // convert the string to a json object
    $json = json_decode($json_file);
    // read the title value
    $possilbe_types = array("select", "select_multiple", "radio","text","country","region","city");

    $ar=[]; //Создаем пустой массив который мы будем возвращать (это набор готовых объектов).

    foreach ($json as $el) {
    //Внутренние переменные (нужны для того, что бы проверить правильность значений в JSON массиве).

    $name = FALSE; //Имя поля
    $label = FALSE; //название поля на руском языке
    $options = FALSE; // Массив доступных опций
    $type = FALSE; //Тип поля - Может быть: select, select_multiple, radio
    $dbName = FALSE; //Имя для базы данных
    $defaultOption = FALSE; //Опции по умолчанию
    $css_class = FALSE; //CSS класс для поля
    $errol_class = FALSE; //CSS класс для поля с ошибками
    $validation_message = FALSE; //Сообщение для ошибок
    $validation_messages_array = FALSE; // Массив ошибок для
    $temp_arr = [];

    //Не добавляются в объект form_filed
    $required = FALSE; //Обязательно ли это поле. - Нужен только для того, что бы определить какой объкт form_params создаем
    $editable = FALSE; //Можно ли это редактировать. Будет тогда создаваться отдельный массив, что бы только эти поля показывались.
    $searchable = FALSE; //Можно ли искать по этому полю. Будет тогда создаваться. Будет тогда создаваться отдельный массив, что бы только эти поля показывались.
    $group = 0; // Группа для разбивания массива на несколько частей
    $order = 0; // Порядок сортировки. Больше лучше. - 5 будет ближе к началу чем 1


    //Присваиваем переменные из JSON
    if (!isset($el->name)) die("Нет поля именя в файле JSON/forms.json");
    if (!isset($el->label)) die("Нет поля label в файле JSON/forms.json ля поля с именем: " . $el->name);
    if (!isset($el->options)) die("Нет массива options в файле JSON/forms.json ля поля с именем: " . $el->name);



    $name = $el->name;
    $label= $el->label;
    $options = $el->options;

    //Переводим Массив из Zero Based в ONE BASED
    $ar_length = count($options);
    for ($i=0; $i<$ar_length; $i++)
    {
       $temp_arr[$i+1]=$options[$i];
    }
    $options=$temp_arr;


    //Проверка остальных полей
    $defaultOption = isset($el->defaultOption) ? $el->defaultOption : FALSE;
    $css_class = isset($el->css_class) ? $el->css_class : FALSE;
    $errol_class = isset($el->errol_class) ? $el->errol_class : FALSE;
    $validation_message = isset($el->validation_message) ? $el->validation_message : FALSE;
    $dbName = isset($el->$defaultOption) ? $el->$defaultOption : FALSE;
    $validation_messages_array = isset($el->validation_messages_array) ? $el->validation_messages_array : FALSE;
    $required = isset($el->required) ? $el->required : FALSE;
    $editable = isset($el->editable) ? $el->editable : FALSE;
    $searchable = isset($el->searchable) ? $el->searchable : FALSE;
    $group = isset($el->group) ? (int)$el->group : 0;
    $order= isset($el->order) ? (int)$el->order : 0;

    //Определяем тип
    if (!in_array($el->type, $possilbe_types)) die("Неправильно назван тип поля в файле  JSON/forms.json для поля с именем: " . $name ); // Проверка на ошибки

    //Делаем тип поля
    if ($el->type==='select' && $required) $type = $fp_sel_req;
    elseif ($el->type==='select') $type = $fp_sel_not_req;
    elseif ($el->type==='select_multiple') $type = $fp_sel_multi;
    elseif ($el->type==='radio') $type = $fp_radio_req;
    elseif ($el->type==='text' && $required) $type = $fp_edit_text_req;
    elseif ($el->type==='text') $type = $fp_edit_text_not_req;
    elseif ($el->type==='country') $type = $fp_sel_country;
    elseif ($el->type==='region') $type = $fp_sel_region;
    elseif ($el->type==='city') $type = $fp_sel_city;
    else $type = $fp_sel_not_req;

    //Создаем объект
    $obj = new form_filed($name,$options,$type,$label,$dbName,$defaultOption,$validation_message,$validation_messages_array,$css_class,$errol_class,
        $editable,$searchable,$order,$group);


    //пушим его в массив
    array_push($ar,$obj);
    }

    //Присваиваем массивы в зависимости от параметра
    $this->allFields = $ar; // Все поля без фильтра.
    $this->ar = $ar; // Все поля без фильтра.




    $this->editableFields = array_filter($ar, function ($el) {return $el->editable ===TRUE ? TRUE : FALSE;});
    $this->searchebleFields = array_filter($ar, function ($el){ return $el->searchable===TRUE ? TRUE : FALSE;});

    //вернуть массив
    return $ar;
    }


public function updatePersonData(int $id, array $data):int
{
    $this->db->where('id',$id);
    $this->db->update('person',$data);
    return $this->db->affected_rows();
}

    public function insertNewPerson($id)
    {
    //Готовим данные для вставки в таблицу персона
    $data['id']=$id; //Айдишник который получили от вставленного юзера

    //Обходим все динамические поля. Они уже проверены валидацией формы. По этому просто вставляем значения
    /** @var form_filed $el  */
    foreach ($this->ar as $el)
    {
        if (!empty($this->input->post($el->get_name())))
        {
            //Для текста делаем исключение
            if (($el->param->type!=form_params::text && $el->param->type!=form_params::edit_text))
                $data[$el->getMysqlName()] = (int)$this->input->post($el->get_name()); // Добавляем в массив данных те значения которые есть в POST
            else $data[$el->getMysqlName()] = $this->input->post($el->get_name());
        }
    }
    $this->db->insert($this->t, $data);
    }


    public function makeValidationRules(){
//Автоматически делаем validation rules только для тех полей, которые нужны в данном режиме
switch ($this->currentType) {
    case Personmodel::new:
        $ar = $this->Personmodel->allFields;
        break;
    case Personmodel::edit:
        $ar = $this->Personmodel->editableFields;
        break;
    case Personmodel::search:
        $ar = $this->Personmodel->searchebleFields;
        break;
        }
//Делаем правила для каждого элемента.
foreach ($ar as $el) $el->get_val_rules();
}


        public function makeAllMysqlFields()
    {
        foreach ($this->ar as $el)
        {
            $el->createDBField();
        }
    }



    public function get_int()
    {
        return $this->glob_var;
    }

    public function get_int_with_increment()
    {
        SELF::$test_int++;
        return ++$this->glob_var;
    }


}

class form_filed
{

    /* @var string имя поля */
    public $name;
    /* @var string|bool Название поля которое выводится пользователю на руском языке */
    public $label = FALSE;
    /* @var string|bool имя поля в базе данных, если отличается от имени поля */
    protected $mysql_field_name = FALSE;
    /* @var array набор опций в списке (т.е. доступные варианты ответов которые пользователь может выбрать)*/
    public $options;
    /* @var form_params тип использованного меню. Для работы используется объект класса - form_params*/
    public $param;
    //Интерфейсы (Паттерн Strategy). Данные интерфейсы реализуеют поведение формы
    /* @var forrm_create_behavior реализация интерфейса по созданию формы*/
    public $form_create_obj;
    /* @var form_validation_rules_behavior реализация интерфейса по созданию формы*/
    public $form_vall_obj;
    /* @var  стандартная опция. Если будет FALSE то будет использоваться стандартная опция из $param (form_params)*/
    protected $default_option = FALSE;
    /* @var  строка ошибки которая выводится. Если будет FALSE то будет использоваться стандартная опция из $param (form_params)*/
    protected $validation_error_message = FALSE;
    /* @var  массив ошибок которые выводятся. Если будет FALSE то будет использоваться стандартная опция из $param (form_params)*/
    protected $validation_errors = FALSE;
    /* @var  CSS класс для формы. Если будет FALSE то будет использоваться стандартная опция из $param (form_params)*/
    protected $css_class = FALSE;
    /* @var  CSS класс для строки с ощибками. Если будет FALSE то будет использоваться стандартная опция из $param (form_params)*/
    protected $error_css_class = FALSE;
    /* @var  boolean редактируемое ли поле*/
    public $editable  = FALSE;
    /* @var  boolean можно ли искать по этому полю*/
    public $searchable = FALSE;
    /* @var  integer Порядок сортировки*/
    public $order = 0;
    /* @var  group группа (блок вывода - div) в рамках которой будет выводитсья данное поле*/
    public $group = 0;




    function __construct($name,$options,$param,$label,$mysql_field_name=FALSE,$default_option=FALSE,$validation_error_message=FALSE,
                         $validation_errors=FALSE,$css_class=FALSE,$error_css_class=FAlSE,$editable,$searchable,$order, $group)
    {
        $this->name = $name;
        $this->options = $options;
        $this->param = $param;
        $this->label =$label;
        $this->default_option=$default_option;
        $this->validation_error_message=$validation_error_message;
        $this->validation_errors=$validation_errors;
        $this->css_class=$css_class;
        $this->error_css_class=$error_css_class;
        $this->mysql_field_name = $mysql_field_name;
        $this->editable=$editable;
        $this->searchable=$searchable;
        $this->order=$order;
        $this->group=$group;


        //Фабричный метод для сборки нужного объекта реализующего необходимое поведение
        //Strategy pattern
        switch ($param->type) {
            case form_params::select:
                $this->form_create_obj = new new_person_create_select();
                $this->form_vall_obj = new new_person_validate_rules_one_possible_answer();
                break;
            case form_params::radio:
                $this->form_create_obj = new new_person_create_radio_button();
                $this->form_vall_obj = new new_person_validate_rules_one_possible_answer();
                break;
            case form_params::select_multiple:
                $this->form_create_obj = new new_person_create_select_multiple();
                $this->form_vall_obj = new new_person_validate_rules_seveveral_possible_answers();
                break;
            case form_params::edit_select:
                $this->form_create_obj = new edit_person_create_select();
                $this->form_vall_obj = new new_person_validate_rules_one_possible_answer();
                break;
            case form_params::text:
                $this->form_create_obj = new new_person_create_text_field();
                $this->form_vall_obj = new new_person_validate_rules_for_text_field();
                break;
            case form_params::edit_text:
                $this->form_create_obj = new person_edit_text_field();
                $this->form_vall_obj = new new_person_validate_rules_for_text_field();
                break;
            case form_params::country:
                $this->form_create_obj = new new_person_create_select_coutry();
                $this->form_vall_obj = new new_person_validate_rules_one_possible_answer();
                break;
            case form_params::region:
                $this->form_create_obj = new new_person_create_select_coutry();
                $this->form_vall_obj = new new_person_validate_rules_one_possible_answer();
                break;
            case form_params::city:
                $this->form_create_obj = new new_person_create_select_coutry();
                $this->form_vall_obj = new new_person_validate_rules_one_possible_answer();
                break;

            default: //TODO: Убрать это говно в дальнейшем! Это Апасна!
                $this->form_create_obj = new new_person_create_select();
                $this->form_vall_obj = new new_person_validate_rules_one_possible_answer();
                break;






        }

   }


   public function createDBField()
   {
   $CI =& get_instance();


   //echo $this->getMysqlName() . "  Тип " . " $this->param->type" . "<br>";




   if ($this->param->type==form_params::select || $this->param->type==form_params::radio || $this->param->type==form_params::edit_select) {
       $data = [$this->getMysqlName() => array(
           'type' => 'tinyint',
           'null' => FALSE, // NOT Null
           'default' => '0')];
   }
   else if ($this->param->type==form_params::country ||  $this->param->type==form_params::region || $this->param->type==form_params::city)
   {
       $data = [$this->getMysqlName()."_id" => array(
           'type' => 'INT',
           'unsigned' => TRUE,
           'null' => null, // NOT Null
           )];

       $text_data = [$this->getMysqlName()."_text" => array(
           'type' => 'VARCHAR',
           'null' => TRUE, // NOT Null
           'constraint' => '128'
           )];
       $CI ->dbforge->add_field($text_data);
   }
   else {
       $data = [$this->getMysqlName() => array(
           'type' => 'VARCHAR',
           'constraint' => '5000',
           'null' => TRUE, // NOT Null
         )];
   }



   $CI ->dbforge->add_field($data);

   }

   public function getDefaultValue():string
    {
        if ($this->default_option===FALSE)
            return $this->param->default_option;
        else
            return (string)$this->default_option;
    }


   public function getMysqlName():string
    {
        if ($this->mysql_field_name===FALSE)
            return $this->name;
        else
            return $this->mysql_field_name;
    }

    public function getErrorMessage():string
    {
        if ($this->validation_error_message===FALSE)
            return $this->param->validation_error_message;
        else
            return $this->validation_error_message;
    }

    public function getValidationErrorsArray()
    {
        if ($this->validation_errors===FALSE)
            return $this->param->error_messages_arr;
        else
           return $this->validation_errors;
    }

    public function getCSSClass():string
    {
        if ($this->css_class===FALSE)
            return $this->param->form_class;
        else
            return $this->css_class;
    }

    public function getCSSErrorClass():string
    {
        if ($this->error_css_class===FALSE)
            return $this->param->error_class;
        else
           return $this->error_css_class;
    }



    /**
     * Функция которая делает HTML представлание формы
     * @return string готовая для вставки в пользовательский интерфейс форма
     */
    public function get_form_html():string
    {
    return $this->form_create_obj->create_html($this);
    }

    /**
     * Создает правила для form validation rules
     */
    public function get_val_rules()
    {
        $this->form_vall_obj->create_validation_rules($this);
    }


    /**
     * Получить текстовое представлание номера пунта.
     * Если переданао невалидное значение то возвращает, значение по умолчанию.
     * @param int Номер в формате INT. Типа ID данного пункта который будет показан пользователю. Нужно для того что бы
     * хранить данные в базе в INT
     * @return string возвращает тектовое представлание данного пункта
     */
    public function get_text_representation(int $i):string
    {
         if (array_key_exists($i,$this->options)) return $this->options[$i];
         elseif ($i===0) return $this->getDefaultValue();
         else return  $this->getDefaultValue();
    }


    /**
     * Возвращает префикс поля
     * @return string|bool возвращает префикс поля. Если его нет возвращает FALSE
     */
    function getPrefix()
    {
      return $this->param->prefix===FALSE ? FALSE : $this->param->prefix;
    }


    /**
     * Возвращает имя текущего поля с префиксом
     * @return string имя поля (просто добавляет префикс, если он нужен)
     */
    function get_name(): string
    {
        if ($this->param->prefix!==FALSE) return $this->param->prefix . $this->name;
        else $this->name;
    }

    /**
     * Получаем значение которое передал пользователь через форму методом POST.
     * @return int|mixed возвращает значение которое передано по методу POST. (не можем кастить в инт, так как может
     * быть возвращен массив).
     */
    function post_value()
    {
        $CI =& get_instance();
        $res = $CI->input->post($this->get_name());
        return is_null($res)?0:$res;
    }
}


class form_params
{
    public const select=1;
    public const radio=2;
    public const select_multiple=3;
    public const edit_select=4;

    //Новые еще не реализованные
    public const text = 5;
    public const edit_text =6;

    public const country = 7;
    public const region = 8;
    public const city = 9;



    /* @var int набор опций в списке*/
    public $type;
    /* @var string дефолтное значение поля - типа как "не выбранно"*/
    public $default_option;
    /* @var array массив правил для from validation, последние это анонимная функция */
    public $validation_rules;
    /* @var string строка которая будет выводиться для анонимной функции в form validation*/
    public $validation_error_message;
    /* @var string класс который будет присвоен полю*/
    public $form_class;
    /* @var string класс который будет присвоен строке ошибки*/
    public $error_class;
    /* @var string префикс имени для сокрытия реального названия в базе*/
    public $prefix;
    /* @var bool использовать ли анонимную функцию в для FORM validation (для того что бы введеное пользователем значние было из списка и никак иначе*/
    public $use_anon_funct;
    /* @var array массив параметров для отображения ошибок кроме каллбака. (для стандартных - типа как required).*/
    public $error_messages_arr;



    /**
     * form_params constructor.
     * @param int $type набор опций в  - const form_params::select form_params::radio и т.д.
     * @param string $default_option - дефолтное значение поля - типа как "не выбранно"
     * @param array $validation_rules -  массив правил для from validation, последние это анонимная функция
     * @param string $validation_error_message - строка которая будет выводиться для анонимной функции в form validation
     * @param string $form_class - класс который будет присвоен полю
     * @param string $form_class - класс который будет строке ошибки
     * @param string $prefix - префикс имени для сокрытия реального названия в базе
     * @param bool $use_anon_funct использовать ли анонимную функцию в для FORM validation (для того что бы введеное пользователем значние было из списка и никак иначе
     * @param bool $error_messages_arr  массив параметров для отображения ошибок кроме каллбака. (для стандартных - типа как required).
     *
     **/
    public function __construct(int $type, string $default_option, array $validation_rules,  string $form_class, string $error_class, string $prefix, bool $use_anon_funct= TRUE, string $validation_error_message="", array $error_messages_arr=null)
    {
        $this->type = $type;
        $this->default_option = $default_option;
        $this->validation_rules = $validation_rules;
        $this->validation_error_message = $validation_error_message;
        $this->form_class = $form_class;
        $this->prefix = $prefix;
        $this->use_anon_funct=$use_anon_funct;
        $this->error_messages_arr=$error_messages_arr;
        $this->error_class=$error_class;
    }
}

//Интерфейсы
interface forrm_create_behavior
{
    public function create_html(form_filed $ff): string;
}

interface form_validation_rules_behavior
{
    public function create_validation_rules(form_filed $ff);
}

interface form_edit_behavior
{
    public function edit_form_html(form_filed $ff, array $qr): string;
}

interface form_editor_validation_rules_behavior
{
    public function create_validation_rules(form_filed $ff, array $qr): string;
}

//абстрактные классы
abstract class form_create_abstract
{

    protected function repopulate(form_filed $ff)
    {
        $CI =&get_instance();
        $val = $CI->input->post($ff->get_name());
        if (!empty($val))
        {
            if (array_key_exists($val,$ff->options))
            {
              return (int)($val);
            } else  return FALSE;
        }
        else return FALSE;
    }


    protected function checkPostedValue(form_filed $ff)
    {
        $CI =&get_instance();
        $val = $CI->input->post($ff->get_name());
        if (!empty($val))
        {
                if (is_numeric($val))
                    return (int)($val);
                else return False;
            }
            else  return FALSE;
    }


    protected function repopulate_for_multi_choice(form_filed $ff)
    {
        $CI =&get_instance();

        $val = $CI->input->post($ff->get_name());
        if (empty($val)) return FALSE;
        if (!is_array($val)) return FALSE;
            else
            {
                $ar=[];
                 foreach ($val as $item)
                {
                    if (!is_numeric($item)) return FALSE;
                    $ar[]=(int)$item;
                }
                return $ar;
        }
    }

    protected function check_error(form_filed $ff)
    {
        if (!empty(form_error($ff->get_name())))  return form_error($ff->get_name());
        else return FALSE;
    }

    protected function make_error_line(form_filed $ff,string $s):string
    {
        return PHP_EOL.'<span class="'.$ff->getCSSErrorClass().'">'.$s.'</span>'.PHP_EOL;
    }


}


//Реализация интерфейсов

class new_person_create_text_field extends form_create_abstract implements forrm_create_behavior
{
    public function create_html(form_filed $ff): string
    {

        $CI =&get_instance();
        $repopulate = $CI->input->post($ff->get_name()); //Обычный репопулайт тут не работает.
        // Так как тут просто текст.
        $error_str  = $this->check_error($ff);

        //Начинаем формировать строку
        $required = in_array('required',$ff->param->validation_rules);
        $str='';//Делаем пустую строку

        //Делаем Label
        if ($required)
            $str.=PHP_EOL.'<label for="'.$ff->get_name().'">'.$ff->label.' * </label>'.PHP_EOL;
        else $str.=PHP_EOL.'<label for="'.$ff->get_name().'">'.$ff->label.'</label>'.PHP_EOL;



        $str.=PHP_EOL.'<textarea  placeholder="'
            .$ff->getDefaultValue(). '"'.
            ' name="'.$ff->get_name().'"'
            .' id="'.$ff->get_name(). '"'
            .' cols="40" rows="5">';

        //Значение по умолчанию
        if ($repopulate==FALSE);
        elseif ($repopulate==TRUE ) $str.=$repopulate;
        $str.='</textarea>';

        if ($error_str)
        {
            $str.=  $this->make_error_line($ff,$error_str);
        }
        return $str;

    }

}



class person_edit_text_field extends form_create_abstract implements forrm_create_behavior
{
    public function create_html(form_filed $ff): string
    {

        $CI =&get_instance();
        $repopulate = $CI->input->post($ff->get_name()); //Обычный репопулайт тут не работает.

        //Hook для Repopulate. Если repopulate пустая значит пользователь еще ничего не отправлял
        //Значит мы заменяем repopulate на значение из базы. Если Repopulate не пустое, значит юзер уже отправил данные.
        $pd = $CI->Personmodel->person_data_table;
        if ($repopulate==false)  $repopulate = $pd[$ff->getMysqlName()];




        // Так как тут просто текст.
        $error_str  = $this->check_error($ff);

        //Начинаем формировать строку
        $required = in_array('required',$ff->param->validation_rules);
        $str='';//Делаем пустую строку

        //Делаем Label
        if ($required)
            $str.=PHP_EOL.'<label for="'.$ff->get_name().'">'.$ff->label.' * </label>'.PHP_EOL;
        else $str.=PHP_EOL.'<label for="'.$ff->get_name().'">'.$ff->label.'</label>'.PHP_EOL;



        $str.=PHP_EOL.'<textarea  placeholder="'
            .$ff->getDefaultValue(). '"'.
            ' name="'.$ff->get_name().'"'
            .' id="'.$ff->get_name(). '"'
            .' cols="40" rows="5">';

        //Значение по умолчанию
        if ($repopulate==FALSE);
        elseif ($repopulate==TRUE ) $str.=$repopulate;
        $str.='</textarea>';

        if ($error_str)
        {
            $str.=  $this->make_error_line($ff,$error_str);
        }
        return $str;

    }

}

class new_person_create_select extends form_create_abstract implements forrm_create_behavior
{
    public function create_html(form_filed $ff): string
    {

        $repopulate = $this->repopulate($ff);
        $error_str  = $this->check_error($ff);
        //Начинаем формировать строку
        $required = in_array('required',$ff->param->validation_rules);
        $str='';//Делаем пустую строку

        if ($required)
            $str.=PHP_EOL.'<label for="'.$ff->get_name().'">'.$ff->label.' * </label>'.PHP_EOL;
        else $str.=PHP_EOL.'<label for="'.$ff->get_name().'">'.$ff->label.'</label>'.PHP_EOL;


        $str.='<select name="'.$ff->get_name(). '" id="'.$ff->get_name().'">'.PHP_EOL;

        //Значение по умолчанию - что то вроде типа - не выбрано
        if ($repopulate===FALSE) $str.='<option value="0" selected>'.$ff->getDefaultValue().'</option>'.PHP_EOL;
        elseif ($repopulate==TRUE ) $str.='<option value="0">'.$ff->getDefaultValue().'</option>'.PHP_EOL;

        foreach($ff->options as $key => $value)
        {
            //Если это ранее выбранная строка пользователем
            if (((int)$key)===$repopulate)
            {
                $str.= '<option value="'.$key.'"  selected >'.$value.'</option>'.PHP_EOL;
            }else
            {
                 $str.= '<option value="'.$key.'">'.$value.'</option>'.PHP_EOL;
            }
        }


        $str.='</select>'.PHP_EOL;
        if ($error_str)
        {
            $str.=  $this->make_error_line($ff,$error_str);
        }
        return $str;

    }

}







class new_person_create_select_coutry extends form_create_abstract implements forrm_create_behavior
{
    public function create_html(form_filed $ff): string
    {
        $CI =&get_instance();
        $repopulate = false;

        if ($ff->param->type==form_params::country) //Если текущее поле Страна
        {
            $ff->options = $CI->Regionmodel->getAllCountries();
            $repopulate = $this->repopulate($ff);
        }
        else if ($ff->param->type==form_params::region) //Если текущие поле Регион
        {
            //Находим другие поля
            $coutry_ff = null;
            foreach ($CI->Personmodel->allFields as $el)
            {
                if ($el->param->type==form_params::country)
                    $coutry_ff = $el;
            }

            $coutryRepopulateVal = $this->checkPostedValue($coutry_ff);
            if ($coutryRepopulateVal)
            {
                $ff->options = $CI->Regionmodel->getRegionsByCountryId($coutryRepopulateVal);
                $repopulate = $this->repopulate($ff);
            }

        }
        else if ($ff->param->type==form_params::city) //Если текущие поле Город
        {
            //Находим другие поля
            $region_ff = null;
            foreach ($CI->Personmodel->allFields as $el)
            {
                if ($el->param->type==form_params::region)
                    $region_ff = $el;
            }

            $coutryRepopulateVal = $this->checkPostedValue($region_ff);
            if ($coutryRepopulateVal)
            {
                $ff->options = $CI->Regionmodel->getCitiesByRegionId($coutryRepopulateVal);
                $repopulate = $this->repopulate($ff);
            }

        }


        $error_str  = $this->check_error($ff);


        //Начинаем формировать строку
        $required = in_array('required',$ff->param->validation_rules);
        $str='';//Делаем пустую строку

        if ($required)
            $str.=PHP_EOL.'<label for="'.$ff->get_name().'">'.$ff->label.' * </label>'.PHP_EOL;
        else $str.=PHP_EOL.'<label for="'.$ff->get_name().'">'.$ff->label.'</label>'.PHP_EOL;


        $str.='<select name="'.$ff->get_name(). '" id="'.$ff->get_name().'">'.PHP_EOL;

        //Значение по умолчанию - что то вроде типа - не выбрано
        if ($repopulate===FALSE) $str.='<option value="0" selected>'.$ff->getDefaultValue().'</option>'.PHP_EOL;
        elseif ($repopulate==TRUE ) $str.='<option value="0">'.$ff->getDefaultValue().'</option>'.PHP_EOL;

        foreach($ff->options as $key => $value)
        {
            //Если это ранее выбранная строка пользователем
            if (((int)$key)===$repopulate)
            {
                $str.= '<option value="'.$key.'"  selected >'.$value.'</option>'.PHP_EOL;
            }else
            {
                $str.= '<option value="'.$key.'">'.$value.'</option>'.PHP_EOL;
            }
        }


        $str.='</select>'.PHP_EOL;
        if ($error_str)
        {
            $str.=  $this->make_error_line($ff,$error_str);
        }
        return $str;

    }

}








class new_person_create_select_multiple extends form_create_abstract implements forrm_create_behavior
{
    public function create_html(form_filed $ff): string
    {
        $repopulate = $this->repopulate_for_multi_choice($ff);
        $error_str  = $this->check_error($ff);
        //Начинаем формировать строку
        $required = in_array('required',$ff->param->validation_rules);
        $str='';//Делаем пустую строку


        if ($required)
            $str.=PHP_EOL.'<label for="'.$ff->get_name().'">'.$ff->label.' * </label>'.PHP_EOL;
        else $str.=PHP_EOL.'<label for="'.$ff->get_name().'">'.$ff->label.'</label>'.PHP_EOL;

        $str.='<select multiple="true" name="'.$ff->get_name().'" id="'.$ff->get_name().'">'.PHP_EOL;

        foreach($ff->options as $key => $value)
        {
            //Если это ранее выбранная строка пользователем

            if ($repopulate==true)
            {
                if (in_array((int)$key,$repopulate))
                {
                    $str.= '<option value="'.$key.'"  selected >'.$value.'</option>'.PHP_EOL;
                }else
                {
                    $str.= '<option value="'.$key.'">'.$value.'</option>'.PHP_EOL;
                }
            }
            else
            {
                $str.= '<option value="'.$key.'">'.$value.'</option>'.PHP_EOL;
            }
        }

        $str.='</select>'.PHP_EOL;
        if ($error_str)
        {
            $str.=  $this->make_error_line($ff,$error_str);
        }
        return $str;

    }

}


class new_person_create_radio_button extends form_create_abstract implements forrm_create_behavior
{
        public function create_html(form_filed $ff): string
    {
        $repopulate = $this->repopulate($ff);
        $error_str  = $this->check_error($ff);
        //Начинаем формировать строку
        $required = in_array('required',$ff->param->validation_rules);
        $str=''; // Пустая строка


        $str.='<label for="'.$ff->get_name().'">'.$ff->label.'</label>'.PHP_EOL;

        //TODO:Убрать это для полей которые required и в которых пользователь, что то наковырял
        foreach($ff->options as $key => $value)
        {
            //Если это ранее выбранная строка пользователем
            if (((int)$key)===$repopulate)
            {
               $str.= '<input type="radio" name="'.$ff->get_name().'" value='.$key.' checked>'.$value.'<br>'.PHP_EOL;

            }else
            {
                 $str.= '<input type="radio" name="'.$ff->get_name().'" value='.$key.' >'.$value.'<br>'.PHP_EOL;
            }
        }


        if ($error_str)
        {
            $str.=  $this->make_error_line($ff,$error_str);
        }

        return $str;

    }
}



class edit_person_create_select extends form_create_abstract implements forrm_create_behavior
{
    public function create_html(form_filed $ff): string
    {

        $CI = &get_instance();
        //Работает только с залогиненым юзером
        $repopulate = $this->repopulate($ff);
        $error_str  = $this->check_error($ff);

        //PD - Person Data - строка для конкретного пользователя. Берется так как пользователь уже залогинен.
        $pd = $CI->Personmodel->person_data_table;

        //Hook для Repopulate. Если repopulate пустая значит пользователь еще ничего не отправлял
        //Значит мы заменяем repopulate на значение из базы. Если Repopulate не пустое, значит юзер уже отправил данные.

        if ($repopulate===false)
        {
             if (array_key_exists((int)$pd[$ff->getMysqlName()],$ff->options))
            {
              $repopulate = (int)$pd[$ff->getMysqlName()];
            } else $repopulate = FALSE;

        }

        //Начинаем формировать строку
        $required = in_array('required',$ff->param->validation_rules);
        $str='';//Делаем пустую строку


        if ($required)
            $str.=PHP_EOL.'<label for="'.$ff->get_name().'">'.$ff->label.' * </label>'.PHP_EOL;
        else $str.=PHP_EOL.'<label for="'.$ff->get_name().'">'.$ff->label.'</label>'.PHP_EOL;

        $str.='<select name="'.$ff->get_name(). '" id="'.$ff->get_name().'">'.PHP_EOL;

        //Значение по умолчанию - что то вроде типа - не выбрано
        if ($repopulate===FALSE) $str.='<option value="0" selected>'.$ff->getDefaultValue().'</option>'.PHP_EOL;
        elseif ($repopulate==TRUE ) $str.='<option value="0">'.$ff->getDefaultValue().'</option>'.PHP_EOL;

        foreach($ff->options as $key => $value)
        {
            //Если это ранее выбранная строка пользователем
            if (((int)$key)===$repopulate)
            {
                $str.= '<option value="'.$key.'"  selected >'.$value.'</option>'.PHP_EOL;
            }else
            {
                 $str.= '<option value="'.$key.'">'.$value.'</option>'.PHP_EOL;
            }
        }


        $str.='</select>'.PHP_EOL;
        if ($error_str)
        {
            $str.=  $this->make_error_line($ff,$error_str);
        }
        return $str;

    }

}




class new_person_validate_rules_one_possible_answer implements form_validation_rules_behavior
{
    /**
     * Создает правила для FormValidation CodeIgniter
     * Не работает если пустой, массив правил. (нужно что бы ходить одно правило было в)
     * @param form_filed $ff ссылка на форм филдс
     */
    public function create_validation_rules(form_filed $ff)
    {
        $rules = $ff->param->validation_rules; //Просто текстовые правила
        //Нельзя исползовать данну.
       // if (empty($arr)) throw new Exception('Нелья испрользовать данную функцию с пустым массивом правил');
         $CI =& get_instance();
         $required = in_array('required',$ff->param->validation_rules);

        $err_description=[];//Создаем пустой массив ошибок.
         //Заполняем $err_description (если переданы они в качестве параметра)
         if (!empty($ff->getValidationErrorsArray()))
        {
            $err_description = $ff->getValidationErrorsArray();
        }

            //Если нам нужен каллбак
        if ($ff->param->use_anon_funct)
        {
            $funct_name = 'callback_inlist'; //Имя каллбака


            $funct = function ($str) use ($ff,$required) //Сам калбак
            {
                if (!is_numeric($str)) return FALSE; //Выходим если это не номер
                $user_int = (int)$str;
                if ((array_key_exists($user_int,$ff->options)) || (($user_int===0) && $required===FALSE)) return TRUE;
                else return FALSE;
            };
                $err_description[$funct_name]= $ff->getErrorMessage(); //Заполняем
                $rules[] = [$funct_name,$funct];
            //Собираем полное правило
            $CI->form_validation->set_rules($ff->get_name(),$ff->label,$rules,$err_description);
        }
        else //Если нам не нужен каллбак
        {
           $CI->form_validation->set_rules($ff->get_name(),$ff->label,$ff->param->validation_rules,$err_description);
        }
    }

}


class new_person_validate_rules_for_text_field implements form_validation_rules_behavior
{
    /**
     * Создает правила для FormValidation CodeIgniter
     * Не работает если пустой, массив правил. (нужно что бы ходить одно правило было в)
     * @param form_filed $ff ссылка на форм филдс
     */
    public function create_validation_rules(form_filed $ff)
    {
        $rules = $ff->param->validation_rules; //Просто текстовые правила
        //Нельзя исползовать данну.
       // if (empty($arr)) throw new Exception('Нелья испрользовать данную функцию с пустым массивом правил');
         $CI =& get_instance();
         $required = in_array('required',$ff->param->validation_rules);

        $err_description=[];//Создаем пустой массив ошибок.
         //Заполняем $err_description (если переданы они в качестве параметра)
         if (!empty($ff->getValidationErrorsArray()))
        {
            $err_description = $ff->getValidationErrorsArray();
        }

            //Если нам нужен каллбак
        if ($ff->param->use_anon_funct)
        {
            $funct_name = 'callback_inlist'; //Имя каллбака

            $funct = function ($str) use ($ff,$required) //Сам калбак
            {
                //TODO: Нужно сделать проверку на плохие слова, и спам филтр.
                return TRUE;
            };
                $err_description[$funct_name]= $ff->getErrorMessage(); //Заполняем
                $rules[] = [$funct_name,$funct];
            //Собираем полное правило
            $CI->form_validation->set_rules($ff->get_name(),$ff->label,$rules,$err_description);
        }
        else //Если нам не нужен каллбак
        {
           $CI->form_validation->set_rules($ff->get_name(),$ff->label,$ff->param->validation_rules,$err_description);
        }
    }

}



class new_person_validate_rules_seveveral_possible_answers  implements form_validation_rules_behavior
{
    /**
     * Создает правила для FormValidation CodeIgniter
     * Не работает если пустой, массив правил. (нужно что бы ходить одно правило было в)
     * @param form_filed $ff ссылка на форм филдс
     */
    public function create_validation_rules(form_filed $ff)
    {
        $rules = $ff->param->validation_rules; //Просто текстовые правила
        //Нельзя исползовать данну.
        // if (empty($arr)) throw new Exception('Нелья испрользовать данную функцию с пустым массивом правил');
        $CI =& get_instance();
        $required = in_array('required',$ff->param->validation_rules);

        $err_description=[];//Создаем пустой массив ошибок.
        //Заполняем $err_description (если переданы они в качестве параметра)
        if (!empty($ff->getValidationErrorsArray()))
        {
            $err_description = $ff->getValidationErrorsArray();
        }

        //Если нам нужен каллбак
        if ($ff->param->use_anon_funct)
        {
            $funct_name = 'callback_inlist'; //Имя каллбака


            $funct = function ($str) use ($ff,$required) //Сам калбак
            {
                if (!is_numeric($str)) return FALSE; //Выходим если это не номер
                $user_int = (int)$str;
                if ((array_key_exists($user_int,$ff->options)) || (($user_int===0) && $required===FALSE)) return TRUE;
                else return FALSE;
            };
            $err_description[$funct_name]= $ff->getErrorMessage(); //Заполняем
            $rules[] = [$funct_name,$funct];
            //Собираем полное правило
            $CI->form_validation->set_rules($ff->get_name(),$ff->label,$rules,$err_description);
        }
        else //Если нам не нужен каллбак
        {
            $CI->form_validation->set_rules($ff->get_name(),$ff->label,$ff->param->validation_rules,$err_description);
        }
    }

}



?>
