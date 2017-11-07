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

function getShapeName($shapelist)
{
	$shapename = '';
	$shapestr = '';
	if(($this->session->userdata('shape'))) $shapes =  $this->session->userdata('shape');
	
	$shapelist = explode('.' , $shapes);
	
	if(sizeof($shapelist>0)){
		foreach ($shapelist as $val){
			if($val!=''){
				switch ($val){
					case 'B':
				  		$shapename = 'Round';
				  		break;
				  	case 'PR':
				  		$shapename = 'Princess';
				  		break;
				  	case 'R':
				  		$shapename = 'Radiant';
				  		break;
				  	case 'E':
				  		$shapename = 'Emerald';
				  		break;
				  	case 'AS':
				  		$shapename = 'Ascher';
				  		break;
				  	case 'O':
				  		$shapename = 'Oval';
				  		break;
				  	case 'M':
				  		$shapename = 'Marquise';
				  		break;
				  	case 'P':
				  		$shapename = 'Pear shape';
				  		break;
				  	case 'H':
				  		$shapename = 'Heart';
				  		break;
				  	case 'C':
				  		$shapename = 'Cushion';
				  		break;								  	
				  	default:
				  		$shapename = '';
				  		break;
				}
				$shapestr .= $shapename;
			}
		}
	}
	return $shapestr;
}


?>
