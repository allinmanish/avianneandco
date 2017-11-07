<?php
class Watches extends Controller {



	function Watches()

	{

		parent::Controller();

		$this->load->model('jewelrymodel');

		

	}



	function index()

	{

		$data = $this->getCommonData();

		$data['title'] = 'Rolex Watches';

		$output = $this->load->view('watches/index' , $data , true);

		$this->output($output , $data);

	}



	private function getCommonData($banner='')

	{

		$data = array();

		$data = $this->commonmodel->getPageCommonData();

		return $data;



	}



	function output($layout = null , $data = array() , $isleft = true , $isright = true)

	{

		$data['loginlink'] = $this->user->loginhtml();

		$output = $this->load->view($this->config->item('template').'header' , $data , true);

		if($isleft)$output .= $this->load->view($this->config->item('template').'left' , $data , true);

		$output .= $layout;

		if($isright)$output .= $this->load->view($this->config->item('template').'right' , $data , true);

		$output .= $this->load->view($this->config->item('template').'footer', $data , true);

		$this->output->set_output($output);

	}	

	

	function ring() {

		

		$data = $this->getCommonData();

		$data['title'] = "Create Your Own Ring, Design Your Own Ring Online, Mens Titanium Rings,
Fancy Colored Diamonds";
		
		$data['meta_tags'] = '<meta http-equiv="Content-Type" content="text/html; iso-8859-1">
	<meta name="title" content="Create Your Own Ring, Design Your Own Ring Online, Mens Titanium Rings,
Fancy Colored Diamonds">
	<meta name="ROBOTS" content="INDEX,FOLLOW">
	<meta name="description" content="Buy diamond promise rings, mens titanium ring, mens titanium rings, fancy colored diamonds, create your own ring, design your own ring online. Purchase discounted rate mens titanium rings, fancy colored diamonds online">
	<meta name="abstract" content="Diamond Rings, Wholesale Diamonds, Estate Jewelry, Custom watches Rings, New York, Chicago, California, Boston, Las Vegas, Columbia, Montgomery">
	<meta name="keywords" content="Buy diamond promise rings, mens titanium ring, mens titanium rings, fancy colored diamonds, create your own ring, design your own ring online. Purchase discounted rate mens titanium rings, fancy colored diamonds online">
	<meta name="author" content="7techniques">
	<meta name="publisher" content="7techniques">
	<meta name="copyright" content="7techniques">
	<meta http-equiv="Reply-to" content="">
	<meta name="creation_Date" content="12/12/2008">
	<meta name="expires" content="">
	<meta name="revisit-after" content="7 days">
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">';

		$output = $this->load->view('watches/ringguide' , $data , true);

		$this->output($output , $data);

	}



	function watches_settings($diamondid = true,$addoption = '',$sidestone1 = '', $sidestone2= '', $extraoption = '')

