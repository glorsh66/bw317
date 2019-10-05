<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class RegionParametrs extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model("Regionmodel");

    }


	public function getRegion( $stateid=0)
	{

	if ($stateid>0 && is_numeric($stateid))
	{
        $stateid = (int)$stateid;
        $str = "";
	    $result =$this->Regionmodel->getRegionsByCountryId($stateid);
        foreach ($result as $key => $value){
            echo $key.','.$value.';';
        }
	}

	}


    public function getCity($stateid=0)
    {

        if ($stateid>0 && is_numeric($stateid))
        {
            $stateid = (int)$stateid;
            $str = "";
            $result =$this->Regionmodel->getCitiesByRegionId($stateid);
            foreach ($result as $key => $value){
                echo $key.','.$value.';';
            }
        }

    }


}
