<?php
class Mailmodel extends CI_Model {
private static $this_table_name = 'mail'; //название текущей таблицы, что бы менять в одном месте

//Функция для вставки почты в базу
public function insert_mail($from,$to,$subject,$text)
{//mail_from
//mail_to
//mail_subject
//mail_text
$data = array(
'mail_from' => $from,
'mail_to' => $to,
'mail_subject' => $subject,
'mail_text' => $text,);
$this->db->insert(SELF::$this_table_name, $data);
}

//Достать последние записи
//Берет только те записи в которых письмо не отправлено
public function get_mails(int $ammount)
{
$this->db->where('mail_has_been_sent', 0);
$this->db->order_by('id', 'DESC');
$query = $this->db->get(SELF::$this_table_name, $ammount);
return $query->result_array();
}


//Достать последние 10 вхождений в почту
//Берет только те записи в которых письмо не отправлено
public function get_last_ten_entries()
{
$this->db->where('mail_has_been_sent', 0);
$this->db->order_by('id', 'DESC');
$query = $this->db->get(SELF::$this_table_name, 10);
return $query->result_array();
}

//Отметить, что письмо было отправлено
public function update_has_been_sent($id)
{
$this->db->set('mail_has_been_sent', 1, FALSE);
$this->db->where('id', $id);
$this->db->limit(1);
$this->db->update(SELF::$this_table_name);
}

//Функция удалить одно письмо
public function delete_one_mail($id)
{
$this->db->where('id', $id);
$this->db->limit(1);
$this->db->delete(SELF::$this_table_name);
return $this->db->affected_rows();
}

//Удаляет все записи которые старше двух месяцев
function delete_old_mails()
{
$this->db->query("DELETE FROM ". SELF::$this_table_name ." WHERE mail_timestamp_created < NOW() - INTERVAL 2 MONTH and mail_has_been_sent = 1 ");
}

}
?>