	{

		$data = $this->getCommonData();

		$this->load->model('diamondmodel');
		$this->load->model('watchesmodel');

		$data['title'] = 'watches Ring Settings'; 

		$data['addoption'] = $addoption;

		$data['lot'] = $diamondid;

					

		$minprice =0; //$this->jewelrymodel->getMinPrice();

		$maxprice =1000000; //$this->jewelrymodel->getMaxPrice();

		$data['minprice'] = $minprice;

		$data['maxprice'] = $maxprice;

		$data['addoption'] = $addoption;

		$this->session->set_userdata('addoption',$addoption);

		
	

		$data['details'] = $this->diamondmodel->getDetailsByLot($diamondid);
			
		$data['extraoption'] = '';

		$data['tabhtml'] = '';
		
		$data['brand'] = $this->watchesmodel->getWatchBrand();
	
		if($diamondid != 'false'){

			if($addoption == 'addtoring') $data['tabhtml'] = $this->commonmodel->getTabHeader('ring', $diamondid);

			if($addoption == 'tothreestone') $data['tabhtml'] = $this->commonmodel->getThreeStoneTab('sidestone');

			

			//$data['tabhtml'] = $this->commonmodel->getTabHeader('ring',$diamondid);

						

			$this->session->set_userdata('mydiamondid',$diamondid);

						

			$data['extraheader']  = '';

					$data['extraheader'] .= '<script src="'.config_item('base_url').'js/jquery.ui.js" type="text/javascript"></script>

					<script src="'.config_item('base_url').'js/swfobject.js" type="text/javascript"></script>

					';

		 	$data['onloadextraheader'] = "getringresults();
			$('#pricerange').slider({ steps: 100, range: true, minValue : 1, slide:   function(e,ui) { 
			if($('#pricerange').slider('value',0) <= 30) {
				val = ($('#pricerange').slider('value',0)*25+(".$minprice."));
				$('#pricerange1').val(val);
			}
			else if($('#pricerange').slider('value',0) > 30 && $('#pricerange').slider('value',0) <= 50) {
				val = ($('#pricerange').slider('value',0)*75+(".$minprice."));
				$('#pricerange1').val(val);
			}
			else if($('#pricerange').slider('value',0) > 50 && $('#pricerange').slider('value',0) <= 70) {
				val = ($('#pricerange').slider('value',0)*250+(".$minprice."));
				$('#pricerange1').val(val);
			}
	 		else if($('#pricerange').slider('value',0) > 70 && $('#pricerange').slider('value',0) <= 80) {								 																																							                val = ($('#pricerange').slider('value',0)*1000+(".$minprice."));						 																																																																		                $('#pricerange1').val(val);
			}
			else if($('#pricerange').slider('value',0) > 80 && $('#pricerange').slider('value',0) <= 90) {
				val = ($('#pricerange').slider('value',0)*10000+(".$minprice."));
				$('#pricerange1').val(val);
			}
			else if($('#pricerange').slider('value',0) > 90 && $('#pricerange').slider('value',0) < 98)
			{
				val = ($('#pricerange').slider('value',0)*20000+(".$minprice."));
				$('#pricerange1').val(val);
			}
			else
			{
				$('#pricerange1').val(".$maxprice.");
			}
			// pricerange2
			if($('#pricerange').slider('value',1) <= 30 && $('#pricerange').slider('value',1) >1){
				val = ((-1)*$('#pricerange').slider('value',1)*25+(".$maxprice."));
				$('#pricerange2').val(val);
			}
			else if($('#pricerange').slider('value',1) > 30 && $('#pricerange').slider('value',1) <= 50) {									 				val = ((-1)*$('#pricerange').slider('value',1)*75+(".$maxprice."));
				$('#pricerange2').val(val);
			}
			else if($('#pricerange').slider('value',1) > 50 && $('#pricerange').slider('value',1) <= 70){
				val = ((-1)*$('#pricerange').slider('value',1)*250+(".$maxprice."));
				$('#pricerange2').val(val);
			}
			else if($('#pricerange').slider('value',1) > 70 && $('#pricerange').slider('value',1) <= 80){
				val = ((-1)*$('#pricerange').slider('value',1)*1000+(".$maxprice."));
				$('#pricerange2').val(val);
			}
			else if($('#pricerange').slider('value',1) > 80 && $('#pricerange').slider('value',1) <= 90){
				val = ((-1)*$('#pricerange').slider('value',1)*10000+(".$maxprice."));
				$('#pricerange2').val(val);
			}
			else if($('#pricerange').slider('value',1) > 90 && $('#pricerange').slider('value',1) < 98) {
				val = ((-1)*$('#pricerange').slider('value',1)*20000+(".$maxprice."));
				$('#pricerange2').val(val);
			}
			else if($('#pricerange').slider('value',1)==1) {
				$('#pricerange2').val(".$minprice.");
			}
			else {
				$('#pricerange2').val(".$maxprice.");
			}																																	
		}, change: function(e,ui) { 
				getwatchresults();	 
		} });
		var so;";						 

			$data['usetips'] = true;	

			$output = $this->load->view('watches/watch_settings' , $data , true);

			$this->output($output , $data, false,false);

		}

		elseif ($extraoption != ''){
		

			$data['extraoption'] = $extraoption;

			

			$data['extraheader']  = '';

					$data['extraheader'] .= '<script src="'.config_item('base_url').'js/jquery.ui.js" type="text/javascript"></script>

					<script src="'.config_item('base_url').'js/swfobject.js" type="text/javascript"></script>

					';

			

		 	$data['onloadextraheader'] = "";
			
			if($extraoption == "solitaire") {
				$data['onloadextraheader'] .= "$('#pavsechk').attr('checked',true);
   		     $('#solitairechk').attr('checked',true);
		     $('#sidestoneschk').attr('checked',false);
			 $('#threestonechk').attr('checked',false); 
			 $('#mathingchk').attr('checked',false); ";
			}
							
		
			if($extraoption == "sidestones") {
				$data['onloadextraheader'] .= "$('#pavsechk').attr('checked',false);
   		     $('#solitairechk').attr('checked',false);
		     $('#sidestoneschk').attr('checked',true);
			 $('#threestonechk').attr('checked',false); 
			 $('#mathingchk').attr('checked',false); ";
			}
			
			if($extraoption == "pave") {
				$data['onloadextraheader'] .= "$('#pavsechk').attr('checked',true);
   		     $('#solitairechk').attr('checked',false);
		     $('#sidestoneschk').attr('checked',false);
			 $('#threestonechk').attr('checked',false); 
			 $('#mathingchk').attr('checked',false); ";
			}
			
			if($extraoption == "threestone") {
				$data['onloadextraheader'] .= "$('#pavsechk').attr('checked',false);
   		     $('#solitairechk').attr('checked',false);
		     $('#sidestoneschk').attr('checked',false);
			 $('#threestonechk').attr('checked',true); 
			 $('#mathingchk').attr('checked',false); ";
			}
			
			if($extraoption == "matching") {
				$data['onloadextraheader'] .= "$('#pavsechk').attr('checked',false);
   		     $('#solitairechk').attr('checked',false);
		     $('#sidestoneschk').attr('checked',false);
			 $('#threestonechk').attr('checked',false); 
			 $('#mathingchk').attr('checked',true); ";
			}
			 
			 
			 $data['onloadextraheader'] .= "$('#anniversarychk').attr('checked',true);	
			 $('#halochk').attr('checked',false); 
			 $('#patinumchk').attr('checked',true);
 		     $('#whitegoldchk').attr('checked',true);
			 $('#ringshape').val();
			 $('#pricerange1').val();
  	         $('#pricerange2').val();
			 $('#marktchk').attr('checked',true); 
			 $('#erdchk').attr('checked',true); 
			 $('#vatchechk').attr('checked',true); 
			 $('#daussichk').attr('checked',true); 
			 $('#antiquechk').attr('checked',true); 
			 $('#goldchk').attr('checked',true); 
			 $('#goldsschk').attr('checked',true);
			getwatchresults();

		 									$('#pricerange').slider({ steps: 100, range: true, minValue : 1, slide:function(e,ui) {  $('#pricerange1').val((parseInt($('#pricerange').slider('value', 0)))* ((".$maxprice."-".$minprice.")/100));

						 																$('#pricerange2').val(parseInt($('#pricerange').slider('value', 1)) * ((".$maxprice."-".$minprice.")/100));

		 									}, change: function(e,ui) { 

																						$('#pricerange1').val((parseInt($('#pricerange').slider('value', 0)))* ((".$maxprice."-".$minprice.")/100));

						 																$('#pricerange2').val(parseInt($('#pricerange').slider('value', 1)) * ((".$maxprice."-".$minprice.")/100));

						 																getwatchresults();	 

															} });

											

											var so;				

		 									";

		 	

			$data['usetips'] = true;	

			$output = $this->load->view('watches/watch_settings' , $data , true);

			$this->output($output , $data, false,false);		

			

			

		}

		else {

			
			header('location:'.config_item('base_url').'diamonds/search/true/false/addtoring');

		}

		

		

		 

	}

 	

