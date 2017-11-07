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


if (! function_exists('getState'))
{
	function getState($id = '')
	{
		if($id == '' || $id == 'other')
		 {
			return '';
		 }else{
			$CI = & get_instance();
			$CI->load->model('worldlocation');
			$state = $CI->worldlocation->getStateName($id);
			if(isset($state[0]['name']))
			return ucfirst($state[0]['name']);
			else return '';
		 }
	}	
}

if (! function_exists('calculateAge'))
{
	function calculateAge($birthday = '')
	{
		if($birthday == '')
		 {
			return 'Birth Day Not available';
		 }else{
					
				list($year,$month,$day) = explode("-",$birthday);
				$year_diff = date("Y") - $year;
				$month_diff = date("m") - $month;
				$day_diff = date("d") - $day;
				if ($month_diff < 0) $year_diff--;
				elseif (($month_diff==0) && ($day_diff < 0)) $year_diff--;
				$year_diff = $year_diff .' Years';
				if($year_diff <= 0) $year_diff = 'Please Asked Your Parent (What is your birthday?)';
				if($year_diff >300) $year_diff = 'Master ! What is your birthday?';
				 
				return $year_diff;
		 }
	}	
}

if (! function_exists('getusdate'))
{
	function getusdate($datetime = '')
	{
		if($datetime == '')
		 {
			return '00-00-0000';
		 }else{
				$datetime = substr($datetime, 0 , 11);	
				list($year,$month,$day) = explode("-",$datetime);
				return $month .' - ' . $day .' - ' . $year;
		 }
	}	
}

if (! function_exists('getouterurl'))
{
	function getouterurl($url = '')
	{
	  
		if($url == '')
		 {
			return '#';
		 }else{
				
				if ((strpos($url, 'http://') === false) && (strpos($url, 'https://') === false))
				$url = 'http://'.$url;
				return $url;
		 }
	}	
}


if (! function_exists('getBusinessIndustry'))
{
	function getBusinessIndustry($industryId = '')
	{
	  
		if($industryId == '')
		 {
			return '';
		 }else{
				
			$CI = & get_instance();
			$CI->load->model('commonmodel');
			$industry = $CI->commonmodel->getIndustryName($industryId);
			if(isset($industry[0]['name']))
			return ucfirst($industry[0]['name']);
			else return '';
		 }
	}	
}



if (! function_exists('makeOptionSelected'))
{
	function makeOptionSelected($selectHtml = '' , $selectedValue = '')
	{
	  
		if($selectHtml == '')
		 {
			return $selectHtml;
		 }else{
		 	$search =  'value="' .$selectedValue . '"';
		 	$replace =  'value="' .$selectedValue . '" selected';
		 	
			$selectHtml =   str_replace( $search , $replace , $selectHtml);
			return $selectHtml;
		 }
	}	
}



if (! function_exists('CategoryPath'))
{
	function CategoryPath($catid = '' , $navJsFunction = 'javascript:void(')
	{
	    $retHtml =  '';
		if($catid == '')
		 {
			return $retHtml;
		 }else{
		 	$CI = & get_instance();
			$CI->load->model('category');
			$category = $CI->category->getCategoryById($catid);
			$nav = ($navJsFunction == 'javascript:void(') ? '0' : $catid ;
			if($category['parent'] == '0' || $category['parent'] == $catid){
				$retHtml .=  '<a href="' . $navJsFunction. $nav .')" rel="category">' . ucfirst($category['catname']) . '</a>';
			}else{
				$category1 = $CI->category->getcategoryById($category['parent']);
				$retHtml .=  '<a href="' . $navJsFunction. $nav .')" rel="category">' . ucfirst($category1['catname']). '</a> <b>&nbsp;>&nbsp;</b> <a href="' . $navJsFunction. $nav .')" rel="category">' .ucfirst($category['catname']) . '</a>';
				
			}
			
			 
				
			
			return $retHtml;
		 }
	}	
}


