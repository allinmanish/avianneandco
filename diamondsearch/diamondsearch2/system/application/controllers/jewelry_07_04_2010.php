<?php class Jewelry extends Controller {

	function __construct(){

		parent::Controller();

		$this->load->model('jewelrymodel');

	}	

	

	function index()

	{

		$data = $this->getCommonData(); 

		$data['title'] = 'Jewelry';

	    $output = $this->load->view('jewelry/index' , $data , true);

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

	

	function search(){

		

		$data = $this->getCommonData(); 

		$data['title'] = 'Build Your Own Earring';

		

		$this->session->unset_userdata('earringstyle','');

		

	    $output = $this->load->view('jewelry/search' , $data , true);

		$this->output($output , $data);	

	}

	

	function searchdiamonds(){

		

		$data = $this->getCommonData();

		$this->load->model('diamondmodel');

		$data['tabhtml'] = '';

		 

		$minprice =  250;//$this->diamondmodel->getMinPrice();

		$maxprice =  1000000;//$this->diamondmodel->getMaxPrice();		

		$data ['totaldiamond'] = $this->diamondmodel->getCountDiamond ( $minprice, $maxprice );

		$data['minprice'] = $minprice;

		$data['maxprice'] = $maxprice;

		$data['settingsid'] = '';

		if(isset($_POST)){

			if(isset($_POST['hid'])){

				$data['title'] = 'Build Your Own Earring: Choose diamonds';

				$data['settingsid'] = $_POST['hid'];

				$settingsid = $data['settingsid'];

				$data['tabhtml'] = $this->commonmodel->earringTab('diamonds',$settingsid);

				

				$output = $this->load->view('jewelry/searchdiamonds' , $data , true);

			}

		}

		else {

			$data['title'] = 'Search Earring Style';

			

			 $output = $this->load->view('jewelry/searchearringstyle' , $data , true);

		}	

	    

		$output = $this->load->view('jewelry/searchdiamonds' , $data , true);

		$data['title'] = 'Build Your Own Earring: Choose diamonds';

		$this->output($output , $data);	

	}	

	

	function build_diamond_pendant(){

		$data = $this->getCommonData();		 

		$data['title'] = "Build Diamond Pendant|Build Your Own Diamond Pendant|Buy Diamond Pendant Online";

		$data['onloadextraheader'] = "getpendantsettingsresult(0);";

		$data['meta_tags'] = '<meta http-equiv="Content-Type" content="text/html; iso-8859-1">
	<meta name="title" content="Build Diamond Pendant|Build Your Own Diamond Pendant|Buy Diamond Pendant Online">
	<meta name="ROBOTS" content="INDEX,FOLLOW">
	<meta name="description" content="Build Diamond Pendant, Build Your Own Diamond Pendant, Buy Diamond Pendant Online, cubic zirconia engagement ring, discount engagement ring, engagement ring mountings, engagement rings cheap, mens engagement rings, pave engagement ring online">
	<meta name="abstract" content="Diamond Rings, Wholesale Diamonds, Estate Jewelry, Custom Engagement Rings, New York, Chicago, California, Boston, Las Vegas, Columbia, Montgomery">
	<meta name="keywords" content="Build Diamond Pendant, Build Your Own Diamond Pendant, Buy Diamond Pendant Online, cubic zirconia engagement ring, discount engagement ring, engagement ring mountings, engagement rings cheap, mens engagement rings, pave engagement ring online">
	<meta name="author" content="7techniques">
	<meta name="publisher" content="7techniques">
	<meta name="copyright" content="7techniques">
	<meta http-equiv="Reply-to" content="">
	<meta name="creation_Date" content="12/12/2008">
	<meta name="expires" content="">
	<meta name="revisit-after" content="7 days">
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">';

	    $output = $this->load->view('jewelry/diamondpendant' , $data , true);

		$this->output($output , $data);	

	}

	

	function getpendantresult(){

		

		$returnhtml = '';

		

		$results = $this->jewelrymodel->getPendants();

		$count = 0;

		$returnhtml .='<div>';

		if(isset($results)){

			foreach ($results as $result){

				 $img = file_exists(config_item('base_path').'images/rings/'.$result['small_image']) ? config_item('base_url').'images/rings/'.$result['small_image'] : config_item('base_url').'images/rings/noimage.jpg';

				if(($count%3) == 0)$returnhtml .='</div><div class="clear"></div><div style="border-bottom:5px #D6DEFC solid;"> ';

				$returnhtml .='

									<div class="floatl ringbox2 txtcenter"> 				      		

						      			<a href="#" onclick="viewpendantdetails('.$result['stock_number'].')"><img src="'.$img.'"> </a><br>

						      			'.substr($result['description'],0,50).'......<br>

						      			<b>Price:</b> $'.$result['price'].'<br>

						      			<a href="#" onclick="viewpendantdetails('.$result['stock_number'].')" class="underline">View Details</a>

						      		</div>

								';

				 

				$count++;

			}

			$returnhtml .='</div>';

			$returnhtml .= '<div class="clear"></div>';

		}

		

		echo $returnhtml;

	}

	

	function getpendantsettingsresult($isPlatinum = '', $isYellowgold = '', $isWhitegold = '', $isSolitaire = '', $isThreestone = ''){

		$returnhtml = '';

		

		$results = $this->jewelrymodel->getAllPendantSettings($isPlatinum, $isYellowgold, $isWhitegold, $isSolitaire, $isThreestone);

		$count = 0;

		

		$returnhtml .='<div>';

		if(isset($results)){

			foreach ($results as $result){

			

   			    $img = file_exists(config_item('base_path').$result['small_image']) ? config_item('base_url').$result['small_image'] : config_item('base_url').'images/rings/noimage.jpg';

				if(($count%3) == 0)$returnhtml .='</div><div class="clear"></div><div style="border-bottom:5px #D6DEFC solid;"> ';

				$returnhtml .='

									<div class="floatl ringbox2 txtcenter"> 				      		

						      			<a href="#" onclick="viewpendantdetails('.$result['id'].',\''.$result['style'].'\')"><img src="'.$img.'"> </a><br>

						      			'.substr($result['description'],0,50).'......<br>

						      			<b>Price:</b> $'.$result['price'].'<br>

						      			<a href="#" onclick="viewpendantdetails('.$result['id'].',\''.$result['style'].'\')" class="underline">View Details</a>

						      		</div>

								';

				 

				$count++;

			}

			$returnhtml .='</div>';

			$returnhtml .= '<div class="clear"></div>';

		}

		

		echo $returnhtml;

	}

	

	function pendantdetailsajax($id = '',$style = '' ){

		$data = $this->getCommonData();		

		$data['title'] = 'Pendant Details';		

		

		$data['style'] = $style;		

		$data['details'] = $this->jewelrymodel->getPendentSettingsById($id); 

			

	    $output = $this->load->view('jewelry/pendantsettingsdetails' , $data , true);		

		echo $output;

	}

	

	function build_three_stone_ring($details = false){		

		$data = $this->getCommonData();

		$this->load->model('diamondmodel'); 

		$data['title'] = 'Build Your Own Three-Stone Ring';

		$data['tabhtml'] = $this->commonmodel->getThreeStoneTab('diamonds');		 

		

		

		if($details){

			$minprice =  250;//$this->diamondmodel->getMinPrice();

			$maxprice =  1000000;//$this->diamondmodel->getMaxPrice();

			

			$minprice = ($this->session->userdata('searchminprice') && ($this->session->userdata('searchminprice')>$minprice) && ($this->session->userdata('searchminprice')<$maxprice)) ? ($this->session->userdata('searchminprice')) : $minprice;

			$maxprice = ($this->session->userdata('searchmaxprice') && ($this->session->userdata('searchmaxprice')<$maxprice) && ($this->session->userdata('searchmaxprice')>$minprice)) ? ($this->session->userdata('searchmaxprice')) : $maxprice;

			

			$data['minprice'] = $minprice;

			$data['maxprice'] = $maxprice;

			$data['totaldiamond'] = $this->diamondmodel->getCountDiamond($minprice,$maxprice);

		}else{

			$data['minprice'] =250;  //$this->diamondmodel->getMinPrice();

			$data['maxprice'] = 1000000;// $this->diamondmodel->getMaxPrice();

			$this->session->set_userdata('searchminprice',$data['minprice'] );

			$this->session->set_userdata('searchmaxprice',$data['maxprice']);

			$this->session->set_userdata ( 'caratmin', .5 );

			$this->session->set_userdata ( 'caratmax', 3.50 );

			$data['totaldiamond'] = $this->diamondmodel->getCountDiamond($data['minprice'],$data['maxprice']);

			 

		}

		$data['title'] = "Build Three Stone Ring|Gold Engagement Rings|Three Stone Princess Cut Engagement Ring";
		$data['meta_tags'] = '<meta http-equiv="Content-Type" content="text/html; iso-8859-1">
	<meta name="title" content="Build Three Stone Ring|Gold Engagement Rings|Three Stone Princess Cut Engagement Ring">
	<meta name="ROBOTS" content="INDEX,FOLLOW">
	<meta name="description" content="Build Three Stone Ring, Gold Engagement Rings, Three Stone Princess Cut Engagement Ring, diamond solitaire engagement rings, princess cut engagement ring, engagement rings princess cut, designing engagement rings, two tone engagement rings online at discounted rate">
	<meta name="abstract" content="Diamond Rings, Wholesale Diamonds, Estate Jewelry, Custom Engagement Rings, New York, Chicago, California, Boston, Las Vegas, Columbia, Montgomery">
	<meta name="keywords" content="Build Three Stone Ring, Gold Engagement Rings, Three Stone Princess Cut Engagement Ring, diamond solitaire engagement rings, princess cut engagement ring, engagement rings princess cut, designing engagement rings, two tone engagement rings online at discounted rate">
	<meta name="author" content="7techniques">
	<meta name="publisher" content="7techniques">
	<meta name="copyright" content="7techniques">
	<meta http-equiv="Reply-to" content="">
	<meta name="creation_Date" content="12/12/2008">
	<meta name="expires" content="">
	<meta name="revisit-after" content="7 days">
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">';
		

	    $output = $this->load->view('jewelry/threestonering' , $data , true);

		$this->output($output , $data);	

	}

	

	function bracelets()

	{

		$data = $this->getCommonData(); 

		$data['title'] = 'Bracelets';

	    $output = $this->load->view('jewelry/bracelets' , $data , true);

		$this->output($output , $data);		

	}

	

	function diamond(){

		

		$data = $this->getCommonData(); 

		$data['title'] = 'Diamond Jewelry';

	    $output = $this->load->view('jewelry/diamondjewelry' , $data , true);

		$this->output($output , $data);	

	}

	

	function earrings(){

		

		$data = $this->getCommonData(); 

		$data['title'] = 'Earrings';

	    $output = $this->load->view('jewelry/earrings' , $data , true);

		$this->output($output , $data);	

	}

	

	function gold(){

		

		$data = $this->getCommonData(); 

		$data['title'] = 'Gold Jewelry';

	    $output = $this->load->view('jewelry/gold' , $data , true);

		$this->output($output , $data);	

	}

	

	function necklaces(){

		

		$data = $this->getCommonData(); 

		$data['title'] = 'Necklaces & Pendants';

	    $output = $this->load->view('jewelry/necklaces' , $data , true);

		$this->output($output , $data);	

	}

	

	function pearls(){

		

		$data = $this->getCommonData(); 

		$data['title'] = 'Pearl Jewelry';

	    $output = $this->load->view('jewelry/pearls' , $data , true);

		$this->output($output , $data);	

	}

	

	function platinum(){

		

		$data = $this->getCommonData(); 

		$data['title'] = 'Platinum Jewelry';

	    $output = $this->load->view('jewelry/platinum' , $data , true);

		$this->output($output , $data);	

	}

	

	function silver(){

		

		$data = $this->getCommonData(); 

		$data['title'] = 'Sterling Silver Jewelry';

	    $output = $this->load->view('jewelry/silver' , $data , true);

		$this->output($output , $data);	

	}

	

	function watches(){

		

		$data = $this->getCommonData(); 

		$data['title'] = 'Watches & Accessories';

	    $output = $this->load->view('jewelry/watch' , $data , true);

		$this->output($output , $data);	

	}

	

	function weddings(){

		

		$data = $this->getCommonData(); 

		$data['title'] = 'Wedding Rings & Anniversary';

	    $output = $this->load->view('jewelry/weddings' , $data , true);

		$this->output($output , $data);	

	}



	function earringdiamondsajax($lot = '',$lot2='', $settingsid = '',$addoption = ''){

		$data = $this->getCommonData();

		$this->load->model('diamondmodel') ;

		$data['title'] = 'Earring Diamond Details'; 		

		$data['settingsid'] = $settingsid;		 

		$data['addoption'] = $addoption;		

		$data['lot'] = $lot;

		

		$diamond = $this->diamondmodel->getDetailsByLot($lot);

		$data['diamond'] = $diamond;

		$data['sidestone1'] = $this->diamondmodel->getDetailsByLot($lot);

		$data['sidestone2'] = $this->diamondmodel->getDetailsByLot($lot2);

			

		$depth = $diamond['Depth'];

		$table = $diamond['TablePercent'];

		

		$tablemin = $table - 1;

		$tablemax = $table + 1;

		$depthmin = $depth - 1.5;

		$depthmax = $depth + 1.5;

		

		$tablecon = " TablePercent >= '".$tablemin."' and TablePercent <= '".$tablemax."' ";

		$depthcon = " Depth >= '".$depthmin."' and Depth <= '".$depthmax."' ";

		

		$data['pairdiamond'] = $this->diamondmodel->getPairSidestone($diamond['carat'], $diamond['color'], $diamond['clarity'],$tablecon, $depthcon);

			

	    $output = $this->load->view('jewelry/earringstonedetails' , $data , true);		

		echo $output;

	}

	

	function earringdetailsview($addoption = '', $settingsid = '', $lot = '', $lot2 = ''){

		

		$data = $this->getCommonData(); 

		$data['title'] = 'Earring Jewelry';

		$this->load->model('diamondmodel');

		

		$data['settingsid'] = $settingsid;		 

		$data['addoption'] = $addoption;		

		$data['lot'] = $lot;

		

		$data['settingsdetails'] = $this->jewelrymodel->getEarringSettingsById($settingsid);

		

		$diamond = $this->diamondmodel->getDetailsByLot($lot);

		$data['diamond'] = $diamond;

		$data['sidestone1'] = $this->diamondmodel->getDetailsByLot($lot);

		$data['sidestone2'] = $this->diamondmodel->getDetailsByLot($lot2);

			

		$depth = $diamond['Depth'];

		$table = $diamond['TablePercent'];

		

		$tablemin = $table - 1;

		$tablemax = $table + 1;

		$depthmin = $depth - 1.5;

		$depthmax = $depth + 1.5;

		

		$tablecon = " TablePercent >= '".$tablemin."' and TablePercent <= '".$tablemax."' ";

		$depthcon = " Depth >= '".$depthmin."' and Depth <= '".$depthmax."' ";

		

		$data['pairdiamond'] = $this->diamondmodel->getPairSidestone($diamond['carat'], $diamond['color'], $diamond['clarity'],$tablecon, $depthcon);

		

	    $output = $this->load->view('jewelry/earringdetailsview' , $data , true);

		$this->output($output , $data);	

		

	}

	

	function addbasket($addoption = '',$settings = '', $lot = ''){

		$data = $this->getCommonData(); 

		$data['title'] = 'Jewelry';

		

		header('location:'.config_item('base_url').'shoppingbasket/mybasket/false/false');

	    //$output = $this->load->view('jewelry/index' , $data , true);

		//$this->output($output , $data);	

	}

	

	function searchearringstyle(){		

		$data = $this->getCommonData(); 

		$data['title'] = 'Search Earring Style';

		$data['tabhtml'] = $this->commonmodel->earringTab('style');

						

	    $output = $this->load->view('jewelry/searchearringstyle' , $data , true);

		$this->output($output , $data);		

	}

	

	//----------------------------------------jewelry earring-----------------------------------------------

	

	function getStyleResult($shape ='', $metal = '', $style = ''){

		$metalhtml = '';

		$stylehtml= '';

		if($shape != ''){

			$metalhtml = $this->getMetalHtml($shape);

		}

		if($metal != ''){

			$earringsettings = $this->jewelrymodel->getEarringSettingsByShapeMetal($shape, $metal);			

			$stylehtml = $this->getStyleHtmls($metal, $shape);

		}

	}

	

	function getStyleHtmlResult($metal, $shape){

		$stylehtml = $this->getStyleHtmls($metal, $shape);

	}

	

	function getMetalHtml($shape = '' , $isajax = true){

		if($shape == 'B'){

			$returnhtml = '

							<div class="commonheader white">2. Select Your Metal</div>

							<div>

					   				<div class="floatl smallbox txtcenter">

							   				<label for="platinum"><img src="'.config_item('base_url').'images/tamal/jewelry/sel_metal_pl.jpg"></label> <br>

							   				<input type="radio" name="Bmetal" id="platinum" onclick="getstyleresult(this,\''.$shape.'\'); genericshowhide(\'buttondiv\',\'false\')" >Platinum

							   		</div>						   		

							   		<div class="floatl smallbox txtcenter">

							   				<label for="18kwhitegold"><img src="'.config_item('base_url').'images/tamal/jewelry/sel_metal_pl.jpg"></label> <br>

							   				<input type="radio" name="Bmetal" id="18kwhitegold" onclick="getstyleresult(this,\''.$shape.'\'); genericshowhide(\'buttondiv\',\'false\')">18k White Gold

							   		</div>

							   		<div class="floatl smallbox txtcenter">

							   				<label for="18kyellowgold"><img src="'.config_item('base_url').'images/tamal/jewelry/sel_metal_18y_r.jpg"></label> <br>

							   				<input type="radio" name="Bmetal" id="18kyellowgold" onclick="getstyleresult(this,\''.$shape.'\'); genericshowhide(\'buttondiv\',\'false\')">18k Yellow Gold

							   		</div>

							   		<div class="clear"></div>

					   		</div>

							';

		}

		elseif($shape == 'PR'){

			$returnhtml = '

							<div class="commonheader white">2. Select Your Metal</div>

					   		<div>

					   				<div class="floatl smallbox txtcenter">

							   				<label for="platinum"><img src="'.config_item('base_url').'images/tamal/jewelry/sel_metal_pl.jpg"></label> <br>

							   				<input type="radio" name="PRmetal" id="platinum" onclick="getstyleresult(this,\''.$shape.'\'); genericshowhide(\'buttondiv\',\'false\')" >Platinum

							   		</div>

							   		<div class="floatl smallbox txtcenter">

							   				<label for="18kyellowgold"><img src="'.config_item('base_url').'images/tamal/jewelry/sel_metal_18y.jpg"></label> <br>

							   				<input type="radio" name="PRmetal" id="18kyellowgold" onclick="getstyleresult(this,\''.$shape.'\'); genericshowhide(\'buttondiv\',\'false\')" >18k Yellow Gold

							   		</div>

							   		<div class="floatl smallbox txtcenter">

							   				<label for="18kwhitegold"><img src="'.config_item('base_url').'images/tamal/jewelry/sel_metal_pl.jpg"></label> <br>

							   				<input type="radio" name="PRmetal" id="18kwhitegold" onclick="getstyleresult(this,\''.$shape.'\'); genericshowhide(\'buttondiv\',\'false\')" >18k White Gold

							   		</div>

							   		<div class="clear"></div>

					   		</div>	

			

			';

			

		}

		elseif($shape == 'E'){

			$returnhtml = '

							<div class="commonheader white">2. Select Your Metal</div>

					   		<div>

					   				<div class="floatl smallbox txtcenter">

							   				<label for="platinum"><img src="'.config_item('base_url').'images/tamal/jewelry/sel_metal_pl.jpg"></label> <br>

							   				<input type="radio" name="Emetal" id="platinum" onclick="getstyleresult(this,\''.$shape.'\'); genericshowhide(\'buttondiv\',\'false\')"  >Platinum

							   		</div>

							   		<div class="floatl smallbox txtcenter">

							   				<label for="18kyellowgold"><img src="'.config_item('base_url').'images/tamal/jewelry/sel_metal_18y.jpg"></label> <br>

							   				<input type="radio" name="Emetal" id="18kyellowgold" onclick="getstyleresult(this,\''.$shape.'\'); genericshowhide(\'buttondiv\',\'false\')" >18k Yellow Gold

							   		</div>

							   		<div class="floatl smallbox txtcenter">

							   				<label for="18kwhitegold"><img src="'.config_item('base_url').'images/tamal/jewelry/sel_metal_pl.jpg"></label> <br>

							   				<input type="radio" name="Emetal" id="18kwhitegold" onclick="getstyleresult(this,\''.$shape.'\'); genericshowhide(\'buttondiv\',\'false\')" >18k White Gold

							   		</div>

							   		<div class="clear"></div>

					   		</div>	

			

			';

		}

		else $returnhtml = '';

		$returnhtml .= '<div id="styleresult"></div>

						<div class="dbr"></div> ';

		if($isajax) echo $returnhtml;

		else return $returnhtml;

			

	}

	

	function getStyleHtml($metal = '', $shape = '', $isajax = true){

		if($shape == 'B'){

			if($metal == 'platinum'){

				$returnhtml = '

								<div class="commonheader white">3. Select Your Stylea</div>

						   		<div>	   		

						   				<div class="floatl smallbox txtcenter">

								   				<label for="Bfpr_pl"><img src="'.config_item('base_url').'images/tamal/jewelry/sel_metal_pl_r.jpg"></label> <br>

								   				<input type="radio" name="Bstyle" id="Bfpr_pl" onclick="genericshowhide(\'buttondiv\',\'true\'); getearringsettings(\''.$shape.'\',\''.$metal.'\')">Four-Prong								   				

								   		</div>

								   		<div class="floatl smallbox txtcenter">

								   				<label for="Bbz_pl"><img src="'.config_item('base_url').'images/tamal/jewelry/sel_style_bezel_r.jpg"></label> <br>

								   				<input type="radio" name="Bstyle" id="Bbz_pl" onclick="genericshowhide(\'buttondiv\',\'true\')">Bezel-Set 

								   		</div>	

								   		<div class="clear"></div> 

						   		</div>

				

				';

			}

			elseif($metal == '18kwhitegold'){

				$returnhtml = '

				

								<div class="commonheader">3. Select Your Style</div>

								<div>	   		

						   				<div class="floatl smallbox txtcenter">

								   				<label for="Bfpr_18wg"><img src="'.config_item('base_url').'images/tamal/jewelry/sel_metal_pl_r.jpg"></label> <br>

								   				<input type="radio" name="Bstyle" id="Bfpr_18wg" onclick="genericshowhide(\'buttondiv\',\'true\')">Four-Prong

								   		</div>

								   		<div class="floatl smallbox txtcenter">

								   				<label for="Bbz_18wg"><img src="'.config_item('base_url').'images/tamal/jewelry/sel_style_bezel_r.jpg"></label> <br>

								   				<input type="radio" name="Bstyle" id="Bbz_18wg" onclick="genericshowhide(\'buttondiv\',\'true\')">Bezel-Set 

								   		</div>

								   		<div class="clear"></div> 

						   		</div>

				';

			}

			elseif($metal == '18kyellowgold'){

				$returnhtml = '

								<div class="commonheader">3. Select Your Style</div>

								<div>   		

						   				<div class="floatl smallbox txtcenter">

								   				<label for="Bfpr_18yg"><img src="'.config_item('base_url').'images/tamal/jewelry/sel_style_4prong_yg_r.jpg"></label> <br>

								   				<input type="radio" name="Bstyle" id="Bfpr_18yg" onclick="genericshowhide(\'buttondiv\',\'true\')">Four-Prong

								   		</div>

								   		<div class="floatl smallbox txtcenter">

								   				<label for="Bbz_18yg"><img src="'.config_item('base_url').'images/tamal/jewelry/sel_style_bez_yg_r.jpg"></label> <br>

								   				<input type="radio" name="Bstyle" id="Bbz_18yg" onclick="genericshowhide(\'buttondiv\',\'true\')">Bezel-Set 

								   		</div>	

								   		<div class="clear"></div> 

						   		</div>

				';

			}

			else $returnhtml = '';

		}

		elseif ($shape == 'PR'){

			if($metal == 'platinum'){

				$returnhtml = '

								<div class="commonheader">3. Select Your Style</div>

						   		<center>	   		

						   				<div class="smallbox">

								   				<label for="PRfpr_pl"><img src="'.config_item('base_url').'images/tamal/jewelry/sel_metal_pl.jpg"></label> <br>

								   				<input type="radio" name="PRstyle" id="PRfpr_pl" onclick="genericshowhide(\'buttondiv\',\'true\')">Four-Prong

								   		</div> 				   		

						   		</center>

				

				';

			}

			elseif($metal == '18kwhitegold' ){

				$returnhtml = '

								<div class="commonheader">3. Select Your Style</div>

						   		<center> 

										<div class="smallbox">

								   				<label for="PRfpr_wg"><img src="'.config_item('base_url').'images/tamal/jewelry/sel_metal_pl.jpg"></label> <br>

								   				<input type="radio" name="PRstyle" id="PRfpr_wg" onclick="genericshowhide(\'buttondiv\',\'true\')">Four-Prong

								   		</div> 				   		

						   		</center>

				

				';

			}

			elseif ($metal == '18kyellowgold'){

				$returnhtml = '

								<div class="commonheader">3. Select Your Style</div>

						   		<center>  

										<div class="smallbox">

								   				<label for="PRfpr_yg"><img src="'.config_item('base_url').'images/tamal/jewelry/sel_metal_18y.jpg"></label> <br>

								   				<input type="radio" name="PRstyle" id="PRfpr_yg" onclick="genericshowhide(\'buttondiv\',\'true\')">Four-Prong

								   		</div>

																   		

						   		</center>

				

				';

			}

			else $returnhtml = '';

		}

		elseif($shape == 'E'){

			if($metal == 'platinum'){

				$returnhtml = '

								<div class="commonheader">3. Select Your Style</div>

						   		<center> 		

						   				<div class="smallbox">

								   				<label for="Efpr_pl"><img src="'.config_item('base_url').'images/tamal/jewelry/sel_metal_pl.jpg"></label> <br>

								   				<input type="radio" name="Estyle" id="Efpr_pl" onclick="genericshowhide(\'buttondiv\',\'true\')">Four-Prong

								   		</div> 				   		

						   		</center>

				

				';

			}

			elseif($metal == '18kwhitegold'){

				$returnhtml = '

								<div class="commonheader">3. Select Your Style</div>

						   		<center>  

										<div class="smallbox">

								   				<label for="Efpr_wg"><img src="'.config_item('base_url').'images/tamal/jewelry/sel_metal_pl.jpg"></label> <br>

								   				<input type="radio" name="Estyle" id="Efpr_wg" onclick="genericshowhide(\'buttondiv\',\'true\')">Four-Prong

								   		</div> 			   		

						   		</center>

				

				';

			}

			elseif ($metal == '18kyellowgold'){

				$returnhtml = '

								<div class="commonheader">3. Select Your Style</div>

						   		<center> 

										<div class="smallbox">

								   				<label for="Efpr_yg"><img src="'.config_item('base_url').'images/tamal/jewelry/sel_metal_18y.jpg"></label> <br>

								   				<input type="radio" name="Estyle" id="Efpr_yg" onclick="genericshowhide(\'buttondiv\',\'true\')">Four-Prong

								   		</div>						   		

						   		</center>

				

				';

			}

			else $returnhtml = ''; 

		}

		 

			

		

		if($isajax) echo $returnhtml;

		else return $returnhtml;

	}

	

	function getStyleHtmls($metal, $shape, $isajax = true){  

		$returnhtml = '';

		$earringsettings = $this->jewelrymodel->getEarringSettingsByShapeMetal($shape, $metal);	

		 

		if(isset($earringsettings)){

			$returnhtml = '<div class="clear"></div>

							<div class="commonheader white">3. Select Your Style</div>

							<div>';

			

			foreach ($earringsettings as $earring){

				$returnhtml .= '

										<div class="floatl smallbox txtcenter">

								   				<label for="'.$earring['style'].'"><img src="'.config_item('base_url').$earring['small_image'].'"></label> <br>

								   				<input type="radio" name="Bstyle" id="'.$earring['style'].'" onclick="genericshowhide(\'buttondiv\',\'true\'); selectsettingsid('.$earring['id'].')">'.$earring['style'].'

								   				<br> $'.$earring['price'].'

								   		</div>								

								';

			}

			

			$returnhtml .= '		<div class="clear"></div>

								</div>

							';

		}

		

		if($isajax) echo $returnhtml;

		else return $returnhtml;

	} 

	 

	//-----------------------------------------earring end--------------------------------------------------

	

	function diamondstudearring(){

		$data = $this->getCommonData(); 

		$data['title'] = 'Jewelry: Diamond Stud Earring';

		

		$data['collections'] = $this->jewelrymodel->getNameBySection('Diamond Stud Earrings');

		

	    $output = $this->load->view('jewelry/diamondstudearring' , $data , true);

		$this->output($output , $data);		

	}

	

	function earringstuddetailsajax($stockno = '', $addoption = '',$price = ''){

		$data = $this->getCommonData();		

		$data['title'] = 'Earring Stud Details';

		$data['addoption'] = $addoption;

		$data['price'] = $price;

		

		$data['details'] = $this->jewelrymodel->getByStock($stockno); 			

	    $output = $this->load->view('jewelry/earringstuddetails' , $data , true);		

		echo $output;

	}

	

	function addtobasket($stockno=''){		

		$data = $this->getCommonData();

		$data['title'] = 'Basket'; 

		

		header('location:'.config_item('base_url').'shoppingbasket/mybasket');

	}

	

	function pendantdetailsview($addoption = '',$lot = '', $settings = '', $sidestone1 = '', $sidestone2 = ''){

		$data = $this->getCommonData(); 

		$this->load->model('diamondmodel');

		$data['title'] = 'Jewelry: Pendant Details';

		$data['details'] = $this->jewelrymodel->getPendentSettingsById($settings);

		$details = $data['details'];

		

		$data['nexturl'] = '#';

		$data['onclickfunction'] = '';

		$side1price = 0;

		$side2price = 0; 

		

		$centerdiamond = $this->diamondmodel->getDetailsByLot($lot);

		$centerprice = $centerdiamond['price'] ;

		

		if($sidestone1 != '' && $sidestone1 != 'false'){

			$side1 = $this->diamondmodel->getDetailsByLot($sidestone1);

			$side1price = $side1['price'];

		}

		if($sidestone2 != '' && $sidestone2 != 'false'){

			$side2 = $this->diamondmodel->getDetailsByLot($sidestone2);

			$side2price = $side2['price'];

		} 

		

		$settingsprice = $details['price'];		 

		$totalprice = $centerprice + $settingsprice + $side1price + $side2price;

		$data['diamondprice'] = $centerprice;

		$data['side1price'] = $side1price;

		$data['side2price'] = $side2price;

		$data['settingsprice'] = $settingsprice;

		$data['totalprice'] = $totalprice;

		

		switch ($addoption) {

			case 'addpendantsetings': 

				$data['onclickfunction'] = 'addtocart(\''.$addoption.'\','.$lot.',false,false,'.$settings.','.$totalprice.')';

				break;

			default:

				break;

		}

		

	    $output = $this->load->view('jewelry/pendantdetailsview' , $data , true);

		$this->output($output , $data);	

	}

	

}?>