	function getwatchresults($isPave = true , $Solitaire = true , $platinum = true, $gold = true, $goldss = true, $minprice = 0 , $maxprice = 1000000 , $page = 0,$isMarkt = '', $lot = 0){
				
			    $start 		= ($page == 'undefined') ? 0 : $page ;

			    $lot 		= ($lot == 'undefined') ? 0 : $lot ;

			    $rp = 16;
				
                $this->load->model('watchesmodel');
				//echo 'a'.$isMarkt;
				$results = $this->watchesmodel->getWatch($start , $rp , $isPave   , $Solitaire  , $platinum  , $gold , $goldss, $minprice , $maxprice ,  $isMarkt);

				    

				$returnhtml = '';

				$this->load->model('sitepaging');

				$paginlinks = $this->sitepaging->getPageing($results['count'] ,'watches' , $start , 'price1', $rp);

			//	echo htmlspecialchars($paginlinks);

				//echo($paginlinks);

				//exit(0);

				$returnhtml .= $paginlinks.'<div class="hr"></div>';

				$addoption = $this->session->userdata('addoption');

				 

				foreach ($results['result'] as $row) {
					$rprice = $row['price1'];
					$oprice = $row['price2'];
					$sprice = $row['price1'] - $row['price2'];
					$returnhtml .= '

									<div class="floatl ringbox txtcenter" >

									

				    				'.ucfirst($row['style']).' collection<br>				    				

						    		 <a href="javascript:void(0)" onclick="viewWatchDetails('.$row['productID'].','.$lot.')">

							    		 <center>

								    		 <div class="ringtile">

								    		 <img id="ringbigimage'.$row['stock_number'].'" src="';

												if($gold=='gold')

												{

												 $img = file_exists(config_item('base_path').$row['thumb']) ? config_item('base_url').$row['thumb'] : config_item('base_url').'images/rings/noimage.jpg';

												}

												else

												{
													

												    $img = file_exists(config_item('base_path').$row['thumb']) ? config_item('base_url').$row['thumb'] : config_item('base_url').'images/rings/noimage.jpg';

												}

															    $returnhtml .= $img.'" width="70" ><br>															

								    		 <!-- <img src="http://www.engagementringsdirect.com/images/rings/'. $row['small_image'].'" width="70" ></a> <br>-->

								    		 </div>

							    		 </center>

						    		 <div class="txtsmall">'.$row['productName'].' <br> SKU:'.$row['SKU'].'<br>
										 Retail Price:$'.$row['price1'].'<br>
										 Our Price:$'.$row['price2'].'<br>
										 Your Saving:$'.$sprice.'

						    		 </div>

						    		 <a href="javascript:void(0)" class="search" onclick="viewWatchDetails('.$row['productID'].','.$lot.')">View Details</a>

						    		 <div >';

					$shapes = $this->watchesmodel->getShapeByStockId($row['stock_number']);		

						if(isset($shapes) && sizeof($shapes)>0){

						$returnhtml .= '<div class="hr"></div><div id="ringdiamondshapes'.$row['stock_number'].'" >';

						foreach ($shapes as $shape){

							$returnhtml .= '<div class="inline shapetile"><img  id="shape'.$shape['id'].'" src="'.config_item('base_url').'/images/diamonds/'.strtolower($shape['shape']).'.jpg" height="20px" width="20px" title="'.$shape['shape'].'"  onclick="$(\'#ringbigimage'.$row['stock_number'].'\').attr(\'src\',\''.config_item('base_url').'images/rings/'.$shape['image'].'\'); $(\'#ringdiamondshapes'.$row['stock_number'].' img\').css(\'border\',\'0px solid #000\'); $(\'#shape'.$shape['id'].'\').css(\'border\',\'1px solid #000\');"></div>';										

						}

						//echo '<div class="clear"></div>';

					     $returnhtml .= ' </div>';

						}

					$returnhtml .= ' </div>

					                 

						    		 </a>

						    		 <br>

						    		 

				    </div>

				    ';

					 	

				}

				

				$returnhtml .= '<div class="hr clear"></div>'.$paginlinks;

				 

				echo $returnhtml;

	}



