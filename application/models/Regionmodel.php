<?php
class Regionmodel extends CI_Model {


    public function getCityById(int $id)
    {

        $this->db->where('id',$id);
        $this->db->limit(1);
        $query = $this->db->get('city');
        $row = $query->row_array();

        if (isset($row))  return  $row['name'];
        else return FALSE;
    }


    public function getCountryById(int $id)
    {
        $this->db->where('id',$id);
        $this->db->limit(1);
        $query = $this->db->get('country');
        $row = $query->row_array();

        if (isset($row))  return  $row['name'];
        else return FALSE;
    }

    public function getRegionById(int $id)
    {
        $this->db->where('id',$id);
        $this->db->limit(1);
        $query = $this->db->get('country');
        $row = $query->row_array();

        if (isset($row))  return  $row['name'];
        else return FALSE;
    }

    public function getAllCountries()
    {
        $query = $this->db->get('country');
        $resar = $query->result_array();
        $temp_arr=[];

        $ar_length = count($resar);

        if ($ar_length>0){
            foreach ($resar as $v)
            {
                $temp_arr[$v['id']] = $v['name'];
            }
        }

        return $temp_arr;
    }






    public function getRegionsByCountryId(int $countryId)
    {
        $this->db->where('country_id',$countryId);
        $query = $this->db->get('region');
        $resar = $query->result_array();

        $temp_arr=[];

        $ar_length = count($resar);

        if ($ar_length>0){
            foreach ($resar as $v)
            {
                $temp_arr[$v['id']] = $v['name'];
            }
        }
        return $temp_arr;
    }

    public function getCitiesByRegionId(int $regionId)
    {
        $this->db->where('region_id',$regionId);
        $query = $this->db->get('city');
        $resar = $query->result_array();

        $temp_arr=[];
        $ar_length = count($resar);
        if ($ar_length>0){
            foreach ($resar as $v)
            {
                $temp_arr[$v['id']] = $v['name'];
            }
        }
        return $temp_arr;
    }


    public function checkIfCountryExist(int $id)
    {
        $this->db->where('id',$id);
        $num_ret = $this->db->count_all_results('country');
        return $num_ret > 0 ? TRUE : FALSE; //Больше нуля возвращаем TRUE
    }


    public function getAllThreeOrTwo(int $countryId,int $regionId, int $cityId=-1)
    {


        $select = 'country.name as country_name, region.name as region_name';
        if ($cityId>0) $select = $select . ',city.name as city_name';

        $this->db->select ($select);
        $this->db->from ( 'country' );
        $this->db->join ( 'region', 'region.country_id = country.id' , 'left' );

        if ($cityId>0) $this->db->join ( 'city', 'city.region_id = region.id' , 'left' );

        $this->db->where('country.id', $countryId);
        $this->db->where('region.id', $regionId);
        if ($cityId>0) $this->db->where('city.id', $cityId);
        $query = $this->db->get ();
        return $query->result_array();
    }






    public function get_first_user()
    {
        $this->db->where('user_name',"glorsh");
        $query = $this->db->get('site_users');

    }



}
?>