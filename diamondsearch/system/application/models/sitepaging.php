<?php 

class Sitepaging extends Model {
	function __construct(){
		parent::Model();
	}
	
		
	function getPageing($total = 0, $case = '', $start =0 , $orderby ='' , $rp = 10, $extra = '' )
	{
		$pagelink = '';
		
		switch ($case){
			case 'rings':
				            if($total>0)$pagingtext = '<br>Total: '. $total . ' Rings Found';
							else $pagingtext = '<br>No Rings Found';

							$pconfig['uri_segment'] = 13;
							$pconfig['functionname'] 	= 'getringresults';
							$pconfig['num_links']   = 2;
							$pconfig['base_url'] 	= 'javascript:void(0); return false;';
							$pconfig['cur_page']   = ($this->session->userdata('randomprofilestart'));
							
				break;
			case 'sidestones':
							if($total>0)$pagingtext = '<br>Total: '. $total . ' Sidestones Found';
							else $pagingtext = '<br>No Sidestones Found';

							$pconfig['uri_segment'] = 4;
							$pconfig['functionname'] 	= 'getsidestoneresult';
							$pconfig['num_links']   = 2;
							$pconfig['base_url'] 	= 'javascript:void(0)';
							$pconfig['cur_page']   = ($this->session->userdata('randomprofilestart'));
				break;
			case 'tesimonial':
							if($total>0)$pagingtext = '<br>Total: '. $total . ' Testimonials Found';
							else $pagingtext = '<br>No Tesimonials Found';

							$pconfig['uri_segment'] = 3;
							$pconfig['functionname'] 	= 'gettestimonialresult';
							$pconfig['num_links']   = 2;
							$pconfig['base_url'] 	= 'javascript:void(0)';
							$pconfig['cur_page']   = ($this->session->userdata('randomprofilestart'));
				break;
			case 'watches':
				            if($total>0)$pagingtext = '<br>Total: '. $total . ' Watches Found';
							else $pagingtext = '<br>No Watches Found';

							$pconfig['uri_segment'] = 13;
							$pconfig['functionname'] 	= 'getwatchresults';
							$pconfig['num_links']   = 2;
							$pconfig['base_url'] 	= 'javascript:void(0); return false;';
							$pconfig['cur_page']   = ($this->session->userdata('randomprofilestart'));
							
				break;
			 
			default:
				$total = 0;
				$pagingtext = '';
				$pconfig['per_page'] 	= '16';
				$pconfig['base_url'] 	= 'javascript:void(0)';
				break;
		}
			 
		 	
		 	    $pconfig['per_page'] 	= $rp;
		 	    $pconfig['orderby'] 	=  $orderby ;
				$pconfig['total_rows'] 	= $total;
								$pconfig['start']   = $start;
				//echo $pconfig['start'];
//exit;
				//$pconfig['cur_tag_open'] = '<li>';
				//$pconfig['cur_tag_close'] = '</li>';
				
				$pconfig['first_tag_open'] = '<li>';
				$pconfig['first_tag_close'] = '</li>';
				$pconfig['last_tag_open'] = '<li>';
				$pconfig['last_tag_close'] = '</li>';
				$pconfig['cur_tag_open'] = '<li class="active">';
				$pconfig['cur_tag_close'] = '</li>';
				$pconfig['next_tag_open'] = '<li>';
				$pconfig['next_tag_close'] = '</li>';
				$pconfig['prev_tag_open'] = '<li>';
				$pconfig['prev_tag_close'] = '</li>';
				$pconfig['num_tag_open'] = '<li>';
				$pconfig['num_tag_close'] = '</li>';
				//echo("<pre>"); 
				//print_r($pconfig);
				//echo("<pre>"); 
				$this->pagination->initialize($pconfig);
				$pagelink = '    <div class="clear"></div>
				 					<div class="pagingdiv">
														'. $pagingtext . '<ul>'
															.$this->pagination->create_links()
															.'</ul>
															 
												 </div>
								   <div class="clear"></div>
								   
								  ';
				return $pagelink;
				
 	}
}

?>