if (! function_exists('postcategory'))
{
	function postcategory($cats = '' , $navJsFunction = 'javascript:void(')
	{
	    $retHtml =  '';
		if(trim($cats) == '')
		 {
			 $retHtml .= categoryPath('0') ;
		 }else{
		 	$CI = & get_instance();
			$CI->load->model('blogmodel');
			$catids  = split(',' , $cats);
			foreach ($catids as $catid){
				$category = $CI->blogmodel->getcategoryById($catid);
				$nav = ($navJsFunction == 'javascript:void(') ? '0' : $catid ;
				if($category['parent'] == '0' || $category['parent'] == $catid){
					$retHtml .=  '<a href="' . $navJsFunction. $nav .')" rel="category">' . ucfirst($category['catname']) . '</a> &nbsp; &nbsp;';
				}else{
					$category1 = $CI->blogmodel->getcategoryById($category['parent']);
					$retHtml .=  '<a href="' . $navJsFunction. $nav .')" rel="category">' . ucfirst($category1['catname']). '</a> <b>&nbsp;>&nbsp;</b> <a href="' . $navJsFunction. $nav .')" rel="category">' .ucfirst($category['catname']) . '</a> &nbsp; &nbsp;';
					
				}
			}
			
			 
				
			
			return $retHtml;
		 }
	}	
}




if (! function_exists('togglerow'))
{
	function togglerow($i = 0 , $rowclass = 'row')
	{
	    return ($i == 0) ? $rowclass . $i : $rowclass . '1';
	}	
}


if (! function_exists('getPostDay'))
{
	function getPostDay($datetime = '')
	{
	    if($datetime == '')
		 {
			return '';
		 }else{
				$datetime = substr($datetime, 0 , 11);	
				list($year,$month,$day) = explode("-",$datetime);
				return $day;
		 }
	}	
}



if (! function_exists('getPostMonth'))
{
	function getPostMonth($datetime = '')
	{
	   if($datetime == '')
		 {
			return '';
		 }else{
				$datetime = substr($datetime, 0 , 10);	
				list($year,$month,$day) = explode("-",$datetime);
				return date("M" , mktime(0, 0, 0, $month, $day, $year));

		 }
	}	
}


if (! function_exists('EventDate'))
{
	function EventDate($datetime = '')
	{
	   if($datetime == '')
		 {
			return '';
		 }else{
				$datetime = substr($datetime, 0 , 10);	
				list($year,$month,$day) = explode("-",$datetime);
				return date("M jS, Y" , mktime(0, 0, 0, $month, $day, $year));

		 }
	}	
}
 
			    


if (! function_exists('FriendRequest'))
{
	function FriendRequest($id = '')
	{
	   if($id == '')
		 {
			return '';
		 }else{
				 	$CI = & get_instance();
					$CI->load->model('user');
					$CI->load->model('friendmodel');
					$user 	         = $CI->session->userdata('user');
				 
					$profiledata = $CI->user->user_miniprofile($id);
					
					$userid = ($CI->session->isLoggedin()) ? $user->id : '';
					 
					if(isset($profiledata[0]['usertype']))
					{			
								$profile = $profiledata[0];
								
						        	switch ($profiledata[0]['usertype']){
				                		case 'golduser':
		                                        		$fanfriendlabel   = '  Fans';
		                                        		if($userid == $profile['id']) $addfanfriendlink = "&nbsp;&nbsp;[ It's You :) ]";
		                                        		else 
		                                        			$addfanfriendlink = ($CI->friendmodel->isFriendRequestExist($profile['id'] , $userid)) ? '&nbsp;&nbsp;Fan Request Pending..' : ' <div id="friendrequest">[ <a href="javascript:void(0)" onclick="AddFriendForm('. $profile['id'] .')"> Become a Fan </a>] </div>'  ;
		                                        		$viewallfanfriendlink = '<a href="javascript:void(0)" onclick="viewAllFriends(\'' . $profile['id'] . '\', \'0\',\'25\')"> View All Fan </a>';
		                                        		$nooffriends = $CI->friendmodel->noOfFriend($id);
		                                        		break;
		                                case 'user':
		                                        		$fanfriendlabel   = ' Friends';
		                                        		if($userid == $profile['id']) $addfanfriendlink = "&nbsp;&nbsp;[ It's You :) ]";
		                                        		else 
		                                        			$addfanfriendlink = ($CI->friendmodel->isFriendRequestExist($profile['id'], $userid)) ? '&nbsp;&nbsp;Friend Request Pending..' : ' <div id="friendrequest">[ <a href="javascript:void(0)" onclick="AddFriendForm('. $profile['id'] .')"> Become a Friend </a>] </div>' ;
		                                        		$viewallfanfriendlink = '<a href="javascript:void(0)" onclick="viewAllFriends(\'' . $profile['id'] . '\', \'0\',\'25\')"> View All Friends </a>';
		                                        		$nooffriends = $CI->friendmodel->noOfFriend($id);
		                                        		break;
		                                        		
				                		case 'admin': 	 
				                						$fanfriendlabel   = '';
				                						$addfanfriendlink = '';
		                                        		$viewallfanfriendlink = '';
		                                        		$nooffriends = '';
		                                        		break;
				                						
				                		default:        $fanfriendlabel   = '';
				                			            $addfanfriendlink = '';
		                                        		$viewallfanfriendlink = '';
		                                        		$nooffriends = '';
		                                        		break;
				                		
						     	}
						 $return  = '<div style="float:left" > <div id="nooffriends">'. $nooffriends . ' ' . $fanfriendlabel .'</div>';
						 if($nooffriends > 0)
						 $return  .= '<div class="floatl" style="margin-left:3px;"> [' .$viewallfanfriendlink  . '] </div> ';
						 $return  .=  $addfanfriendlink . '
						 <div class="clear"></div>
						</div>';
						 return  $return;
					}
					else return '';
		 	             
		 	
		 	
		 	
				

		 }
	}	
}




