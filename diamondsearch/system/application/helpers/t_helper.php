<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
 
if (! function_exists('getCountry'))
{
	function getAllCountry()
	{
		$CI = & get_instance();
		$CI->load->model('worldlocation');
		$result = $CI->worldlocation->getCountry();
		
		if(isset($result))return $result;		
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

if (! function_exists('getDetailsByLot'))
{
	function getDetailsByLot($lot = '')
	{
		$CI = & get_instance();
		$CI->load->model('diamondmodel');
		$result = $CI->diamondmodel->getDetailsByLot($lot);
				
		if(isset($result))return $result;
		else return '';
	}	
}


if (! function_exists('getAllByStock'))
{
	function getAllByStock($stock = '')
	{
		$CI = & get_instance();
		$CI->load->model('jewelrymodel');
		$result = $CI->jewelrymodel->getAllByStock($stock);
				
		if(isset($result))return $result;
		else return '';
	}	
}

if (! function_exists('getPendentSettingsById'))
{
	function getPendentSettingsById($id = '')
	{
		$CI = & get_instance();
		$CI->load->model('jewelrymodel');
		$result = $CI->jewelrymodel->getPendentSettingsById($id);
				
		if(isset($result))return $result;
		else return '';
	}	
} 

if (! function_exists('getEarringSettingsById'))
{
	function getEarringSettingsById($id = '')
	{
		$CI = & get_instance();
		$CI->load->model('jewelrymodel');
		$result = $CI->jewelrymodel->getEarringSettingsById($id);
				
		if(isset($result))return $result;
		else return '';
	}	
}


if (! function_exists('getAllSearchResultByKey'))
{
	function getAllSearchResultByKey($inputkey = '')
	{
		$CI = & get_instance();
		$CI->load->model('searchresultmodel');
		$result = $CI->searchresultmodel->getAllSearchResultByKey($inputkey);
				
		if(isset($result))return $result;
		else return '';
	}	
}


if (! function_exists('getStateList'))
{
	function getStateLists()
	{
		$CI = & get_instance();
		$CI->load->model('worldlocation');
		$result = $CI->worldlocation->getStateList();
		
		if(isset($result))return $result;		
		else return '';
	}	
} 

if (! function_exists('getWatchByID'))
{
	function getWatchByID($lot = '')
	{
		$CI = & get_instance();
		$CI->load->model('watchesmodel');
		$result = $CI->watchesmodel->getWatchByProductId($lot);
				
		if(isset($result))return $result;
		else return '';
	}	

}

if (! function_exists('getShippinginfo'))
{
	function getShippinginfo($orderid, $customerid)
	{
		$CI = & get_instance();
		$CI->load->model('Shoppingbasketmodel');
		$result = $CI->Shoppingbasketmodel->getShippinginfo($orderid, $customerid);
				
		if(isset($result))return $result;
		else return '';
	}	

} 
?>
