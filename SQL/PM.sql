/*Выбираем борду, что бы получить информацию о пользователе*/
explain select pb.*,
pm.pm_text,
lu.user_name as lesser_user_name,
gu.user_name as greater_user_name
from (SELECT * FROM `PM_board` WHERE `lesser_id` =1
UNION
SELECT * FROM `PM_board` WHERE `greater_id` =1) as pb
left join private_messages as pm  on pb.last_message=pm.id
left join site_users as lu on lu.id=pb.lesser_id
left join site_users as gu on gu.id=pb.greater_id




CREATE TABLE `person` ( `id` INT(9) UNSIGNED NOT NULL, `person_option` tinyint NOT NULL DEFAULT '0', `person_option2_editable` tinyint NOT NULL DEFAULT '0', `person_option3_editable` tinyint NOT NULL DEFAULT '0', `person_option4_editable` tinyint NOT NULL DEFAULT '0', `person_text_editable_req` VARCHAR NOT NULL, `person_text_editable_not_req` VARCHAR NOT NULL, CONSTRAINT `pk_person` PRIMARY KEY(`id`) ) DEFAULT CHARACTER SET = utf8 COLLATE = utf8_general_ci