	function search($option='',$details = false){

		

		$data = $this->getCommonData(); 

		$data['title'] = 'Rolex Watches';

		$this->load->model('diamondmodel');

		//$this->load->model('commonmodel');

		

		$minprice = 0; //$this->diamondmodel->getMinPrice();

		$maxprice = 1000000; //$this->diamondmodel->getMaxPrice();

		$data['totaldiamond'] = $this->diamondmodel->getCountDiamond($minprice,$maxprice);

		

		if($details || $details == 'true'){

			

			$minprice = ($this->session->userdata('searchminprice') && ($this->session->userdata('searchminprice')>$minprice) && ($this->session->userdata('searchminprice')<$maxprice)) ? ($this->session->userdata('searchminprice')) : $minprice;

			$maxprice = ($this->session->userdata('searchmaxprice') && ($this->session->userdata('searchmaxprice')<$maxprice) && ($this->session->userdata('searchmaxprice')>$minprice)) ? ($this->session->userdata('searchmaxprice')) : $maxprice;

			

			$data['minprice'] = $minprice;

			$data['maxprice'] = $maxprice;

		}else{

			

			$data['minprice'] = $minprice;

			$data['maxprice'] = $maxprice;

			

			/*$this->session->set_userdata('searchminprice',$data['minprice'] );

			$this->session->set_userdata('searchmaxprice',$data['maxprice']);*/

			

			$lastsearchMin = $this->session->userdata('searchminprice');

			$lastsearchMax = $this->session->userdata('searchmaxprice');

			$this->session->set_userdata('lastsearchMin',$lastsearchMin );

			$this->session->set_userdata('lastsearchMax',$lastsearchMax );			

					

			$data['lastminpr'] = $lastsearchMin;

			$data['lastmaxpr'] = $lastsearchMax; 			

			 

			$data['diashape'] = $this->session->userdata('shape');

			$data['shapename'] = $this->getShapeName($data['diashape']);

			 

		}

		

		 

				

		switch ($option){

			case $option == 'diamonds':

				$data['tabhtml'] = $this->commonmodel->getTabHeader('diamonds');

				$output =   $this->load->view('watches/searchdiamond' , $data , true)  ;

				break;

				

			/*case $option == 'ring':

				$data['tabhtml'] = $this->commonmodel->getTabHeader('ring');

				$output =   $this->load->view('watches/ringdetails' , $data , true)  ;

				break;*/

				

			case $option == 'addbasket':

				$data['tabhtml'] = $this->commonmodel->getTabHeader('addbasket');				

				$output =   $this->load->view('watches/addbasket' , $data , true)  ;

				break;

			default:

				$data['tabhtml'] = $this->commonmodel->getTabHeader();

				$output =   $this->load->view('watches/searchdiamond' , $data , true)  ;

				break;

		}			
	  
	  $data['title'] = "Diamonds Rolex Watches";
	  
		$data['meta_tags'] = '<meta http-equiv="Content-Type" content="text/html; iso-8859-1">
<meta name="title" content="Diamonds Rolex Wtches">
<meta name="ROBOTS" content="INDEX,FOLLOW">
<meta name="description" content="Buy Online asscher cut engagement rings, diamond solitaire engagement ring, white gold engagement ring, antique engagement ring, discount diamond engagement rings, cheap diamond engagement rings, wholesale diamond engagement rings, unique diamond engagement rings, three stone engagement rings">
<meta name="abstract" content="Diamond Rings, Wholesale Diamonds, Estate Jewelry, Custom Engagement Rings, New York, Chicago, California, Boston, Las Vegas, Columbia, Montgomery">
<meta name="keywords" content="asscher cut engagement rings, diamond solitaire engagement ring, white gold engagement ring, antique engagement ring, discount diamond engagement rings, cheap diamond engagement rings, wholesale diamond engagement rings, unique diamond engagement rings, three stone engagement rings">
<meta name="author" content="7techniques">
<meta name="publisher" content="7techniques">
<meta name="copyright" content="7techniques">
<meta http-equiv="Reply-to" content="">
<meta name="creation_Date" content="12/12/2008">
<meta name="expires" content="">
<meta name="revisit-after" content="7 days">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">';

	   	$this->output($output , $data);

	}

	

