<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
 
if (! function_exists('getCountry'))
{
	function getCountry($iso = '')
	{
		$CI = & get_instance();
		$CI->load->model('worldlocation');
		$country = $CI->worldlocation->getCountryName($iso);
		if(isset($country[0]['printable_name']))
		return ucfirst($country[0]['printable_name']);
		else return '';
	}	
} 


if (! function_exists('getAllByCollectionName'))
{
	function getAllByCollectionName($collectionname = '')
	{
		$CI = & get_instance();
		$CI->load->model('jewelrymodel');
		$result = $CI->jewelrymodel->getAllByCollectionName($collectionname);
		
		if(isset($result))return $result;		
		else return '';
	}	
}


?>
