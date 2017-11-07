<?php
class Diamonds extends Controller {
	function __construct() {
		parent::Controller ();
		$this->load->model ( 'diamondmodel' );
	}
	
	function index() {
		$data = $this->getCommonData ();
		$data ['title'] = 'Diamonds';
		$data ['extraheader'] = '<script src="' . config_item ( 'base_url' ) . 'js/swfobject.js" type="text/javascript"></script>';
		$data ['onloadextraheader'] = " 
			var so;	
			so = new SWFObject(\"" . config_item ( 'base_url' ) . "flash/flash.swf\", \"test\", \"710\", \"275\", \"8\", \"#000\");
			so.addParam(\"wmode\", \"transparent\");
			so.write(\"flashdiamond\");
			";
		
		
		
		$data['meta_tags'] = '<meta http-equiv="Content-Type" content="text/html; iso-8859-1">
<meta name="title" content="Engagement Rings|Diamond|Gold Diamond|Princess|Pink|Blue|Antique Diamond Rings of Engagement">
<meta name="ROBOTS" content="INDEX,FOLLOW">
<meta name="description" content="Buy online antique diamond engagement rings, White,Yellow, Pink, Blue diamond engagement rings, emerald cut rings, pave diamond rings, princess cut engagement rings
white gold rings, yellow diamond engagement rings, gold diamond engagement ring, white gold diamond engagement ring.">
<meta name="abstract" content="Diamond Rings, Wholesale Diamonds, Estate Jewelry, Custom Engagement Rings, New York, Chicago, California, Boston, Las Vegas, Columbia, Montgomery">
<meta name="keywords" content="antique diamond engagement rings, White, Yellow, Pink, Blue diamond engagement rings, emerald cut rings, pave diamond rings, princess cut engagement rings
white gold rings, yellow diamond engagement rings, gold diamond engagement ring, white gold diamond engagement ring">
<meta name="author" content="7techniques">
<meta name="publisher" content="7techniques">
<meta name="copyright" content="7techniques">
<meta http-equiv="Reply-to" content="">
<meta name="creation_Date" content="12/12/2008">
<meta name="expires" content="">
<meta name="revisit-after" content="7 days">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">';
		
		
				
		$output = $this->load->view ( 'diamond/index', $data, true );
		$this->output ( $output, $data );
	}
	
	private function getCommonData($banner = '') {
		$data = array ();
		$data = $this->commonmodel->getPageCommonData ();
		return $data;
	
	}
	
	function output($layout = null, $data = array(), $isleft = true, $isright = true) {
		$data ['loginlink'] = $this->user->loginhtml ();
		$output = $this->load->view ( $this->config->item ( 'template' ) . 'header', $data, true );
		if ($isleft)
			$output .= $this->load->view ( $this->config->item ( 'template' ) . 'left', $data, true );
		$output .= $layout;
		if ($isright)
			$output .= $this->load->view ( $this->config->item ( 'template' ) . 'right', $data, true );
		$output .= $this->load->view ( $this->config->item ( 'template' ) . 'footer', $data, true );
		$this->output->set_output ( $output );
	}
	
	private function getShapeName($shapelist) {
		$shapename = '';
		$shapestr = '';
		if (($this->session->userdata ( 'shape' ))) {
			
			$shapes = $this->session->userdata ( 'shape' );
			$shapelist = explode ( '.', $shapes );
			
			if (sizeof ( $shapelist > 0 )) {
				foreach ( $shapelist as $val ) {
					if ($val != '') {
						switch ($val) {
							case 'B' :
								$shapename = 'Round';
								break;
							case 'PR' :
								$shapename = 'Princess';
								break;
							case 'R' :
								$shapename = 'Radiant';
								break;
							case 'E' :
								$shapename = 'Emerald';
								break;
							case 'AS' :
								$shapename = 'Ascher';
								break;
							case 'O' :
								$shapename = 'Oval';
								break;
							case 'M' :
								$shapename = 'Marquise';
								break;
							case 'P' :
								$shapename = 'Pear shape';
								break;
							case 'H' :
								$shapename = 'Heart';
								break;
							case 'C' :
								$shapename = 'Cushion';
								break;
							default :
								$shapename = '';
								break;
						}
						$shapestr .= $shapename . ", ";
					}
				}
				$shapestr = substr ( $shapestr, 0, (strlen ( $shapestr ) - 2) );
			}
		}
		return $shapestr;
	}
	
	function getDepthMinMax($shapelist = '') {
		$shapemax = '';
		$shapemin = '';
		$min = 100;
		$max = 0;
		if (($this->session->userdata ( 'shape' ))) {
			
			$shapes = $this->session->userdata ( 'shape' );
			$shapelist = explode ( '.', $shapes );
			
			if (sizeof ( $shapelist > 0 )) {
				foreach ( $shapelist as $val ) {
					if ($val != '') {
						switch ($val) {
							case 'B' :
								$shapemin = '56.5';
								$shapemax = '67';
								break;
							case 'PR' :
								$shapemin = '55';
								$shapemax = '86';
								break;
							case 'R' :
								$shapemin = '55';
								$shapemax = '86';
								break;
							case 'E' :
								$shapemin = '54';
								$shapemax = '79';
								break;
							case 'AS' :
								$shapemin = '54';
								$shapemax = '79';
								break;
							case 'O' :
								$shapemin = '50';
								$shapemax = '74';
								break;
							case 'M' :
								$shapemin = '50';
								$shapemax = '74';
								break;
							case 'P' :
								$shapemin = '50';
								$shapemax = '74';
								break;
							case 'H' :
								$shapemin = '45';
								$shapemax = '65';
								break;
							case 'C' :
								$shapemin = '54';
								$shapemax = '73';
								break;
							default :
								$shapemin = '10';
								$shapemax = '100';
								break;
						}
						if ($shapemin < $min)
							$min = $shapemin;
						if ($shapemax > $max)
							$max = $shapemax;
					
					}
				}
			
			} else {
				$min = '10';
				$max = '100';
			}
		} else {
			$min = '10';
			$max = '100';
		}
		return array (
				$min, 
				$max );
	
	}
	
	function getTableMinMax($shapelist = '') {
		$shapemax = '';
		$shapemin = '';
		$min = 100;
		$max = 0;
		if (($this->session->userdata ( 'shape' ))) {
			
			$shapes = $this->session->userdata ( 'shape' );
			$shapelist = explode ( '.', $shapes );
			if (sizeof ( $shapelist > 0 )) {
				foreach ( $shapelist as $val ) {
					if ($val != '') {
						switch ($val) {
							case 'B' :
								$shapemin = '50';
								$shapemax = '67';
								break;
							case 'PR' :
								$shapemin = '51';
								$shapemax = '89';
								break;
							case 'R' :
								$shapemin = '51';
								$shapemax = '89';
								break;
							case 'E' :
								$shapemin = '51';
								$shapemax = '79';
								break;
							case 'AS' :
								$shapemin = '51';
								$shapemax = '79';
								break;
							case 'O' :
								$shapemin = '50';
								$shapemax = '71';
								break;
							case 'M' :
								$shapemin = '50';
								$shapemax = '71';
								break;
							case 'P' :
								$shapemin = '50';
								$shapemax = '71';
								break;
							case 'H' :
								$shapemin = '51';
								$shapemax = '70';
								break;
							case 'C' :
								$shapemin = '49';
								$shapemax = '71';
								break;
							default :
								$shapemin = '10';
								$shapemax = '100';
								break;
						}
						if ($shapemin < $min)
							$min = $shapemin;
						if ($shapemax > $max)
							$max = $shapemax;
					
					}
				}
			
			} else {
				$min = '10';
				$max = '100';
			}
		} else {
			$min = '10';
			$max = '100';
		}
		return array (
				$min, 
				$max );
	
	}
	
	function search($details = false, $shape = '', $option = '', $ispremium = false, $settingsid = '') {
		$data = $this->getCommonData ();
		$data ['title'] = 'Diamonds';
		$shapearray = array (
				'B', 
				'H', 
				'M', 
				'AS', 
				'PR', 
				'P', 
				'E', 
				'C', 
				'R', 
				'O' );
		
		if (in_array ( $shape, $shapearray )) {
			$this->session->set_userdata ( 'shape', $shape );
			$this->session->set_userdata ( 'cutmin', 0 );
			$this->session->set_userdata ( 'cutmax', 3 );
		
		}
		if ($ispremium)
			$this->session->set_userdata ( 'ispremium', true );
		else
			$this->session->set_userdata ( 'ispremium', false );
		
		switch ($option) {
			case 'tothreestone' :
				$this->session->set_userdata ( 'caratmin', .5 );
				$this->session->set_userdata ( 'caratmax', 3.25 );
				$caratminmax = array (
						'min' => 0.5, 
						'max' => '3.25' );
				break;
			case 'addpendantsetings3stone' :
				$this->session->set_userdata ( 'caratmin', .5 );
				$this->session->set_userdata ( 'caratmax', 3.25 );
				$caratminmax = array (
						'min' => 0.5, 
						'max' => '3.25' );
				break;
			case 'toearring' :
				$caratminmax = array (
						'min' => .25, 
						'max' => .75 );
				break;
			default :
				$caratminmax = $this->diamondmodel->getMinMaxCarat ();
				break;
		}
		
		$data ['caratminmax'] = $caratminmax;
		$data ['option'] = $option;
		$data ['addoption'] = $option;
		$data ['settingsid'] = $settingsid;
		
		$depthminmax = $this->getDepthMinMax ();
		$data ['depthmin'] = $depthminmax [0];
		$data ['depthmax'] = $depthminmax [1];
		
		$tableminmax = $this->getTableMinMax ();
		$data ['tablemin'] = $tableminmax [0];
		$data ['tablemax'] = $tableminmax [1];
		
		$minprice = 250; //$this->diamondmodel->getMinPrice();
		$maxprice = 1000000; //$this->diamondmodel->getMaxPrice();			
		$minpricepercrt = $this->diamondmodel->getMinPricePerCarat ();
		$maxpricepercrt = $this->diamondmodel->getMaxPricePerCarat ();
		
		$data ['totaldiamond'] = $this->diamondmodel->getCountDiamond ( $minprice, $maxprice );
		
		if (isset ( $_POST ['resumesearch'] )) {
			$details = true;
		}
		
		if ($details == 'true') {
			
			/*$minprice =  $this->diamondmodel->getMinPrice();
			$maxprice =  $this->diamondmodel->getMaxPrice();
			
			$minpricepercrt =  $this->diamondmodel->getMinPricePerCarat();
			$maxpricepercrt =  $this->diamondmodel->getMaxPricePerCarat();*/
			
			if (isset ( $_POST ['searchdiamonds'] )) {
				
				$this->session->set_userdata ( 'searchminprice', $minprice );
				$this->session->set_userdata ( 'searchmaxprice', $maxprice );
				$array_items = array (
						'depthmin' => '', 
						'depthmax' => '', 
						'tablemin' => '', 
						'tablemax' => '', 
						'caratmin' => '', 
						'caratmax' => '', 
						'cutmin' => '', 
						'cutmax' => '', 
						'colormin' => '', 
						'colormax' => '', 
						'fluromin' => '', 
						'fluromax' => '', 
						'polismin' => '', 
						'polismax' => '', 
						'symmetmin' => '', 
						'symmetmax' => '', 
						'pricePerCaratmin' => '', 
						'pricePerCaratmax' => '', 
						'claritymin' => '', 
						'claritymax' => '', 
						'mydiamond' => '', 
						'ispremium' => false );
				$this->session->unset_userdata ( $array_items );
			}
			
			$minprice = ($this->session->userdata ( 'searchminprice' ) && ($this->session->userdata ( 'searchminprice' ) > $minprice) && ($this->session->userdata ( 'searchminprice' ) < $maxprice)) ? ($this->session->userdata ( 'searchminprice' )) : $minprice;
			$maxprice = ($this->session->userdata ( 'searchmaxprice' ) && ($this->session->userdata ( 'searchmaxprice' ) < $maxprice) && ($this->session->userdata ( 'searchmaxprice' ) > $minprice)) ? ($this->session->userdata ( 'searchmaxprice' )) : $maxprice;
			
			$minpricepercrt = ($this->session->userdata ( 'pricePerCaratmin' ) && ($this->session->userdata ( 'pricePerCaratmin' ) > $minpricepercrt) && ($this->session->userdata ( 'pricePerCaratmin' ) < $maxpricepercrt)) ? ($this->session->userdata ( 'pricePerCaratmin' )) : $minpricepercrt;
			$maxpricepercrt = ($this->session->userdata ( 'pricePerCaratmax' ) && ($this->session->userdata ( 'pricePerCaratmax' ) < $maxpricepercrt) && ($this->session->userdata ( 'pricePerCaratmax' ) > $minpricepercrt)) ? ($this->session->userdata ( 'pricePerCaratmax' )) : $maxpricepercrt;
			
			$data ['minprice'] = $minprice;
			$data ['maxprice'] = $maxprice;
			$data ['minpricepercrt'] = $minpricepercrt;
			$data ['maxpricepercrt'] = $maxpricepercrt;
		
		} else {
			
			/*$minprice =  $this->diamondmodel->getMinPrice();
			$maxprice =  $this->diamondmodel->getMaxPrice();*/
			$data ['minprice'] = $minprice;
			$data ['maxprice'] = $maxprice;
			/*$data['totaldiamond'] = $this->diamondmodel->getCountDiamond($minprice,$maxprice);*/
			
			$lastsearchMin = $this->session->userdata ( 'searchminprice' );
			$lastsearchMax = $this->session->userdata ( 'searchmaxprice' );
			$this->session->set_userdata ( 'lastsearchMin', $lastsearchMin );
			$this->session->set_userdata ( 'lastsearchMax', $lastsearchMax );
			
			$data ['lastminpr'] = ($lastsearchMin == '') ? $data ['minprice'] : $lastsearchMin;
			$data ['lastmaxpr'] = ($lastsearchMax == '') ? $data ['maxprice'] : $lastsearchMax;
			
			$data ['diashape'] = $this->session->userdata ( 'shape' );
			$data ['shapename'] = $this->getShapeName ( $data ['diashape'] );
		
		}
		
		$url = '';
		if ($option != '' && $settingsid != '') {
			$url = config_item ( 'base_url' ) . "diamonds/getsearchresult/" . $option . "/" . $settingsid;
		} elseif ($option != '') {
			$url = config_item ( 'base_url' ) . "diamonds/getsearchresult/" . $option;
		} else {
			$url = config_item ( 'base_url' ) . "diamonds/getsearchresult";
		}
		//echo($url);
		

		if ($details && $details == 'true') {
			
			$data ['onloadextraheader'] = "
			$('body').append('<div id=\"slideblocker\">&nbsp;</div>');
			
			$('#pricerange').slider({ steps: 100, range: true, minValue : 1,slide :function(e,ui) { 
						
if($('#pricerange').slider('value',0) <= 30)
									 									 																{
									 									 																	val = ($('#pricerange').slider('value',0)*25+(" . $minprice . "));
									 									 																	$('#pricerange1').val(val);
									 									 																}
									 									 																else if($('#pricerange').slider('value',0) > 30 && $('#pricerange').slider('value',0) <= 50)
									 									 																{
									 									 																	val = ($('#pricerange').slider('value',0)*75+(" . $minprice . "));
									 									 																	$('#pricerange1').val(val);
									 									 																}
									 									 																else if($('#pricerange').slider('value',0) > 50 && $('#pricerange').slider('value',0) <= 70)
									 									 																{
									 									 																	val = ($('#pricerange').slider('value',0)*250+(" . $minprice . "));
									 									 																	$('#pricerange1').val(val);
									 									 																}
									 									 																else if($('#pricerange').slider('value',0) > 70 && $('#pricerange').slider('value',0) <= 80)
									 									 																{									 									 																	
									 									 																	val = ($('#pricerange').slider('value',0)*1000+(" . $minprice . "));
									 									 																	$('#pricerange1').val(val);
									 									 																}
									 									 																else if($('#pricerange').slider('value',0) > 80 && $('#pricerange').slider('value',0) <= 90)
									 									 																{
									 									 																	val = ($('#pricerange').slider('value',0)*10000+(" . $minprice . "));
									 									 																	$('#pricerange1').val(val);
									 									 																}
									 									 																else if($('#pricerange').slider('value',0) > 90 && $('#pricerange').slider('value',0) < 98)
									 									 																{
									 									 																	val = ($('#pricerange').slider('value',0)*20000+(" . $minprice . "));
									 									 																	$('#pricerange1').val(val);
									 									 																}
									 									 																else
									 									 																{
									 									 																	$('#pricerange1').val(" . $minprice . ");
									 									 																}
									 									 																
									 									 																// pricerange2
									 									 																if($('#pricerange').slider('value',1) <= 30 && $('#pricerange').slider('value',1) >1)
									 									 																{
									 									 																	val = ((-1)*$('#pricerange').slider('value',1)*25+(" . $maxprice . "));
									 									 																	$('#pricerange2').val(val);
									 									 																}
									 									 																else if($('#pricerange').slider('value',1) > 30 && $('#pricerange').slider('value',1) <= 50)
									 									 																{
									 									 																	val = ((-1)*$('#pricerange').slider('value',1)*75+(" . $maxprice . "));
									 									 																	$('#pricerange2').val(val);
									 									 																}
									 									 																else if($('#pricerange').slider('value',1) > 50 && $('#pricerange').slider('value',1) <= 70)
									 									 																{
									 									 																	val = ((-1)*$('#pricerange').slider('value',1)*250+(" . $maxprice . "));
									 									 																	$('#pricerange2').val(val);
									 									 																}
									 									 																else if($('#pricerange').slider('value',1) > 70 && $('#pricerange').slider('value',1) <= 80)
									 									 																{
									 									 																	val = ((-1)*$('#pricerange').slider('value',1)*1000+(" . $maxprice . "));
									 									 																	$('#pricerange2').val(val);
									 									 																}
									 									 																else if($('#pricerange').slider('value',1) > 80 && $('#pricerange').slider('value',1) <= 90)
									 									 																{
									 									 																	val = ((-1)*$('#pricerange').slider('value',1)*10000+(" . $maxprice . "));
									 									 																	$('#pricerange2').val(val);
									 									 																}
									 									 																else if($('#pricerange').slider('value',1) > 90 && $('#pricerange').slider('value',1) < 98)
									 									 																{
									 									 																	val = ((-1)*$('#pricerange').slider('value',1)*20000+(" . $maxprice . "));
									 									 																	$('#pricerange2').val(val);
									 									 																}
									 									 																else if($('#pricerange').slider('value',1)==1)
									 									 																{
									 									 																	$('#pricerange2').val(" . $minprice . ");
									 									 																}
									 									 																else
									 									 																{
									 									 																	$('#pricerange2').val(" . $maxprice . ");
									 									 																}


																																	},

								 									 																change: function(e,ui) { 
								 									 																	
								 									 																	
																																		
								 									 																	//modifyresult('searchminprice',parseInt($('#pricerange1').val()));

								 									 																	//modifyresult('searchmaxprice',parseInt($('#pricerange2').val()));

								 									 																	//searchdiamonds();
								 									 																	globalsortname = 'price';
								 									 																	couplemodifyresult('searchminprice',parseInt($('#pricerange1').val()),'searchmaxprice',parseInt($('#pricerange2').val())); 	
															} });
					 									$('#depth').slider({ min:4000,max:9000, range: true, slide : function(e,ui) { 
							 									 																	$('#depthmin').val((parseInt($('#depth').slider('value', 0)))/100);
								 									 																$('#depthmax').val(parseInt($('#depth').slider('value', 1))/100);
					 									}, change: function(e,ui) { 
							 									 																	$('#depthmin').val((parseInt($('#depth').slider('value', 0)))/100);
								 									 																$('#depthmax').val(parseInt($('#depth').slider('value', 1))/100);
								 									 																	//modifyresult('depthmin',parseInt($('#depthmin').val()));
								 									 																	//modifyresult('depthmax',parseInt($('#depthmax').val()));
								 									 																	//searchdiamonds();
								 									 																	couplemodifyresult('depthmin',parseInt($('#depthmin').val()),'depthmax',parseInt($('#depthmax').val()));
					 									 } });
					 									 
					 									 $('#tablerange').slider({  min:4800,max:9000, range: true, slide : function(e,ui) { $('#tablemin').val((parseInt($('#tablerange').slider('value', 0)))/100);
								 									 																$('#tablemax').val(parseInt($('#tablerange').slider('value', 1))/100);
								 									 																},change: function(e,ui) { 
							 									 																	$('#tablemin').val((parseInt($('#tablerange').slider('value', 0)))/100);
								 									 																$('#tablemax').val(parseInt($('#tablerange').slider('value', 1))/100);
								 									 																	//modifyresult('tablemin',parseInt($('#tablemin').val()));
								 									 																	//modifyresult('tablemax',parseInt($('#tablemax').val()));
								 									 																	//searchdiamonds();
								 									 																	couplemodifyresult('tablemin',parseInt($('#valuemin').val()),'tablemax',parseInt($('#tablemax').val()));
					 									 } });
														 
					 									 
										 	 $('#carat').slider({ steps: 1000, range: true, slide : function(e,ui) { value = $('#caratmin').val((parseInt($('#carat').slider('value', 0)))* ((".$caratminmax['max'].")/100));
										 						 																$('#caratmax').val((parseInt($('#carat').slider('value', 1)))* ((".$caratminmax['max'].")/100));
										 						 																}, change: function(e,ui) { 
										 					 																	$('#caratmin').val((parseInt($('#carat').slider('value', 0)))* ((".$caratminmax['max'].")/100));
										 						 																$('#caratmax').val((parseInt($('#carat').slider('value', 1)))* ((".$caratminmax['max'].")/100));
										 						 																	//modifyresult('caratmin', ($('#caratmin').val()));
										 						 																	//modifyresult('caratmax', ($('#caratmax').val()));
										 						 																	//searchdiamonds();
										 						 																	globalsortname = 'carat';
										 						 																	couplemodifyresult('caratmin', ($('#caratmin').val()),'caratmax', ($('#caratmax').val()));									
																																	 } });	
					 									 
					 									 $('#cut').slider({ steps: 3, range: true, slide : function(e,ui) { $('#cutmin').val((parseInt(($('#cut').slider('value', 0)))* ((3)/100)));
								 									 																$('#cutmax').val((parseInt(($('#cut').slider('value', 1)))* ((3)/100)));
								 									 																}, change: function(e,ui) { 
							 									 																	$('#cutmin').val((parseInt(($('#cut').slider('value', 0)))* ((3)/100)));
								 									 																$('#cutmax').val((parseInt(($('#cut').slider('value', 1)))* ((3)/100)));
								 									 																	//modifyresult('cutmin',($('#cutmin').val()));
								 									 																	//modifyresult('cutmax',($('#cutmax').val()));
								 									 																	//searchdiamonds();
								 									 																	globalsortname = 'cut';
								 									 																	couplemodifyresult('cutmin',($('#cutmin').val()),'cutmax',($('#cutmax').val()));
					 									 } });
					 									 
					 									 $('#color').slider({ steps: 6, range: true,minValue: 0, maxValue: 6,  slide : function(e,ui) { $('#colormin').val(parseInt(parseInt($('#color').slider('value', 0))*(0.07)));
								 									 																$('#colormax').val((parseInt((($('#color').slider('value', 1)))* ((7)/100))));
								 									 																 },change: function(e,ui) { 
							 									 																	$('#colormin').val(parseInt(parseInt($('#color').slider('value', 0))*(0.07)));
								 									 																$('#colormax').val((parseInt((($('#color').slider('value', 1)))* ((7)/100))));
								 									 																	//modifyresult('colormin', parseInt($('#colormin').val()));
								 									 																	//modifyresult('colormax', parseInt($('#colormax').val()));
								 									 																	//searchdiamonds();
								 									 																	globalsortname = 'color';
								 									 																	couplemodifyresult('colormin', parseInt($('#colormin').val()),'colormax', parseInt($('#colormax').val()));
					 									 } });
					 									 
					 									 $('#clarity').slider({ steps: 7, range: true,minValue: 0, maxValue: 7, slide : function(e,ui) { $('#claritymin').val(parseInt(parseInt($('#clarity').slider('value', 0))*(0.08)));
								 									 																$('#claritymax').val((parseInt((($('#clarity').slider('value', 1)))* ((8)/100))));
								 									 																}, change: function(e,ui) { 
							 									 																	$('#claritymin').val(parseInt(parseInt($('#clarity').slider('value', 0))*(0.08)));
								 									 																$('#claritymax').val((parseInt((($('#clarity').slider('value', 1)))* ((8)/100))));
								 									 																	//modifyresult('claritymin', parseInt($('#claritymin').val()));
								 									 																	//modifyresult('claritymax', parseInt($('#claritymax').val()));
								 									 																	//searchdiamonds();
								 									 																	globalsortname = 'clarity';
								 									 																	couplemodifyresult('claritymin', parseInt($('#claritymin').val()),'claritymax', parseInt($('#claritymax').val()));
					 									 } });
					 									 
					 									 					 									 
					 									 $('#fluro').slider({ steps: 5, range: true, slide : function(e,ui) { $('#fluromin').val((parseInt(($('#fluro').slider('value', 0)))* ((5)/100)));
								 									 																$('#fluromax').val((parseInt(($('#fluro').slider('value', 1)))* ((5)/100)));
								 									 																}, change: function(e,ui) { 
							 									 																	$('#fluromin').val((parseInt(($('#fluro').slider('value', 0)))* ((5)/100)));
								 									 																$('#fluromax').val((parseInt(($('#fluro').slider('value', 1)))* ((5)/100)));
								 									 																	//modifyresult('fluromin', parseInt($('#fluromin').val()));
								 									 																	//modifyresult('fluromax', parseInt($('#fluromax').val()));
		  									 																							//searchdiamonds();
		  									 																							couplemodifyresult('fluromin', parseInt($('#fluromin').val()),'fluromax', parseInt($('#fluromax').val()));
					 									 } });
					 									 
					 									  $('#polis').slider({ steps: 4, range: true, slide : function(e,ui) { $('#polismin').val((parseInt(($('#polis').slider('value', 0)))* ((4)/100)));
								 									 																$('#polismax').val((parseInt(($('#polis').slider('value', 1)))* ((4)/100)));
								 									 																}, change: function(e,ui) { 
							 									 																	$('#polismin').val((parseInt(($('#polis').slider('value', 0)))* ((4)/100)));
								 									 																$('#polismax').val((parseInt(($('#polis').slider('value', 1)))* ((4)/100)));
								 									 																	//modifyresult('polismin', parseInt($('#polismin').val()));
								 									 																	//modifyresult('polismax', parseInt($('#polismax').val()));
								 									 																	//searchdiamonds();
								 									 																	couplemodifyresult('polismin', parseInt($('#polismin').val()),'polismax', parseInt($('#polismax').val()));
					 									 } });
					 									 
					 									 $('#symmet').slider({ steps: 4, range: true, slide : function(e,ui) { $('#symmetmin').val((parseInt(($('#symmet').slider('value', 0)))* ((4)/100)));
								 									 																$('#symmetmax').val((parseInt(($('#symmet').slider('value', 1)))* ((4)/100)));
								 									 																}, change: function(e,ui) { 
							 									 																	$('#symmetmin').val((parseInt(($('#symmet').slider('value', 0)))* ((4)/100)));
								 									 																$('#symmetmax').val((parseInt(($('#symmet').slider('value', 1)))* ((4)/100)));
								 									 																	//modifyresult('symmetmin', parseInt($('#symmetmin').val()));
								 									 																	//modifyresult('symmetmax', parseInt($('#symmetmax').val()));
								 									 																	//searchdiamonds();
								 									 																	couplemodifyresult('symmetmin', parseInt($('#symmetmin').val()),'symmetmax', parseInt($('#symmetmax').val()));
					 									 } });
					 									 
					 									 $('#pricePerCaratRange').slider({ steps: 100, range: true, minValue : 1, slide : function(e,ui) { $('#pricePerCaratmin').val((parseInt($('#pricePerCaratRange').slider('value', 0)))* ((" . $maxpricepercrt . "-" . $minpricepercrt . ")/100));
								 									 																$('#pricePerCaratmax').val(parseInt($('#pricePerCaratRange').slider('value', 1)) * ((" . $maxpricepercrt . "-" . $minpricepercrt . ")/100));
								 									 																}, change: function(e,ui) { 
																																	$('#pricePerCaratmin').val((parseInt($('#pricePerCaratRange').slider('value', 0)))* ((" . $maxpricepercrt . "-" . $minpricepercrt . ")/100));
								 									 																$('#pricePerCaratmax').val(parseInt($('#pricePerCaratRange').slider('value', 1)) * ((" . $maxpricepercrt . "-" . $minpricepercrt . ")/100));
								 									 																	//modifyresult('pricePerCaratmin',parseInt($('#pricePerCaratmin').val()));
								 									 																	//modifyresult('pricePerCaratmax',parseInt($('#pricePerCaratmax').val()));
								 									 																	//searchdiamonds();
								 									 																	couplemodifyresult('pricePerCaratmin',parseInt($('#pricePerCaratmin').val()),'pricePerCaratmax',parseInt($('#pricePerCaratmax').val()));
														 } });
					 									 
					 									 
					 									 
														$(\"#searchresult\").flexigrid
																	(
																	{   	 							
																	url: '" . $url . "',
																	dataType: 'json',
																	colModel : [
																		{display: 'Lot', name : 'lot', width : 110, sortable : false, align: 'left'},
																		{display: 'Shape', name : 'shape', width : 70, sortable : true, align: 'center'},
																		{display: 'Carat', name : 'carat', width : 55, sortable : true, align: 'center'},
																		{display: 'Color', name : 'color', width : 55, sortable : true, align: 'center'},
																		{display: 'Clarity', name : 'clarity', width : 55, sortable : true, align: 'center'},
																		{display: 'Price', name : 'price', width : 90, sortable : true, align: 'center'},
																		{display: 'Ratio', name : 'ratio', width : 65, sortable : true, align: 'center'},
																		{display: 'Cut', name : 'cut', width : 70, sortable : true, align: 'center'},																		
																		{display: 'Depth', name : 'Depth', width : 45, sortable : true, align: 'center',hide: true},
																		{display: 'Polish', name : 'Polish', width : 45, sortable : true, align: 'center',hide: true},
																		{display: 'Table%', name : 'TablePercent', width : 45, sortable : true, align: 'center',hide: true},
																		{display: 'Flurosence', name : 'Flour', width : 50, sortable : true, align: 'center',hide: true},
																		{display: 'Culet', name : 'Culet', width : 45, sortable : true, align: 'center'},
																		{display: 'Cert', name : 'Cert', width : 45, sortable : true, align: 'center'},
																		{display: 'Symmetry', name : 'Sym', width : 40, sortable : false, align: 'center',hide: true},
																		{display: 'Price/Carat', name : 'pricepercrt', width : 50, sortable : false, align: 'center',hide: true}
																		],
																	 		
																	sortname: \"price\",
																	sortorder: \"asc\",
																	usepager: true,
																	title: '<h1 class=\"pageheader\">Diamonds Search Result</h1>',
																	useRp: true,
																	rp: 25,
																	showTableToggleBtn: false,
																	striped:false,
																	width: 900,
																	height: \"auto\"
																	}
																	);
														 
																$(\".flexigrid .bDiv\").height(418);
																$(\".flexigrid .nDiv\").height(418);
																$(\".flexigrid .hGrip\").height(418);			
														 
														 ";
			
			$data ['extraheader'] = '';
			$data ['extraheader'] .= '<script src="' . config_item ( 'base_url' ) . 'js/jquery.ui.js" type="text/javascript"></script>
											 <script src="' . config_item ( 'base_url' ) . 'third_party/flexigrid/flexigrid.js"></script>';
			$data ['extraheader'] .= '<link type="text/css" href="' . config_item ( 'base_url' ) . 'third_party/flexigrid/css/flexigrid/flexigrid.css" rel="stylesheet" /> ';
			$data ['bodyonload'] = 'initialize()';
			$data ['bodyonunload'] = 'GUnload()';
			$data ['usetips'] = true;
			
			$data['meta_tags'] = '<meta http-equiv="Content-Type" content="text/html; iso-8859-1">
	<meta name="title" content="diamond anniversary band|diamond bridal sets|diamond solitaire pendant| blue diamond jewelry">
	<meta name="ROBOTS" content="INDEX,FOLLOW">
	<meta name="description" content="Buy online fair trade diamond, loose gia diamond, wholesale certified diamonds, 
diamond anniversary band, diamond bridal sets, diamond solitaire pendant, blue diamond jewelry. Purchase discounted rate diamond anniversary band, diamond bridal sets, diamond solitaire pendant,  blue diamond jewelry">
	<meta name="abstract" content="Diamond Rings, Wholesale Diamonds, Estate Jewelry, Custom Engagement Rings, New York, Chicago, California, Boston, Las Vegas, Columbia, Montgomery">
	<meta name="keywords" content="fair trade diamond, loose gia diamond, wholesale certified diamonds, 
diamond anniversary band, diamond bridal sets, diamond solitaire pendant, blue diamond jewelry. Purchase discounted rate diamond anniversary band, diamond bridal sets, diamond solitaire pendant,  blue diamond jewelry">
	<meta name="author" content="7techniques">
	<meta name="publisher" content="7techniques">
	<meta name="copyright" content="7techniques">
	<meta http-equiv="Reply-to" content="">
	<meta name="creation_Date" content="12/12/2008">
	<meta name="expires" content="">
	<meta name="revisit-after" content="7 days">
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">';
			
			$output = $this->load->view ( 'diamond/advancesearch', $data, true );
			$this->output ( $output, $data, false, false );
		} else {
			
			$data['meta_tags'] = '<meta http-equiv="Content-Type" content="text/html; iso-8859-1">
	<meta name="title" content="diamond anniversary band|diamond bridal sets|diamond solitaire pendant| blue diamond jewelry">
	<meta name="ROBOTS" content="INDEX,FOLLOW">
	<meta name="description" content="Buy online fair trade diamond, loose gia diamond, wholesale certified diamonds, 
diamond anniversary band, diamond bridal sets, diamond solitaire pendant, blue diamond jewelry. Purchase discounted rate diamond anniversary band, diamond bridal sets, diamond solitaire pendant,  blue diamond jewelry">
	<meta name="abstract" content="Diamond Rings, Wholesale Diamonds, Estate Jewelry, Custom Engagement Rings, New York, Chicago, California, Boston, Las Vegas, Columbia, Montgomery">
	<meta name="keywords" content="fair trade diamond, loose gia diamond, wholesale certified diamonds, 
diamond anniversary band, diamond bridal sets, diamond solitaire pendant, blue diamond jewelry. Purchase discounted rate diamond anniversary band, diamond bridal sets, diamond solitaire pendant,  blue diamond jewelry">
	<meta name="author" content="7techniques">
	<meta name="publisher" content="7techniques">
	<meta name="copyright" content="7techniques">
	<meta http-equiv="Reply-to" content="">
	<meta name="creation_Date" content="12/12/2008">
	<meta name="expires" content="">
	<meta name="revisit-after" content="7 days">
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">';
			
			$output = $this->load->view ( 'diamond/search', $data, true );
			$this->output ( $output, $data );
		}
	
	}
	
	function getsearchresult($addoption = '', $settingsid = '') {
		$page = isset ( $_POST ['page'] ) ? $_POST ['page'] : 1;
		$rp = isset ( $_POST ['rp'] ) ? $_POST ['rp'] : 15;
		$sortname = isset ( $_POST ['sortname'] ) ? $_POST ['sortname'] : 'price';
		$sortorder = isset ( $_POST ['sortorder'] ) ? $_POST ['sortorder'] : 'desc';
		$query = isset ( $_POST ['query'] ) ? $_POST ['query'] : '';
		$qtype = isset ( $_POST ['qtype'] ) ? $_POST ['qtype'] : 'title';
		
		$results = $this->diamondmodel->getSearch ( $page, $rp, $sortname, $sortorder, $query, $qtype, '', $addoption );
		header ( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
		header ( "Last-Modified: " . gmdate ( "D, d M Y H:i:s" ) . "GMT" );
		header ( "Cache-Control: no-cache, must-revalidate" );
		header ( "Pragma: no-cache" );
		header ( "Content-type: text/x-json" );
		$json = "";
		$json .= "{\n";
		$json .= "page: $page,\n";
		$json .= "sortname: \"carat\",\n";
		$json .= "total: " . $results ['count'] . ",\n";
		$json .= "rows: [";
		$rc = false;
		
		foreach ( $results ['result'] as $row ) {
			$shape = '';
			switch ($row ['shape']) {
				case 'B' :
					$shape = 'Round';
					break;
				case 'PR' :
					$shape = 'Princess';
					break;
				case 'R' :
					$shape = 'Radiant';
					break;
				case 'E' :
					$shape = 'Emerald';
					break;
				case 'AS' :
					$shape = 'Ascher';
					break;
				case 'O' :
					$shape = 'Oval';
					break;
				case 'M' :
					$shape = 'Marquise';
					break;
				case 'P' :
					$shape = 'Pear shape';
					break;
				case 'H' :
					$shape = 'Heart';
					break;
				case 'C' :
					$shape = 'Cushion';
					break;
				default :
					$shape = $row ['shape'];
					break;
			}
			
			/*$url='';
									if($addoption!=''){
										$url= config_item('base_url')."diamonds/getsearchresult/".$option;
									}
									else {$url= config_item('base_url')."diamonds/getsearchresult";}*/
			
			$settingsid = ($settingsid != '') ? $settingsid : 'false';
			
			if ($rc)
				$json .= ",";
			$json .= "\n {";
			$json .= "lot:'" . $row ['lot'] . "',";
			$json .= "cell:['<input type=\'checkbox\' name=\'products[]\' value=\'" . $row ['lot'] . "\'><a href=\"#\" onclick=\"viewDiamondDetails(" . $row ['lot'] . ",\'" . $addoption . "\'," . $settingsid . ")\" class=\"blue search\">View Details</a>'";
			$json .= ",'" . $shape . "'";
			$json .= ",'" . addslashes ( $row ['carat'] ) . "'";
			$json .= ",'" . addslashes ( $row ['color'] ) . "'";
			$json .= ",'" . addslashes ( $row ['clarity'] ) . "'";
			
			//$priceRatio = $this->diamondmodel->getRatio($row ['price']);
			
			$json .= ",'$". addslashes ( number_format ( round ( $row ['price'] ), ',' ) ) . "'";
			$json .= ",'" . addslashes ( $row ['ratio'] ) . "'";
			$json .= ",'" . addslashes ( $row ['cut'] ) . "'";
			$json .= ",'" . addslashes ( $row ['Depth'] ) . "'";
			$json .= ",'" . addslashes ( $row ['Polish'] ) . "'";
			$json .= ",'" . addslashes ( $row ['TablePercent'] ) . "'";
			$json .= ",'" . addslashes ( $row ['Flour'] ) . "'";
			$json .= ",'" . addslashes ( $row ['Culet'] ) . "'";
			$cimage = trim ( $row ['certimage'] );
			//$cimage = substr(trim($row['certimage']),7,(strlen(trim($row['certimage']))-7)); 
			//'www.rapnet.com/UF/50217/Certs/1087.jpg' ;
			if (isset ( $cimage ) && $cimage != '') {
				$json .= ",'<a class=\"blue\"  onclick=\"viewChart(\'" . $cimage . "\')\"  href=\"#\" >" . addslashes ( $row ['Cert'] ) . "</a>'";
			} //onclick=\"viewChart(\'".$row['certimage']."\')\"
else {
				$json .= ",'" . addslashes ( $row ['Cert'] ) . "'";
			}
			$json .= ",'" . addslashes ( $row ['Sym'] ) . "'";
			$json .= ",'" . addslashes ( $row ['pricepercrt'] ) . "'";
			$json .= ",'']";
			$json .= "}";
			$rc = true;
		}
		$json .= "]\n";
		$json .= "}";
		echo $json;
	}
	
	function premium() {
		$data = $this->getCommonData ();
		$data ['title'] = 'Diamonds';
		$data ['usetips'] = true;
		$data ['extraheader'] = '<script src="' . config_item ( 'base_url' ) . 'js/interface.js" type="text/javascript"></script>
								<script src="' . config_item ( 'base_url' ) . 'js/swfobject.js" type="text/javascript"></script>';
		$data ['onloadextraheader'] = "$('#fisheye').Fisheye(
				{
					maxWidth: 50,
					items: 'a',
					itemsText: 'span',
					container: '.fisheyeContainter',
					itemWidth: 40,
					proximity: 90,
					halign : 'center'
				}
			) ;
			var so;	
			so = new SWFObject(\"" . config_item ( 'base_url' ) . "swf/markt.swf\", \"test\", \"205\", \"150\", \"8\", \"#fff\");
			so.addParam(\"wmode\", \"transparent\");
			so.write(\"marktcollectionswf\");
			so = new SWFObject(\"" . config_item ( 'base_url' ) . "swf/pave.swf\", \"test\", \"205\", \"150\", \"8\", \"#fff\");
			so.addParam(\"wmode\", \"transparent\");
			so.write(\"pavecollectionswf\");
			so = new SWFObject(\"" . config_item ( 'base_url' ) . "swf/halo.swf\", \"test\", \"205\", \"150\", \"8\", \"#fff\");
			so.addParam(\"wmode\", \"transparent\");
			so.write(\"halocollectionswf\");
			so = new SWFObject(\"" . config_item ( 'base_url' ) . "swf/matching.swf\", \"test\", \"205\", \"150\", \"8\", \"#fff\");
			so.addParam(\"wmode\", \"transparent\");
			so.write(\"matchingsetswf\");
			so = new SWFObject(\"" . config_item ( 'base_url' ) . "flash/3-58-172.swf\", \"test\", \"240\", \"190\", \"8\", \"#fff\");
			so.addParam(\"wmode\", \"transparent\");
			so.write(\"flashdiamond\");
			
			";
			
			$data['meta_tags'] = '<meta http-equiv="Content-Type" content="text/html; iso-8859-1">
	<meta name="title" content="Diamond Wedding Ring|Wedding Ring Sets|Loose Diamond Wholesale|Discount Loose Diamonds">
	<meta name="ROBOTS" content="INDEX,FOLLOW">
	<meta name="description" content="Buy diamond wedding ring, wedding ring diamonds, inexpensive wedding rings, cheap wedding rings, discount wedding rings, wedding ring set, wedding ring sets, loose diamonds, loose diamond, cheap loose diamonds, loose diamond wholesale, discount loose diamonds, loose diamonds wholesale">
	<meta name="abstract" content="Diamond Rings, Wholesale Diamonds, Estate Jewelry, Custom Engagement Rings, New York, Chicago, California, Boston, Las Vegas, Columbia, Montgomery">
	<meta name="keywords" content="Buy diamond wedding ring, wedding ring diamonds, inexpensive wedding rings, cheap wedding rings, discount wedding rings, wedding ring set, wedding ring sets, loose diamonds, loose diamond, cheap loose diamonds, loose diamond wholesale, discount loose diamonds, loose diamonds wholesale">
	<meta name="author" content="7techniques">
	<meta name="publisher" content="7techniques">
	<meta name="copyright" content="7techniques">
	<meta http-equiv="Reply-to" content="">
	<meta name="creation_Date" content="12/12/2008">
	<meta name="expires" content="">
	<meta name="revisit-after" content="7 days">
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">';
			
		$output = $this->load->view ( 'diamond/premiumdiamonds', $data, true );
		$this->output ( $output, $data );
	
	}
	
	function diamonddetails($lot, $addoption = '', $settingsid = '') {
		
		$this->load->model ( 'commonmodel' );
		$data = $this->getCommonData ();
		$data ['title'] = 'Diamonds Details';
		
		$details = $this->diamondmodel->getDetailsByLot ( $lot );
		$data ['details'] = $details;
		$this->session->set_userdata ( 'addoption', $addoption );
		$this->session->set_userdata ( 'mydiamond', $lot );
		
		$addoption = $this->session->userdata ( 'addoption' );
		$data ['addoption'] = $addoption;
		$data ['tabhtml'] = '';
		$data ['pageheader'] = '';
		$data ['onclickfunction'] = '';
		
		$date ['nexturl'] = '';
		switch ($addoption) {
			case 'addloosediamond' :
				$data ['pageheader'] = 'Diamond Details';
				$data ['nexturl'] = '##';
				$data ['onclickfunction'] = 'addtocart(\'' . $addoption . '\',' . $lot . ',false,false,false,' . number_format(round($details ['price']), ',') . ' )';
				$data ['linkhtml'] = '';
				break;
			case 'addtoring' :
				$data ['pageheader'] = 'Build Your Own Ring';
				$data ['nexturl'] = config_item ( 'base_url' ) . 'engagement/engagement_ring_settings/' . $lot . '/addtoring';
				$this->session->set_userdata ( 'addoption', $addoption );
				$data ['linkhtml'] = '';
				break;
			case 'tothreestone' :
				$data ['pageheader'] = 'Build Your Own Three-Stone Ring';
				$data ['nexturl'] = config_item ( 'base_url' ) . 'diamonds/searchsidestone/' . $lot . '/addsidestone';
				$this->session->set_userdata ( 'addoption', $addoption );
				$data ['linkhtml'] = '';
				break;
			case 'tosidestone' :
				$data ['pageheader'] = 'Build Your Own Three-Stone Ring';
				$data ['nexturl'] = config_item ( 'base_url' ) . 'diamonds/sidestonedetails/' . $lot;
				$this->session->set_userdata ( 'addoption', $addoption );
				$data ['linkhtml'] = '';
				break;
			case 'addpendantsetings' :
				$data ['pageheader'] = 'Build Your Own Diamond Pendant';
				$data ['nexturl'] = '##';
				$data ['onclickfunction'] = 'addtocart(\'' . $addoption . '\',' . $lot . ',false,false,' . $settingsid . ',false)';
				$data ['linkhtml'] = '';
				break;
			case 'addpendantsetings3stone' :
				$data ['pageheader'] = 'Build Your Own Diamond Pendant';
				$data ['nexturl'] = config_item ( 'base_url' ) . '/diamonds/searchsidestone/' . $lot . '/' . $addoption . '/' . $settingsid;
				$this->session->set_userdata ( 'addoption', $addoption );
				$data ['linkhtml'] = '';
				break;
			
			default :
				$data ['nexturl'] = '#';
				$data ['linkhtml'] = ' <div id="add_diamond_list" class="linkmanunext" style="display:none;">
				      	      				<ul class="textleft">
				      	      					<li><a href="' . config_item ( 'base_url' ) . 'engagement/engagement_ring_settings/' . $lot . '/addtoring"  class="redd">to ring</a> </li>
				      	      					<li><a href="' . config_item ( 'base_url' ) . 'diamonds/searchsidestone/' . $lot . '/addsidestone" class="redd">to three stone ring</a> </li>
				      	      					<!--<li><a href="#" onclick="viewearringdiamonddetails(' . $lot . ')" class="redd">to earring</a> </li>
				      	      					<li><a href="#" class="redd">to diamond pendant</a> </li>-->
				      	      					<li><a href="javascript:void(0)" class="redd" onclick="addtocart(\'addloosediamond\',' . $lot . ',false,false,false,false )">to shopping basket</a> </li>
				      	      				</ul>
				      	      		 </div>
				      	      		 
									';
				$data ['pageheader'] = 'Details View';
				break;
		}
		
		if ($addoption = 'addtoring' || $addoption = 'tothreestone') {
			$output = $this->load->view ( 'diamond/viewdiamond', $data, true );
		} else
			$output = $this->load->view ( 'diamond/index', $data, true );
		
		$this->output ( $output, $data );
	}
	
	function diamonddetailsajax($lot, $addoption = '', $settingsid = '') {
		
		$this->load->model ( 'commonmodel' );
		$data = $this->getCommonData ();
		$data ['title'] = 'Diamonds Details';
		
		$data ['details'] = $this->diamondmodel->getDetailsByLot ( $lot );
		
		$this->session->set_userdata ( 'addoption', $addoption );
		$this->session->set_userdata ( 'mydiamond', $lot );
		
		$addoption = $this->session->userdata ( 'addoption' );
		$data ['addoption'] = $addoption;
		$data ['settingsid'] = $settingsid;
		
		$date ['nexturl'] = '';
		$data ['onclickfunction'] = '';
		switch ($addoption) {
			
			case 'addtoring' :
				$data ['pageheader'] = 'Build Your Own Ring';
				$data ['nexturl'] = config_item ( 'base_url' ) . 'engagement/engagement_ring_settings/' . $lot . '/addtoring';
				$this->session->set_userdata ( 'addoption', $addoption );
				$data ['linkhtml'] = '';
				break;
			case 'tothreestone' :
				$data ['pageheader'] = 'Build Your Own Three-Stone Ring';
				$data ['nexturl'] = config_item ( 'base_url' ) . 'diamonds/searchsidestone/' . $lot . '/addsidestone';
				$this->session->set_userdata ( 'addoption', $addoption );
				$data ['linkhtml'] = '';
				break;
			case 'tosidestone' :
				$data ['pageheader'] = 'Build Your Own Three-Stone Ring';
				$data ['nexturl'] = config_item ( 'base_url' ) . 'diamonds/sidestonedetails/' . $lot;
				$this->session->set_userdata ( 'addoption', $addoption );
				$data ['linkhtml'] = '';
				break;
			case 'addpendantsetings' :
				$data ['pageheader'] = 'Build Your Own Diamond Pendant';
				//$data['nexturl'] = config_item('base_url').'/diamonds/addthisdiamond/'.$addoption.'/'.$lot.'/'.$settingsid;
				$data ['nexturl'] = config_item ( 'base_url' ) . 'jewelry/pendantdetailsview/' . $addoption . '/' . $lot . '/' . $settingsid;
				//$data['onclickfunction'] = 'addtocart(\''.$addoption.'\','.$lot.',false,false,'.$settingsid.',false)';
				//$this->session->set_userdata('addoption',$addoption);				
				$data ['linkhtml'] = '';
				break;
			case 'addpendantsetings3stone' :
				$data ['pageheader'] = 'Build Your Own Diamond Pendant';
				$data ['nexturl'] = config_item ( 'base_url' ) . '/diamonds/searchsidestone/' . $lot . '/' . $addoption . '/' . $settingsid;
				$this->session->set_userdata ( 'addoption', $addoption );
				$data ['linkhtml'] = '';
				break;
			/*case 'toearring':
				$data['pageheader'] = 'Build Your Earring';
				$data['nexturl'] = '#';
				$this->session->set_userdata('addoption',$addoption);				
				$data['linkhtml']='';
				break;*/
			
			default :
				$data ['nexturl'] = '#';
				$data ['linkhtml'] = ' <div id="add_diamond_list" class="linkmanunext" style="display:none;">
				      	      				<ul class="textleft">
				      	      					<li><a href="' . config_item ( 'base_url' ) . 'engagement/engagement_ring_settings/' . $lot . '/addtoring"  class="redd">to ring</a> </li>
				      	      					<li><a href="' . config_item ( 'base_url' ) . 'diamonds/searchsidestone/' . $lot . '/addsidestone" class="redd">to three stone ring</a> </li>
				      	      					<!--<li><a href="#" onclick="viewearringdiamonddetails(' . $lot . ')" class="redd">to earring</a> </li>
				      	      					<li><a href="#" class="redd">to diamond pendant</a> </li>-->
				      	      					<li><a href="' . config_item ( 'base_url' ) . 'diamonds/diamonddetails/' . $lot . '/addloosediamond" class="redd">to shopping basket</a> </li>
				      	      				</ul>
				      	      		 </div>
				      	      		 
									';
				$data ['pageheader'] = 'Details View';
				break;
		}
		
		if ($addoption = 'addtoring' || $addoption = 'tothreestone') {
			$output = $this->load->view ( 'diamond/diamonddetails', $data, true );
		} else
			header ( 'location: ' . config_item ( 'base_url' ) . 'diamonds/search' );
		
		echo $output;
	}
	
	function searchsidestone($lot, $addoption = '', $pendantsettingsid = '') {
		
		$data = $this->getCommonData ();
		$data ['title'] = 'Find Sidestone';
		
		$basket = $this->session->userdata ( 'basket' );
		//$centerstone = $basket['threestonering']['centerstone'];
		$data ['tabhtml'] = $this->commonmodel->getThreeStoneTab ( 'sidestone' );
		$data ['addoption'] = $addoption;
		$data ['pendantsettingsid'] = $pendantsettingsid;
		
		$carat = '';
		$color = '';
		$clarity = '';
		$condition = '';
		
		if (isset ( $lot )) {
			$data ['diamond'] = $this->diamondmodel->getDetailsByLot ( $lot );
			$data ['sidestones'] = $this->diamondmodel->getSidestoneByCenterLot ( $data ['diamond'] );
			$data ['onloadextraheader'] = "getsidestoneresult(0);";
			$data ['hlot'] = $lot;
		}
		
		if ($addoption == 'addsidestone' || $addoption == 'addpendantsetings3stone') {
			$output = $this->load->view ( 'diamond/searchsidestone', $data, true );
		} else
			$output = $this->load->view ( 'diamond/search/false/tothreestone', $data, true );
		
		$this->output ( $output, $data );
	}
	
	function getsidestoneresult($lot, $page = 0, $pendantsettingsid = '', $addoption = '') {
		
		$start = ($page == 'undefined') ? 0 : $page;
		$data ['diamond'] = $this->diamondmodel->getDetailsByLot ( $lot );
		$data ['sidestones'] = $this->diamondmodel->getAllSideStones ( $data ['diamond'], $start );
		$alldamnstones = $data ['sidestones'];
		$allsidestones = $this->diamondmodel->getAllSideStones ( $data ['diamond'], $start );
		
		//$addoption = ($pendantsettingsid == '') ? 'tosidestone' : 'addpendantsetings3stone';
		

		$returnhtml = '';
		$this->load->model ( 'sitepaging' );
		$data ['result'] = $allsidestones;
		$paginlinks = $this->sitepaging->getPageing ( $allsidestones ['count'], 'sidestones', $start, 'lot', 10 );
		$returnhtml .= $paginlinks . '<div class="hr"></div>';
		
		$sidestones = $alldamnstones ['result'];
		$diamond = $data ['diamond'];
		$returnhtml .= '<div>
							<table width="500px" style="text-align:center;">
								<tr class="tablaheader">									
									<td width="50px">Weight</td>
									<td width="80px">Cut</td>
									<td>Color</td>
									<td>Clarity</td>
									<td>Polish/<br>Symmetry</td>
									<td>Report</td>
									<td width="90px">Price</td>
									<td width="60px">Details</td>
								</tr>';
		if (isset ( $sidestones )) {
			foreach ( $sidestones as $sidestone ) {
				$returnhtml .= '<tr>									
								<td>' . $sidestone ['carat'] . '</td>
								<td>' . $sidestone ['cut'] . '</td>
								<td>' . $sidestone ['color'] . '</td>
								<td>' . $sidestone ['clarity'] . '</td>
								<td>' . $sidestone ['Polish'] . '/' . $sidestone ['Sym'] . '</td>
								<td>' . $sidestone ['Cert'] . '</td>
								<td>' . '$' . number_format ( round ( $sidestone ['price'] ) ) . '</td>
								<td><a href="#" onclick="viewSidestoneDetails(' . $sidestone ['lot'] . ',\'' . $addoption . '\',' . $diamond ['lot'] . ',' . $pendantsettingsid . ')" class="underline">Select</a></td>
							</tr>';
			}
		
		} else {
			$returnhtml .= '<tr>
		 						<td colspan="8">No side stone found </td>
		 					</tr>';
		}
		$returnhtml .= '	</table>
						</div>';
		
		echo $returnhtml;
	}
	
	function sidestonedetailsajax($sidelot = '', $addoption = '', $centerlot = '', $pendantsettingsid = '') {
		$data = $this->getCommonData ();
		$data ['title'] = 'Sidestone Details';
		
		$data ['centerlot'] = $centerlot;
		$data ['pendantsettingsid'] = $pendantsettingsid;
		$data ['addoption'] = $addoption;
		$centerdetails = $this->diamondmodel->getDetailsByLot ( $centerlot );
		$data ['centerlotprice'] = $centerdetails ['price'];
		
		$diamond = $this->diamondmodel->getDetailsByLot ( $sidelot );
		$depth = $diamond ['Depth'];
		$table = $diamond ['TablePercent'];
		$data ['sidestone2'] = $diamond;
		
		$tablemin = $table - 1;
		$tablemax = $table + 1;
		$depthmin = $depth - 1.5;
		$depthmax = $depth + 1.5;
		
		$tablecon = " TablePercent >= '" . $tablemin . "' and TablePercent <= '" . $tablemax . "' ";
		$depthcon = " Depth >= '" . $depthmin . "' and Depth <= '" . $depthmax . "' ";
		
		$data ['pairdiamond'] = $this->diamondmodel->getPairSidestone ( $diamond ['carat'], $diamond ['color'], $diamond ['clarity'], $tablecon, $depthcon );
		
		$output = $this->load->view ( 'diamond/sidestonedetails', $data, true );
		echo $output;
	}
	
	function sidestonedetails($lot = '') {
		$data = $this->getCommonData ();
		$data ['title'] = 'Sidestone Details';
		$output = $this->load->view ( 'diamond/sidestonedetails', $data, true );
		$this->output ( $output, $data );
	}
	
	function addthisdiamond($addoption = '', $lot = '', $pendantsettingsid = '', $sidelot1 = '', $sidelot2 = '') {
		//$toring = false, $tothreestonering = false, $todiamondpendant = false, $tobasket = false, 
		

		$data = $this->getCommonData ();
		$data ['title'] = 'Add This Diamond';
		$basket = $this->session->userdata ( 'basket' );
		
		if ($tothreestonering && $tothreestonering != 'false') {
			$basket ['threestonering'] ['centerstone'] = $lot;
			$this->session->set_userdata ( 'basket', $basket );
			$output = $this->load->view ( 'diamond/searchsidestone/addsidestone', $data, true );
		}
		
		switch ($addoption) {
			case 'addtoring' :
				$basket ['ring'] ['diamond'] = $lot;
				$this->session->set_userdata ( 'basket', $basket );
				header ( 'location:' . config_item ( 'base_url' ) . 'engagement/engagement_ring_settings/' . $lot );
				break;
			case 'addpendantsetings' :
				header ( 'location:' . config_item ( 'base_url' ) . 'shoppingbasket/mybasket/' . $lot . '/false/false/' . $pendantsettingsid . '/false/false/' . $addoption );
				break;
			case 'addpendantsetings3stone' :
				header ( 'location:' . config_item ( 'base_url' ) . 'shoppingbasket/mybasket/' . $lot . '/false/false/' . $pendantsettingsid . '/' . $sidelot1 . '/' . $sidelot2 . '/' . $addoption );
				break;
			default :
				$addoption = 'addloosediamond';
				header ( 'location:' . config_item ( 'base_url' ) . 'shoppingbasket/mybasket/' . $lot . '/false/false/false/false/false/' . $addoption );
				break;
		
		}
		
		//$output = $this->load->view('diamond/index' , $data , true);
		$this->output ( $output, $data );
	}

}
?>