	function threestoneringsettings($centrestoneid = '', $sidestoneid1 = '',$sidestoneid2 = '',$addoption = ''){

		

		$data = $this->getCommonData();

		$data['title'] = 'Three-Stone Rings';



		$data['tabhtml'] = $this->commonmodel->getThreeStoneTab('ring',$centrestoneid,$sidestoneid1,$sidestoneid2);

		$data['centerstoneid'] = $centrestoneid;

		$data['sidestoneid1'] = $sidestoneid1;

		$data['sidestoneid2'] = $sidestoneid2;

		$data['addoption'] = $addoption;

		

		

		$data['extraheader']  = '';

					$data['extraheader'] .= '

					<script src="'.config_item('base_url').'js/swfobject.js" type="text/javascript"></script>

					';

			

        $this->load->model('watchesmodel');		

        $data['threestonerings'] = $this->jewelrymodel->getAllThreestoneRing();

		

		$output = $this->load->view('watches/threestonerings' , $data , true);

		$this->output($output , $data);

		    

		

	}



	function ringdetails($stockno = '',$ringoption = '', $lotno = ''){

		$data = $this->getCommonData();

		$data['title'] = 'Rolex Watch Details';

		

		$this->load->model('watchesmodel');

		$this->load->model('jewelrymodel');

		

		$this->session->set_userdata('stocknumber',$stockno);



			

		$data['details']  = $this->jewelrymodel->getAllByStock($stockno);

		 

		$data['tabhtml'] = $this->commonmodel->getTabHeader('ring',$lotno,$stockno);

		$data['lotno'] = $lotno;//$this->session->userdata('mydiamondid');

		$data['stockno'] = $stockno;

		

		if($data['lotno'] && $data['details']){

			$output = $this->load->view('watches/ringdetails' , $data , true);

			$this->output($output , $data);			

		}		

	}

	

