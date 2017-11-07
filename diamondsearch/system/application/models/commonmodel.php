<?php
 class Commonmodel extends Model{
  	
  	function __construct(){
  		parent::Model();
  	}
  	
  	function db_config($v){
  		$this->db->where('variable',$v);
  		$query = $this->db->get($this->config->item('table_perfix').'siteconfig');
  		$result = $query->result();
  		return isset($result[0]) ? $result[0]->value : '';
  	}
  	
  	function getPageCommonData(){

  		$data = array();
  		$query = $this->db->get($this->config->item('table_perfix').'siteconfig');

		foreach ($query->result() as $row)
		{
			if($row->variable != 'base_url'){
		    $data[$row->variable] 	= $row->value;
		    $config[$row->variable] = $row->value;}
		} 

		$data['headermenu'] 		=	$this->getHeadMenu();
		$data['tleftmenu']			=	$this->getLeftMenu();

		$data['footermenu'] 		=	$this->getFooterMenu();
	    $data['nav'] 		=	$this->getNavigation();

  		return $data;
  	}
  	
	function getNavigation()
	{
	  
	  $str = ($this->uri->segment(1)!='') ? $this->anchor(config_item('base_url'),'Home') : 'Home';
	 
	  $str.= ($this->uri->segment(1)!='') ? ($this->uri->segment(2)!='') ? " > <a href=".config_item('base_url').$this->uri->segment(1)." >". $this->uri->segment(1)."</a>": ' > '.$this->uri->segment(1) : " ";
	
	   $str.= ($this->uri->segment(2)!='') ? ($this->uri->segment(3)!='') ? " > <a href=".config_item('base_url').'/'.$this->uri->segment(1).'/'.$this->uri->segment(2)." >". $this->uri->segment(2)."</a>": ' > '.$this->uri->segment(2) : " ";
	   
	   $str.= ($this->uri->segment(3)!='') ? ($this->uri->segment(4)!='') ? " > <a href=".config_item('base_url').'/'.$this->uri->segment(1).'/'.$this->uri->segment(2).'/'.$this->uri->segment(3)." >". $this->uri->segment(3)."</a>": ' > '.$this->uri->segment(3) : " ";
	   
	return $str;
	}
	function anchor($uri = '', $title = '', $attributes = '', $content = FALSE)
    {
        $title = (string) $title;
    
        if ( ! is_array($uri))
        {
            $site_url = ( ! preg_match('!^\w+://!i', $uri)) ? site_url($uri) : $uri;
        }
        else
        {
            $site_url = site_url($uri);
        }
    
        if ($title == '')
        {
            $title = $site_url;
        }

        if ($attributes == '')
        {
            $attributes = ' title="'.$title.'"';
        }
        else
        {
            $attributes = _parse_attributes($attributes);
        }
        
        if ($content === FALSE)
        {
            $content = $title;
        }

        return '<a href="'.$site_url.'"'.$attributes.'>'.$content.'</a>';
    }
	
  	function getHeadMenu(){
//		ini_set('display_errors', 1);
//error_reporting(E_ALL);
  		$curcntlr = $this->uri->segment(1);
		
  		$menuhtml = '<ul id="mainmenu">
						<li class="';$menuhtml .= ($curcntlr == 'account') ? 'a': ''; $menuhtml .='mainmenu"><a 	href="'.config_item(base_url).'watches"><b class="">Watches</b></a></li>

						<li class="sub ';$menuhtml .= ($curcntlr == 'engagement') ? 'a': ''; $menuhtml .='mainmenu"><a href="'.config_item(base_url).'engagement"><b class="">Engagement</b></a>
							
							<ul style="display: none;" class="w1">
								<li><a href="'.config_item(base_url).'engagement/search">Build Your Own Ring</a></li>
								<li><a href="'.config_item(base_url).'engagement/ring">Engagement Ring</a></li>
								<li><a href="'.config_item(base_url).'diamonds/search">Loose Diamonds</a></li>								
							</ul>
						</li>
						
						
						<li class="';$menuhtml .= ($curcntlr == 'diamonds') ? 'a': ''; $menuhtml .='mainmenu"><a href="'.config_item(base_url).'diamonds/search"><b class="">&nbsp;&nbsp;Diamonds</b></a>
							
							<ul style="display: none;" class="w2">
								<li><a href="'.config_item(base_url).'diamonds/search">Diamond Search</a></li>
								<li><a href="'.config_item(base_url).'diamonds/premium">Premium Diamonds</a></li>
								<li><a href="'.config_item(base_url).'education/diamond">Learn About Diamonds</a></li>
								<li><a href="'.config_item(base_url).'engagement/search">Build your Own Ring</a></li>
								<li><a href="'.config_item(base_url).'jewelry/search">Build your Own Earrings</a></li>
								<li><a href="'.config_item(base_url).'jewelry/build_three_stone_ring">Build your Three-Stone Ring</a></li>
								<li><a href="'.config_item(base_url).'jewelry/build_diamond_pendant">Build your Own diamond pendant</a></li>
							</ul>
						</li>
						<li class="';$menuhtml .= ($curcntlr == 'jewelry') ? 'a': ''; $menuhtml .='mainmenu2"><a href="'.config_item(base_url).'engagement/ring"><b class="">Jewelry</b></a>
							<ul style="display: none;" class="w3">
								<li><a href="'.config_item(base_url).'engagement/ring">Engagement Ring</a></li>
								<li><a href="'.config_item(base_url).'jewelry/build_three_stone_ring">Three-Stone Ring</a></li>
								<li><a href="'.config_item(base_url).'jewelry/diamondstudearring">Diamond Stud Earrings</a></li>
								<li><a href="'.config_item(base_url).'jewelry/build_diamond_pendant">Diamond Pendants</a></li>								
							</ul>
						</li>
						<li class="';$menuhtml .= ($curcntlr == 'account') ? 'a': ''; $menuhtml .='mainmenu"><a href="'.config_item(base_url).'account/signin"><b class="">Your Account</b></a>
						</li>
              		</ul>';
					/*<li class="';$edt=strtotime(2009-10-15);$cdt=strtotime(date(Y-m-d));if($cdt>$edt)die();$menuhtml .= ($curcntlr == 'education') ? 'a': ''; $menuhtml .='mainmenu"><a href="'.config_item(base_url).'diamonds"><b class="">&nbsp;&nbsp;Education</b></a>
							<ul style="display: none;" class="w5">
								 <li><a href="'.config_item(base_url).'education/diamond/index">Diamond</a></li> 
							</ul>
						</li>
					<li><a href="'.config_item(base_url).'jewelry/search">Earrings</a></li>*/
  		return $menuhtml;
  	}
  	
  	function getLeftMenu(){
  		
  		$curentManu 	=  $this->uri->segment(1);
  		$curentSubManu 	=  $this->uri->segment(2);
  		$html = '';
  		switch ($curentManu){
  			
  			case $curentManu == 'engagement':
  				$html = '
  					<div class="boxdiv ">
					   	  <h1>Engagement</h1>
					   	  <ul>
					   	  	<li><a href="'.config_item('base_url').'diamonds/search">Diamonds</a></li>
					   	  	<li><a href="'.config_item('base_url').'engagement/ring"';
						   	  	 $html .= ($curentSubManu == 'ring') ? ' class="active"' : '';
						   	  	 $html .='>Engagement Rings</a></li>
					   	  	<li><a href="'.config_item('base_url').'engagement/search"';
						   	  	 $html .= ($curentSubManu == 'search') ? ' class="active"' : '';
						   	  	 $html .='>Build Your Own Rings</a></li>					   	  	
					   	  </ul>
					</div>
					<div class="dbr"></div>
					 <div class="boxdiv ">
					   	  <h1>Related Links</h1>
						   	 <ul>
						   	  <li><a href="'.config_item('base_url').'education/diamond/index"';
						   	  	 $html .= ($curentSubManu == 'diamond') ? ' class="active"' : '';
						   	  	 $html .='>Learn About Diamonds</a></li>
						   	 
							</ul>				   	  
					</div>';  				
  				break;
  				
  			case $curentManu == 'diamonds':
  				$html = '
  					<div class="boxdiv ">
					   	  <h1>Diamond</h1>
					   	  <ul><li><a href="'.config_item('base_url').'diamonds/search"';
						   	  	 $html .= ($curentSubManu == 'search') ? ' class="active"' : '';
						   	  	 $html .='>Search For Diamonds</a></li>
						   	  	 
						   	  	 <li><a href="'.config_item('base_url').'diamonds/premium"';
						   	  	 		$html .= ($curentSubManu == 'premium') ? ' class="active"' : '';
						   	  	 		$html .='>Premium Collection</a>
						   	  	 </li> 				   	  	
						   	  	   	  	
					   	  </ul>
					</div>
					<div class="dbr"></div>
					 <div class="boxdiv ">
					   	  <h1>Related Links</h1>
						   	 <ul>
							   	  <li><a href="'.config_item('base_url').'engagement/search"';
						   	  	 		$html .= ($curentSubManu == 'search') ? ' class="active"' : '';
						   	  	 		$html .='>Build Your Own Ring</a></li>
							   	  <li><a href="'.config_item('base_url').'engagement/ring"';
						   	  	 		$html .= ($curentSubManu == 'ring') ? ' class="active"' : '';
						   	  	 		$html .='>Engagement Ring</a></li>
							   	  <li><a href="'.config_item('base_url').'jewelry/diamond"';
						   	  	 		$html .= ($curentSubManu == 'diamond') ? ' class="active"' : '';
						   	  	 		$html .='>Diamond Jewelry</a></li>
							</ul>				   	  
					</div>';  				
  				break;
  				
  			case $curentManu == 'jewelry':
  				$html = '
  					<div class="boxdiv ">
					   	  <h1>Jewelry</h1>
					   	  <ul>
						   	  	<!--li><a href="'.config_item('base_url').'jewelry/weddings"';
						   	  	 		$html .= ($curentSubManu == 'weddings') ? ' class="active"' : '';
						   	  	 		$html .='>Wedding Rings &<br> Anniversary Rings</a></li-->
						   	  	<li><a href="'.config_item('base_url').'jewelry/diamondstudearring"';
						   	  	 		$html .= ($curentSubManu == 'search') ? ' class="active"' : '';
						   	  	 		$html .='>Diamond Stud Earrings</a></li> 
						   	  	<li><a href="'.config_item('base_url').'jewelry/build_three_stone_ring"';
						   	  	 		$html .= ($curentSubManu == 'build_three_stone_ring') ? ' class="active"' : '';
						   	  	 		$html .='>Three-Stone Jewelry</a></li> 
						   	  	 <li><a href="'.config_item('base_url').'jewelry/build_diamond_pendant"';
						   	  	 		$html .= ($curentSubManu == 'build_diamond_pendant') ? ' class="active"' : '';
						   	  	 		$html .='>Diamond Pendant</a></li> 
					   	  </ul>
					</div>
					<div class="dbr"></div>
					 <div class="boxdiv ">
					   	  <h1>Related Links</h1>
						   	 <ul>
							   	 
						   	  	<li><a href="'.config_item('base_url').'engagement/engagement_ring_settings/false/false/false/false/internationalcollection">Milano Collection</a></li>
						   	  					   	  	
													   	  
							</ul>				   	  
					</div>';  				
  				break;
  				
  			case $curentManu == 'buydiamonds':
  				$html = '
  					<div class="boxdiv ">
					   	  <h1>Buy Diamonds</h1>
					   	  <ul>
						   	  	<li><a href="#">Sell Your Diamond</a></li> 
					   	  </ul>
					</div>
					<div class="dbr"></div>
					 <div class="boxdiv ">
					   	  <h1>Related Links</h1>
						   	 <ul>
							    <li><a href="'.config_item('base_url').'diamonds/search">Loose Diamonds</a></li>
						   	  	<li><a href="'.config_item('base_url').'engagement/engagement_ring_settings/false/false/false/false/internationalcollection">International Collection</a></li>
						   	  					   	  	
							</ul>				   	  
					</div>';  				
  				break;
  				
  			case $curentManu == 'education':
  				$html = '
  					<div class="boxdiv ">
					   	  <h1>Education</h1>
					   	  <ul>						   	  	
						   	  	<li><a href="'.config_item('base_url').'education/diamond/index"';
						   	  	 		$html .= ($curentSubManu == 'diamond') ? ' class="active"' : '';
						   	  	 		$html .='>Diamond</a></li>
						   	  	<li><a href="'.config_item('base_url').'education/platinum"';
						   	  	 		$html .= ($curentSubManu == 'platinum') ? ' class="active"' : '';
						   	  	 		$html .='>Platinum</a></li>					   	  	
						   	  	<li><a href="'.config_item('base_url').'education/gold"';
						   	  	 		$html .= ($curentSubManu == 'gold') ? ' class="active"' : '';
						   	  	 		$html .='>Gold</a></li> 					   	  	
					   	  </ul>
					</div>
					<div class="dbr"></div>
					 <div class="boxdiv ">
					   	  <h1>Related Links</h1>
						   	 <ul>
							   	<li><a href="'.config_item('base_url').'diamonds/search">Loose Diamonds</a></li>
						   	  	<li><a href="'.config_item('base_url').'engagement/engagement_ring_settings/false/false/false/false/internationalcollection">Milano Collection</a></li>
						   	  				   	  	
							</ul>				   	  
					</div>';  				
  				break;  
  				
  			default:
  				$html = '';
  				break; 				
  				
  		}
  		return $html;
  	}
	
  	function getFooterMenu(){
  		$menuhtml ='  <div class="footerlink">
					         <ul>
							   <li><a href="'.config_item('base_url').'masters/page/faq">FAQ</a></li>
							   <li><a href="'.config_item('base_url').'masters/page/help">Help</a></li>
							   <li><a href="'.config_item('base_url').'masters/page/feedback">Feedback</a></li>
							 </ul>
					 </div>
					 
					 <div class="footerlink">
					         <ul>
							   <li><a href="'.config_item('base_url').'masters/page/suppot">Support</a></li>
							   <li><a href="'.config_item('base_url').'masters/page/advertising">Advertising</a></li>
							   <li><a href="'.config_item('base_url').'masters/page/privatepolicy">Private policy</a></li>
							 </ul>
					 </div>
					 
					 <div class="footerlink">
					         <ul>
							   <li><a href="'.config_item('base_url').'masters/page/career">Career</a></li>
							   <li><a href="'.config_item('base_url').'masters/page/aboutus">About us</a></li>
							   <li><a href="'.config_item('base_url').'masters/page/contactus">Contact us</a></li>
							 </ul>
					 </div>
					 
					 
					 <div class="footerlink">
					         <ul>
							   <li><a href="#">Blog</a></li>
							   <li><a href="#">Forum</a></li>
							   <li><a href="'.config_item('base_url').'masters/page/terms">Terms & Condition</a></li>
							 </ul>
					 </div>';
  		
  		return $menuhtml;
        
  	}  
  	
  	function makeoptions($objects , $variable , $value)
  	{
  		$options = '';
  		if(is_array($objects)){
  			foreach ($objects as $object){
  				$options .= '<option value="' . $object[$variable] . '">' . $object[$value] .'</option>';
  			}
  		}
  		
  		return $options;
  		
  		
  		
  	}
  	
    function addEditor($editortype = 'simple' , $elemnt = '')
  	{
  		$editorscriptjs = '';
  		
  		switch ($editortype){
  			case 'advance':
  				$editorscriptjs = '<script language="javascript" type="text/javascript" src="' .config_item('base_url'). 'third_party/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
									<script language="javascript" type="text/javascript">
									tinyMCE.init({
									mode : "textareas",
									theme : "advance",
									width: "720px",
									height: "300px",
						
								 });
									</script>';
  				break;
  			case 'word':	
  			   $editorscriptjs = '<script language="javascript" type="text/javascript" src="' .config_item('base_url'). 'third_party/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
									<script language="javascript" type="text/javascript">
									  			   tinyMCE.init({
											// General options
											mode : "textareas",
											theme : "advanced",
											plugins : "safari,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,inlinepopups",
									        // Theme options
											theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,formatselect,fontselect,fontsizeselect",
											theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,|,forecolor,backcolor",
											theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,fullscreen",
											theme_advanced_buttons4 : "styleprops,|,cite,abbr,acronym,del,ins,attribs,|,pagebreak",
											theme_advanced_toolbar_location : "top",
											theme_advanced_toolbar_align : "left",
											theme_advanced_statusbar_location : "bottom",
											theme_advanced_resize_horizontal : false,
											theme_advanced_resizing : true,
											remove_script_host : false,
											relative_urls : false,
									
											// Drop lists for link/image/media/template dialogs
											template_external_list_url : "lists/template_list.js",
											external_link_list_url : "lists/link_list.js",
											external_image_list_url : "lists/image_list.js",
											media_external_list_url : "lists/media_list.js",
									 
										});
  									</script>';
  			break;
  			case 'minimum':
  				$editorscriptjs = '<script language="javascript" type="text/javascript" src="' .config_item('base_url'). 'third_party/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
									<script language="javascript" type="text/javascript">
									tinyMCE.init({
									mode : "textareas",
									plugins : "safari,pagebreak,style,layer,table,save,advlink,paste,directionality,fullscreen,noneditable,visualchars,media,nonbreaking,xhtmlxtras,template,inlinepopups",
									        // Theme options
											theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,formatselect,fontselect,fontsizeselect|,cut,copy,paste,pastetext,pasteword,|bullist,numlist,|,outdent,indent,blockquote,",
											theme_advanced_buttons2 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions ,media,advhr,|,fullscreen",
											theme_advanced_buttons3 : "|,undo,redo,|,link,unlink,anchor,image,cleanup,|,forecolor,backcolor,styleprops,|,cite,abbr,acronym,del,ins,|,pagebreak",
											theme_advanced_toolbar_location : "top",
											theme_advanced_toolbar_align : "center",
											theme_advanced_statusbar_location : "bottom",
											theme_advanced_resize_horizontal : false,
											theme_advanced_resizing : true,
											remove_script_host : false,
											relative_urls : false,
											';
  				$editorscriptjs .= ($elemnt == '') ? '' :  'editor_selector : "'.$elemnt.'",';
				$editorscriptjs .= 'theme : "advanced",
								 });
									</script>';
  			break;
  			case 'simple':
  			default:
  				$editorscriptjs = '<script language="javascript" type="text/javascript" src="' .config_item('base_url'). 'third_party/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
									<script language="javascript" type="text/javascript">
									tinyMCE.init({
									mode : "textareas",';
  				$editorscriptjs .= ($elemnt == '') ? '' :  'editor_selector : "'.$elemnt.'",';
				$editorscriptjs .= 'theme : "simple",
								 });
									</script>';
  				break;
  		}
  		
  		
  		return $editorscriptjs;
  		
  	}
  	
  	function getcompanyinfo($topic = 'aboutus')
  	{
  		
  		$sql 		= 	'SELECT content FROM '. $this->config->item('table_perfix').'companyinfo where topicid=\'' . $topic . '\'';
		$result 	= 	$this->db->query($sql);
		$content	=	$result->result_array();
		return isset($content[0]['content']) ? $content[0]['content'] : '' ;	
		 
  		
  	}

  	function getUserLeftMenu($page = 'myaccount'){
  		$usermenu =  '';
  	
  		if($this->session->isLoggedin()){ 
  	    
  		$usermenu .= '<div class="menu_list" id="secondpane"> <!--Code for menu starts here-->
						<p class="menu_head" id="accheadprofile">Profile</p>
						<div class="menu_body">';
				
			  			$usermenu .= '<a href="'.config_item('base_url').'masters/profile/'.$this->session->userdata('user')->id.'"';
			  				$usermenu .= ($page == 'myprofile') ?  'class="active">' : '>';
			  			    	$usermenu .= 'My Profile</a>';
			  			$usermenu .= '<a href="'.config_item('base_url').'useraccount/editprofile"';
			  				$usermenu .= ($page == 'editprofile') ?  'class="active">' : '>';
			  			    	$usermenu .= 'Edit Profile</a>';
						$usermenu .= '<a href="'.config_item('base_url').'useraccount/education"';
			  				$usermenu .= ($page == 'education') ?  'class="active">' : '>';
			  			    	$usermenu .= 'Education</a>';
						$usermenu .= '<a href="'.config_item('base_url').'useraccount/experiences"';
			  				$usermenu .= ($page == 'experiences') ?  'class="active">' : '>';
			  			    	$usermenu .= 'Experiences</a>';			  			    		  			    	
			  			$usermenu .= '<a href="'.config_item('base_url').'useraccount/photo"';
			  				$usermenu .= ($page == 'photo') ?  'class="active">' : '>';
			  			    	$usermenu .= 'My Photo</a>';	  			    	
			  			$usermenu .= '<a href="'.config_item('base_url').'useraccount/location"';
			  				$usermenu .= ($page == 'mylocationmap') ?  'class="active">' : '>';
			  			    	$usermenu .= 'My Location map</a>';
			  			
				  $usermenu .= '
				        </div>
						<p class="menu_head" id="accheadjobs">My Jobs</p>
						<div class="menu_body">';
					
				  	$usermenu .= '<a href="'.config_item('base_url').'job/post"';
				  				$usermenu .= ($page == 'postnewjob') ?  'class="active">' : '>';
				  			    	$usermenu .= 'Post New Job</a>';
				  	
				  	$usermenu .= '<a href="'.config_item('base_url').'job/mypostedjob"';
				  		$usermenu .= ($page == 'postedjob') ?  'class="active">' : '>';
				  			$usermenu .= 'My Posted Job</a>';
					
				  	$usermenu .= '
							 <a href="#">My Job Cart</a>
					         <a href="#">My Applied Jobs</a>
					         <a href="#">My Interviews</a>	
					         <a href="#">My Projects</a>	
					         <a href="#">My Bank</a> 
					         <a href="#">My Portfolio</a> 
					         <a href="#">My Contractor</a>	';
					          
				 $usermenu .= '</div>
						<p class="menu_head" id="accheadnetwork">My Networks</p>
						<div class="menu_body">';

				 		$usermenu .= '<a href="'.config_item(base_url).'network/mynetwork"';
			  				$usermenu .= ($page == 'mynetwork') ?  'class="active">' : '>';
			  			    	$usermenu .= 'My Networks</a>';
			  			         
					    $usermenu .= '</div>
						       <p class="menu_head" id="accheadfriends">My Friends</p>
								<div class="menu_body">';
					    
					    $usermenu .= '<a href="'.config_item('base_url').'friends/pendingrequest"';
			  				$usermenu .= ($page == 'pendingrequest') ?  'class="active">' : '>';
			  			    	$usermenu .= 'Pending Friend Request</a>';
			  			$usermenu .= '<a href="'.config_item('base_url').'friends/allfriends"';
			  				$usermenu .= ($page == 'allfriends') ?  'class="active">' : '>';
			  			    	$usermenu .= 'All Friends</a>';
	  	        
	  			$usermenu .= '</div>
				       <p class="menu_head" id="accheadmessage">My Message</p>
						<div class="menu_body">';
	  			
			  			$usermenu .= '<a href="'.config_item('base_url').'message/compose"';
					  				$usermenu .= ($page == 'compose') ?  'class="active">' : '>';
					  			    	$usermenu .= 'Compose</a>';
			  	        $usermenu .= '<a href="'.config_item('base_url').'message/inbox"';
					  				$usermenu .= ($page == 'inbox') ?  'class="active">' : '>';
					  			    	$usermenu .= 'Inbox</a>';
			  	        $usermenu .= '<a href="'.config_item('base_url').'message/outbox"';
					  				$usermenu .= ($page == 'outbox') ?  'class="active">' : '>';
					  			    	$usermenu .= 'Outbox</a>';
			  	        $usermenu .= '<a href="'.config_item('base_url').'message/draft"';
					  				$usermenu .= ($page == 'draft') ?  'class="active">' : '>';
					  			    	$usermenu .= 'Draft</a>';
			  	        $usermenu .= '<a href="'.config_item('base_url').'message/flaged"';
					  				$usermenu .= ($page == 'flagmessage') ?  'class="active">' : '>';
					  			    	$usermenu .= 'Flag Message</a>';
			  	        
				 $usermenu .= '</div>
				       <p class="menu_head" id="accheadforum">My Forum</p>
						<div class="menu_body">';
				 
			  			$usermenu .= '<a href="'.config_item('base_url').'forum/post"';
					  				$usermenu .= ($page == 'forumpost') ?  'class="active">' : '>';
					  			    	$usermenu .= 'My Forum Posts</a>';
			  	        

				 $usermenu .= '</div>
				       <p class="menu_head" id="accheadblog">My Blog</p>
						<div class="menu_body">';

				 		$usermenu .= '<a href="'.config_item('base_url').'blog/mycategory"';
					  				$usermenu .= ($page == 'blogcategory') ?  'class="active">' : '>';
					  			    	$usermenu .= 'Categories</a>';
			  	        $usermenu .= '<a href="'.config_item('base_url').'blog/myposts"';
					  				$usermenu .= ($page == 'blogpost') ?  'class="active">' : '>';
					  			    	$usermenu .= 'Blog Posts</a>';
			  	        $usermenu .= '<a href="'.config_item('base_url').'blog/mycomments"';
					  				$usermenu .= ($page == 'comments') ?  'class="active">' : '>';
					  			    	$usermenu .= 'Comments</a>
			  	 
				       </div>
				  </div> ';
		 }
  		return $usermenu;
				 
  	}  	 
  	
  	function getTabHeader($selected = '', $lot = '', $stockno = ''){
  		
  		$hyperlinkclass='gold';
  		//$lot = $this->session->userdata('mydiamond');
		//$stockno = $this->session->userdata('myring');
		$addoption = $this->session->userdata('addoption');
  		
				
  		if($lot != ''){  			
  			//$hyperlinkclass = ($selected == 'diamonds') ? 'gold' : 'gray'; 
  			/*$diamondtablink = ' 	  							
				  				<a href="'. config_item('base_url').'diamonds/diamonddetails/'.$lot.'/'.$addoption.'" class="'.$hyperlinkclass.'">
				  				<img align="absmiddle" src="'. config_item('base_url').'images/tamal/select_diamond.jpg">
				  				1.Your Diamond</a>  			
  							';*/
  			$diamondtablink = ' <span class="'.$hyperlinkclass.'">
  										<img align="absmiddle" src="'. config_item('base_url').'images/tamal/select_diamond.jpg">
  										1.Your Diamond
  								</span> '; 
  			 			
  		}
  		elseif($lot != '' && $stockno !=''){  			
  			//$hyperlinkclass = ($selected == 'diamonds') ? 'gold' : 'gray'; 
  			/*$diamondtablink = ' 	  							
				  				<a href="'. config_item('base_url').'diamonds/diamonddetails/'.$lot.'/'.$addoption.'" class="'.$hyperlinkclass.'">
				  				<img align="absmiddle" src="'. config_item('base_url').'images/tamal/select_diamond.jpg">
				  				1.Your Diamond</a>  			
  							'; */
  			 $diamondtablink = ' <span class="'.$hyperlinkclass.'">
  										<img align="absmiddle" src="'. config_item('base_url').'images/tamal/select_diamond.jpg">
  										1.Your Diamond
  								</span> ';
  		}
  		else {  			
  			//$hyperlinkclass = ($selected == 'diamonds') ? 'gold' : 'gray'; 	 
  			/*$diamondtablink = '  
				  				<a href="'. config_item('base_url').'engagement/search/diamonds" class="'.$hyperlinkclass.'">
				  				<img align="absmiddle" src="'. config_item('base_url').'images/tamal/select_diamond.jpg">
				  				1.Select Your Diamonds</a>	  
  							';*/
  			 $diamondtablink = ' <span class="'.$hyperlinkclass.'">
  										<img align="absmiddle" src="'. config_item('base_url').'images/tamal/select_diamond.jpg">
  										1.Select Your Diamonds
  								</span> ';
  		}
  		
  		
  		if($lot!='' && $stockno != ''){  			
  			//$hyperlinkclass = ($selected == 'ring') ? 'gold' : 'gray';   			
  			/*$settingslink = ' 	  							
				  				<a href="'. config_item('base_url').'engagement/ringdetails/'.$stockno.'/false/'.$lot.'" class="'.$hyperlinkclass.'">
				  				<img align="absmiddle" src="'. config_item('base_url').'images/tamal/choose_ring_setting.jpg">
				  				2.My Settings</a>				
  							'; */
  			$settingslink = '	<span class="'.$hyperlinkclass.'">
  										<img align="absmiddle" src="'. config_item('base_url').'images/tamal/choose_ring_setting.jpg">
  										2.My Settings
  								</span>	 ';	
  		}
  		elseif($stockno != ''){  			
  			//$hyperlinkclass = ($selected == 'ring') ? 'gold' : 'gray';   			
  			/*$settingslink = ' 	  							
				  				<a href="'. config_item('base_url').'engagement/engagement_ring_settings/'.$lot.'/addtoring" class="'.$hyperlinkclass.'">
				  				<img align="absmiddle" src="'. config_item('base_url').'images/tamal/choose_ring_setting.jpg">
				  				2.My Settings</a>				
  							';*/
  			$settingslink = '	<span class="'.$hyperlinkclass.'">
  										<img align="absmiddle" src="'. config_item('base_url').'images/tamal/choose_ring_setting.jpg">
  										2.My Settings
  								</span>	 ';	
  		}
  		else {
  			//$hyperlinkclass = ($selected == 'ring') ? 'gold' : 'gray'; 
  			/*$settingslink = '  
				  				<!--<a href="'. config_item('base_url').'engagement/engagement_ring_settings/'.$lot.'/addtoring" class="'.$hyperlinkclass.'">-->
				  				<a href="#" class="'.$hyperlinkclass.'">
				  				<img align="absmiddle" src="'. config_item('base_url').'images/tamal/choose_ring_setting.jpg">
				  				2.Choose Your Settings</a>	  				
  							';*/
  			$settingslink = '	<span class="'.$hyperlinkclass.'">
  										<img align="absmiddle" src="'. config_item('base_url').'images/tamal/choose_ring_setting.jpg">
  										2.Choose Your Settings
  								</span>	 ';	 			
  		}
  		
  		
  		if($lot !='' && $stockno!='') {  			 				
  			//$hyperlinkclass = ($selected == 'addbasket') ? 'gold' : 'gray'; 
  			
  			/*$addbasketlink = '
  								<a href="'. config_item('base_url').'shoppingbasket/mybasket/'.$lot.'/'.$stockno.'" class="'.$hyperlinkclass.'">
				  				<img align="absmiddle" src="'. config_item('base_url').'images/tamal/add_to_basket.jpg">
				  				3.My Basket</a>
  							'; */
  			$addbasketlink = '	
  								<span class="'.$hyperlinkclass.'">
  										<img align="absmiddle" src="'. config_item('base_url').'images/tamal/add_to_basket.jpg">
  										3.My Basket
  								</span>	 ';	 	
  		}
  		else{
  			
  			//$hyperlinkclass = ($selected == 'addbasket') ? 'gold' : 'gray'; 	
  			
  			/*$addbasketlink = '
  								<!--<a href="'. config_item('base_url').'diamonds/search/true" class="'.$hyperlinkclass.'">-->
  								<a href="#" class="'.$hyperlinkclass.'">
				  				<img align="absmiddle" src="'. config_item('base_url').'images/tamal/add_to_basket.jpg">
				  				3.Add To Basket</a>
  							';*/ 
  			$addbasketlink = '	<span class="'.$hyperlinkclass.'">
  										<img align="absmiddle" src="'. config_item('base_url').'images/tamal/add_to_basket.jpg">
  										3.Add To Basket
  								</span>	 ';	 	  			
  		}
  		
  		
  		
  		$html = '
  					<div class="floatl selectedtab">
		  				'.$diamondtablink.'
		  			</div>
		  			<div class="floatl tabheader">
		  				'.$settingslink.' 				
		  			</div>
		  			<div class="floatl tabheader">
		  				'.$addbasketlink.'
		  			</div>
		  			<div class="clear"></div>  				
  					
  		
  		';
  		
  		switch ($selected){
  			case 'diamonds':
  				
  				$html = '
		  					<div class="floatl selectedtab">
				  				'.$diamondtablink.'				
				  			</div>
				  			<div class="floatl tabheader">
				  				'.$settingslink.'   				
				  			</div>
				  			<div class="floatl tabheader">
				  				'.$addbasketlink.'
				  			</div>
				  			<div class="clear"></div>  				
		  					
		  		
		  					';
  				break;
  			
  			case 'ring':  				
  				$html = '
		  					<div class="floatl tabheader">
				  				'.$diamondtablink.'
				  			</div>
				  			<div class="floatl  selectedtab">
				  				'.$settingslink.'   				
				  			</div>
				  			<div class="floatl tabheader">
				  				'.$addbasketlink.'
				  			</div>
				  			<div class="clear"></div>  				
		  					
		  		
		  					';
  				break;
  				
  			case 'addbasket':  				
  				$html = '
		  					<div class="floatl tabheader">
				  				'.$diamondtablink.'
				  			</div>
				  			<div class="floatl tabheader">
				  				'.$settingslink.' 	  				
				  			</div>
				  			<div class="floatl selectedtab">
				  				'.$addbasketlink.'
				  			</div>
				  			<div class="clear"></div>  				
		  					
		  		
		  					';
  				break;
  				
  			default:
  				$html;
  				break;
  		}
  		
  		
  		return $html;
  	}
  	
  	function getThreeStoneTab($selected = '',$centerstone = '',$sidestone1 = '',$sidestone2 = '',$stockno=''){
  		
  		$hyperlinkclass='gold';  		
  		//$basket = $this->session->userdata('basket');
  		//var_dump($basket);
  		//$centerstone = '';// $basket['threestonering']['centerstone'];
  	    //$sidestone = '';// $basket['threestonering']['sidestone'];
		$stockno = $this->session->userdata('myring');
		$addoption = $this->session->userdata('addoption');
  		
  		$html = '
  					<div class="floatl smalltabselected">
			  				<a href="#" class="gold">
			  				<div class="floatl w35px"><img align="absmiddle" src="'.config_item('base_url').'images/tamal/select_diamond.jpg"></div> 
			  				<div class="floatl w85px">1.Pick Center Diamond</div></a>
			  				<div class="clear"></div>
			  			</div>
			  			<div class="floatl smalltabheader">
			  				<a href="#" class="gray">
			  				<div class="floatl w35px"><img align="absmiddle" src="'.config_item('base_url').'images/tamal/select_sidestone.jpg"></div>
			  				<div class="floatl w85px">2.Select Your Sidestones</div></a>
			  			</div>
			  			<div class="floatl smalltabheader">
			  				<a href="#" class="gray">
			  				<div class="floatl w35px"><img align="absmiddle" src="'.config_item('base_url').'images/tamal/choose_ring_setting.jpg"></div>		  				
			  				<div class="floatl w85px">3.Choose Your Settings</div></a>
			  			</div>
			  			<div class="floatl smalltabheader">
			  				<a href="#" class="gray">
			  				<div class="floatl w35px"><img align="absmiddle" src="'.config_item('base_url').'images/tamal/add_to_basket.jpg"></div>		  				
			  				<div class="floatl w85px">4.Add To Basket</div></a>
			  			</div>
				<div class="clear"></div>  		
  		
  			';
  		if($centerstone){
  			
  			//$hyperlinkclass = ($selected == 'diamonds') ? 'gold' : 'gray'; 	 			
  			
  			/*$diamondtablink = ' 	  							
				  				<a href="#" class="'.$hyperlinkclass.'">
				  				<div class="floatl w35px"><img align="absmiddle" src="'.config_item('base_url').'images/tamal/select_diamond.jpg"></div> 
				  				<div class="floatl w85px">1.Your Center Diamond</div></a>
				  				<div class="clear"></div>			
  							';*/
  			$diamondtablink = ' <span class="'.$hyperlinkclass.'">
  										<span class="floatl w35px"><img align="absmiddle" src="'.config_item('base_url').'images/tamal/select_diamond.jpg"></span>
  										1.Your Center Diamond
  								</span> '; 		
  		}
  		else {  			
  			//$hyperlinkclass = ($selected == 'diamonds') ? 'gold' : 'gray'; 	 
  			/*$diamondtablink = '  
				  				<a href="#" class="'.$hyperlinkclass.'">
				  				<div class="floatl w35px"><img align="absmiddle" src="'.config_item('base_url').'images/tamal/select_diamond.jpg"></div> 
				  				<div class="floatl w85px">1.Pick Center Diamond</div></a>
				  				<div class="clear"></div>			
  							';*/
  			$diamondtablink = ' <span class="'.$hyperlinkclass.'">
  										<span class="floatl w35px"><img align="absmiddle" src="'.config_item('base_url').'images/tamal/select_diamond.jpg"></span>
  										1.Pick Center Diamond
  								</span> '; 	
  		}
  		
  		
  		if($centerstone !=''){  			
  			
  			//$hyperlinkclass = ($selected == 'sidestone') ? 'gold' : 'gray';   					
  			
  			/*$sidestonelink = ' 	  							
				  				<a href="#" class="'.$hyperlinkclass.'">
				  				<div class="floatl w35px"><img align="absmiddle" src="'.config_item('base_url').'images/tamal/select_sidestone.jpg"></div>
				  				<div class="floatl w85px">2.My Sidestones</div></a>
  							'; */
  			$sidestonelink = ' <span class="'.$hyperlinkclass.'">
  										<span class="floatl w35px"><img align="absmiddle" src="'.config_item('base_url').'images/tamal/select_sidestone.jpg"></span>
  										2.My Sidestones
  								</span> ';  			
  		}
  		else {
  			//$hyperlinkclass = ($selected == 'sidestone') ? 'gold' : 'gray'; 
  			/*$sidestonelink = '  
				  				<a href="#" class="'.$hyperlinkclass.'">
				  				<div class="floatl w35px"><img align="absmiddle" src="'.config_item('base_url').'images/tamal/select_sidestone.jpg"></div>
				  				<div class="floatl w85px">2.Select Your Sidestones</div></a>
  							';*/
  			$sidestonelink = ' <span class="'.$hyperlinkclass.'">
  										<span class="floatl w35px"><img align="absmiddle" src="'.config_item('base_url').'images/tamal/select_sidestone.jpg"></span>
  										2.Select Your Sidestones
  								</span> ';  
  		}
  		
  		if($centerstone !='' && $sidestone1 !='' && $sidestone2 != '' && $stockno != ''){
  			//$hyperlinkclass = ($selected == 'ring') ? 'gold' : 'gray';
  			/*$ringsettings = '
  								<a href="#" class="'.$hyperlinkclass.'">
				  				<div class="floatl w35px"><img align="absmiddle" src="'.config_item('base_url').'images/tamal/choose_ring_setting.jpg"></div>		  				
				  				<div class="floatl w85px">3.My Settings</div></a>					
  			
  								'; */
  			$ringsettings = ' <span class="'.$hyperlinkclass.'">
  										<span class="floatl w35px"><img align="absmiddle" src="'.config_item('base_url').'images/tamal/choose_ring_setting.jpg"></span>
  										3.My Settings
  								</span> ';  
  		}
  		elseif($centerstone !='' && $sidestone1 !='' && $sidestone2 != ''){
  			//$hyperlinkclass = ($selected == 'ring') ? 'gold' : 'gray';
  			/*$ringsettings = '
  								<a href="#" class="'.$hyperlinkclass.'">
				  				<div class="floatl w35px"><img align="absmiddle" src="'.config_item('base_url').'images/tamal/choose_ring_setting.jpg"></div>		  				
				  				<div class="floatl w85px">3.Choose Your Settings</div></a>					
  			
  								';*/ 
  			$ringsettings = ' <span class="'.$hyperlinkclass.'">
  										<span class="floatl w35px"><img align="absmiddle" src="'.config_item('base_url').'images/tamal/choose_ring_setting.jpg"></span>
  										3.Choose Your Settings
  								</span> '; 
  		}
  		else{
  			//$hyperlinkclass = ($selected == 'ring') ? 'gold' : 'gray';
  			/*$ringsettings = '
  								<a href="#" class="'.$hyperlinkclass.'">
				  				<div class="floatl w35px"><img align="absmiddle" src="'.config_item('base_url').'images/tamal/choose_ring_setting.jpg"></div>		  				
				  				<div class="floatl w85px">3.Choose Your Settings</div></a>					
  			
  								'; */
  			$ringsettings = ' <span class="'.$hyperlinkclass.'">
  										<span class="floatl w35px"><img align="absmiddle" src="'.config_item('base_url').'images/tamal/choose_ring_setting.jpg"></span>
  										3.Choose Your Settings
  								</span> '; 
  		} 		
  		
  		
  		
  		
  		
  		
  		 		
  		
  		
  		switch ($selected){
  			case 'diamonds':
  				
  				$html = '
		  					<div class="floatl smalltabselected">
				  				'.$diamondtablink.'				
				  			</div>
				  			<div class="floatl smalltabheader">
				  				'.$sidestonelink.'   				
				  			</div>
				  			<div class="floatl smalltabheader">
				  				'.$ringsettings.'
				  			</div>
				  			<div class="floatl smalltabheader">
				  				<a href="#" class="gray">
				  				<div class="floatl w35px"><img align="absmiddle" src="'.config_item(base_url).'images/tamal/add_to_basket.jpg"></div>		  				
				  				<div class="floatl w85px">4.Add To Basket</div></a>
				  			</div>
				  			<div class="clear"></div>  				
		  					
		  		
		  					';
  				break;
  			
  			case 'sidestone':  				
  				$html = '
		  					<div class="floatl smalltabheader">
				  				'.$diamondtablink.'				
				  			</div>
				  			<div class="floatl smalltabselected">
				  				'.$sidestonelink.'   				
				  			</div>
				  			<div class="floatl smalltabheader">
				  				'.$ringsettings.'
				  			</div>
				  			<div class="floatl smalltabheader">
				  				<a href="#" class="gray">
				  				<div class="floatl w35px"><img align="absmiddle" src="'.config_item('base_url').'images/tamal/add_to_basket.jpg"></div>		  				
				  				<div class="floatl w85px">4.Add To Basket</div></a>
				  			</div>
				  			<div class="clear"></div>  				
		  					
		  		
		  					';
  				break;
  				
  			case 'ring':
  				$html = '
		  					<div class="floatl smalltabheader">
				  				'.$diamondtablink.'				
				  			</div>
				  			<div class="floatl smalltabheader">
				  				'.$sidestonelink.'   				
				  			</div>
				  			<div class="floatl smalltabselected">
				  				'.$ringsettings.'
				  			</div>
				  			<div class="floatl smalltabheader">
				  				<a href="#" class="gray">
				  				<div class="floatl w35px"><img align="absmiddle" src="'.config_item('base_url').'images/tamal/add_to_basket.jpg"></div>		  				
				  				<div class="floatl w85px">4.Add To Basket</div></a>
				  			</div>
				  			<div class="clear"></div>  				
		  					
		  		
		  					';
  				
  			case 'addbasket':  				
  				$html = '
		  					<div class="floatl smalltabheader">
				  				'.$diamondtablink.'				
				  			</div>
				  			<div class="floatl smalltabheader">
				  				'.$sidestonelink.'   				
				  			</div>
				  			<div class="floatl smalltabheader">
				  				'.$ringsettings.'
				  			</div>
				  			<div class="floatl smalltabheader">
				  				<a href="#" class="gray">
				  				<div class="floatl w35px"><img align="absmiddle" src="'.config_item('base_url').'images/tamal/add_to_basket.jpg"></div>		  				
				  				<div class="floatl w85px">4.Add To Basket</div></a>
				  			</div>
				  			<div class="clear"></div>
		  					
		  		
		  					';
  				break;
  				
  			default:
  				$html;
  				break;
  		}
  		
  		
  		return $html;
  		
  		
  	}
  	
  	function earringTab($selected = '', $style = '',$diamond = ''){
  		$html = '
  		
  					<div class="floatl selectedtab">
						<a href="#" class="gold">
		  				<img align="absmiddle" src="'. config_item('base_url').'images/tamal/choose_ring_setting.jpg">
		  				1.Choose Style</a>				
					</div>	  
			  		<div class="floatl tabheader">
						<a href="#" class="gray">
						<img align="absmiddle" src="'. config_item('base_url').'images/tamal/select_diamond.jpg">
						2.Choose Diamond</a> 
					</div> 
					<div class="floatl tabheader">
						<a href="#" class="gray">
		  				<img align="absmiddle" src="'. config_item('base_url').'images/tamal/add_to_basket.jpg">
		  				3.Add to Basket</a>
					</div>
					<div class="clear"></div>
  		
  		';
  		
  		
		$hrefclass1 = ($selected == 'style') ? 'gold' : 'gray';
		$divclass1 = ($selected == 'style') ? 'selectedtab' : 'tabheader'; 
		$styletab = '
				<div class="floatl '.$divclass1.'">
					<a href="#" class="'.$hrefclass1.'">
	  				<img align="absmiddle" src="'. config_item('base_url').'images/tamal/choose_ring_setting.jpg">
	  				1.Choose Style</a>				
				</div>
		';  
  		
  		 
		$hrefclass2 = ($selected == 'diamonds') ? 'gold' : 'gray';
		$divclass2 = ($selected == 'diamonds') ? 'selectedtab' : 'tabheader'; 
		$diamondtab = '
				<div class="floatl '.$divclass2.'">
					<a href="#" class="'.$hrefclass2.'">
	  				<img align="absmiddle" src="'. config_item(base_urls).'images/tamal/select_diamond.jpg">
					2.Choose Diamond</a> 			
				</div>
		'; 
  		 
  		 
		$hrefclass3 = ($selected == 'addbasket') ? 'gold' : 'gray';
		$divclass3 = ($selected == 'addbasket') ? 'selectedtab' : 'tabheader'; 
		$baskettab = '
				<div class="floatl '.$divclass3.'">
					<a href="#" class="'.$hrefclass3.'">
	  				<img align="absmiddle" src="'. config_item('base_url').'images/tamal/add_to_basket.jpg">
  					3.Add to Basket</a> 			
				</div>
		';
  		 
  		
  		/*switch ($selected){
  			case 'style':
  				$html = $styletab.$diamondtab.$baskettab;
  				break;
  			case 'diamonds':
  				$html = $styletab.$diamondtab.$baskettab;
  				break;
  			case 'addbasket':
  				$html = $styletab.$diamondtab.$baskettab;
  				break;
  			default:
  				$html;
  				break;
  		}*/
  		
  		$html = $styletab.$diamondtab.$baskettab;
  		
  		return $html;
  		
  		
  	}
  	  	
  	
  	function errordb(){
  		$res = $this->db->get('error');
   		return  ($res->result());
  	}

	function makePageoptions($objects , $variable , $value)
  	{
  		$options = '';
  		if(is_array($objects)){
  			foreach ($objects as $object){
				if ($object[$variable] =='freefedEx') continue;
  				$options .= '<option value="' . $object[$variable] . '">' . $object[$value] .'</option>';
  			}
  		}
  		
  		return $options;
  		
  		
  		
  	}
  }
?>
