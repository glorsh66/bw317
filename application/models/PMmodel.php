<?php
class PMmodel extends CI_Model {

private $pm_table ="private_messages";


    /**
     * Вставляет сообщение в базу.
     * Увеличивает счетчик полученных/прочитанных сообщений
     * Не осуществляет никаких проверок (только работа с базой данной)
     * @param int $from
     * @param int $to
     * @param string $pm
     */
    public function insert_message(int $from, int $to, string $pm)
    {
        //Готовим данные
        $lesser = min($from,$to);
        $greater = max($from,$to);

        $tolesser =0;
        $togreater =0;
        //Чекаем где добавлять плюсик. Т.е. Кому добавлять полученных сообщений.
        if ($to === $lesser) $tolesser=1;
        else $togreater=1;

        //Данные для вставки в базу
        $data = array(
        'from_id' => $from,
        'to_id' => $to,
        'lesser_id' => $lesser,
        'greater_id' => $greater,
        'ip_address' => $_SERVER['REMOTE_ADDR'],
        'pm_text' => $pm);

        //Начинаем транзакцию
        $this->db->trans_begin();
        $this->db->insert($this->pm_table,$data);
        $insertId = $this->db->insert_id();

        //Формируем запрос для вставки боарда
        $sql_q = 'INSERT INTO PM_board (lesser_id, greater_id, all_count,lesser_count,greater_count,lesser_unread,greater_unread,last_message)
        VALUES (?, ?, 1,'."$tolesser,$togreater,$tolesser,$togreater,".  $insertId.') ON DUPLICATE KEY UPDATE '.
        "all_count=all_count+1,lesser_count=lesser_count+$tolesser,greater_count=greater_count+$togreater,lesser_unread=lesser_unread+$tolesser,".
        "greater_unread=greater_unread+$togreater,last_message=$insertId;";
        $query = $this->db->query($sql_q,array($lesser,$greater));
        $this->db->trans_complete(); //закрыли транзакцию
    }

}
?>