	function addtobasket($diamondlotno='',$stockno='',$addoption='',$sidestone1 = '',$sidestone2 ='',$dsize=''){

		

		$data = $this->getCommonData();

		$this->load->model('watchesmodel');

		$this->load->model('diamondmodel');

		$this->load->model('jewelrymodel');

		$this->load->model('cartmodel');

		

		$data['title'] = 'Shopping Basket';

		$data['tabhtml'] = '';

		

		$data['addoption'] = $addoption;

		$data['lotno'] = $diamondlotno;

		$data['stockno'] = $stockno;

		$data['sidestone1'] = ($sidestone1=='false') ? '' : $sidestone1;

		$data['sidestone2'] = ($sidestone2=='false') ? '' : $sidestone2;

		

		//$data['sidestone2']	= $sidestone2;

		

		$data['dsize'] = $dsize;

		

		$data['side1'] = '';

		$data['side2'] = '';

		

	//	print_r($data);

	//	exit(0);

		

		if($sidestone1 != '' && $sidestone2 != ''){

			$data['side1'] = $this->diamondmodel->getDetailsByLot($sidestone1);

			$data['side2'] = $this->diamondmodel->getDetailsByLot($sidestone2);

		}

		

		if($addoption == 'addtoring'){$data['tabhtml'] = $this->commonmodel->getTabHeader('addbasket',$diamondlotno, $stockno);}

		if($addoption == 'tothreestone'){$data['tabhtml'] = $this->commonmodel->getTabHeader('addbasket',$diamondlotno, $stockno);} //$this->commonmodel->getThreeStoneTab('addbasket',$diamondlotno, $sidestone1,$sidestone2,$stockno);}

						

		//$data['ringdetails'] = $this->watchesmodel->getRingByStockId($stockno);

		$data['ringdetails'] = $this->jewelrymodel->getAllByStock($stockno);

		$data['diamonddetails'] = $this->diamondmodel->getDetailsByLot($diamondlotno); 

		

		/*$success = false;

		switch ($addoption){

			case 'tothreestone':

				$success = $this->cartmodel->add3stonering($diamondlotno,$sidestone1,$sidestone2,$stockno);

				echo ($success) ? 'Successfully added' : 'Faild to add! Please try again properly.';

				break;

			default:

				break;

		}*/

		

		$basket = $this->session->userdata('basket');

		$basket['ring']['diamond'] = $diamondlotno;

		$basket['ring']['setting'] = $stockno;

		$basket['ring']['dsize'] = $dsize;

		

		$data['extraheader']  = ' <script src="'.config_item('base_url').'js/swfobject.js" type="text/javascript"></script>

					';

		$data['flashfiles'] =  $this->watchesmodel->getFlashByStockId($stockno);	

		$this->session->set_userdata('basket',$basket);	 

		

		$output = $this->load->view('watches/addbasket' , $data , true);

		$this->output($output , $data);

	}

	