if (! function_exists('createsortlink'))
{
	function createsortlink($baselink = '' , $sortterms = '' , $orderby = '' , $extraquery = '' , $linktitle = 'click Here')
	{
	    $link = '';
	    if($orderby == $sortterms.' asc') $link = '<img src="asc.jpg" alt="^"><a href="'. $baselink . '/' .$sortterms .'%20desc'. $extraquery.'">' . $linktitle . '</a>';
	    elseif ($orderby == $sortterms.' desc')$link = '<img src="desc.jpg"><a href="'. $baselink . '/' .$sortterms .'%20asc'. $extraquery.'">' . $linktitle . '</a>';
	    else 
	    $link = '<a href="'. $baselink . '/' .$sortterms .'%20asc'. $extraquery.'">' . $linktitle . '</a>';
	    return $link;
	}	
}
 





if (! function_exists('getSkillName'))
{
	function getSkillName($id)
	{			
		$CI = & get_instance();		
		$CI->load->model('jobmodel');
		$skill = $CI->jobmodel->getSkillName($id);						
		return  (isset($skill[0]['name'])) ? $skill[0]['name'] : '';			
	}	
}

if (! function_exists('getCategoryById'))
{
	function getCategoryById($id)
	{			
		$CI = & get_instance();		
		$CI->load->model('categorymodel');
		$name = $CI->categorymodel->getCategoryById($id);							
		return  (isset($name[0]['catname'])) ?  $name[0]['catname'] : '';			
	}	
}

if (! function_exists('getAllSubCategory'))
{
	function getAllSubCategory($id)
	{			
		$CI = & get_instance();		
		$CI->load->model('categorymodel');
		$name = $CI->categorymodel->getAllSubCategory($id);		
		return  $name;
	}	
}

if (! function_exists('getCategorywiseJob'))
{
	function getCategorywiseJob($id)
	{			
		$CI = & get_instance();		
		$CI->load->model('categorymodel');
		$name = $CI->categorymodel->getCategorywiseJob($id);		
		return  (isset($name[0]['cnt'])) ?  $name[0]['cnt'] : '0';
	}	
}


if (! function_exists('getMinMaxWithShape'))
{
	function getMinMaxWithShape($shapeid = 'B' , $field = 'color', $isminmax = false)
	{			
		$CI = & get_instance();		
		$CI->load->model('adminmodel');
		$ret = $CI->adminmodel->getminmaxForShape($shapeid, $field, $isminmax);		
		return $ret;
	}	
}

if (! function_exists('PricescopeExport'))
{
	function PricescopeExport($shapeid = 'B')
	{			
		$CI = & get_instance();		
		$CI->load->model('adminmodel');
		$ret = $CI->adminmodel->PricescopeExport($shapeid);		
		return $ret;
	}	
}


 



 




?>
