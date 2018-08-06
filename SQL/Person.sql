/*Простой селект из персон*/
SELECT id FROM person
WHERE (height BETWEEN 110 and 180)
and (weight BETWEEN 50 and 250)
and sex =1
and sexual_orientation =1
and relationship =1
and education > 1
and employment = 1
and smoke = 1
and alcohol =1
and sport = 1
and health = 1
and virus_hiv =1
and virus_hepatitis_c = 1;


/*Более улучшенный селект*/
select * from
(SELECT person.id
FROM person
left join site_users on person.id=site_users.id
WHERE (height BETWEEN 110 and 180)
and (weight BETWEEN 50 and 250)
and sex =1
and sexual_orientation =1
and relationship =1
and education > 1
and employment = 1
and smoke = 1
and alcohol =1
and sport = 1
and health = 1
and virus_hiv =1
and virus_hepatitis_c = 1
order by site_users.user_registration_date) as t
join person on  t.id=person.id
join site_users on t.id = site_users.id;