	function test()

	{

		echo config_item('base_path').'--------------'.dirname(__FILE__);

	}

	

	private function getShapeName($shapelist){

		$shapename = '';

		$shapestr = '';

		if(($this->session->userdata('shape'))){

			

			$shapes =  $this->session->userdata('shape');		

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

						$shapestr .= $shapename.", ";

					}				

				}

				$shapestr = substr($shapestr  , 0 , (strlen($shapestr) -2));	

			}

		}

		return $shapestr;

	}

	

	function searchresult(){

   		$data = $this->getCommonData(); 

		$data['title'] = 'Search Result';

		$this->load->model('searchresultmodel');

		$this->load->model('diamondmodel');

				

		$getsearchresult = '';

		$inputvalue = '';

		$inputarray = array();

		$diamonddetails = '';

		$jewelrydetails = '';

		$keydetails =  array();

		

		$inputvalue = $_POST['searchkeyword'];

		$inputarray = explode(' ',$inputvalue);

		

		for($i=0; $i<sizeof($inputarray); $i++){			

			

			if(is_numeric($inputarray[$i])){

				$diamonddetails = $this->diamondmodel->getDetailsByLot($inputarray[$i]);

				$jewelrydetails = $this->jewelrymodel->getAllByStock($inputarray[$i]);

			}

			$keydetails = $this->searchresultmodel->getAllSearchResultByKey($inputarray[$i]);

			

		}

		

		$data['inputvalue'] = $inputvalue;

		$data['diamonddetails'] = $diamonddetails;

		$data['jewelrydetails'] = $jewelrydetails;

		$data['keydetails'] = $keydetails;

		

		//var_dump($keydetails); 

			

	    $output = $this->load->view('watches/searchresult' , $data , true);

		$this->output($output , $data);	

   }

   	function watchdetails($productID = '', $addoption = ''){

		$data = $this->getCommonData();

		$lot 		= ($lot == 'undefined') ? 0 : $lot ;

	    //$this->load->model('diamondmodel');

		//$this->load->model('engagementmodel');

		$this->load->model('watchesmodel');

		//$data['products'] = $this->diamondmodel->getDetailsByLot($lot);

		$data['details'] = $this->watchesmodel->getWatchByProductId($productID);				

		$data['productID'] = $productID;

		$data['addoption'] = $addoption;

		//$data['ringoption'] = $ringoption;

		//$data['lot'] = $lot;

		

		//$data['flashfiles']	= $this->engagementmodel->getFlashByStockId($stockno);			

						

		if($data['details']){

			$output = $this->load->view('watches/watchdetails' , $data , true);

			echo $output;

		}	

	 

   }

   function addWatchtoEbay($productID = ''){
		$data = $this->getCommonData();

		$this->load->model('watchesmodel');
		echo'<Pre>';
		//$data['products'] = $this->diamondmodel->getDetailsByLot($lot);

		if($productID !='') {	
			$data['details'] = $this->watchesmodel->getWatchByProductId($productID);
			$status = $this->watchesmodel->addWatchtoEbay($data['details']);
			echo $status;
		} else {
			$data['details'] = $this->watchesmodel->getAllWatches();
			foreach($data['details'] AS $index=>$value) {
				$status = $this->watchesmodel->addWatchtoEbay($value);
				echo $status;
			}
		}
		

		
		
		print_r($data['details']);
		
						

			

	}

}