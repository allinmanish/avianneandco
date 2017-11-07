<?php 

class Admin extends Controller {
	function __construct(){
		parent::Controller();
		$this->load->model('adminmodel');
		$this->load->model('user');
		$this->load->model('sitepaging');
		$this->load->model('commonmodel');
		$this->load->model('helixmodel');
	}
	
	function index()
	{
		$data 	= $this->getCommonData(); 
		if($this->isadminlogin()){
				
				$data['onloadextraheader'] = '$("#secondpane p.menu_head").click(function()
									    {
										     $(this).css({backgroundImage:"url('.config_item('base_url').'images/minus.jpg)"}).next("div.menu_body").slideDown(500).siblings("div.menu_body").slideUp("slow");
									         $(this).siblings().css({backgroundImage:"url('.config_item('base_url').'images/plus.jpg)"});
										});
										';
			 $data['leftmenus']	=	$this->adminmodel->adminmenuhtml();	
			 $data['newtestimonials'] = $this->adminmodel->newfeedbacks();	
			 $output = $this->load->view('admin/index' , $data , true); 		
		}else $output = $this->load->view('admin/login', $data , true);
	    
		$this->output($output , $data);
 		
	}
	
	function isadminlogin(){
		if(($this->session->isLoggedin()) && ($this->session->userdata('usertype') == 'admin')){
			return true;
		}else{
			return false;
		}
	}
	
	function commonpagetemplate($templateid = ''){
		$data 					= $this->getCommonData(); 
		
		if($this->isadminlogin()){
			$this->load->model('adminmodel');
			$data['onloadextraheader'] = '$("#secondpane p.menu_head").click(function()
									    {
										     $(this).css({backgroundImage:"url('.config_item('base_url').'images/minus.jpg)"}).next("div.menu_body").slideDown(500).siblings("div.menu_body").slideUp("slow");
									         $(this).siblings().css({backgroundImage:"url('.config_item('base_url').'images/plus.jpg)"});
										});
										$("#sitemanage").click();
										 
										';
			
			  $data['leftmenus']	=	$this->adminmodel->adminmenuhtml('staticpage');	
			  $pages  = $this->adminmodel->getCommonPages();
			  $data['pages'] = $this->commonmodel->makePageoptions($pages , 'topicid' , 'description');
			  $data['templateid'] = ( $_POST) ? $_POST['templateid'] : '';
			  if($_POST){
			  	if(isset($_POST['contenthtml'])){
			  		if($this->adminmodel->saveCommonPageTemplate($data['templateid'] , $_POST['contenthtml']))
			  		$data['success'] = 'Page Template saved';
			  		else $data['error'] = 'Page Template not saved';
			  	}
			  }
			  $data['use_tinymce'] = 'admin';
			  
			  $data['content'] = $this->adminmodel->getCommonPageTemplate($data['templateid']);
			  $output = $this->load->view('admin/commonpagetemplates' , $data , true); 		
		}else  $output =$this->load->view('admin/login', $data , true);
	    
	    
		$this->output($output , $data);
		
	}
		
	function globalvariables($configid = ''){
		$data 					= $this->getCommonData(); 
		
		if($this->isadminlogin()){
			
			$data['onloadextraheader'] = '$("#secondpane p.menu_head").click(function()
									    {
										     $(this).css({backgroundImage:"url('.config_item('base_url').'images/minus.jpg)"}).next("div.menu_body").slideDown(500).siblings("div.menu_body").slideUp("slow");
									         $(this).siblings().css({backgroundImage:"url('.config_item('base_url').'images/plus.jpg)"});
										});
										$("#sitemanage").click();
										 
										';
			
			  $data['leftmenus']	=	$this->adminmodel->adminmenuhtml('globalvariable');	
			  $globalvariable  = $this->adminmodel->getglobalvariable();
			  $data['globalvariables'] = $this->commonmodel->makeoptions($globalvariable , 'id' , 'descriptions');
			  $data['globalvariableid'] = ( $_POST) ? $_POST['globalvariableid'] : '';
			  if($_POST){
			  	if(isset($_POST['contenthtml'])){
			  		if($this->adminmodel->saveglobalvariableByid($data['globalvariableid'] , $_POST['contenthtml']))
			  		$data['success'] = '<h1 class="success">Global Information saved</h1>';
			  		else $data['error'] = '<h1 class="error">Global Inforation not saved</h1>';
			  	}
			  }
			  
			  
			  $data['content'] = $this->adminmodel->getglobalvariableByid($data['globalvariableid']);
			  $output = $this->load->view('admin/globalvariable' , $data , true); 		
		}else  $output =$this->load->view('admin/login', $data , true);
	    
	    
		$this->output($output , $data);
		
	}
	
	private function output($layout = null , $data = array())
	{	
		$this->load->model('user');
		$this->load->model('adminmodel');
		$user 					= 	$this->session->userdata('user');
		$data['user']			= 	$user;
		$data['loginlink']		=	$this->user->loginhtml('admin');
		
    	 $output = $this->load->view('admin/header' , $data , true);
		 if($this->session->isLoggedin() && ($this->session->userdata('usertype') == 'admin')){
		  		 $output .= $layout;
				 
		  }else{ 		
		  	    $output .= $this->load->view('admin/login', $data , true);
		  }
  	    $output .= $this->load->view('admin/footer', $data , true);
		$this->output->set_output($output);
  
	}
	
	private function getCommonData()
	{
	 	$data = array();
  		$data = $this->commonmodel->getPageCommonData();
  		return $data;
	 
	}
	
	function jewelries($action = 'view' , $id = 0){
		$data 	= $this->getCommonData(); 
		$data['extraheader']  = '';
		$collections = '';
		$typeoptions = '';
		$data['collections'] ='';
		$data['typeoptions'] = '';
		
		if($this->isadminlogin()){
			if($action == 'delete'){  
									$ret = $this->adminmodel->jewelries($_POST , $action , $id);
									header("Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
									header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT" );
									header("Cache-Control: no-cache, must-revalidate" );
									header("Pragma: no-cache" );
									header("Content-type: text/x-json");
									$json = "";
									$json .= "{\n";
									$json .= "total: ".$ret['total'].",\n";
									$json .= "}\n";
									echo $json; 

			}else{
				
	if($action == 'add' || $action == 'edit'){
		
		
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		 
		$this->form_validation->set_rules('price', 'Price', 'trim|required');
		$this->form_validation->set_rules('section', 'Section', 'trim|required');
		$this->form_validation->set_rules('collection', 'Collection', 'trim|required');
		$this->form_validation->set_rules('shape', 'Shape', 'trim|required');
		$this->form_validation->set_rules('metal', 'Metal', 'trim|required');
		$this->form_validation->set_rules('style', 'Style', 'trim|required');
		$this->form_validation->set_rules('name', 'Name', 'trim|required');
		$this->form_validation->set_rules('platinum_price', 'Platinum Price', 'trim|required');
		$this->form_validation->set_rules('white_gold_price', 'White Gold Price', 'trim|required');
		$this->form_validation->set_rules('yellow_gold_price', 'Yellow Gold Price', 'trim|required');
		
		 
		 
		$this->form_validation->set_error_delimiters('<font class="require">', '</font>');
									
		 if(isset($_POST[$action.'btn'])){
						   if ($this->form_validation->run() == FALSE){
								    $data['error'] = 'ERROR ! Please check the error fields';
								    if($action != 'edit')$data['details'] = $_POST;
							}else {
							 
								$ret = $this->adminmodel->jewelries($_POST , $action , $id);
								if($ret['error'] == '')$data['success'] = $ret['success'];
								else{
									$data['error'] = $ret['error'];
									if($action != 'edit')$data['details']  = $_POST;
								}
							 
							}
						}
		
	    $data['extraheader'] .= $this->commonmodel->addEditor('simple' );
        $data['collectionoptions'] = $this->commonmodel->makeoptions($this->adminmodel->getcollections() , 'collection' , 'collection');
	                //$data['sectionoptions'] = $this->commonmodel->makeoptions($this->adminmodel->getsections(), 'section' , 'section');
		
		  if($action == 'edit') {
				$this->load->model('jewelrymodel');
				$data['details'] = $this->jewelrymodel->getAllByStock($id);
				$details = $data['details'];
				
				switch ($details['section']){
					case 'Earrings':
						$collections = '<option value="DiamondStud">Diamond Stud Earrings</option> 
											  <option value="BuildEarring">Build Your Own Earrings</option>
											';
						break;
					case 'EngagementRings':
						$collections = '
												<option value="International Collection">International Collection</option>
											';
						break;
					case 'Jewelry':
						$collections = '
												<option value="MensWeddingRing">Men\'s Wedding Rings</option>  
									            <option value="WomensWeddingRing">Women\'s Wedding Rings</option>  
										 		<option value="WomensAnniversaryRing">Women\'s Anniversary Rings</option> 
											 	';
						break;
					case 'Pendants':
						$collections = '
												<option value="BuildPendant">Build your own Pendants</option>
											';
						break;
					default:
						break;
				}
				$data['collections'] =$collections;
				
				
				$data['animations'] = $this->adminmodel->getFlashByStockId($id);
				$data['id'] = $id;
			}
				    
		}
			$data['onloadextraheader'] = '$("#secondpane p.menu_head").click(function()
									    {
										     $(this).css({backgroundImage:"url('.config_item('base_url').'images/minus.jpg)"}).next("div.menu_body").slideDown(500).siblings("div.menu_body").slideUp("slow");
									         $(this).siblings().css({backgroundImage:"url('.config_item('base_url').'images/plus.jpg)"});
										});
										$("#jewelrymanage").click();
										 
										';
			
			  $data['leftmenus']	=	$this->adminmodel->adminmenuhtml('jewelries');	
			  $url = config_item('base_url').'admin/getjewelries';
			  $data['action'] = $action; 
			  $data['onloadextraheader'] .= "   $(\"#results\").flexigrid
																	(
																	{   	 							
																	url: '".$url."',
																	dataType: 'json',
																	colModel : [
																		{display: 'ID', name : 'stock_number', width : 80, sortable : true, align: 'center'},
																		{display: 'Price', name : 'price', width : 85, sortable : true, align: 'center'},
																		{display: 'Section', name : 'section', width : 120, sortable : true, align: 'center'},
																		{display: 'Collection', name : 'collection', width : 120, sortable : true, align: 'center'},
																		{display: 'Shape', name : 'shape', width : 50, sortable : true, align: 'center'},
																		{display: 'Metal', name : 'metal', width : 80, sortable : true, align: 'center'},
																		{display: 'Style', name : 'style', width : 60, sortable : true, align: 'center'},
																		{display: 'Carat', name : 'carat', width : 60, sortable : true, align: 'center',hide: true},
																		{display: 'Total Carats', name : 'total_carats', width : 60, sortable : true, align: 'center'},
																		{display: 'Diamond Count', name : 'diamond_count', width : 60, sortable : true, align: 'center',hide: true},
																		{display: 'Diamond Size', name : 'diamond_size', width : 60, sortable : true, align: 'center',hide: true},
																		{display: 'Pearl Lenght', name : 'pearl_lenght', width : 60, sortable : true, align: 'center',hide: true},
																		{display: 'Pearl mm', name : 'pearl_mm', width : 60, sortable : true, align: 'center',hide: true},
																		{display: 'Semi mounted', name : 'semi_mounted', width : 60, sortable : true, align: 'center',hide: true},
																		{display: 'Side', name : 'side', width : 60, sortable : true, align: 'center',hide: true},
																		{display: 'Carat image', name : 'carat_image', width : 60, sortable : true, align: 'center',hide: true},
																		{display: 'Description', name : 'description', width : 250, sortable : false, align: 'left'}
																		],
																		 buttons : [{name: 'Add', bclass: 'add', onpress : test},
																				{name: 'Delete', bclass: 'delete', onpress : test},
																				{separator: true}
																			],
																	searchitems : [
																		{display: 'Lot #', name : 'stock_number', isdefault: true},
																		{display: 'Shape', name : 'shape', isdefault: true},
																		{display: 'Style', name : 'style', isdefault: false}
																		
																		], 		
																	sortname: \"stock_number\",
																	sortorder: \"desc\",
																	usepager: true,
																	title: '<h1 class=\"pageheader\">Jewelries</h1>',
																	useRp: true,
																	rp: 10,
																	showTableToggleBtn: false,
																	width:1020,
																	height: 565
																	}
																	);
																	
																	function test(com,grid)
																	{
																		if (com=='Delete')
																			{ 
																			  
																			if($('.trSelected').length>0){
																			            if(confirm('Remove ' + $('.trSelected').length + ' rows?')){
																                                var items = $('.trSelected');
																                                var itemlist ='';
																                                for(i=0;i<items.length;i++){
																                                        itemlist+= items[i].id.substr(3)+\",\";
																                                }
																                                
																                                $.ajax({
																										   type: \"POST\",
																										   dataType: \"json\",
																										   url: \"".config_item('base_url')."admin/jewelries/delete\",
																										   data: \"items=\"+itemlist,
																										   success: function(data){
																										   	alert('Total Deleted rows: '+data.total);
																										    $(\"#results\").flexReload();
																										   }
																										 });
																										 						  
		
		
																                                														                        }
																                } else{
																                        alert('You have to select a row.');
																                } 
																			
																			
																			}
																		else if (com=='Add')
																			{
																				window.location = '".config_item('base_url')."admin/jewelries/add';
																			}			
																	}
														 
														 ";
					 
					
					$data['extraheader'] .= ' 
											 <script src="'.config_item('base_url').'third_party/flexigrid/flexigrid.js"></script>';
					$data['extraheader'] .= '<link type="text/css" href="'.config_item('base_url').'third_party/flexigrid/css/flexigrid/flexigrid.css" rel="stylesheet" /> ';
				 
					$data['extraheader'] .= ' 
					<script src="'.config_item('base_url').'js/swfobject.js" type="text/javascript"></script>
					';
					$data['extraheader'] .= ' 
					<script src="'.config_item('base_url').'js/t.js" type="text/javascript"></script>
					';
		 	       $data['onloadextraheader'] .= " 
											var so;				
		 									";
					$data['usetips'] = true;			
				
		
			
			  $output = $this->load->view('admin/jewelries' , $data , true); 	
			  
		 
			  $this->output($output , $data);
		
	   }
	 
		}else { $output =$this->load->view('admin/login', $data , true);$this->output($output , $data);}
	}  
	 
	function getjewelries($addoption=''){
							    $page 		= isset($_POST['page']) 	? $_POST['page'] : 1;
		                        $rp 		= isset($_POST['rp']) 		? $_POST['rp'] : 10;
		                        $sortname 	= isset($_POST['sortname']) ? $_POST['sortname'] : 'stock_number';
		                        $sortorder 	= isset($_POST['sortorder'])? $_POST['sortorder'] : 'desc';
		                        $query 		= isset($_POST['query']) 	? $_POST['query'] : '';
		                        $qtype 		= isset($_POST['qtype']) 	? $_POST['qtype'] : 'title';
		                          
		                        
		                        $results = $this->adminmodel->getjewelries($page, $rp, $sortname ,$sortorder  ,$query  , $qtype);
								header("Expires: Mon, 26 Jul 1997 05:00:00 GMT" ); 
								header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT" ); 
								header("Cache-Control: no-cache, must-revalidate" ); 
								header("Pragma: no-cache" );
								header("Content-type: text/x-json");
								$json = "";
								$json .= "{\n";
								$json .= "page: $page,\n";
								$json .= "total: ".$results['count'].",\n";
								$json .= "rows: [";
								$rc = false;
								  
								foreach ($results['result'] as $row) {
								  $shape = '';
								  
									if ($rc) $json .= ",";
									$json .= "\n {";
									$json .= "id:'".$row['stock_number']."',";
									$json .= "cell:['Lot #: ".$row['stock_number']."<br /><a href=\"javascript:void(0)\" onclick=\"jewelrydetails(".$row['stock_number'].");\" class=\"edit\">View Details</a>'";
									if(file_exists(config_item('base_path').'images/rings/'.addslashes($row['small_image'])) && $row['small_image'] != '')
									$json .= ",'<img src=\'".config_item('base_url')."images/rings/".addslashes($row['small_image'])."\' width=\'80\'><br />$ ".addslashes($row['price'])."'";
									else 
									$json .= ",'<img src=\'".config_item('base_url')."images/rings/noringimage.png\' width=\'80\'><br />$ ".addslashes($row['price'])."'";
									$json .= ",'".addslashes($row['section'])."'";	
									$json .= ",'".addslashes($row['collection'])."'";	
									$json .= ",'".addslashes($row['shape'])."'";
									$json .= ",'".addslashes($row['name'])."'";	
									$json .= ",'".addslashes($row['metal'])."'";
									$json .= ",'".addslashes($row['style'])."'";
									$json .= ",'".addslashes($row['carat'])."'";
									$json .= ",'".addslashes($row['total_carats'])."'";
									$json .= ",'".addslashes($row['diamond_count'])."'";
									$json .= ",'".addslashes($row['diamond_size'])."'";
									$json .= ",'".addslashes($row['pearl_lenght'])."'";
									$json .= ",'".addslashes($row['pearl_mm'])."'";
									$json .= ",'".addslashes($row['semi_mounted'])."'";
									$json .= ",'".addslashes($row['side'])."'";
									$json .= ",'".addslashes($row['carat_image'])."'";
								    $json .= ",'".str_replace("\r", '<br />', str_replace("\n", '<br />', addslashes($row['description'])))."'";
									$json .= "]";
									$json .= "}";
									$rc = true;		
									
								}
								$json .= "]\n";
								$json .= "}";
								echo $json;
								
	}
    
	function sitemap(){
	$data = $this->getCommonData();
	if($this->isadminlogin()){
	
	$data['onloadextraheader'] = '$("#secondpane p.menu_head").click(function()
	{
	$(this).css({backgroundImage:"url('.config_item('base_url').'images/minus.jpg)"}).next("div.menu_body").slideDown(500).siblings("div.menu_body").slideUp("slow");
	$(this).siblings().css({backgroundImage:"url('.config_item('base_url').'images/plus.jpg)"});
	});
	$("#sitemanage").click();
	';
	$data['leftmenus'] = $this->adminmodel->adminmenuhtml('sitemap');
	$output = $this->load->view('admin/sitemap' , $data , true);
	}else $output = $this->load->view('admin/login', $data , true);
	
	$this->output($output , $data);
	}
	
	function customers($action = 'view' , $id = 0){
		$data 	= $this->getCommonData(); 
		if($this->isadminlogin()){
			if($action == 'delete'){  
									$ret = $this->adminmodel->customers($_POST , $action , $id);
									header("Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
									header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT" );
									header("Cache-Control: no-cache, must-revalidate" );
									header("Pragma: no-cache" );
									header("Content-type: text/x-json");
									$json = "";
									$json .= "{\n";
									$json .= "total: ".$ret['total'].",\n";
									$json .= "}\n";
									echo $json; 

			}else{		
				$data['onloadextraheader'] = '$("#secondpane p.menu_head").click(function()
									    {
										     $(this).css({backgroundImage:"url('.config_item('base_url').'images/minus.jpg)"}).next("div.menu_body").slideDown(500).siblings("div.menu_body").slideUp("slow");
									         $(this).siblings().css({backgroundImage:"url('.config_item('base_url').'images/plus.jpg)"});
										});
										$("#ecommerce").click();
										';
			      $data['leftmenus']	=	$this->adminmodel->adminmenuhtml('Customers');	
			 
			 
			 
	$url = config_item('base_url').'admin/getcustomers/';
	$data['action'] = $action;
	$data['onloadextraheader'] .= " $(\"#pageresults\").flexigrid
									(
										{
										url: '".$url."',
										dataType: 'json',
										colModel : [
										{display: 'ID', name : 'id', width : 50, sortable : true, align: 'center'},
										{display: 'Name', name : 'fname', width : 200, sortable : true, align: 'left'},
										{display: 'E-mail', name : 'email', width : 250, sortable : true, align: 'left'},
										{display: 'Phone', name : 'phone', width : 100, sortable : true, align: 'left'},
										{display: 'Address', name : 'address', width : 300, sortable : true, align: 'left'}
										],
										 buttons : [ 
					 								{name: 'Delete', bclass: 'delete', onpress : test},
													{separator: true}
												 ],
										searchitems : [
										{display: 'id', name : 'id', isdefault: true},
										{display: 'email', name : 'email', isdefault: true}
										
										],
										sortname: \"id\",
										sortorder: \"asc\",
										usepager: true,
										title: '<h1 class=\"pageheader\">Customers</h1>',
										useRp: true,
										rp: 20,
										showTableToggleBtn: false,
										width:965,
										height: 300
										}
									);
									function test(com,grid)
																	{
																		if (com=='Delete')
																			{ 
																			  
																			if($('.trSelected').length>0){
																			            if(confirm('Remove ' + $('.trSelected').length + ' rows?')){
																                                var items = $('.trSelected');
																                                var itemlist ='';
																                                for(i=0;i<items.length;i++){
																                                        itemlist+= items[i].id.substr(3)+\",\";
																                                }
																                                
																                                $.ajax({
																										   type: \"POST\",
																										   dataType: \"json\",
																										   url: \"".config_item('base_url')."admin/customers/delete\",
																										   data: \"items=\"+itemlist,
																										   success: function(data){
																										   	alert('Total Deleted rows: '+data.total);
																										    $(\"#pageresults\").flexReload();
																										   }
																										 });
																										 						  
		
		
																                                														                        }
																                } else{
																                        alert('You have to select a row.');
																                } 
																			
																			
																			}
																	 		
																	}
									
									";
									
	
	$data['extraheader'] = '
	<script src="'.config_item('base_url').'third_party/flexigrid/flexigrid.js"></script>';
	$data['extraheader'] .= '<link type="text/css" href="'.config_item('base_url').'third_party/flexigrid/css/flexigrid/flexigrid.css" rel="stylesheet" /> ';
	
	 
			 
			 
			 
			 
			 $output = $this->load->view('admin/customers' , $data , true); 		
			 $this->output($output , $data);
			}
		}else { $output = $this->load->view('admin/login', $data , true);
	    
				$this->output($output , $data);
		}
	}
	
	function getcustomers(){
		
		$page = isset($_POST['page']) ? $_POST['page'] : 1;
		$rp = isset($_POST['rp']) ? $_POST['rp'] : 10;
		$sortname = isset($_POST['sortname']) ? $_POST['sortname'] : 'id';
		$sortorder = isset($_POST['sortorder'])? $_POST['sortorder'] : 'desc';
		$query = isset($_POST['query']) ? $_POST['query'] : '';
		$qtype = isset($_POST['qtype']) ? $_POST['qtype'] : 'title';
		
		
		$results = $this->adminmodel->getcustomers($page, $rp, $sortname ,$sortorder  ,$query  , $qtype);
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
		header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT" );
		header("Cache-Control: no-cache, must-revalidate" );
		header("Pragma: no-cache" );
		header("Content-type: text/x-json");
		$json = "";
		$json .= "{\n";
		$json .= "page: $page,\n";
		$json .= "total: ".$results['count'].",\n";
		$json .= "rows: [";
		$rc = false;
		
		foreach ($results['result'] as $row) {
		$shape = '';
		
		if ($rc) $json .= ",";
		$json .= "\n {";
		$json .= "id:'".$row['id']."',";
		$json .= "cell:['<a href=\"#\">".$row['id']."</a>'";
		$json .= ",'".addslashes(ucfirst($row['fname']))." ".addslashes($row['lname'])."'";
		$json .= ",'".addslashes($row['email'])." '";
		$json .= ",'".addslashes($row['phone'])." '";
		 $json .= ",'".str_replace("\r", '<br />', str_replace("\n", '<br />', addslashes($row['address'])))."'";
		$json .= "]";
		$json .= "}";
		$rc = true;
		
		}
		$json .= "]\n";
		$json .= "}";
		echo $json;
		
	}
	
	function getorders(){
		
		$page = isset($_POST['page']) ? $_POST['page'] : 1;
		$rp = isset($_POST['rp']) ? $_POST['rp'] : 10;
		$sortname = isset($_POST['sortname']) ? $_POST['sortname'] : 'id';
		$sortorder = isset($_POST['sortorder'])? $_POST['sortorder'] : 'desc';
		$query = isset($_POST['query']) ? $_POST['query'] : '';
		$qtype = isset($_POST['qtype']) ? $_POST['qtype'] : 'title';
		
		
		$results = $this->adminmodel->getorders($page, $rp, $sortname ,$sortorder  ,$query  , $qtype);
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
		header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT" );
		header("Cache-Control: no-cache, must-revalidate" );
		header("Pragma: no-cache" );
		header("Content-type: text/x-json");
		$json = "";
		$json .= "{\n";
		$json .= "page: $page,\n";
		$json .= "total: ".$results['count'].",\n";
		$json .= "rows: [";
		$rc = false;
		
		foreach ($results['result'] as $row) {
		$shape = '';
		
		if ($rc) $json .= ",";
		$json .= "\n {";
		$json .= "id:'".$row['id']."',";
		$json .= "cell:['<a href=\"#\" onclick=\"vieworders(\'".$row['id']."\')\">View Details : ID :".$row['id']."</a>'";
		$json .= ",'".addslashes($row['paymentmethod'])." '";
		$json .= ",'".addslashes($row['totalprice'])." '";
		$json .= ",'".addslashes($row['orderdate'])." '";
		$json .= "]";
		$json .= "}";
		$rc = true;
		
		}
		$json .= "]\n";
		$json .= "}";
		echo $json;
		
	}
		
	function orders($action = 'view', $id = 0){
		$data 	= $this->getCommonData(); 
		if($this->isadminlogin()){
		if($action == 'delete'){  
									$ret = $this->adminmodel->orders($_POST , $action , $id);
									header("Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
									header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT" );
									header("Cache-Control: no-cache, must-revalidate" );
									header("Pragma: no-cache" );
									header("Content-type: text/x-json");
									$json = "";
									$json .= "{\n";
									$json .= "total: ".$ret['total'].",\n";
									$json .= "}\n";
									echo $json; 

			}else{				
				$data['onloadextraheader'] = '$("#secondpane p.menu_head").click(function()
									    {
										     $(this).css({backgroundImage:"url('.config_item('base_url').'images/minus.jpg)"}).next("div.menu_body").slideDown(500).siblings("div.menu_body").slideUp("slow");
									         $(this).siblings().css({backgroundImage:"url('.config_item('base_url').'images/plus.jpg)"});
										});
										$("#ecommerce").click();
										';
			 $data['leftmenus']	=	$this->adminmodel->adminmenuhtml('Orders');	
			 
			 		 
	$url = config_item('base_url').'admin/getorders/';
	$data['action'] = $action;
	$data['onloadextraheader'] .= " $(\"#results\").flexigrid
									(
										{
										url: '".$url."',
										dataType: 'json',
										colModel : [
										{display: 'Orderid', name : 'id', width : 150, sortable : true, align: 'center'},
										{display: 'Payment method', name : 'paymentmethod', width : 250, sortable : true, align: 'left'},
										{display: 'Payment Amount', name : 'totalprice', width : 250, sortable : true, align: 'left'},
										{display: 'Order Date', name : 'orderdate', width : 250, sortable : true, align: 'left'}
										],
										 buttons : [ 
					 								{name: 'Delete', bclass: 'delete', onpress : test},
													{separator: true}
												 ],
										searchitems : [
										{display: 'id', name : 'id', isdefault: true}
										],
										sortname: \"id\",
										sortorder: \"asc\",
										usepager: true,
										title: '<h1 class=\"pageheader\">Orders</h1>',
										useRp: true,
										rp: 20,
										showTableToggleBtn: false,
										width:965,
										height: 300
										}
									);
									
									function test(com,grid)
																	{
																		if (com=='Delete')
																			{ 
																			  
																			if($('.trSelected').length>0){
																			            if(confirm('Remove ' + $('.trSelected').length + ' rows?')){
																                                var items = $('.trSelected');
																                                var itemlist ='';
																                                for(i=0;i<items.length;i++){
																                                        itemlist+= items[i].id.substr(3)+\",\";
																                                }
																                                
																                                $.ajax({
																										   type: \"POST\",
																										   dataType: \"json\",
																										   url: \"".config_item('base_url')."admin/orders/delete\",
																										   data: \"items=\"+itemlist,
																										   success: function(data){
																										   	alert('Total Deleted rows: '+data.total);
																										    $(\"#results\").flexReload();
																										   }
																										 });
																										 						  
		
		
																                                														                        }
																                } else{
																                        alert('You have to select a row.');
																                } 
																			
																			
																			}
																	 		
																	}
									";
									
	
			$data['extraheader'] = '
			<script src="'.config_item('base_url').'third_party/flexigrid/flexigrid.js"></script>';
			$data['extraheader'] .= '<link type="text/css" href="'.config_item('base_url').'third_party/flexigrid/css/flexigrid/flexigrid.css" rel="stylesheet" /> ';
			 
			 
			 $output = $this->load->view('admin/order' , $data , true); 		
			 		$this->output($output , $data);
			}
		}else {$output = $this->load->view('admin/login', $data , true);
	    
				$this->output($output , $data);
		}
	}

	function basictemp($action = 'view' , $id = 0){
		$data 					= $this->getCommonData(); 
		
		if($this->isadminlogin()){
			$this->load->model('adminmodel');
			$data['onloadextraheader'] = '$("#secondpane p.menu_head").click(function()
									    {
										     $(this).css({backgroundImage:"url('.config_item('base_url').'images/minus.jpg)"}).next("div.menu_body").slideDown(500).siblings("div.menu_body").slideUp("slow");
									         $(this).siblings().css({backgroundImage:"url('.config_item('base_url').'images/plus.jpg)"});
										});
										$("#sitemanage").click();
										 
										';
			
			  $data['leftmenus']	=	$this->adminmodel->adminmenuhtml('editpage');	
			   
			  $output = $this->load->view('admin/commonpagetemplates' , $data , true); 		
		}else  $output =$this->load->view('admin/login', $data , true);
	    
	    
		$this->output($output , $data);
		
	}
	
	function jewelrydetails($id = ''){
		$data 					= $this->getCommonData(); 
		$data['id'] = $id;
		if($this->isadminlogin()){
			$this->load->model('engagementmodel');
			$this->load->model('jewelrymodel');
			$data['details'] = $this->jewelrymodel->getAllByStock($id);
			$data['flashfiles'] = $this->adminmodel->getFlashByStockId($id);
			$data['shapes'] = $this->engagementmodel->getShapeByStockId($id);		 
			
			echo $this->load->view('admin/jewelrydetails' , $data , true); 		
			
		}else { $output =$this->load->view('admin/login', $data , true); $this->output($output , $data);}
	    
	     
	}
	
	function testimonials($action = 'view' , $id = 0){
		$data 	= $this->getCommonData(); 
		if($this->isadminlogin()){
				
			if($action == 'delete'){  
									$ret = $this->adminmodel->testimonials($_POST , $action , $id);
									header("Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
									header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT" );
									header("Cache-Control: no-cache, must-revalidate" );
									header("Pragma: no-cache" );
									header("Content-type: text/x-json");
									$json = "";
									$json .= "{\n";
									$json .= "total: ".$ret['total'].",\n";
									$json .= "}\n";
									echo $json; 

			}elseif ($action == 'accept'){  
									$ret = $this->adminmodel->testimonials($_POST , $action , $id);
									header("Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
									header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT" );
									header("Cache-Control: no-cache, must-revalidate" );
									header("Pragma: no-cache" );
									header("Content-type: text/x-json");
									$json = "";
									$json .= "{\n";
									$json .= "total: ".$ret['total'].",\n";
									$json .= "}\n";
									echo $json; 

			}elseif ($action == 'reject'){  
									$ret = $this->adminmodel->testimonials($_POST , $action , $id);
									header("Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
									header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT" );
									header("Cache-Control: no-cache, must-revalidate" );
									header("Pragma: no-cache" );
									header("Content-type: text/x-json");
									$json = "";
									$json .= "{\n";
									$json .= "total: ".$ret['total'].",\n";
									$json .= "}\n";
									echo $json; 

			}
			else{
			
				$data['onloadextraheader'] = '$("#secondpane p.menu_head").click(function()
									    {
										     $(this).css({backgroundImage:"url('.config_item('base_url').'images/minus.jpg)"}).next("div.menu_body").slideDown(500).siblings("div.menu_body").slideUp("slow");
									         $(this).siblings().css({backgroundImage:"url('.config_item('base_url').'images/plus.jpg)"});
										});
										$("#manageTestimonials").click();
										';
			 $data['leftmenus']	=	$this->adminmodel->adminmenuhtml('testimonials');	
			 
			 
			 
			 
			  $url = config_item('base_url').'admin/gettestmonials';
			  $data['action'] = $action; 
			  $data['onloadextraheader'] .= "   $(\"#results\").flexigrid
																	(
																	{   	 							
																	url: '".$url."',
																	dataType: 'json',
																	colModel : [
																		{display: 'Date', name : 'adddate', width : 60, sortable : true, align: 'center'},
																		{display: 'Status', name : 'status', width : 40, sortable : true, align: 'center'},
																		{display: 'Name', name : 'name', width : 210, sortable : true, align: 'center'},
																		{display: 'E-mail', name : 'email', width : 160, sortable : true, align: 'center'},
																		{display: 'Http Address', name : 'httpaddress', width : 200, sortable : true, align: 'center'},
																		{display: 'Description', name : 'description', width : 250, sortable : false, align: 'left'}
																		],
																		 buttons : [{name: 'Accept', bclass: 'accept', onpress : test},
																		 		{name: 'Reject', bclass: 'reject', onpress : test},
																				{name: 'Delete', bclass: 'delete', onpress : test},
																				{separator: true}
																			],
																	searchitems : [
																		{display: 'id #', name : 'id', isdefault: true},
																		{display: 'name', name : 'name', isdefault: true},
																		{display: 'status', name : 'status', isdefault: false}
																		
																		], 		
																	sortname: \"status\",
																	sortorder: \"asc\",
																	usepager: true,
																	title: '<h1 class=\"pageheader\">Testimonials</h1>',
																	useRp: true,
																	rp: 20,
																	showTableToggleBtn: false,
																	width:1020,
																	height: 550
																	}
																	);
																	
																		function test(com,grid)
																	{
																		if (com=='Delete')
																			{ 
																			  
																			if($('.trSelected').length>0){
																			            if(confirm('Remove ' + $('.trSelected').length + ' rows?')){
																                                var items = $('.trSelected');
																                                var itemlist ='';
																                                for(i=0;i<items.length;i++){
																                                        itemlist+= items[i].id.substr(3)+\",\";
																                                }
																                                
																                                $.ajax({
																										   type: \"POST\",
																										   dataType: \"json\",
																										   url: \"".config_item('base_url')."admin/testimonials/delete\",
																										   data: \"items=\"+itemlist,
																										   success: function(data){
																										   	alert('Total Deleted rows: '+data.total);
																										    $(\"#results\").flexReload();
																										   }
																										 });
																										 						  
		
		
																                                														                        }
																                } else{
																                        alert('You have to select a row.');
																                } 
																			
																			
																			}
																		if (com=='Accept')
																			{ 
																			  
																			if($('.trSelected').length>0){
																			            if(confirm('Accept ' + $('.trSelected').length + ' rows?')){
																                                var items = $('.trSelected');
																                                var itemlist ='';
																                                for(i=0;i<items.length;i++){
																                                        itemlist+= items[i].id.substr(3)+\",\";
																                                }
																                                
																                                $.ajax({
																										   type: \"POST\",
																										   dataType: \"json\",
																										   url: \"".config_item('base_url')."admin/testimonials/accept\",
																										   data: \"items=\"+itemlist,
																										   success: function(data){
																										   	alert('Total Updated rows: '+data.total);
																										    $(\"#results\").flexReload();
																										   }
																										 });
																										 						  
		
		
																                                														                        }
																                } else{
																                        alert('You have to select a row.');
																                } 
																			
																			
																			}	
																		if (com=='Reject')
																			{ 
																			  
																			if($('.trSelected').length>0){
																			            if(confirm('Reject ' + $('.trSelected').length + ' rows?')){
																                                var items = $('.trSelected');
																                                var itemlist ='';
																                                for(i=0;i<items.length;i++){
																                                        itemlist+= items[i].id.substr(3)+\",\";
																                                }
																                                
																                                $.ajax({
																										   type: \"POST\",
																										   dataType: \"json\",
																										   url: \"".config_item('base_url')."admin/testimonials/reject\",
																										   data: \"items=\"+itemlist,
																										   success: function(data){
																										   	alert('Total Rejected rows: '+data.total);
																										    $(\"#results\").flexReload();
																										   }
																										 });
																										 						  
		
		
																                                														                        }
																                } else{
																                        alert('You have to select a row.');
																                } 
																			
																			
																			}	
																		
																		
																		
																	}
														 
														 ";
					 
					
					$data['extraheader'] = ' 
											 <script src="'.config_item('base_url').'third_party/flexigrid/flexigrid.js"></script>';
					$data['extraheader'] .= '<link type="text/css" href="'.config_item('base_url').'third_party/flexigrid/css/flexigrid/flexigrid.css" rel="stylesheet" /> ';
				 
					$data['extraheader'] .= ' 
					<script src="'.config_item('base_url').'js/swfobject.js" type="text/javascript"></script>
					';
		 	       $data['onloadextraheader'] .= " 
											var so;				
		 									";
					$data['usetips'] = true;			
				
		
			 
			 
			 
			 
			$output = $this->load->view('admin/testimonials' , $data , true); 	
			$this->output($output , $data);	
		}
		
		
		}else {$output = $this->load->view('admin/login', $data , true);
	    
		$this->output($output , $data);
		} 
	}
	
	function gettestmonials(){
								 $page 		= isset($_POST['page']) 	? $_POST['page'] : 1;
		                        $rp 		= isset($_POST['rp']) 		? $_POST['rp'] : 10;
		                        $sortname 	= isset($_POST['sortname']) ? $_POST['sortname'] : 'id';
		                        $sortorder 	= isset($_POST['sortorder'])? $_POST['sortorder'] : 'desc';
		                        $query 		= isset($_POST['query']) 	? $_POST['query'] : '';
		                        $qtype 		= isset($_POST['qtype']) 	? $_POST['qtype'] : 'title';
		                          
		                        
		                        $results = $this->adminmodel->gettestimonials($page, $rp, $sortname ,$sortorder  ,$query  , $qtype);
								header("Expires: Mon, 26 Jul 1997 05:00:00 GMT" ); 
								header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT" ); 
								header("Cache-Control: no-cache, must-revalidate" ); 
								header("Pragma: no-cache" );
								header("Content-type: text/x-json");
								$json = "";
								$json .= "{\n";
								$json .= "page: $page,\n";
								$json .= "total: ".$results['count'].",\n";
								$json .= "rows: [";
								$rc = false;
								  
								foreach ($results['result'] as $row) {
								  $shape = '';
								  
									if ($rc) $json .= ",";
									$json .= "\n {";
									$json .= "id:'".$row['id']."',";
									$json .= "cell:['".$row['adddate']."'";
									if($row['status'] == 'accepted')
									$json .= ",'<img src=\'".config_item('base_url')."images/accept.png\' width=\'20\'>'";
									elseif ($row['status'] == 'rejected')
									$json .= ",'<img src=\'".config_item('base_url')."images/error.png\' width=\'20\'>'";	
									else $json .= ",'<img src=\'".config_item('base_url')."images/new.png\' width=\'20\'>'";	
									$json .= ",'".addslashes($row['name'])."'";	
									$json .= ",'".addslashes($row['email'])."'";	
									$json .= ",'".addslashes($row['httpaddress'])."'";
									$json .= ",'".str_replace("\r", '<br />', str_replace("\n", '<br />', addslashes($row['description'])))."'";
									$json .= "]";
									$json .= "}";
									$rc = true;		
									
								}
								$json .= "]\n";
								$json .= "}";
								echo $json;
		
	}
	
	function diamondsitemap($action = 'view' , $id = 'diamond'){
	$data = $this->getCommonData();
	if($this->isadminlogin()){
	
	$data['onloadextraheader'] = '$("#secondpane p.menu_head").click(function()
	{
	$(this).css({backgroundImage:"url('.config_item('base_url').'images/minus.jpg)"}).next("div.menu_body").slideDown(500).siblings("div.menu_body").slideUp("slow");
	$(this).siblings().css({backgroundImage:"url('.config_item('base_url').'images/plus.jpg)"});
	});
	$("#sitemanage").click();
	';
	$data['leftmenus'] = $this->adminmodel->adminmenuhtml('sitemap');
	
	
	
	$url = config_item('base_url').'admin/getdiamondsitemap/'.$id;
	$data['action'] = $action;
	$data['onloadextraheader'] .= " $(\"#pageresults\").flexigrid
	(
	{
	url: '".$url."',
	dataType: 'json',
	colModel : [
	{display: 'ID Title', name : 'pageid', width : 100, sortable : true, align: 'left'},
	{display: 'Page Title', name : 'pagetitle', width : 300, sortable : true, align: 'left'},
	{display: 'Site Url', name : 'httpaddress', width : 300, sortable : true, align: 'left'},
	{display: 'Edit', name : 'pageid', width : 50, sortable : true, align: 'left'}
	],
	searchitems : [
	{display: 'pageid', name : 'pageid', isdefault: true},
	{display: 'pagetitle', name : 'pagetitle', isdefault: true}
	
	],
	sortname: \"pagemodule\",
	sortorder: \"asc\",
	usepager: true,
	title: '<h1 class=\"pageheader\">Diamond</h1>',
	useRp: true,
	rp: 20,
	showTableToggleBtn: false,
	width:800,
	height: 300
	}
	);
	";
	
	
	$data['extraheader'] = '
	<script src="'.config_item('base_url').'third_party/flexigrid/flexigrid.js"></script>';
	$data['extraheader'] .= '<link type="text/css" href="'.config_item('base_url').'third_party/flexigrid/css/flexigrid/flexigrid.css" rel="stylesheet" /> ';
	
	$data['extraheader'] .= '
	<script src="'.config_item('base_url').'js/swfobject.js" type="text/javascript"></script>
	';
	$data['onloadextraheader'] .= "
	var so;
	";
	$data['usetips'] = true;
	
	
	
	
	
	
	$output = $this->load->view('admin/diamondmap' , $data , true);
	$this->output($output , $data);
	
	
	
	}else {$output = $this->load->view('admin/login', $data , true);
	
	$this->output($output , $data);
	}
	}
	
	function getdiamondsitemap($module){
	$page = isset($_POST['page']) ? $_POST['page'] : 1;
	$rp = isset($_POST['rp']) ? $_POST['rp'] : 10;
	$sortname = isset($_POST['sortname']) ? $_POST['sortname'] : 'id';
	$sortorder = isset($_POST['sortorder'])? $_POST['sortorder'] : 'desc';
	$query = isset($_POST['query']) ? $_POST['query'] : '';
	$qtype = isset($_POST['qtype']) ? $_POST['qtype'] : 'title';
	
	
	$results = $this->adminmodel->getdiamondsmap($page, $rp, $sortname ,$sortorder ,$query,'title', '', $module);
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
	header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT" );
	header("Cache-Control: no-cache, must-revalidate" );
	header("Pragma: no-cache" );
	header("Content-type: text/x-json");
	$json = "";
	$json .= "{\n";
	$json .= "page: $page,\n";
	$json .= "total: ".$results['count'].",\n";
	$json .= "rows: [";
	$rc = false;
	
	foreach ($results['result'] as $row) {
	$shape = '';
	
	if ($rc) $json .= ",";
	$json .= "\n {";
	$json .= "pageid:'".$row['pageid']."',";
	$json .= "cell:['<input type=\'checkbox\' name=\'products[]\' value=\'".$row['pageid']."\'><a href=\"#\" onclick=\"viewpagevars(\'".config_item('base_url')."admin/pagedetails/view/".$row['pageid']."\',\'".config_item('base_url')."".$row['httpaddress']."\')\" class=\"blue search\">View Details</a>'";
	$json .= ",'".addslashes($row['pagetitle'])."'";
	$json .= ",'<a href=\"#\" onclick=\"viewpagevars(\'".config_item('base_url')."admin/pagedetails/view/".$row['pageid']."\',\'".config_item('base_url')."".$row['httpaddress']."\')\" class=\"blue search\">".addslashes($row['httpaddress'])."</a>'";
	$json .= ",'<a href=\"#\" onclick=\"editpageinfo(".$row['pageid'].")\">Edit</a>'";
	$json .= "]";
	$json .= "}";
	$rc = true;
	
	}
	$json .= "]\n";
	$json .= "}";
	echo $json;
	
	}

	function editpageinfos($id){
	// echo $id;
	$result['pageinfo'] = $this->adminmodel->editpagedata($id);
	$output = $this->load->view('admin/infopage' , $result , true);
	echo $output;
	
	}
	
	function pageupdate($id){
	$result = $this->adminmodel->updatepagedata($id);
	}
	
	function pagedetails($action = 'view' , $id = 0){
	$data = $this->getCommonData();
	if($this->isadminlogin()){
	
	$data['onloadextraheader'] = '$("#secondpane p.menu_head").click(function()
	{
	$(this).css({backgroundImage:"url('.config_item('base_url').'images/minus.jpg)"}).next("div.menu_body").slideDown(500).siblings("div.menu_body").slideUp("slow");
	$(this).siblings().css({backgroundImage:"url('.config_item('base_url').'images/plus.jpg)"});
	});
	$("#sitemanage").click();
	';
	$data['leftmenus'] = $this->adminmodel->adminmenuhtml('sitemap');
	
	
	
	
	$url = config_item('base_url').'admin/getpageinfo/'.$id;
	$data['action'] = $action;
	$data['onloadextraheader'] .= " $(\"#pageresultdetail\").flexigrid
	(
	{
	url: '".$url."',
	dataType: 'json',
	colModel : [
	{display: 'Page ID', name : 'pageid', width : 100, sortable : true, align: 'left'},
	{display: 'Page Title', name : 'pagetitle', width : 300, sortable : true, align: 'left'},
	{display: 'Description', name : 'description', width : 300, sortable : true, align: 'left'},
	{display: 'Edit', name : 'pageid', width : 50, sortable : true, align: 'left'}
	],
	searchitems : [
	{display: 'id #', name : 'id', isdefault: true},
	{display: 'name', name : 'name', isdefault: true},
	{display: 'status', name : 'status', isdefault: false}
	
	],
	sortname: \"pagemodule\",
	sortorder: \"asc\",
	usepager: true,
	title: '<h1 class=\"pageheader\">Diamond</h1>',
	useRp: true,
	rp: 20,
	showTableToggleBtn: false,
	width:800,
	height: 300
	}
	);
	";
	
	
	$data['extraheader'] = '
	<script src="'.config_item('base_url').'third_party/flexigrid/flexigrid.js"></script>';
	$data['extraheader'] .= '<link type="text/css" href="'.config_item('base_url').'third_party/flexigrid/css/flexigrid/flexigrid.css" rel="stylesheet" /> ';
	
	$data['extraheader'] .= '
	<script src="'.config_item('base_url').'js/swfobject.js" type="text/javascript"></script>
	';
	$data['onloadextraheader'] .= "
	var so;
	";
	$data['usetips'] = true;
	
	
	
	
	
	
	$output = $this->load->view('admin/diamondmap' , $data , true);
	$this->output($output , $data);
	
	
	}else {$output = $this->load->view('admin/login', $data , true);
	
	$this->output($output , $data);
	}
	}
	
	function getpageinfo($id){
	$page = isset($_POST['page']) ? $_POST['page'] : 1;
	$rp = isset($_POST['rp']) ? $_POST['rp'] : 10;
	$sortname = isset($_POST['sortname']) ? $_POST['sortname'] : 'id';
	$sortorder = isset($_POST['sortorder'])? $_POST['sortorder'] : 'desc';
	$query = isset($_POST['query']) ? $_POST['query'] : '';
	$qtype = isset($_POST['qtype']) ? $_POST['qtype'] : 'title';
	
	
	$results = $this->adminmodel->getgetpageinfodata($id);
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
	header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT" );
	header("Cache-Control: no-cache, must-revalidate" );
	header("Pragma: no-cache" );
	header("Content-type: text/x-json");
	$json = "";
	$json .= "{\n";
	$json .= "page: $page,\n";
	$json .= "total: ".$results['count'].",\n";
	$json .= "rows: [";
	$rc = false;
	
	foreach ($results['result'] as $row) {
	$shape = '';
	
	if ($rc) $json .= ",";
	$json .= "\n {";
	$json .= "pageid:'".$row['pageid']."',";
	$json .= "cell:['".$row['pageid']."'";
	$json .= ",'".addslashes($row['pageposition'])."'";
	//$json .= ",'".addslashes($row['description'])."'";
	$json .= ",'".str_replace("\r", '<br />', str_replace("\n", '<br />', addslashes($row['description'])))."'";
	$json .= ",'<a href=\"#\" onclick=\"editpageinfodata(".$row['pageid'].",\'".$row['pageposition']."\')\">Edit</a>'";
	$json .= "]";
	$json .= "}";
	$rc = true;
	
	}
	$json .= "]\n";
	$json .= "}";
	echo $json;
	
	}
	
	function managesearchresult(){
		$data 	= $this->getCommonData(); 
		if($this->isadminlogin()){
				
				$data['onloadextraheader'] = '$("#secondpane p.menu_head").click(function()
									    {
										     $(this).css({backgroundImage:"url('.config_item('base_url').'images/minus.jpg)"}).next("div.menu_body").slideDown(500).siblings("div.menu_body").slideUp("slow");
									         $(this).siblings().css({backgroundImage:"url('.config_item('base_url').'images/plus.jpg)"});
									        
										}); $("#sitemanage").click();
										';
			 $data['leftmenus']	=	$this->adminmodel->adminmenuhtml('managesearchresult');	
			 
			 $searchresult  = $this->adminmodel->getAllSearch();
			 $data['searchkeys'] = $this->commonmodel->makeoptions($searchresult , 'id' , 'keyfield');
			 $data['keyid'] = ( $_POST) ? $_POST['searchkey'] : '';
			 
			 $data['post'] = $_POST;
			 
			 $keydetails = '';
			 $data['keydetails'] = '';
			 
			 if($_POST){
			 	
			 	if($data['keyid'] != ''){
			 		$keydetails = $this->adminmodel->getSearchById($data['keyid']);
			 		$data['keydetails'] = $keydetails;
			 	}
			 }
			 
			 $output = $this->load->view('admin/managesearchresult' , $data , true); 		
		}else $output = $this->load->view('admin/login', $data , true);
	    
		$this->output($output , $data);
	}
	
	function rightads($templateid = ''){
		$data = $this->getCommonData();
		
		if($this->isadminlogin()){
			$this->load->model('adminmodel');
			$data['onloadextraheader'] = '$("#secondpane p.menu_head").click(function()
							{
								$(this).css({backgroundImage:"url('.config_item('base_url').'images/minus.jpg)"}).next("div.menu_body").slideDown(500).siblings("div.menu_body").slideUp("slow");
								$(this).siblings().css({backgroundImage:"url('.config_item('base_url').'images/plus.jpg)"});
							});
							$("#sitemanage").click();
							
							';
			
			$data['leftmenus'] = $this->adminmodel->adminmenuhtml('rightads');
			$pages = $this->adminmodel->getrightadd();
			$data['pages'] = $this->commonmodel->makeoptions($pages , 'id' , 'controller');
			$data['templateid'] = ( $_POST) ? $_POST['templateid'] : '';
			if($_POST){
				if(isset($_POST['contenthtml'])){
				if($this->adminmodel->saverightaddcontent($data['templateid'] , $_POST['contenthtml']))
					$data['success'] = 'Page Template saved';
				else $data['error'] = 'Page Template not saved';
				}
			}
			$data['use_tinymce'] = 'admin';
			
			$data['content'] = $this->adminmodel->getrightaddcontent($data['templateid']);
			$output = $this->load->view('admin/rightadd' , $data , true);
		}else $output =$this->load->view('admin/login', $data , true);
		
		
		$this->output($output , $data);

}

	function editpageinfodata($id, $position){
		$result['pageinfo'] = $this->adminmodel->editpageinfodata($id, $position);
		$output = $this->load->view('admin/editpage' , $result , true);
		echo $output;
	}
	
	function pagedataupdate($id, $position){
	   $result = $this->adminmodel->updatepageinfodata($id, $position);
	}
 	 
	function diamonds($action = 'view' , $id = 0){
		$data 	= $this->getCommonData(); 
		$data['extraheader']  = '';
		 
		
		if($this->isadminlogin()){
			if($action == 'delete'){  
									$ret = $this->adminmodel->diamonds($_POST , $action , $id);
									header("Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
									header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT" );
									header("Cache-Control: no-cache, must-revalidate" );
									header("Pragma: no-cache" );
									header("Content-type: text/x-json");
									$json = "";
									$json .= "{\n";
									$json .= "total: ".$ret['total'].",\n";
									$json .= "}\n";
									echo $json; 

			}else{
				
	if($action == 'add' || $action == 'edit'){
		
		
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		 
		$this->form_validation->set_rules('price', 'Price', 'trim|required');
		 
		
		 
		 
		$this->form_validation->set_error_delimiters('<font class="require">', '</font>');
									
		 if(isset($_POST[$action.'btn'])){
						   if ($this->form_validation->run() == FALSE){
								    $data['error'] = 'ERROR ! Please check the error fields';
								    if($action != 'edit')$data['details'] = $_POST;
							}else {
							 
								$ret = $this->adminmodel->jewelries($_POST , $action , $id);
								if($ret['error'] == '')$data['success'] = $ret['success'];
								else{
									$data['error'] = $ret['error'];
									if($action != 'edit')$data['details']  = $_POST;
								}
							 
							}
						}
		
	    $data['extraheader'] .= $this->commonmodel->addEditor('simple' );
      
		  if($action == 'edit') {
				$this->load->model('diamondmodel');
				$data['details'] = $this->diamondmodel->getDetailsByLot($id);
				$details = $data['details'];
				  
				$data['id'] = $id;
			}
				    
		}
			$data['onloadextraheader'] = '$("#secondpane p.menu_head").click(function()
									    {
										     $(this).css({backgroundImage:"url('.config_item('base_url').'images/minus.jpg)"}).next("div.menu_body").slideDown(500).siblings("div.menu_body").slideUp("slow");
									         $(this).siblings().css({backgroundImage:"url('.config_item('base_url').'images/plus.jpg)"});
										});
										$("#jewelrymanage").click();
										 
										';
			
	  	
			  $data['leftmenus']	=	$this->adminmodel->adminmenuhtml('diamonds');	
			  $url = config_item('base_url').'admin/getdiamonds';
			  $data['action'] = $action; 
			  $data['onloadextraheader'] .= "   $(\"#results\").flexigrid
																	(
																	{   	 							
																	url: '".$url."',
																	dataType: 'json',
																	colModel : [
																		{display: 'Lot #', name : 'lot', width : 80, sortable : true, align: 'center'},
																		{display: 'Owner', name : 'owner', width : 85, sortable : true, align: 'center'},
																		{display: 'Shape', name : 'shape', width : 80, sortable : true, align: 'center'},
																		{display: 'Carat', name : 'carat', width : 80, sortable : true, align: 'center'},
																		{display: 'color', name : 'color', width : 50, sortable : true, align: 'center'},
																		{display: 'cut', name : 'cut', width : 100, sortable : true, align: 'left'},
																		{display: 'clarity', name : 'clarity', width : 80, sortable : true, align: 'center'},
																		{display: 'price', name : 'price', width : 60, sortable : true, align: 'center'},
																		{display: 'Rap', name : 'Rap', width : 60, sortable : true, align: 'center',hide: false},
																		{display: 'Cert', name : 'Cert', width : 60, sortable : true, align: 'center'},
																		{display: 'Depth', name : 'Depth', width : 60, sortable : true, align: 'center',hide: false},
																		{display: 'Table', name : 'TablePercent', width : 60, sortable : true, align: 'center',hide: false},
																		{display: 'Girdle', name : 'Girdle', width : 60, sortable : true, align: 'center',hide: false},
																		{display: 'Culet', name : 'Culet', width : 60, sortable : true, align: 'center',hide: false},
																		{display: 'Polish', name : 'Polish', width : 60, sortable : true, align: 'center',hide: false},
																		{display: 'Symetry', name : 'Sym', width : 60, sortable : true, align: 'center',hide: false},
																		{display: 'Floururance', name : 'Flour', width : 60, sortable : true, align: 'center',hide: false},
																		{display: 'Meas', name : 'Meas', width : 60, sortable : true, align: 'center',hide: false},
																		{display: 'Comment', name : 'Comment', width : 250, sortable : false, align: 'left'},
																		{display: 'Stones', name : 'Stones', width : 50, sortable : true, align: 'left'},
																		{display: 'Cert_n', name : 'Cert_n', width : 50, sortable : true, align: 'left'},
																		{display: 'Stock_n', name : 'Stock_n', width : 50, sortable : true, align: 'left'},
																		{display: 'Make', name : 'Make', width : 150, sortable : true, align: 'left'},
																		{display: 'City', name : 'City', width : 150, sortable : true, align: 'left'},
																		{display: 'State', name : 'State', width : 150, sortable : true, align: 'left'},
																		{display: 'Country', name : 'Country', width : 150, sortable : true, align: 'left'},
																		{display: 'ratio', name : 'ratio', width : 150, sortable : true, align: 'left'}
																		],
																		 buttons : [ {name: 'Delete', bclass: 'delete', onpress : test},
																				{separator: true}
																			],
																	searchitems : [
																		{display: 'Lot #', name : 'lot', isdefault: true},
																		], 		
																	sortname: \"lot\",
																	sortorder: \"desc\",
																	usepager: true,
																	title: '<h1 class=\"pageheader\">Diamonds</h1>',
																	useRp: true,
																	rp: 25,
																	showTableToggleBtn: false,
																	width:1020,
																	height: 565
																	}
																	);
																	
																	function test(com,grid)
																	{
																		if (com=='Delete')
																			{ 
																			  
																			if($('.trSelected').length>0){
																			            if(confirm('Remove ' + $('.trSelected').length + ' rows?')){
																                                var items = $('.trSelected');
																                                var itemlist ='';
																                                for(i=0;i<items.length;i++){
																                                        itemlist+= items[i].id.substr(3)+\",\";
																                                }
																                                
																                                $.ajax({
																										   type: \"POST\",
																										   dataType: \"json\",
																										   url: \"".config_item('base_url')."admin/diamonds/delete\",
																										   data: \"items=\"+itemlist,
																										   success: function(data){
																										   	alert('Total Deleted rows: '+data.total);
																										    $(\"#results\").flexReload();
																										   }
																										 });
																										 						  
		
		
																                                														                        }
																                } else{
																                        alert('You have to select a row.');
																                } 
																			
																			
																			}
																		else if (com=='Add')
																			{
																				window.location = '".config_item('base_url')."admin/diamonds/add';
																			}			
																	}
														 
														 ";
					 
					
					$data['extraheader'] .= ' 
											 <script src="'.config_item('base_url').'third_party/flexigrid/flexigrid.js"></script>';
	               
					$data['extraheader'] .= '<link type="text/css" href="'.config_item('base_url').'third_party/flexigrid/css/flexigrid/flexigrid.css" rel="stylesheet" /> ';
				 
					$data['extraheader'] .= ' 
					<script src="'.config_item('base_url').'js/swfobject.js" type="text/javascript"></script>
					';
		 	       $data['onloadextraheader'] .= " 
											var so;				
		 									";
					$data['usetips'] = true;			
				
		
			
			  $output = $this->load->view('admin/diamonds' , $data , true); 	
			  
		 
			  $this->output($output , $data);
		
	   }
	 
		}else { $output =$this->load->view('admin/login', $data , true);$this->output($output , $data);}
	}  
	 
	function getdiamonds($table = 'products'){
							    $page 		= isset($_POST['page']) 	? $_POST['page'] : 1;
		                        $rp 		= isset($_POST['rp']) 		? $_POST['rp'] : 25;
		                        $sortname 	= isset($_POST['sortname']) ? $_POST['sortname'] : 'lot';
		                        $sortorder 	= isset($_POST['sortorder'])? $_POST['sortorder'] : 'desc';
		                        $query 		= isset($_POST['query']) 	? $_POST['query'] : '';
		                        $qtype 		= isset($_POST['qtype']) 	? $_POST['qtype'] : 'title';
		                          
		                        
		                        $results = $this->adminmodel->getdiamonds($page, $rp, $sortname ,$sortorder  ,$query  , $qtype , '', $table);
								header("Expires: Mon, 26 Jul 1997 05:00:00 GMT" ); 
								header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT" ); 
								header("Cache-Control: no-cache, must-revalidate" ); 
								header("Pragma: no-cache" );
								header("Content-type: text/x-json");
								$json = "";
								$json .= "{\n";
								$json .= "page: $page,\n";
								$json .= "total: ".$results['count'].",\n";
								$json .= "rows: [";
								$rc = false;
								  
								foreach ($results['result'] as $row) {
								 if($row['lot'] !=''){
								  $shape = '';
								  
								  switch ($row['shape']){
								  	case 'B':
								  		$shape = 'Round';
								  		break;
								  	case 'PR':
								  		$shape = 'Princess';
								  		break;
								  	case 'R':
								  		$shape = 'Radiant';
								  		break;
								  	case 'E':
								  		$shape = 'Emerald';
								  		break;
								  	case 'AS':
								  		$shape = 'Ascher';
								  		break;
								  	case 'O':
								  		$shape = 'Oval';
								  		break;
								  	case 'M':
								  		$shape = 'Marquise';
								  		break;
								  	case 'P':
								  		$shape = 'Pear shape';
								  		break;
								  	case 'H':
								  		$shape = 'Heart';
								  		break;
								  	case 'C':
								  		$shape = 'Cushion';
								  		break;								  	
								  	default:
								  		$shape = $row['shape'];
								  		break;
								  } 
								  
									if ($rc) $json .= ",";
									$json .= "\n {";
									$json .= "id:'".$row['lot']."',";
									$json .= "cell:['Lot #: ".$row['lot']."'";
									$json .= ",'".addslashes($row['owner'])."'";
									$json .= ",'".$shape."'";	
									$json .= ",'".addslashes($row['carat'])."'";	
									$json .= ",'".addslashes($row['color'])."'";	
									$json .= ",'".addslashes($row['cut'])."'";
									$json .= ",'".$this->fixclarity(addslashes($row['clarity']))."'";
									$json .= ",' $ ".addslashes($row['price'])."'";
									$json .= ",'".addslashes($row['Rap'])."'";
									$json .= ",'".addslashes($row['Cert'])."'";
									$json .= ",'".addslashes($row['Depth'])."'";
									$json .= ",'".addslashes($row['TablePercent'])."'";
									$json .= ",'".addslashes($row['Girdle'])."'";
									$json .= ",'".addslashes($row['Culet'])."'";
									$json .= ",'".addslashes($row['Polish'])."'";
									$json .= ",'".addslashes($row['Sym'])."'";
									$json .= ",'".addslashes($row['Flour'])."'";
									$json .= ",'".addslashes($row['Meas'])."'";
									$json .= ",'".str_replace("\r", '<br />', str_replace("\n", '<br />', addslashes($row['Comment'])))."'";
									$json .= ",'".addslashes($row['Stones'])."'";
									$json .= ",'".addslashes($row['Cert_n'])."'";
									$json .= ",'".addslashes($row['Stock_n'])."'";
									$json .= ",'".addslashes($row['Make'])."'";
									$json .= ",'".addslashes($row['City'])."'";
									$json .= ",'".addslashes($row['State'])."'";
									$json .= ",'".addslashes($row['Country'])."'";
									$json .= ",'".addslashes($row['ratio'])."'";
									$json .= "]";
									$json .= "}";
									$rc = true;		
								 }
								}
								$json .= "]\n";
								$json .= "}";
								echo $json;
								
								
	}
	
	function fixclarity($clarity = 7){
		$ret = 'NA';
		switch ($clarity){
			case 0:
			case '0':
				$ret = 'IF';
				break;
			case 1:
			case '1':
				$ret = 'VVS1';
				break;
			case 2:
			case '2':
				$ret = 'VVS2';
				break;
			case 3:
			case '3':
				$ret = 'VS1';
				break;
			case 4:
			case '4':
				$ret = 'VS2';
				break;
			case 5:
			case '5':
				$ret = 'SI1';
				break;
			case 6:
			case '6':
				$ret = 'SI2';
				break;
				
			default:
				$ret = 'NA';	
				
		}
		
		 
		return  $ret;
		
	}
	
	function helixprice($action = 'view' , $id = 0){
		$data 	= $this->getCommonData(); 
		$data['extraheader']  = '';
		if($this->isadminlogin())
		{
			if($action == 'delete'){  
									$ret = $this->helixmodel->helixprices($_POST , $action , $id);
									header("Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
									header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT" );
									header("Cache-Control: no-cache, must-revalidate" );
									header("Pragma: no-cache" );
									header("Content-type: text/x-json");
									$json = "";
									$json .= "{\n";
									$json .= "total: ".$ret['total'].",\n";
									$json .= "}\n";
									echo $json; 

			}else{
				
	if($action == 'add' || $action == 'edit'){
		
		
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		 
		$this->form_validation->set_rules('pricefrom', 'price from', 'trim|required');
		$this->form_validation->set_rules('priceto', 'to price', 'trim|required');
		$this->form_validation->set_rules('rate', 'price rate (%)', 'trim|required');
		 
		
		 
		 
		$this->form_validation->set_error_delimiters('<font class="require">', '</font>');
									
		 if(isset($_POST[$action.'btn'])){
						   if ($this->form_validation->run() == FALSE){
								    $data['error'] = 'ERROR ! Please check the error fields';
								    if($action != 'edit')$data['details'] = $_POST;
							}else {
							 
								$ret = $this->helixmodel->helixprices($_POST , $action , $id);
								if($ret['error'] == '')$data['success'] = $ret['success'];
								else{
									$data['error'] = $ret['error'];
									if($action != 'edit')$data['details']  = $_POST;
								}
							 
							}
						}
		
	    
		  if($action == 'edit') {
				$data['details'] = $this->helixmodel->getPriceByID($id);
				$details = $data['details'];
				$data['id'] = $id;
			}
				    
		}
			$data['onloadextraheader'] = '$("#secondpane p.menu_head").click(function()
									    {
										     $(this).css({backgroundImage:"url('.config_item('base_url').'images/minus.jpg)"}).next("div.menu_body").slideDown(500).siblings("div.menu_body").slideUp("slow");
									         $(this).siblings().css({backgroundImage:"url('.config_item('base_url').'images/plus.jpg)"});
										});
										$("#rapnet").click();
										 
										';
			
	  	
			  $data['leftmenus']	=	$this->adminmodel->adminmenuhtml('helixprice');	
			  $url = config_item('base_url').'admin/gethelixprice';
			  $data['action'] = $action; 
			  $data['onloadextraheader'] .= "   $(\"#results\").flexigrid
																	(
																	{   	 							
																	url: '".$url."',
																	dataType: 'json',
																	colModel : [
																		{display: 'ID #', name : 'rowid', width : 80, sortable : true, align: 'center'},
																		{display: 'Price From', name : 'pricefrom', width : 280, sortable : true, align: 'center'},
																		{display: 'Price To', name : 'priceto', width : 280, sortable : true, align: 'center'},
																		{display: 'Rate', name : 'rate', width : 280, sortable : true, align: 'center'}
																	 	],
																		 buttons : [ 
																		 		{name: 'Add', bclass: 'add', onpress : test},
																		        {name: 'Delete', bclass: 'delete', onpress : test},
																				{separator: true}
																			],
																	searchitems : [
																		{display: 'Price From', name : 'pricefrom', isdefault: true},
																		{display: 'Price To', name : 'priceto', isdefault: true},
																		], 		
																	sortname: \"rowid\",
																	sortorder: \"asc\",
																	usepager: true,
																	title: '<h1 class=\"pageheader\">Helix Price Rules</h1>',
																	useRp: true,
																	rp: 25,
																	showTableToggleBtn: false,
																	width:1020,
																	height: 400
																	}
																	);
																	
																	function test(com,grid)
																	{
																		if (com=='Delete')
																			{ 
																			  
																			if($('.trSelected').length>0){
																			            if(confirm('Remove ' + $('.trSelected').length + ' rows?')){
																                                var items = $('.trSelected');
																                                var itemlist ='';
																                                for(i=0;i<items.length;i++){
																                                        itemlist+= items[i].id.substr(3)+\",\";
																                                }
																                                
																                                $.ajax({
																										   type: \"POST\",
																										   dataType: \"json\",
																										   url: \"".config_item('base_url')."admin/helixprice/delete\",
																										   data: \"items=\"+itemlist,
																										   success: function(data){
																										   	alert('Total Deleted rows: '+data.total);
																										    $(\"#results\").flexReload();
																										   }
																										 });
																										 						  
		
		
																                                														                        }
																                } else{
																                        alert('You have to select a row.');
																                } 
																			
																			
																			}
																		else if (com=='Add')
																			{
																				window.location = '".config_item('base_url')."admin/helixprice/add';
																			}			
																	}
														 
														 ";
					 
					
					$data['extraheader'] .= ' 
											 <script src="'.config_item('base_url').'third_party/flexigrid/flexigrid.js"></script>';
					$data['extraheader'] .= '<link type="text/css" href="'.config_item('base_url').'third_party/flexigrid/css/flexigrid/flexigrid.css" rel="stylesheet" /> ';
				 
				 
			  $output = $this->load->view('admin/helixprice' , $data , true); 	
			  
		 
			  $this->output($output , $data);
		
	    	}
	    
			
			   
	    
			 
		}else { $output =$this->load->view('admin/login', $data , true);
	    
	    
		$this->output($output , $data);}
	}
	
	function gethelixprice(){
							    $page 		= isset($_POST['page']) 	? $_POST['page'] : 1;
		                        $rp 		= isset($_POST['rp']) 		? $_POST['rp'] : 25;
		                        $sortname 	= isset($_POST['sortname']) ? $_POST['sortname'] : 'lot';
		                        $sortorder 	= isset($_POST['sortorder'])? $_POST['sortorder'] : 'desc';
		                        $query 		= isset($_POST['query']) 	? $_POST['query'] : '';
		                        $qtype 		= isset($_POST['qtype']) 	? $_POST['qtype'] : 'title';
		                          
		                        
		                        $results = $this->helixmodel->getprices($page, $rp, $sortname ,$sortorder  ,$query  , $qtype , '');
								header("Expires: Mon, 26 Jul 1997 05:00:00 GMT" ); 
								header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT" ); 
								header("Cache-Control: no-cache, must-revalidate" ); 
								header("Pragma: no-cache" );
								header("Content-type: text/x-json");
								$json = "";
								$json .= "{\n";
								$json .= "page: $page,\n";
								$json .= "total: ".$results['count'].",\n";
								$json .= "rows: [";
								$rc = false;
								  
								foreach ($results['result'] as $row) {
								 if($row['rowid'] !=''){
								   if ($rc) $json .= ",";
									$json .= "\n {";
									$json .= "id:'".$row['rowid']."',";
									$json .= "cell:['<a href=\"".config_item('base_url')."admin/helixprice/edit/".$row['rowid']."\" class=\"edit\">Edit : ".$row['rowid']."</a>'";
									$json .= ",'$ ".addslashes($row['pricefrom'])."'";
									$json .= ",'$ ".addslashes($row['priceto'])."'";	
									$json .= ",'".addslashes($row['rate'])."'";	
									$json .= "]";
									$json .= "}";
									$rc = true;		
								 }
								}
								$json .= "]\n";
								$json .= "}";
								echo $json;
								
								
	}
	
	function syncronizerapnet($action = ''){
		$data 	= $this->getCommonData(); 
		
	
		if($this->isadminlogin()){
			if($action == 'confirm'){
				$data['confirm'] = true;
			    $ret = $this->adminmodel->syncronizerapnet();
			    if(isset($ret['success']) && $ret['success'] != '')$data['success'] = $ret['success'];
			     if(isset($ret['error']) && $ret['error'] != '')$data['error'] = $ret['error'];
			    
			}
			$data['onloadextraheader'] = '$("#secondpane p.menu_head").click(function()
									    {
										     $(this).css({backgroundImage:"url('.config_item('base_url').'images/minus.jpg)"}).next("div.menu_body").slideDown(500).siblings("div.menu_body").slideUp("slow");
									         $(this).siblings().css({backgroundImage:"url('.config_item('base_url').'images/plus.jpg)"});
										});
										$("#rapnet").click();
										';
			
			  $data['leftmenus']	=	$this->adminmodel->adminmenuhtml('syncronize');	
			   
			  $output = $this->load->view('admin/syncronizerapnet' , $data , true); 		
		}else  $output =$this->load->view('admin/login', $data , true);
	    
	    
		$this->output($output , $data);
		
	}
	
	function helixrules($action = ''){
		$data 	= $this->getCommonData(); 
		
	
		if($this->isadminlogin()){
			 
			$data['onloadextraheader'] = '$("#secondpane p.menu_head").click(function()
									    {
										     $(this).css({backgroundImage:"url('.config_item('base_url').'images/minus.jpg)"}).next("div.menu_body").slideDown(500).siblings("div.menu_body").slideUp("slow");
									         $(this).siblings().css({backgroundImage:"url('.config_item('base_url').'images/plus.jpg)"});
										});
										$("#rapnet").click();
										';
			
			  $data['leftmenus']	=	$this->adminmodel->adminmenuhtml('helixvar');	
			
			  if(isset($_POST['savehelixrules'])){
			  	 $ret = $this->helixmodel->savecurlurl($_POST);
			     if(isset($ret['success']) && $ret['success'] != '')$data['success'] = $ret['success'];
			     if(isset($ret['error']) && $ret['error'] != '')$data['error'] = $ret['error'];
			  	
			  }
			  $data['helixinclude']  = $this->helixmodel->gethelixinclude();
			  $data['helixexclude']  = $this->helixmodel->gethelixexclude();
			  if($data['helixinclude'] == '') $data['helixinclude'] = '60037,34292,55009,3430,12724,33858,11685,13227,6603,45680,36778,23402,30597,9913,46115,32185,46677,19029,29424,30113,32640,59908,40476,13198,20996,28578,24538,16393,18908,24893,19515,56065,14948,32102,46668,51356,14255,4356,25336,26199,46913,66863,11811,60822,2655,65821,43225,32931,36177,53017,24321,43820,43615,8588,39038,20986,21571,19106,21592,24784,46761';
			  if($data['helixexclude'] == '') $data['helixexclude'] = '39427,14152,14661,55155,16387,13321,8972,32856,34004,30579,18762,67851,13177,13712,48606,61592,67042,55582,18063,1928,24639,1309,50167,8142,53991,39216,30138,15558,13211,55605,39790,55149,6262,6907,48044,29361,12045,31896,32019,1178,12199,13789,15860,48623,39822,16172,12108,21677,44473,53443';
										   
			  
			  $output = $this->load->view('admin/helixrules' , $data , true); 		
		}else  $output =$this->load->view('admin/login', $data , true);
	    
	    
		$this->output($output , $data);
	}

	function gethelixdiamonds($action = ''){
		$data 	= $this->getCommonData(); 
		 
		if($this->isadminlogin()){
				if($action == 'delete'){  
									$ret = $this->adminmodel->diamonds($_POST , $action , $id , 'helix_products');
									header("Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
									header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT" );
									header("Cache-Control: no-cache, must-revalidate" );
									header("Pragma: no-cache" );
									header("Content-type: text/x-json");
									$json = "";
									$json .= "{\n";
									$json .= "total: ".$ret['total'].",\n";
									$json .= "}\n";
									echo $json; 

			         }else{	
							
				 	$data['leftmenus']	=	$this->adminmodel->adminmenuhtml('helixget');	
				 
				 	if($action == 'get') {
											$data['confirm'] = true;
									 	   
									       $helixinclude  = $this->helixmodel->gethelixinclude();
			  							   $helixexclude  = $this->helixmodel->gethelixexclude();
										   
			  							   if($helixinclude == '') $helixexclude = '39427,14152,14661,55155,16387,13321,8972,32856,34004,30579,18762,67851,13177,13712,48606,61592,67042,55582,18063,1928,24639,1309,50167,8142,53991,39216,30138,15558,13211,55605,39790,55149,6262,6907,48044,29361,12045,31896,32019,1178,12199,13789,15860,48623,39822,16172,12108,21677,44473,53443';

										  if($helixexclude == '')  $helixinclude = '60037,34292,55009,3430,12724,33858,11685,13227,6603,45680,36778,23402,30597,9913,46115,32185,46677,19029,29424,30113,32640,59908,40476,13198,20996,28578,24538,16393,18908,24893,19515,56065,14948,32102,46668,51356,14255,4356,25336,26199,46913,66863,11811,60822,2655,65821,43225,32931,36177,53017,24321,43820,43615,8588,39038,20986,21571,19106,21592,24784,46761';						
									        
									       if($helixinclude == '') $curlurl = 'http://www.diamonds.net/rapnet/DownloadListings/download.aspx?ExcludedSellers=39427&SortBy=owner&White=1&Programmatically=yes';
                                           else $curlurl =   'http://www.diamonds.net/rapnet/DownloadListings/download.aspx?SellerLogin='.$helixinclude.'&ExcludedSellers='.$helixexclude.'&SortBy=owner&White=1&Programmatically=yes'	;								       				     
										   
                                          
                                           $url = $curlurl;
										   //var_dump($url);
										   set_time_limit(2400);
										   $ch = curl_init();
										   curl_setopt($ch, CURLOPT_URL, $url);
										   curl_setopt($ch, CURLOPT_HEADER, 1);
									//     curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
									       curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
										   curl_setopt($ch, CURLOPT_USERPWD, '35696:samoa$velar');
										   curl_setopt($ch, CURLOPT_COOKIESESSION, 1);
										   $user_agent = "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)";
									
										   curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
										   $curldata = curl_exec($ch);
									       
									       $curldata = trim(substr($curldata , strpos($curldata , 'Content-Type: text/csv; charset=utf-8')+ strlen('Content-Type: text/csv; charset=utf-8')));
									         
										   curl_close($ch);
										   $rows = explode("\n",$curldata);
										   $i = 0;
										   // echo '<table border=1>';
										   if(sizeof($rows) > 0) $r = $this->helixmodel->emptyhelix();
														   foreach ($rows as $row){ 
														   	
												//		   	echo '<tr>';
																		   	if($i>1)
																		   	{
																		   		 
																		   		$cols = explode(',' , $row);
																		   	//	foreach ($cols as $col) echo '<td>'.$col.'</td>';
																				$t = $this->helixmodel->saveinhelix($cols);								   		 
																			//  echo $t;
																			
																		   	}
																		   	
												//		   	echo '</tr>';
														   	
														   	$i++;
														   }
											 $t = $this->helixmodel->fixhelix();				   
									        // echo '</table>';		
									        			   
										   
				 	}									     
									 
			  $url = config_item('base_url').'admin/getdiamonds/helix_products';
			  $data['action'] = $action; 
			  $data['onloadextraheader'] = " $(\"#secondpane p.menu_head\").click(function()
													    {
														     $(this).css({backgroundImage:\"url(".config_item('base_url')."images/minus.jpg)\"}).next(\"div.menu_body\").slideDown(500).siblings(\"div.menu_body\").slideUp(\"slow\");
													         $(this).siblings().css({backgroundImage:\"url(".config_item('base_url')."images/plus.jpg)\"});
														});
														$(\"#rapnet\").click();
														
														$(\"#results\").flexigrid
																	(
																	{   	 							
																	url: '".$url."',
																	dataType: 'json',
																	colModel : [
																		{display: 'Lot #', name : 'lot', width : 80, sortable : true, align: 'center'},
																		{display: 'Owner', name : 'owner', width : 85, sortable : true, align: 'center'},
																		{display: 'Shape', name : 'shape', width : 80, sortable : true, align: 'center'},
																		{display: 'Carat', name : 'carat', width : 80, sortable : true, align: 'center'},
																		{display: 'color', name : 'color', width : 50, sortable : true, align: 'center'},
																		{display: 'cut', name : 'cut', width : 100, sortable : true, align: 'left'},
																		{display: 'clarity', name : 'clarity', width : 80, sortable : true, align: 'center'},
																		{display: 'price', name : 'price', width : 60, sortable : true, align: 'center'},
																		{display: 'Rap', name : 'Rap', width : 60, sortable : true, align: 'center',hide: false},
																		{display: 'Cert', name : 'Cert', width : 60, sortable : true, align: 'center'},
																		{display: 'Depth', name : 'Depth', width : 60, sortable : true, align: 'center',hide: false},
																		{display: 'Table', name : 'TablePercent', width : 60, sortable : true, align: 'center',hide: false},
																		{display: 'Girdle', name : 'Girdle', width : 60, sortable : true, align: 'center',hide: false},
																		{display: 'Culet', name : 'Culet', width : 60, sortable : true, align: 'center',hide: false},
																		{display: 'Polish', name : 'Polish', width : 60, sortable : true, align: 'center',hide: false},
																		{display: 'Symetry', name : 'Sym', width : 60, sortable : true, align: 'center',hide: false},
																		{display: 'Floururance', name : 'Flour', width : 60, sortable : true, align: 'center',hide: false},
																		{display: 'Meas', name : 'Meas', width : 60, sortable : true, align: 'center',hide: false},
																		{display: 'Comment', name : 'Comment', width : 250, sortable : false, align: 'left'},
																		{display: 'Stones', name : 'Stones', width : 50, sortable : true, align: 'left'},
																		{display: 'Cert_n', name : 'Cert_n', width : 50, sortable : true, align: 'left'},
																		{display: 'Stock_n', name : 'Stock_n', width : 50, sortable : true, align: 'left'},
																		{display: 'Make', name : 'Make', width : 150, sortable : true, align: 'left'},
																		{display: 'City', name : 'City', width : 150, sortable : true, align: 'left'},
																		{display: 'State', name : 'State', width : 150, sortable : true, align: 'left'},
																		{display: 'Country', name : 'Country', width : 150, sortable : true, align: 'left'},
																		{display: 'ratio', name : 'ratio', width : 150, sortable : true, align: 'left'}
																		],
																		 buttons : [ {name: 'Delete', bclass: 'delete', onpress : test},
																				{separator: true}
																			],
																	searchitems : [
																		{display: 'Lot #', name : 'lot', isdefault: true},
																		], 		
																	sortname: \"lot\",
																	sortorder: \"desc\",
																	usepager: true,
																	title: '<h1 class=\"pageheader\">Rapnet Diamonds : Helix Diamonds Temp Table</h1>',
																	useRp: true,
																	rp: 25,
																	showTableToggleBtn: false,
																	width:1020,
																	height: 565
																	}
																	);
																	
																	function test(com,grid)
																	{
																		if (com=='Delete')
																			{ 
																			  
																			if($('.trSelected').length>0){
																			            if(confirm('Remove ' + $('.trSelected').length + ' rows?')){
																                                var items = $('.trSelected');
																                                var itemlist ='';
																                                for(i=0;i<items.length;i++){
																                                        itemlist+= items[i].id.substr(3)+\",\";
																                                }
																                                
																                                $.ajax({
																										   type: \"POST\",
																										   dataType: \"json\",
																										   url: \"".config_item('base_url')."admin/gethelixdiamonds/delete\",
																										   data: \"items=\"+itemlist,
																										   success: function(data){
																										   	alert('Total Deleted rows: '+data.total);
																										    $(\"#results\").flexReload();
																										   }
																										 });
																										 						  
		
		
																                                														                        }
																                } else{
																                        alert('You have to select a row.');
																                } 
																			
																			
																			}
																		 		
																	}
														 
														 ";
					 
					
					$data['extraheader']  = ' <script src="'.config_item('base_url').'third_party/flexigrid/flexigrid.js"></script> <link type="text/css" href="'.config_item('base_url').'third_party/flexigrid/css/flexigrid/flexigrid.css" rel="stylesheet" /> ';
				 					
									
				 	}
				 
				 
				 $output = $this->load->view('admin/rapnetindex' , $data , true); 	
			 
		}else $output = $this->load->view('admin/login', $data , true);
	    
		$this->output($output , $data);
	}
	
	function gethelixRedSellerdiamonds($action = ''){
		$data 	= $this->getCommonData(); 
		if($this->isadminlogin()){
										
				 	$data['leftmenus']	=	$this->adminmodel->adminmenuhtml('helixgetRedSeller');	
					$data['confirm'] = true;
					$helixexclude  = $this->helixmodel->gethelixexclude();
				  if($helixexclude == '') $helixexclude = '39427,14152,14661,55155,16387,13321,8972,32856,34004,30579,18762,67851,13177,13712,48606,61592,67042,55582,18063,1928,24639,1309,50167,8142,53991,39216,30138,15558,13211,55605,39790,55149,6262,6907,48044,29361,12045,31896,32019,1178,12199,13789,15860,48623,39822,16172,12108,21677,44473,53443';
				$RedSellerId=explode(",",$helixexclude);
					
					foreach($RedSellerId as $seller=>$val){
								if($val!=""){
									$redsellerDiomaond=$this->scrapingRedSellerdiomand($val);
								 }
					}
					
				//	$t = $this->helixmodel->fixhelix();				   
									        // echo '</table>';		
									        			   
										   
				 								     
								 
			  $url = config_item('base_url').'admin/getdiamonds/helix_productsredseller';
			  $data['action'] = $action; 
			  $data['onloadextraheader'] = " $(\"#secondpane p.menu_head\").click(function()
													    {
														     $(this).css({backgroundImage:\"url(".config_item('base_url')."images/minus.jpg)\"}).next(\"div.menu_body\").slideDown(500).siblings(\"div.menu_body\").slideUp(\"slow\");
													         $(this).siblings().css({backgroundImage:\"url(".config_item('base_url')."images/plus.jpg)\"});
														});
														$(\"#rapnet\").click();
														
														$(\"#results\").flexigrid
																	(
																	{   	 							
																	url: '".$url."',
																	dataType: 'json',
																	colModel : [
																		{display: 'Lot #', name : 'lot', width : 80, sortable : true, align: 'center'},
																		{display: 'Owner', name : 'owner', width : 85, sortable : true, align: 'center'},
																		{display: 'Shape', name : 'shape', width : 80, sortable : true, align: 'center'},
																		{display: 'Carat', name : 'carat', width : 80, sortable : true, align: 'center'},
																		{display: 'color', name : 'color', width : 50, sortable : true, align: 'center'},
																		{display: 'cut', name : 'cut', width : 100, sortable : true, align: 'left'},
																		{display: 'clarity', name : 'clarity', width : 80, sortable : true, align: 'center'},
																		{display: 'price', name : 'price', width : 60, sortable : true, align: 'center'},
																		{display: 'Rap', name : 'Rap', width : 60, sortable : true, align: 'center',hide: false},
																		{display: 'Cert', name : 'Cert', width : 60, sortable : true, align: 'center'},
																		{display: 'Depth', name : 'Depth', width : 60, sortable : true, align: 'center',hide: false},
																		{display: 'Table', name : 'TablePercent', width : 60, sortable : true, align: 'center',hide: false},
																		{display: 'Girdle', name : 'Girdle', width : 60, sortable : true, align: 'center',hide: false},
																		{display: 'Culet', name : 'Culet', width : 60, sortable : true, align: 'center',hide: false},
																		{display: 'Polish', name : 'Polish', width : 60, sortable : true, align: 'center',hide: false},
																		{display: 'Symetry', name : 'Sym', width : 60, sortable : true, align: 'center',hide: false},
																		{display: 'Floururance', name : 'Flour', width : 60, sortable : true, align: 'center',hide: false},
																		{display: 'Meas', name : 'Meas', width : 60, sortable : true, align: 'center',hide: false},
																		{display: 'Comment', name : 'Comment', width : 250, sortable : false, align: 'left'},
																		{display: 'Stones', name : 'Stones', width : 50, sortable : true, align: 'left'},
																		{display: 'Cert_n', name : 'Cert_n', width : 50, sortable : true, align: 'left'},
																		{display: 'Stock_n', name : 'Stock_n', width : 50, sortable : true, align: 'left'},
																		{display: 'Make', name : 'Make', width : 150, sortable : true, align: 'left'},
																		{display: 'City', name : 'City', width : 150, sortable : true, align: 'left'},
																		{display: 'State', name : 'State', width : 150, sortable : true, align: 'left'},
																		{display: 'Country', name : 'Country', width : 150, sortable : true, align: 'left'},
																		{display: 'ratio', name : 'ratio', width : 150, sortable : true, align: 'left'}
																		],
																		 buttons : [ {name: 'Delete', bclass: 'delete', onpress : test},
																				{separator: true}
																			],
																	searchitems : [
																		{display: 'Lot #', name : 'lot', isdefault: true},
																		], 		
																	sortname: \"lot\",
																	sortorder: \"desc\",
																	usepager: true,
																	title: '<h1 class=\"pageheader\">Rapnet Diamonds : Helix Diamonds Temp Table</h1>',
																	useRp: true,
																	rp: 25,
																	showTableToggleBtn: false,
																	width:1020,
																	height: 565
																	}
																	);
																	
																	function test(com,grid)
																	{
																		if (com=='Delete')
																			{ 
																			  
																			if($('.trSelected').length>0){
																			            if(confirm('Remove ' + $('.trSelected').length + ' rows?')){
																                                var items = $('.trSelected');
																                                var itemlist ='';
																                                for(i=0;i<items.length;i++){
																                                        itemlist+= items[i].id.substr(3)+\",\";
																                                }
																                                
																                                $.ajax({
																										   type: \"POST\",
																										   dataType: \"json\",
																										   url: \"".config_item('base_url')."admin/gethelixdiamonds/delete\",
																										   data: \"items=\"+itemlist,
																										   success: function(data){
																										   	alert('Total Deleted rows: '+data.total);
																										    $(\"#results\").flexReload();
																										   }
																										 });
																										 						  
		
		
																                                														                        }
																                } else{
																                        alert('You have to select a row.');
																                } 
																			
																			
																			}
																		 		
																	}
														 
														 ";
					 
					
					$data['extraheader']  = ' <script src="'.config_item('base_url').'third_party/flexigrid/flexigrid.js"></script> <link type="text/css" href="'.config_item('base_url').'third_party/flexigrid/css/flexigrid/flexigrid.css" rel="stylesheet" /> ';
				 					
									
				 	
				 
				 
				 $output = $this->load->view('admin/rapnetindex' , $data , true); 	
			 
		}else $output = $this->load->view('admin/login', $data , true);
	    
		$this->output($output , $data);
	}
	function diamondsreport(){
		$data 					= $this->getCommonData(); 
		if($this->isadminlogin()){
			$this->load->model('diamondmodel');
			$data['onloadextraheader'] = '$("#secondpane p.menu_head").click(function()
									    {
										     $(this).css({backgroundImage:"url('.config_item('base_url').'images/minus.jpg)"}).next("div.menu_body").slideDown(500).siblings("div.menu_body").slideUp("slow");
									         $(this).siblings().css({backgroundImage:"url('.config_item('base_url').'images/plus.jpg)"});
										});
										$("#jewelrymanage").click();
										 
										';
			
			  $data['leftmenus']	=	$this->adminmodel->adminmenuhtml('diamondsreport');	
			  
			  
			  
			  
			  $data['diamondscountbysellerswithshape'] = $this->adminmodel->diamondscountbysellerswithshape();
			  $data['diamondscountforsellers'] = $this->adminmodel->diamondscountbysellers();
			  
			  $output = $this->load->view('admin/diamonds_shapereport' , $data , true);
			  $output .= $this->load->view('admin/diamondsreport' , $data , true); 		
		}else  $output =$this->load->view('admin/login', $data , true);
	    
	    
		$this->output($output , $data);
	}
	
	function pricescopestructure(){
		$data 					= $this->getCommonData(); 
		if($this->isadminlogin()){
		 
			$data['onloadextraheader'] = '$("#secondpane p.menu_head").click(function()
									    {
										     $(this).css({backgroundImage:"url('.config_item('base_url').'images/minus.jpg)"}).next("div.menu_body").slideDown(500).siblings("div.menu_body").slideUp("slow");
									         $(this).siblings().css({backgroundImage:"url('.config_item('base_url').'images/plus.jpg)"});
										});
										$("#pricescopeconvert").click();
										 
										';
			//var_dump($_POST);
			if(isset($_POST['savepricescopest'])){
				$ret = $this->adminmodel->savePriceScopeStructure($_POST);
				if($ret['success'] != '') $data['success'] = $ret['success'];
				else  $data['error'] = $ret['error'];
				
			}
			
			  $data['leftmenus']	=	$this->adminmodel->adminmenuhtml('pricescopestruct');	
			  $data['structure'] = $this->adminmodel->getPriceScopeStructure(); 
			  $output = $this->load->view('admin/pricescopestruct' , $data , true); 		
		}else  $output =$this->load->view('admin/login', $data , true);
	    
	    
		$this->output($output , $data);
	}
	
	function savedcsv($cmd = 'view', $filename = ''){
			if($cmd == 'delete'){
				$filename = str_replace('_', '.', $filename);
				if(file_exists(config_item('base_path').'exports/'.$filename)){ unlink(config_item('base_path').'exports/'.$filename);}
				//var_dump(config_item('base_path').'exports/'.$filename);
			}
			
	   $data 					= $this->getCommonData(); 
		 
		if($this->isadminlogin()){
			$data['onloadextraheader'] = '$("#secondpane p.menu_head").click(function()
									    {
										     $(this).css({backgroundImage:"url('.config_item('base_url').'images/minus.jpg)"}).next("div.menu_body").slideDown(500).siblings("div.menu_body").slideUp("slow");
									         $(this).siblings().css({backgroundImage:"url('.config_item('base_url').'images/plus.jpg)"});
										});
										$("#pricescopeconvert").click();
										 
										';
		 	  $data['leftmenus']	=	$this->adminmodel->adminmenuhtml('savedcsv');	
			  $output = $this->load->view('admin/savedcsv' , $data , true); 		
			  	
			
			$this->output($output , $data);	
			 
			  
		}else  {$output =$this->load->view('admin/login', $data , true);$this->output($output , $data);}
		
	}
	
	function pricescopecsv($isbaseic = false){
		$data 					= $this->getCommonData(); 
		if($isbaseic)$data['basic'] = $isbaseic;
		if($this->isadminlogin()){
			 
			$data['onloadextraheader'] = '$("#secondpane p.menu_head").click(function()
									    {
										     $(this).css({backgroundImage:"url('.config_item('base_url').'images/minus.jpg)"}).next("div.menu_body").slideDown(500).siblings("div.menu_body").slideUp("slow");
									         $(this).siblings().css({backgroundImage:"url('.config_item('base_url').'images/plus.jpg)"});
										});
										$("#pricescopeconvert").click();
										 
										';
			
			  //var_dump($_POST);
			  
			  $data['structure'] = $this->adminmodel->getPriceScopeStructure('exportorder'); 
			  $exportsrt = '';
			  $exportqstr = '';
			  $exportcsvstr = '';
			  $i =  0;
			  foreach ($data['structure'] as $row){
						if($row['isexport']){
							$exportsrt .= '<b>[ </b><font color="green">'.$row['erdfield'].'</font> => <font color="red">'. $row['exportname'].' ('.$row['exportorder'].') </font><b>] </b>'.'  ,   ';
							$exportqstr .= $row['erdfield'] . ',';
							$exportcsvstr .= $row['exportname'].',';
						}
						
						$i++;
		 	  }
		 	  if(strlen($exportsrt)>1) $exportsrt = substr($exportsrt , 0, strlen($exportsrt) - 1);
		 	  $data['exportstr'] = $exportsrt;
		 	  if(strlen($exportqstr)>1) $exportqstr = substr($exportqstr , 0, strlen($exportqstr) - 1);
		 	  if(strlen($exportcsvstr)>1) $exportcsvstr = substr($exportcsvstr , 0, strlen($exportcsvstr) - 1);
		 	  
			
			  $data['leftmenus']	=	$this->adminmodel->adminmenuhtml('pricescopecsv');	
			  		
			  
				  if(isset($_POST['exportcsv']) || isset($_POST['savebasicsettings'])){
					  				    $shapesarray = array('B','PR','R','E','AS','O','M','P','H','C');
					  				    $colors = array("'D'","'E'","'F'","'G'","'H'","'I'","'J'","''");
					  				    $flours = array("'NO'","'FB'","'MED'","'MB'","'ST BLUE'","'VST BLUE'");
 					  				       
										$wheres = array();
										
										for($i =0; $i<10; $i ++){
											  $update = "update ".config_item('table_perfix')."pricescopebasic ";
										  	  if(isset($_POST['shape'.$i])) {
										  	  							    $where = '';
										  	  							    
										  	  							    $arraycert = array();
										  	  							    if(isset($_POST[$shapesarray[$i].'cert'])){
																					  	  							    foreach (($_POST[$shapesarray[$i].'cert']) as $e){
																					  	  							    	array_push($arraycert , "'".$e."'");
																					  	  							    	
																					  	  							    }
										  	  							    }
										  	  							    $arraysym = array();
										  	  							    if(isset($_POST[$shapesarray[$i].'sym'])){
																					  	  							    foreach (($_POST[$shapesarray[$i].'sym']) as $e){
																					  	  							    	array_push($arraysym , "'".$e."'");
																					  	  							    	
																					  	  							    }
										  	  							    }
										  	  							    $arraypolish = array();
										  	  							    if(isset($_POST[$shapesarray[$i].'polish'])){
																					  	  							    foreach (($_POST[$shapesarray[$i].'polish']) as $e){
																					  	  							    	array_push($arraypolish , "'".$e."'");
																					  	  							    	
																					  	  							    }
										  	  							    }
										  	  							    $where = "(";
										  	  							    $where .= "shape = '".$shapesarray[$i]."'";
										  	  							    
										  	  							    ///////////////////////carat settings  
										  	  							    $carat1 = (isset($_POST['carat1'.$i])) ? floatval($_POST['carat1'.$i]) : 0;
										  	  								$carat2 = (isset($_POST['carat2'.$i])) ? floatval($_POST['carat2'.$i]) : 0;
										  	  								if($carat1 > $carat2){
										  	  									$update .= "set	carat1 = ".$carat2." , 	carat1 = ".$carat1;
										  	  									$where .= " and carat >= $carat2 and carat <= $carat1 ";
										  	  								}else{
										  	  									$update .= "set carat1 = ".$carat1." , 	carat2 = ".$carat2;
										  	  									$where .= " and carat >= $carat1 and carat <= $carat2 ";
										  	  								}
										  	  								
										  	  								////////////////////// tablepercentage settings
										  	  								$tablepercent1 = (isset($_POST['tablepercent1'.$i])) ? floatval($_POST['tablepercent1'.$i]) : 0;
										  	  								$tablepercent2 = (isset($_POST['tablepercent2'.$i])) ? floatval($_POST['tablepercent2'.$i]) : 0;
										  	  								if($tablepercent1 > $tablepercent2){
										  	  									$where .= " and TablePercent >= $tablepercent2 and TablePercent <= $tablepercent1 ";
										  	  									$update .= ", table1 = ".$tablepercent2." , 	table2 = ".$tablepercent1;
										  	  								}else{
										  	  									$where .= " and TablePercent >= $tablepercent1 and TablePercent <= $tablepercent2 ";
										  	  									$update .= ", table1 = ".$tablepercent1." , 	table2 = ".$tablepercent2;
										  	  								}
										  	  								////////////////////////// depth settings
										  	  							    
										  	  								$depth1 = (isset($_POST['depth1'.$i])) ? floatval($_POST['depth1'.$i]) : 0;
										  	  								$depth2 = (isset($_POST['depth2'.$i])) ? floatval($_POST['depth2'.$i]) : 0;
										  	  								if($depth1 > $depth2){
										  	  									$where .= " and Depth >= $depth2 and Depth <= $depth1 ";
										  	  									$update .= ",	depth1 = ".$depth2." , 	depth2 = ".$depth1;
										  	  								}else{
										  	  									$where .= " and Depth >= $depth1 and Depth <= $depth2 ";
										  	  									$update .= ",	depth1 = ".$depth1." , 	depth2 = ".$depth2;
										  	  								}
										  	  								
										  	  							     
										  	  							    
										  	  							     ///////////////// color settings 
										  	  							    
										  	  							    $color1 = (isset($_POST['color1'.$i])) ? $_POST['color1'.$i] : 0;
										  	  								$color2 = (isset($_POST['color2'.$i])) ? $_POST['color2'.$i] : 0;
										  	  								if($color1 > $color2){
										  	  									$where .= " and color in (".implode(',',array_slice($colors , (int)$color2, ((int)($color1 - $color2)) +1 )).") ";
										  	  									$update .= ",color1 = ".$color2." , color2 = ".$color1;
										  	  								}else{
										  	  									$where .= " and color in (".implode(',',array_slice($colors , (int)$color1, ((int)($color2 - $color1)) +1 )).") ";
										  	  									$update .= ",color1 = ".$color1." , color2 = ".$color2;
										  	  								}
										  	  								
										  	  								
										  	  								//////////////flour settings
										  	  								
										  	  								$flour1 = (isset($_POST['flour1'.$i])) ? $_POST['flour1'.$i] : 0;
										  	  								$flour2 = (isset($_POST['flour2'.$i])) ? $_POST['flour2'.$i] : 0;
										  	  								if($flour1 > $flour2){
										  	  									$where .= " and Flour in (".implode(',',array_slice($flours , (int)$flour2, ((int)($flour1 - $flour2))+1)).") ";
										  	  									$update .= ",	flour1 = ".$flour2." , 	flour2 = ".$flour1;
										  	  								}else{
										  	  									$where .= " and Flour in (".implode(',',array_slice($flours , (int)$flour1, ((int)($flour2 - $flour1))+1)).") ";
										  	  									$update .= ",	flour1 = ".$flour1." , 	flour2 = ".$flour2;
										  	  								}
										  	  							    
										  	  							    if(sizeof($arraycert)>0){ $where .= " and cert in (". (implode(',',$arraycert)).")"; $update .= ", cert = '".str_replace("'","",implode(',',$arraycert))."'";}
										  	  							    if(sizeof($arraysym)>0){$where .= " and Sym in (". (implode(',',$arraysym)).")"; $update .= ", symmertry = '".str_replace("'","",implode(',',$arraysym))."'";}
										  	  							    if(sizeof($arraypolish)>0){$where .= " and polish in (". (implode(',',$arraypolish)).") "; $update .= ", polish = '".str_replace("'","",implode(',',$arraypolish))."'";}
										  	  								
										  	  							    $update .= ",export =1 where shape = '".$shapesarray[$i]."'";
										  	  								
										  	  								
										  	  								 
										  	  							    
										  	  							    $where .= ")"; 
										  	  								
										  	  								
										  	  								
										  	  								
										  	  								array_push($wheres , $where);
										  	  }else{ $update .= " set export = 0 where shape ='".$shapesarray[$i]."'";} 
										  	  
										  	  if(isset($_POST['savebasicsettings'])){
											        $this->adminmodel->savePriceScopeBasic($update);
											         
											  }
										}
										 
										$wherecondition = implode(' or ' , $wheres);	
										if(isset($_POST['savebasicsettings'])){
											         
											        $output = $this->load->view('admin/pricescopecsv' , $data , true); 
											        $this->output($output , $data);	
										}else{
			 										// var_dump($_POST);
													//var_dump($wherecondition);   
											    	$csvname = isset($_POST['csvfilename']) ? $_POST['csvfilename'].date('Y-m-d.h.i.s').'.csv' : date('Y-m-d.h.i.s').'.csv';
											    	$csvname = str_replace(' ', '',$csvname);
											    	$csvname = str_replace('_', '-',$csvname);
											        header("Content-type:text/octect-stream");
											        header("Content-Disposition:attachment;filename=".$csvname);
											        print $exportcsvstr . "\n";
											        $results = $this->adminmodel->getPriceScopeCSV($exportqstr , $wherecondition);
											        $fp = fopen(config_item('base_path').'exports/'.$csvname , 'w+');
											        fwrite($fp , $exportcsvstr . "\n");
											        foreach ($results as $result){
											        		  print '"' . stripslashes(implode('","',$result)) . "\"\n";
											         		  fprintf($fp , '"' . stripslashes(implode('","',$result)) . "\"\n");
											        }
											        
											        fclose($fp);
										} 
										//$this->output($output , $data);	
								    }else{
								    	$output = $this->load->view('admin/pricescopecsv' , $data , true); 
										$this->output($output , $data);	
								    }
			  
		}else  {$output =$this->load->view('admin/login', $data , true);$this->output($output , $data);}
	    
	    
	}
	
	function edittemplate($action = 'page' , $id  = ''){
		$data 					= $this->getCommonData(); 
		 
		if($this->isadminlogin()){
			$data['onloadextraheader'] = '$("#secondpane p.menu_head").click(function()
									    {
										     $(this).css({backgroundImage:"url('.config_item('base_url').'images/minus.jpg)"}).next("div.menu_body").slideDown(500).siblings("div.menu_body").slideUp("slow");
									         $(this).siblings().css({backgroundImage:"url('.config_item('base_url').'images/plus.jpg)"});
										});
										$("#design").click();
										';
			 
			  
		 	    $data['leftmenus']	=	$this->adminmodel->adminmenuhtml('edittemplate');	

		 	    if($action == 'edit'){
		 	    	$data['id'] = $id;
		 	    	$data['templatedata'] = $this->adminmodel->getaddedittemplate('get' , array(), $id);
		 	    	$filepath = config_item('base_path').$data['templatedata']['tpath'];
		 	    	
		 	    	if(isset($_POST[$action.'btn'])){
		 	    	           if(file_exists($filepath)){
		 	    	     		    $handle = fopen($filepath, "w");
	  							      if (fwrite($handle, $_POST['content']) === FALSE) {
									        $data['error'] = '<table width="100%" align="center"><tr><td width = "60"><img src="'.config_item('base_url').'/images/error.gif"></td><td>ERROR !!Cannot write to file ('.$filepath.') </td></tr>  </table>';
									        exit;
									    }
									    $data['success'] = '<table width="100%" align="center"><tr><td width = "60"><img src="'.config_item('base_url').'/images/ok.jpg"></td><td>Edit Success</td></tr>  </table>';
 
         
		 	    				 }
		 	    		
		 	    	}
		 	    	
		 	    	 if(file_exists($filepath)){
		 	    	     		    $handle = fopen($filepath, "r");
	  							    $contents = fread($handle, filesize($filepath));
									fclose($handle);
									$data['content'] = $contents;
		 	    	 }
		 	    	
		 	    }
					 	  
				$url = config_item('base_url').'admin/gettemplate';
				$data['action'] = $action;
				$data['onloadextraheader'] .= " $(\"#results\").flexigrid
				(
						{
						url: '".$url."',
						dataType: 'json',
						colModel : [
						{display: 'Resource Type', name : 'type', width : 200, sortable : true, align: 'left'},
						{display: 'Path', name : 'tpath', width : 250, sortable : true, align: 'left'},
						{display: 'Site Url', name : 'siteurl', width : 200, sortable : true, align: 'left'}
						],
						sortname: \"siteurl\",
						sortorder: \"asc\",
						usepager: true,
						title: '<h1 class=\"pageheader\">Template Resources</h1>',
						useRp: true,
						rp: 20,
						showTableToggleBtn: false,
						width:800,
						height: 300
						}
				);
				";
				
				
				$data['extraheader'] = '
				<script src="'.config_item('base_url').'third_party/flexigrid/flexigrid.js"></script>';
				$data['extraheader'] .= '<link type="text/css" href="'.config_item('base_url').'third_party/flexigrid/css/flexigrid/flexigrid.css" rel="stylesheet" /> ';

				$output = $this->load->view('admin/edittemplate' , $data , true); 		
			  	
			
			$this->output($output , $data);	
			 
			  
		}else  {$output =$this->load->view('admin/login', $data , true);$this->output($output , $data);}
	}
	
	function gettemplate(){
		$page = isset($_POST['page']) ? $_POST['page'] : 1;
		$rp = isset($_POST['rp']) ? $_POST['rp'] : 10;
		$sortname = isset($_POST['sortname']) ? $_POST['sortname'] : 'id';
		$sortorder = isset($_POST['sortorder'])? $_POST['sortorder'] : 'desc';
		$query = isset($_POST['query']) ? $_POST['query'] : '';
		$qtype = isset($_POST['qtype']) ? $_POST['qtype'] : 'id';
		
		
		$results = $this->adminmodel->gettemplate($page, $rp, $sortname ,$sortorder  ,$query  , $qtype);
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
		header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT" );
		header("Cache-Control: no-cache, must-revalidate" );
		header("Pragma: no-cache" );
		header("Content-type: text/x-json");
		$json = "";
		$json .= "{\n";
		$json .= "page: $page,\n";
		$json .= "total: ".$results['count'].",\n";
		$json .= "rows: [";
		$rc = false;
		
		foreach ($results['result'] as $row) {
		$shape = '';
		
		if ($rc) $json .= ",";
		$json .= "\n {";
		$json .= "id:'".$row['id']."',";
		$json .= "cell:['<a href=\"".addslashes(config_item('base_url'))."admin/edittemplate/edit/".$row['id']."\">".$row['type']." - Edit</a>'";
		$json .= ",'".addslashes(config_item('base_path')).addslashes(ucfirst($row['tpath']))."'";
		$json .= ",'<a href=\"".addslashes(config_item('base_url'))."".addslashes($row['siteurl'])."\" target=\"_blank\">".addslashes(config_item('base_url'))."".addslashes($row['siteurl'])."</a>'";
		$json .= "]";
		$json .= "}";
		$rc = true;
		
		}
		$json .= "]\n";
		$json .= "}";
		echo $json;
	}
	
function scrapingRedSellerdiomand($seller_id) {
	include_once("includes/simple_html_dom.php");
	$tab_header=array();
	$tab_content=array();
	$tab_inner_content=array();
    // create HTML DOM
	           //  http://www.diamonds.net/Rapnet/Sells/SearchResults.aspx?SellerLogin=39427&DisplayList=1&TextSize=10&NumResults=1000&SortBy=-[LowestDiscount]%20desc,LowestPrice
	          // search by pagination   "http://www.diamonds.net/rapnet/sells/SearchResults.aspx?SellerLogin=39427              &TextSize=10&NumResults=1000&SortBy=-[LowestDiscount]%20desc,LowestPrice	
	//          http://www.diamonds.net/Rapnet/Sells/SearchResults.aspx?SellerLogin=39427&DisplayList=1&TextSize=10&NumResults=1000&SortBy=-[LowestDiscount]%20desc,LowestPrice
	 $curlurl  ="http://www.diamonds.net/rapnet/sells/SearchResults.aspx?SellerLogin=".$seller_id."&DisplayList=1&TextSize=10&NumResults=1000&SortBy=-[LowestDiscount]%20desc,LowestPrice";					
	$userAgent = 'Googlebot/2.1 (http://www.googlebot.com/bot.html)'; 
	$url = $curlurl;
	set_time_limit(2400);
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HEADER, 1);
	//     curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_USERPWD, '35696:samoa$velar');
	curl_setopt($ch, CURLOPT_COOKIESESSION, 1);
	$user_agent = "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)";
	curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
	$curldata = curl_exec($ch);
	curl_close($ch);
    $html = str_get_html($curldata );
	
	foreach($html->find('table[id="ctl00_ContentPlaceHolderMainContent_SearchResults1_gvResults"]') as $row){
		$i=0;$k=0;
		if(!is_array($row)){
		//echo $row;
			foreach($row->find('tr') as $tr){
				if($i==0){
					foreach($tr->find('th') as $val){
						$tab_header[]=$val->plaintext;
					}
				}else{
					if((($i+10)%10)==1){
						foreach($tr->find('td') as $Tdval){
							$tab_content[$k][]=$Tdval->plaintext;
						}
						$k++;
					}else if((($i+10)%10)==3){
						foreach($tr->find('table[class="resTable"]') as $innerTable ){
							foreach($innerTable->find('tr td') as $innerText){
								$tab_inner_content[$k-1][]=$innerText->plaintext;
							}
						}
					}
				}
				$i++;
			}
		 }	
	}
	
	$database_product_info=array();
	foreach($tab_content as $key=>$table){
		 if(is_array($table)){
			if(isset($tab_inner_content[$key])){
					$database_product_info[$key]['lot']=$tab_inner_content[$key][1];
					
					$database_product_info[$key]['owner']=$table[6];
					$database_product_info[$key]['shape']=$table[7];
					$database_product_info[$key]['carat']='0';
					$database_product_info[$key]['color']='';
					$database_product_info[$key]['clarity']=$table[10];
					$database_product_info[$key]['price']='';//Image
					$database_product_info[$key]['Rap']='';//Image
					$database_product_info[$key]['Cert']='';//Image
					$database_product_info[$key]['Depth']=$table[18];
					$database_product_info[$key]['TablePercent']=$table[19];
					$database_product_info[$key]['Girdle']=$tab_inner_content[$key][9];
					$database_product_info[$key]['Culet']=$tab_inner_content[$key][15];
					$database_product_info[$key]['Polish']=$table[12];
					$database_product_info[$key]['Sym']=$table[13];
					$database_product_info[$key]['Flour']=$table[17];
					$database_product_info[$key]['Meas']=$table[20];
					$database_product_info[$key]['Comment']=$tab_inner_content[$key][29];
					$database_product_info[$key]['Stones']='';
					$database_product_info[$key]['Cert_n']='';
					$database_product_info[$key]['Stock_n']=$tab_inner_content[$key][11];
					$database_product_info[$key]['Make']='';
					$database_product_info[$key]['Date']=$tab_inner_content[$key][37];
					//explode city,state,country
						$location=$tab_inner_content[$key][23];
						$loc=explode(",",$location);
						$city=$loc[0];
						$state=$loc[1];
						$country=$loc[2];
					//--------------------------
					$database_product_info[$key]['City']=$city;
					$database_product_info[$key]['State']=$state;
					$database_product_info[$key]['Country']=$country;
					$database_product_info[$key]['ratio']=$tab_inner_content[$key][25];
					$database_product_info[$key]['cut']='';
					$database_product_info[$key]['tbl']='';
					$database_product_info[$key]['pricepercrt']='';
					$lot= $database_product_info[$key]['lot'];
					$database_product_info[$key]['tbl']='';
				 }	
			 }
		}

    $html->clear();
    unset($html);
	foreach($database_product_info as $cols){
	 
		$lot 			= isset($cols['lot']) ? $cols['lot'] : 0;
	  	$owner  		= isset($cols['owner']) ? $cols['owner'] : 'NA';
	  	$shape  		= isset($cols['shape']) ? $cols['shape'] : '';
	  	$carat  		= isset($cols['shape']) ? $cols['shape']: '0';
	  	$color  		= isset($cols['color']) ? $cols['color'] : '';
	  	$clarity  		= isset($cols['clarity']) ?$cols['clarity'] : '';
	  	$cut  			= isset($cols['cut']) ? $cols['cut']: '';
	  	$price  		= isset($cols['price']) ? $cols['price'] : '250';
	  	$Rap  	    	= isset($cols['Rap']) ?$cols['Rap'] : '0';
	  	$Cert  			= isset($cols['Cert']) ?$cols['Cert'] : '';
	  	$Depth  		= isset($cols['Depth']) ?$cols['Depth'] : '0';
	  	$TablePercent  	= isset($cols['TablePercent']) ?$cols['TablePercent'] : 'NA';
	  	$Girdle  		= isset($cols['Girdle']) ? $cols['Girdle'] : '';
	  	$Culet  		= isset($cols['Culet']) ? $cols['Culet']: '';
	  	$Polish  		= isset($cols['Polish']) ? $cols['Polish']: '';
	  	$Sym  			= isset($cols['Sym']) ? $cols['Sym'] : '';
	  	$Flour  		= isset($cols['Flour']) ? $cols['Flour'] : '';
	  	$Meas  			= isset($cols['Meas']) ? $cols['Meas'] : '0';
	  	$Comment  		= isset($cols['Comment']) ?'': '';
	  	$Stones  		= isset($cols['Stones']) ? $cols['Stones'] : '';
	  	$Cert_n  		= isset($cols['Cert_n']) ? $cols['Cert_n'] : '';
	  	$Stock_n    	= isset($cols['Stock_n']) ? $cols['Stock_n'] : '';
	  	$Make    		= isset($cols['Make']) ? $cols['Make'] : '';
	  	$Date    		= isset($cols['Date']) ? $cols['Date'] : '';
	  	$City    		= isset($cols['City']) ? $cols['City'] : '';
	  	$State   		= isset($cols['State']) ?$cols['State'] : '';
	  	$Country   		= isset($cols['Country']) ? $cols['Country'] : '';
	  	 
	  	 
	  	$Cert = strtoupper($Cert);
	  	$ratio = ( isset($ratio) && $ratio != null) ? $ratio : ' ';
		//$price = $this->helixmodel->erdprice($price);
				
	  	$data=array('lot'=> trim($lot),
					       'owner' =>  trim($owner),
					       'shape' =>  trim($shape),
					       'carat' => trim($carat),
					       'color' => trim($color),
					       'clarity' => trim($clarity),
					       'cut'   => trim($cut),
					       'price' => trim($price),
					       'Rap'   => trim($Rap),
					       'Cert'  			=> trim($Cert),
					       'Depth' 			=> trim($Depth),
					       'TablePercent' 	=> trim($TablePercent),
					       'Girdle' 		=> trim($Girdle),
					       'Culet' 	=> trim($Culet),
					       'Polish' => trim($Polish),
					       'Sym' 	=> trim($Sym),
					       'Flour' 	=> trim($Flour),
					       'Meas' 	=> trim($Meas),
					       'Comment' 	=> trim($Comment),
					       'Stones' 	=> trim($Stones),
					       'Cert_n' 	=> trim($Cert_n),
					       'Stock_n' 	=> trim($Stock_n),
					       'Make' 	=> trim($Make),
					       'Date' 	=> trim($Date),
					       'City' 	=> trim($City),
					       'State' 	=> trim($State),
					       'Country' => trim($Country),
					       'ratio'  => trim($ratio),
					       'tbl'	=> ''
						);
	
	if(($this->helixmodel->lotExistRedSeller($lot))==FALSE){
	
	$isinsert = $this->db->insert($this->config->item('table_perfix').'helix_productsredseller',$data);
		$this->db->insert_id();
		
	}
		
		
	}


 }
 
 
 function ViewhelixRedSellerdiamonds($action = ''){
		$data 	= $this->getCommonData(); 
		if($this->isadminlogin()){
										
				 	$data['leftmenus']	=	$this->adminmodel->adminmenuhtml('helixgetRedSellerView');	
					$data['confirm'] = true;
							 
			  $url = config_item('base_url').'admin/getdiamonds/helix_productsredseller';
			  $data['action'] = $action; 
			  $data['onloadextraheader'] = " $(\"#secondpane p.menu_head\").click(function()
													    {
														     $(this).css({backgroundImage:\"url(".config_item('base_url')."images/minus.jpg)\"}).next(\"div.menu_body\").slideDown(500).siblings(\"div.menu_body\").slideUp(\"slow\");
													         $(this).siblings().css({backgroundImage:\"url(".config_item('base_url')."images/plus.jpg)\"});
														});
														$(\"#rapnet\").click();
														
														$(\"#results\").flexigrid
																	(
																	{   	 							
																	url: '".$url."',
																	dataType: 'json',
																	colModel : [
																		{display: 'Lot #', name : 'lot', width : 80, sortable : true, align: 'center'},
																		{display: 'Owner', name : 'owner', width : 85, sortable : true, align: 'center'},
																		{display: 'Shape', name : 'shape', width : 80, sortable : true, align: 'center'},
																		{display: 'Carat', name : 'carat', width : 80, sortable : true, align: 'center'},
																		{display: 'color', name : 'color', width : 50, sortable : true, align: 'center'},
																		{display: 'cut', name : 'cut', width : 100, sortable : true, align: 'left'},
																		{display: 'clarity', name : 'clarity', width : 80, sortable : true, align: 'center'},
																		{display: 'price', name : 'price', width : 60, sortable : true, align: 'center'},
																		{display: 'Rap', name : 'Rap', width : 60, sortable : true, align: 'center',hide: false},
																		{display: 'Cert', name : 'Cert', width : 60, sortable : true, align: 'center'},
																		{display: 'Depth', name : 'Depth', width : 60, sortable : true, align: 'center',hide: false},
																		{display: 'Table', name : 'TablePercent', width : 60, sortable : true, align: 'center',hide: false},
																		{display: 'Girdle', name : 'Girdle', width : 60, sortable : true, align: 'center',hide: false},
																		{display: 'Culet', name : 'Culet', width : 60, sortable : true, align: 'center',hide: false},
																		{display: 'Polish', name : 'Polish', width : 60, sortable : true, align: 'center',hide: false},
																		{display: 'Symetry', name : 'Sym', width : 60, sortable : true, align: 'center',hide: false},
																		{display: 'Floururance', name : 'Flour', width : 60, sortable : true, align: 'center',hide: false},
																		{display: 'Meas', name : 'Meas', width : 60, sortable : true, align: 'center',hide: false},
																		{display: 'Comment', name : 'Comment', width : 250, sortable : false, align: 'left'},
																		{display: 'Stones', name : 'Stones', width : 50, sortable : true, align: 'left'},
																		{display: 'Cert_n', name : 'Cert_n', width : 50, sortable : true, align: 'left'},
																		{display: 'Stock_n', name : 'Stock_n', width : 50, sortable : true, align: 'left'},
																		{display: 'Make', name : 'Make', width : 150, sortable : true, align: 'left'},
																		{display: 'City', name : 'City', width : 150, sortable : true, align: 'left'},
																		{display: 'State', name : 'State', width : 150, sortable : true, align: 'left'},
																		{display: 'Country', name : 'Country', width : 150, sortable : true, align: 'left'},
																		{display: 'ratio', name : 'ratio', width : 150, sortable : true, align: 'left'}
																		],
																		 buttons : [ {name: 'Delete', bclass: 'delete', onpress : test},
																				{separator: true}
																			],
																	searchitems : [
																		{display: 'Lot #', name : 'lot', isdefault: true},
																		], 		
																	sortname: \"lot\",
																	sortorder: \"desc\",
																	usepager: true,
																	title: '<h1 class=\"pageheader\">Rapnet Diamonds : Helix Diamonds Temp Table</h1>',
																	useRp: true,
																	rp: 25,
																	showTableToggleBtn: false,
																	width:1020,
																	height: 565
																	}
																	);
																	
																	function test(com,grid)
																	{
																		if (com=='Delete')
																			{ 
																			  
																			if($('.trSelected').length>0){
																			            if(confirm('Remove ' + $('.trSelected').length + ' rows?')){
																                                var items = $('.trSelected');
																                                var itemlist ='';
																                                for(i=0;i<items.length;i++){
																                                        itemlist+= items[i].id.substr(3)+\",\";
																                                }
																                                
																                                $.ajax({
																										   type: \"POST\",
																										   dataType: \"json\",
																										   url: \"".config_item('base_url')."admin/gethelixdiamonds/delete\",
																										   data: \"items=\"+itemlist,
																										   success: function(data){
																										   	alert('Total Deleted rows: '+data.total);
																										    $(\"#results\").flexReload();
																										   }
																										 });
																										 						  
		
		
																                                														                        }
																                } else{
																                        alert('You have to select a row.');
																                } 
																			
																			
																			}
																		 		
																	}
														 
														 ";
					 
					
					$data['extraheader']  = ' <script src="'.config_item('base_url').'third_party/flexigrid/flexigrid.js"></script> <link type="text/css" href="'.config_item('base_url').'third_party/flexigrid/css/flexigrid/flexigrid.css" rel="stylesheet" /> ';
				 					
									
				 	
				 
				 
				 $output = $this->load->view('admin/rapnetindex' , $data , true); 	
			 
		}else $output = $this->load->view('admin/login', $data , true);
	    
		$this->output($output , $data);
	}

	function category($action = 'view' , $cid = 0, $id = 0){
		$data 	= $this->getCommonData(); 
		$data['extraheader']  = '';
		$collections = '';
		$typeoptions = '';
		$data['collections'] ='';
		$data['typeoptions'] = '';
		
		$this->load->model('categorymodel');
		$this->load->model('productmodel');
		
		$catarray	=	$this->categorymodel->getCategoryName($cid);
		$data['category'] = $catarray;

		if($this->isadminlogin()){
			if($action == 'delete'){  
									$ret = $this->adminmodel->category($_POST , $action , $id);
									header("Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
									header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT" );
									header("Cache-Control: no-cache, must-revalidate" );
									header("Pragma: no-cache" );
									header("Content-type: text/x-json");
									$json = "";
									$json .= "{\n";
									$json .= "total: ".$ret['total'].",\n";
									$json .= "}\n";
									echo $json; 

			}else{
				
	if($action == 'add' || $action == 'edit'){
		
		
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		 
		$this->form_validation->set_rules('category_label', 'category_name', 'trim|required');
		//$this->form_validation->set_rules('yellow_gold_price', 'Yellow Gold Price', 'trim|required');
		
		 
		 
		$this->form_validation->set_error_delimiters('<font class="require">', '</font>');
									
		 if(isset($_POST[$action.'btn'])){
//		 	print_r($_POST);
						   if ($this->form_validation->run() == FALSE){
								    $data['error'] = 'ERROR ! Please check the error fields';
								    if($action != 'edit')$data['details'] = $_POST;
							}else {

								$rootparentname = $this->productmodel->getRootParent($catarray);
								$ret = $this->adminmodel->category($_POST , $action , $rootparentname, $id);
								//$ret = $this->adminmodel->category($_POST , $action , $id);
								if($ret['error'] == '')$data['success'] = $ret['success'];
								else{
									$data['error'] = $ret['error'];
									if($action != 'edit')$data['details']  = $_POST;
								}
							 
							}
//							die();
						}
		
	    $data['extraheader'] .= $this->commonmodel->addEditor('simple' );
		
		  if($action == 'edit') {
				
				$data['details'] = $this->categorymodel->getAllByStock($id);
				$details = $data['details'];
				$data['cid'] = $cid;
				$data['id'] = $id;
			}
				    
		}
		$prodButton = '';
		if($cid != '0') {
			$prodButton = "{name: 'Add Product', bclass: 'add', onpress : test},";
		}
			$data['onloadextraheader'] = '$("#secondpane p.menu_head").click(function()
									    {
										     $(this).css({backgroundImage:"url('.config_item('base_url').'images/minus.jpg)"}).next("div.menu_body").slideDown(500).siblings("div.menu_body").slideUp("slow");
									         $(this).siblings().css({backgroundImage:"url('.config_item('base_url').'images/plus.jpg)"});
										});
										$("#jewelrymanage").click();
										 
										';
			
			  $data['leftmenus']	=	$this->adminmodel->adminmenuhtml('category');	
			  $url = config_item('base_url').'admin/getcategory/'.$cid;
			  $data['action'] = $action; 
			  $data['onloadextraheader'] .= "   $(\"#results\").flexigrid
																	(
																	{   	 							
																	url: '".$url."',
																	dataType: 'json',
																	colModel : [
																		{display: 'ID', name : 'id', width : 120, sortable : true, align: 'center'},
																		{display: 'Category Name', name : 'category_name', width : 300, sortable : true, align: 'center'},
																		{display: 'Category Image', name : 'image', width : 250, sortable : true, align: 'center'},
																		{display: 'Total Products', name : '', width : 120, sortable : true, align: 'center'}
																		],
																		 buttons : [{name: 'Add', bclass: 'add', onpress : test},
																				{name: 'Delete', bclass: 'delete', onpress : test},
																				$prodButton
																				{separator: true}
																			],
																	searchitems : [
																		{display: 'Lot #', name : 'id', isdefault: true},
																		{display: 'Category Name', name : 'category_name', isdefault: true},
																		], 		
																	sortname: \"id\",
																	sortorder: \"desc\",
																	usepager: true,
																	title: '<h1 class=\"pageheader\">Category Listing</h1>',
																	useRp: true,
																	rp: 10,
																	showTableToggleBtn: false,
																	width:1020,
																	height: 565
																	}
																	);
																	
																	function test(com,grid)
																	{
																		if (com=='Delete')
																			{ 
																			  
																			if($('.trSelected').length>0){
																			            if(confirm('Remove ' + $('.trSelected').length + ' rows?')){
																                                var items = $('.trSelected');
																                                var itemlist ='';
																                                for(i=0;i<items.length;i++){
																                                        itemlist+= items[i].id.substr(3)+\",\";
																                                }
																								//var url = 'admin/category/delete/'+itemlist;

																                                //prompt('a', url);
																                                $.ajax({
																										   type: \"POST\",
																										   dataType: \"json\",
																										   url: \"".config_item('base_url')."admin/category/delete\",
																										   data: \"items=\"+itemlist,
																										   success: function(data){
																										   	alert('Total Deleted rows: '+data.total);
																										    $(\"#results\").flexReload();
																										   }
																										 });
																										}
																                } else{
																                        alert('You have to select a row.');
																                } 
																			
																			
																			}
																		else if (com=='Add')
																			{
																				window.location = '".config_item('base_url')."admin/category/add/$cid';
																			}
																		else if (com=='Add Product')
																			{
																				window.location = '".config_item('base_url')."admin/product/add/$cid';
																			}		
																	}
														 
														 ";
					 
					
					$data['extraheader'] .= ' 
											 <script src="'.config_item('base_url').'third_party/flexigrid/flexigrid.js"></script>';
					$data['extraheader'] .= '<link type="text/css" href="'.config_item('base_url').'third_party/flexigrid/css/flexigrid/flexigrid.css" rel="stylesheet" /> ';
				 
					$data['extraheader'] .= ' 
					<script src="'.config_item('base_url').'js/swfobject.js" type="text/javascript"></script>
					';
					$data['extraheader'] .= ' 
					<script src="'.config_item('base_url').'js/t.js" type="text/javascript"></script>
					';
		 	       $data['onloadextraheader'] .= " 
											var so;				
		 									";
					$data['usetips'] = true;			
				
		
			
			  $output = $this->load->view('admin/category' , $data , true); 	
			  
		 
			  $this->output($output , $data);
		
	   }
	 
		}else { $output =$this->load->view('admin/login', $data , true);$this->output($output , $data);}
	}  
	function getcategory($id=0, $addoption=''){
							    $page 		= isset($_POST['page']) 	? $_POST['page'] : 1;
		                        $rp 		= isset($_POST['rp']) 		? $_POST['rp'] : 10;
		                        $sortname 	= isset($_POST['sortname']) ? $_POST['sortname'] : 'category_name';
		                        $sortorder 	= isset($_POST['sortorder'])? $_POST['sortorder'] : 'desc';
		                        $query 		= isset($_POST['query']) 	? $_POST['query'] : '';
		                        $qtype 		= isset($_POST['qtype']) 	? $_POST['qtype'] : 'category_name';
		                          
		                        
		                        $results = $this->adminmodel->getcategory($page, $rp, $sortname ,$sortorder  ,$query  , $qtype, $id);
								
								header("Expires: Mon, 26 Jul 1997 05:00:00 GMT" ); 
								header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT" ); 
								header("Cache-Control: no-cache, must-revalidate" ); 
								header("Pragma: no-cache" );
								header("Content-type: text/x-json");
								
								$json = "";
								$json .= "{\n";
								$json .= "page: $page,\n";
								$json .= "total: ".$results['count'].",\n";
								$json .= "rows: [";
								$rc = false;
								
								$this->load->model('categorymodel');
								$this->load->model('productmodel');

								$catarray	=	$this->categorymodel->getCategoryName($id);
								//$parentname = $this->productmodel->getRootParent($catarray);
								
								foreach ($results['result'] as $row) {
								  $shape = '';
									$catarray	=	$this->categorymodel->getCategoryName($row['id']);
									$parentname = $this->productmodel->getRootParent($catarray);
									$count = $this->adminmodel->getCategoryCount($row['id']);
									if ($rc) $json .= ",";
									$json .= "\n {";
									$json .= "id:'".$row['id']."',";
									$json .= "cell:['Lot #: ".$row['id']."<br /><a href=\'".config_item('base_url')."admin/category/view/".$row['id']."\'  class=\"edit\">View Category ($count[total])</a><br /><a href=\'".config_item('base_url')."admin/category/edit/".$row['parentid']."/".$row['id']."\'  class=\"edit\">Edit Category</a>'";
									$count = $this->adminmodel->getProductCount($row['id'], $parentname);
									$json .= ",'".$row['category_label']."'";	
									//$filepath = 'images/category/'.$parentname.'/'.$row['image'];
									$json .= ",'".addslashes($this->productmodel->getProductImage($row['image']))."'";
									$json .= ",'<a href=\'".config_item('base_url')."admin/product/view/".$row['id']."\'  class=\"edit\">View Product (".$count['total'].")</a>'";	
									$json .= "]";
									$json .= "}";
									$rc = true;		
									
								}
								$json .= "]\n";
								$json .= "}";
								echo $json;
								
	}


	function product($action = 'view' , $cid = 0, $id = 0){
		$data 	= $this->getCommonData(); 
		$data['extraheader']  = '';
		$collections = '';
		$typeoptions = '';
		$data['collections'] ='';
		$data['typeoptions'] = '';

		$this->load->model('categorymodel');
		$this->load->model('productmodel');
		
		$catarray	=	$this->categorymodel->getCategoryName($cid);
		$data['category'] = $catarray;
    	$view =	$this->productmodel->getFieldHeadingLayout($catarray);
		$headerButton =	$this->productmodel->getHeaderButton($catarray);
		if($this->isadminlogin()){
			if($action == 'delete'){  
									$ret = $this->adminmodel->product($_POST , $action , $id);
									header("Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
									header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT" );
									header("Cache-Control: no-cache, must-revalidate" );
									header("Pragma: no-cache" );
									header("Content-type: text/x-json");
									$json = "";
									$json .= "{\n";
									$json .= "total: ".$ret['total'].",\n";
									$json .= "}\n";
									echo $json; 

			}else{
				
	if($action == 'add' || $action == 'edit'){
		
		
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		 
		/*$this->form_validation->set_rules('price', 'Price', 'trim|required');
		$this->form_validation->set_rules('brand', 'Brand', 'trim|required');
		$this->form_validation->set_rules('uprice', 'User Price', 'trim|required');
		$this->form_validation->set_rules('model_number', 'Model Number', 'trim|required');
		$this->form_validation->set_rules('metal', 'Metal', 'trim|required');
		$this->form_validation->set_rules('style', 'Style', 'trim|required');
		$this->form_validation->set_rules('name', 'Name', 'trim|required');
		$this->form_validation->set_rules('brand', 'Brand', 'trim|required');
		$this->form_validation->set_rules('gender', 'Gender', 'trim|required');
		//$this->form_validation->set_rules('yellow_gold_price', 'Yellow Gold Price', 'trim|required');
		
		 
		 
		$this->form_validation->set_error_delimiters('<font class="require">', '</font>');*/
									
		 if(isset($_POST[$action.'btn'])){
//		 	print_r($_POST);
						   /*if ($this->form_validation->run() == FALSE){
								    $data['error'] = 'ERROR ! Please check the error fields';
								    if($action != 'edit')$data['details'] = $_POST;
							}else {*/
								$rootparentname = $this->productmodel->getRootParent($catarray);
								$ret = $this->adminmodel->product($_POST , $action , $rootparentname, $id);
								if($ret['error'] == '')$data['success'] = $ret['success'];
								else{
									$data['error'] = $ret['error'];
									if($action != 'edit')$data['details']  = $_POST;
								}
							 
							//}
//							die();
						}
		
	    $data['extraheader'] .= $this->commonmodel->addEditor('simple' );
        $data['collectionoptions'] = $this->commonmodel->makeoptions($this->adminmodel->getcollections() , 'collection' , 'collection');
		$collections = $this->productmodel->defineAddField($catarray);
		//print_r($collections);
		$data['collections'] = $collections;
	
		$data['cid'] = $cid;
		  if($action == 'edit') {
				$data['id'] = $id;
				$data['details'] = $this->productmodel->getAllByStock($id);
				//print_r($data['details']);
			}
				    
		}
			$data['onloadextraheader'] = '$("#secondpane p.menu_head").click(function()
									    {
										     $(this).css({backgroundImage:"url('.config_item('base_url').'images/minus.jpg)"}).next("div.menu_body").slideDown(500).siblings("div.menu_body").slideUp("slow");
									         $(this).siblings().css({backgroundImage:"url('.config_item('base_url').'images/plus.jpg)"});
										});
										$("#jewelrymanage").click();
										 
										';
			
			  $data['leftmenus']	=	$this->adminmodel->adminmenuhtml('product');	
			  $url = config_item('base_url').'admin/getproduct/'.$cid;
			  $data['action'] = $action; 
			  $data['onloadextraheader'] .= "   $(\"#results\").flexigrid
																	(
																	{   	 							
																	url: '".$url."',
																	dataType: 'json',
																	colModel : [
																		$view
																		],
																		 buttons : [
																			$headerButton
																			],
																	searchitems : [
																		{display: 'Lot #', name : 'id', isdefault: true},
																		{display: 'Product Name', name : 'price', isdefault: true},
																		], 		
																	sortname: \"id\",
																	sortorder: \"desc\",
																	usepager: true,
																	title: '<h1 class=\"pageheader\">Product Listing</h1>',
																	useRp: true,
																	rp: 10,
																	showTableToggleBtn: false,
																	width:1020,
																	height: 565
																	}
																	);
																	
																	function test(com,grid)
																	{
																		if (com=='Delete')
																			{ 
																			  
																			if($('.trSelected').length>0){
																			            if(confirm('Remove ' + $('.trSelected').length + ' rows?')){
																                                var items = $('.trSelected');
																                                var itemlist ='';
																                                for(i=0;i<items.length;i++){
																                                        itemlist+= items[i].id.substr(3)+\",\";
																                                }
																                                
																                                $.ajax({
																										   type: \"POST\",
																										   dataType: \"json\",
																										   url: \"".config_item('base_url')."admin/product/delete\",
																										   data: \"items=\"+itemlist,
																										   success: function(data){
																										   	alert('Total Deleted rows: '+data.total);
																										    $(\"#results\").flexReload();
																										   }
																										 });
																										 						  
		
		
																                                														                        }
																                } else{
																                        alert('You have to select a row.');
																                } 
																			
																			
																			}
																		else if (com=='Add')
																			{
																				window.location = '".config_item('base_url')."admin/product/add/$cid';
																			}			
																	}
														 
														 ";
					 
					
					$data['extraheader'] .= ' 
											 <script src="'.config_item('base_url').'third_party/flexigrid/flexigrid.js"></script>';
					$data['extraheader'] .= '<link type="text/css" href="'.config_item('base_url').'third_party/flexigrid/css/flexigrid/flexigrid.css" rel="stylesheet" /> ';
				 
					$data['extraheader'] .= ' 
					<script src="'.config_item('base_url').'js/swfobject.js" type="text/javascript"></script>
					';
					$data['extraheader'] .= ' 
					<script src="'.config_item('base_url').'js/t.js" type="text/javascript"></script>
					';
		 	       $data['onloadextraheader'] .= " 
											var so;				
		 									";
					$data['usetips'] = true;			
				
		
			
			  $output = $this->load->view('admin/product' , $data , true); 	
			  
		 
			  $this->output($output , $data);
		
	   }
	 
		}else { $output =$this->load->view('admin/login', $data , true);$this->output($output , $data);}
	}  
	function getproduct($cid, $addoption=''){
							    $page 		= isset($_POST['page']) 	? $_POST['page'] : 1;
		                        $rp 		= isset($_POST['rp']) 		? $_POST['rp'] : 10;
		                        $sortname 	= isset($_POST['sortname']) ? $_POST['sortname'] : 'id';
		                        $sortorder 	= isset($_POST['sortorder'])? $_POST['sortorder'] : 'desc';
		                        $query 		= isset($_POST['query']) 	? $_POST['query'] : $cid;
		                        $qtype 		= isset($_POST['qtype']) 	? $_POST['qtype'] : 'categoryid';
		                      
								$this->load->model('categorymodel');
								$this->load->model('productmodel');
								
								$catarray	=	$this->categorymodel->getCategoryName($cid);
								$parent = $this->productmodel->getRootParent($catarray);
								$viewField	=	$this->productmodel->defineViewFieldValue($catarray);
								//print_r($viewField);
								
		                        $results = $this->adminmodel->getproduct($page, $rp, $sortname, $sortorder, $cid, 'categoryid', '', $parent);
								header("Expires: Mon, 26 Jul 1997 05:00:00 GMT" ); 
								header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT" ); 
								header("Cache-Control: no-cache, must-revalidate" ); 
								header("Pragma: no-cache" );
								header("Content-type: text/x-json");
								$json = "";
								$json .= "{\n";
								$json .= "page: $page,\n";
								$json .= "total: ".$results['count'].",\n";
								$json .= "rows: [";
								$rc = false;
								  
								foreach ($results['result'] as $row) {
								  $shape = '';
								  
									if ($rc) $json .= ",";
									$json .= "\n {";
									$json .= "id:'".$row['id']."',";
									$json .= "cell:['Lot #: ".$row['lot']."<br /><a href=\'".config_item('base_url')."admin/product/edit/".$cid."/".$row['id']."\'  class=\"edit\">Edit Product</a>'";
									
									foreach($viewField as $field) {
										if($field['type']=='text') {
											$json .=  ",'".$row[$field['field']]."'";	
										} elseif($field['type']=='shape') {
											$json .= ",'".addslashes($this->productmodel->getProductShape($row[$field['field']]))."'";
										} elseif($field['type']=='price') {
											$json .= ",'$".addslashes ( number_format ( round ( $row ['price'] ), ',' ) )."'";
										} elseif($field['type']=='image') {
											$json .= ",'".$filepath.addslashes($this->productmodel->getProductImage($row[$field['field']]))."'";
										} elseif($field['type']=='link') {
											$json .= ",'".$this->productmodel->getLink($catarray['category_name'], $row[$field['field']], $row)."'";
										} else {
											$json .=  ",'".$row[$field['field']]."'";
										}

									}
									$json .= "]";
									$json .= "}";
									$rc = true;		
									
								}
								$json .= "]\n";
								$json .= "}";
								echo $json;
								
	}
    
	function rolex($action = 'view' , $id = 0){
		$data 	= $this->getCommonData(); 
		$data['extraheader']  = '';
		$collections = '';
		$typeoptions = '';
		$data['collections'] ='';
		$data['typeoptions'] = '';
		
		if($this->isadminlogin()){
			if($action == 'delete'){  
									$ret = $this->adminmodel->rolex($_POST , $action , $id);
									header("Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
									header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT" );
									header("Cache-Control: no-cache, must-revalidate" );
									header("Pragma: no-cache" );
									header("Content-type: text/x-json");
									$json = "";
									$json .= "{\n";
									$json .= "total: ".$ret['total'].",\n";
									$json .= "}\n";
									echo $json; 

			}else{
				
	if($action == 'add' || $action == 'edit'){
		
		
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		 
		$this->form_validation->set_rules('price', 'Price', 'trim|required');
		$this->form_validation->set_rules('brand', 'Brand', 'trim|required');
		$this->form_validation->set_rules('uprice', 'User Price', 'trim|required');
		$this->form_validation->set_rules('model_number', 'Model Number', 'trim|required');
		$this->form_validation->set_rules('metal', 'Metal', 'trim|required');
		$this->form_validation->set_rules('style', 'Style', 'trim|required');
		$this->form_validation->set_rules('name', 'Name', 'trim|required');
		$this->form_validation->set_rules('brand', 'Brand', 'trim|required');
		$this->form_validation->set_rules('gender', 'Gender', 'trim|required');
		//$this->form_validation->set_rules('yellow_gold_price', 'Yellow Gold Price', 'trim|required');
		
		 
		 
		$this->form_validation->set_error_delimiters('<font class="require">', '</font>');
									
		 if(isset($_POST[$action.'btn'])){
//		 	print_r($_POST);
						   if ($this->form_validation->run() == FALSE){
								    $data['error'] = 'ERROR ! Please check the error fields';
								    if($action != 'edit')$data['details'] = $_POST;
							}else {
								$ret = $this->adminmodel->rolex($_POST , $action , $id);
								if($ret['error'] == '')$data['success'] = $ret['success'];
								else{
									$data['error'] = $ret['error'];
									if($action != 'edit')$data['details']  = $_POST;
								}
							 
							}
//							die();
						}
		
	    $data['extraheader'] .= $this->commonmodel->addEditor('simple' );
        $data['collectionoptions'] = $this->commonmodel->makeoptions($this->adminmodel->getcollections() , 'collection' , 'collection');
	                //$data['sectionoptions'] = $this->commonmodel->makeoptions($this->adminmodel->getsections(), 'section' , 'section');
		 $data['brandoptions'] = $this->commonmodel->makeoptions($this->adminmodel->getWatchBrand() , 'brand' , 'brand');

		  if($action == 'edit') {
				$this->load->model('rolexmodel');
				$this->load->model('watchesmodel');
				$data['details'] = $this->watchesmodel->getWatchByProductId($id);
				$details = $data['details'];
				
				switch ($details['section']){
					case 'Earrings':
						$collections = '<option value="DiamondStud">Diamond Stud Earrings</option> 
											  <option value="BuildEarring">Build Your Own Earrings</option>
											';
						break;
					case 'EngagementRings':
						$collections = '
												<option value="International Collection">International Collection</option>
											';
						break;
					case 'Rolex':
						$collections = '
												<option value="MensWeddingRing">Men\'s Wedding Rings</option>  
									            <option value="WomensWeddingRing">Women\'s Wedding Rings</option>  
										 		<option value="WomensAnniversaryRing">Women\'s Anniversary Rings</option> 
											 	';
						break;
					case 'Pendants':
						$collections = '
												<option value="BuildPendant">Build your own Pendants</option>
											';
						break;
					default:
						break;
				}
				$data['collections'] =$collections;
				
				
				$data['animations'] = $this->adminmodel->getFlashByStockId($id);
				$data['id'] = $id;
			}
				    
		}
			$data['onloadextraheader'] = '$("#secondpane p.menu_head").click(function()
									    {
										     $(this).css({backgroundImage:"url('.config_item('base_url').'images/minus.jpg)"}).next("div.menu_body").slideDown(500).siblings("div.menu_body").slideUp("slow");
									         $(this).siblings().css({backgroundImage:"url('.config_item('base_url').'images/plus.jpg)"});
										});
										$("#jewelrymanage").click();
										 
										';
			
			  $data['leftmenus']	=	$this->adminmodel->adminmenuhtml('rolex');	
			  $url = config_item('base_url').'admin/getrolex';
			  $data['action'] = $action; 
			  $data['onloadextraheader'] .= "   $(\"#results\").flexigrid
																	(
																	{   	 							
																	url: '".$url."',
																	dataType: 'json',
																	colModel : [
																		{display: 'ID', name : 'productID', width : 80, sortable : true, align: 'center'},
																		{display: 'Retail Price', name : 'price1', width : 85, sortable : true, align: 'center'},
																		{display: 'User Price', name : 'price2', width : 120, sortable : true, align: 'center'},
																		{display: 'Brand', name : 'brand', width : 120, sortable : true, align: 'center'},
																		{display: 'Model Number', name : 'model_number', width : 75, sortable : true, align: 'center'},
																		{display: 'Metal', name : 'metal', width : 125, sortable : true, align: 'center'},
																		{display: 'Style', name : 'style', width : 60, sortable : true, align: 'center'},
																		{display: 'Gender', name : 'gender', width : 60, sortable : true, align: 'center',hide: true}
																		],
																		 buttons : [{name: 'Add', bclass: 'add', onpress : test},
																				{name: 'Delete', bclass: 'delete', onpress : test},
																				{separator: true}
																			],
																	searchitems : [
																		{display: 'Lot #', name : 'ProductID', isdefault: true},
																		{display: 'Gender', name : 'gender', isdefault: true},
																		{display: 'Style', name : 'style', isdefault: false}
																		
																		], 		
																	sortname: \"productID\",
																	sortorder: \"desc\",
																	usepager: true,
																	title: '<h1 class=\"pageheader\">Rolex</h1>',
																	useRp: true,
																	rp: 10,
																	showTableToggleBtn: false,
																	width:1020,
																	height: 565
																	}
																	);
																	
																	function test(com,grid)
																	{
																		if (com=='Delete')
																			{ 
																			  
																			if($('.trSelected').length>0){
																			            if(confirm('Remove ' + $('.trSelected').length + ' rows?')){
																                                var items = $('.trSelected');
																                                var itemlist ='';
																                                for(i=0;i<items.length;i++){
																                                        itemlist+= items[i].id.substr(3)+\",\";
																                                }
																                                
																                                $.ajax({
																										   type: \"POST\",
																										   dataType: \"json\",
																										   url: \"".config_item('base_url')."admin/rolex/delete\",
																										   data: \"items=\"+itemlist,
																										   success: function(data){
																										   	alert('Total Deleted rows: '+data.total);
																										    $(\"#results\").flexReload();
																										   }
																										 });
																										 						  
		
		
																                                														                        }
																                } else{
																                        alert('You have to select a row.');
																                } 
																			
																			
																			}
																		else if (com=='Add')
																			{
																				window.location = '".config_item('base_url')."admin/rolex/add';
																			}			
																	}
														 
														 ";
					 
					
					$data['extraheader'] .= ' 
											 <script src="'.config_item('base_url').'third_party/flexigrid/flexigrid.js"></script>';
					$data['extraheader'] .= '<link type="text/css" href="'.config_item('base_url').'third_party/flexigrid/css/flexigrid/flexigrid.css" rel="stylesheet" /> ';
				 
					$data['extraheader'] .= ' 
					<script src="'.config_item('base_url').'js/swfobject.js" type="text/javascript"></script>
					';
					$data['extraheader'] .= ' 
					<script src="'.config_item('base_url').'js/t.js" type="text/javascript"></script>
					';
		 	       $data['onloadextraheader'] .= " 
											var so;				
		 									";
					$data['usetips'] = true;			
				
		
			
			  $output = $this->load->view('admin/rolex' , $data , true); 	
			  
		 
			  $this->output($output , $data);
		
	   }
	 
		}else { $output =$this->load->view('admin/login', $data , true);$this->output($output , $data);}
	}  

	function getrolex($addoption=''){
			$page 		= isset($_POST['page']) 	? $_POST['page'] : 1;
			$rp 		= isset($_POST['rp']) 		? $_POST['rp'] : 10;
			$sortname 	= isset($_POST['sortname']) ? $_POST['sortname'] : 'productID';
			$sortorder 	= isset($_POST['sortorder'])? $_POST['sortorder'] : 'desc';
			$query 		= isset($_POST['query']) 	? $_POST['query'] : '';
			$qtype 		= isset($_POST['qtype']) 	? $_POST['qtype'] : 'title';
			  
			
			$results = $this->adminmodel->getrolex($page, $rp, $sortname ,$sortorder  ,$query  , $qtype);
			header("Expires: Mon, 26 Jul 1997 05:00:00 GMT" ); 
			header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT" ); 
			header("Cache-Control: no-cache, must-revalidate" ); 
			header("Pragma: no-cache" );
			header("Content-type: text/x-json");
			$json = "";
			$json .= "{\n";
			$json .= "page: $page,\n";
			$json .= "total: ".$results['count'].",\n";
			$json .= "rows: [";
			$rc = false;
			  
			foreach ($results['result'] as $row) {
			  $shape = '';
			  
				if ($rc) $json .= ",";
				$json .= "\n {";
				$json .= "id:'".$row['productID']."',";
				$json .= "cell:['Lot #: ".$row['productID']."<br /><a href=\'".config_item('base_url')."admin/rolex/edit/".$row['productID']."\'  class=\"edit\">Edit Lot</a>'";
				if(file_exists(config_item('base_path').addslashes($row['thumb'])) && $row['thumb'] != '')
				$json .= ",'<img src=\'".config_item('base_url').addslashes($row['thumb'])."\' width=\'80\'><br />$ ".addslashes($row['price1'])."'";
				else 
				$json .= ",'<img src=\'".config_item('base_url')."images/rings/noringimage.png\' width=\'80\'><br />$ ".addslashes($row['price1'])."'";
				$json .= ",'".addslashes($row['price2'])."'";	
				$json .= ",'".addslashes($row['brand'])."'";	
				$json .= ",'".addslashes($row['model_number'])."'";
				if ($row['metal'] == 'gold') 
					$metal = 'Gold';
				elseif($row['metal'] == 'ss') 
					$metal = 'Stainless Steel';
				else 
					$metal = 'Stainless Steel and Gold';
				$json .= ",'".addslashes($metal)."'";
				$json .= ",'".addslashes(ucfirst($row['style']))."'";
				$json .= ",'".addslashes(ucfirst($row['gender']))."'";
				$json .= "]";
				$json .= "}";
				$rc = true;		
				
			}
			$json .= "]\n";
			$json .= "}";
			echo $json;
								
	}
}

//mark79