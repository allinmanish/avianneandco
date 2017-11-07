var globalsortname = null; 



function couplemodifyresult(var1,val1,var2,val2) {

$("#slideblocker").show();
	urllink = base_url+'site/setsession/'+var1+'/'+ val1;
	$.post(urllink,function(data){
		urllink = base_url+'site/setsession/'+var2+'/'+ val2;
		$.post(urllink,function(data){
			couplesearchdiamonds();
		});
	});
}

function couplesearchdiamonds(){

	 $("#searchresult").html('&nbsp;');

	 /*if (!globalsortname){globalsortname = "price"};

	 $("#searchresult").flexOptions({sortname:globalsortname,sortorder:"asc"});

	 $(".hDivBox th").removeClass("sorted");

	 $(".hDivBox th div").removeClass("sasc");

	 $(".hDivBox th[@abbr='"+globalsortname+"']").addClass("sorted").find("div").addClass("sasc");*/

	 $("#searchresult").flexReload(); 

}

function searchdiamonds(){

	$("#searchresult").html('&nbsp;');

	$("#searchresult").flexReload();

}

function modifyresult(svar , sval){

	urllink = base_url+'site/setsession/'+svar+'/'+ sval;

	//alert(urllink);

	$.ajax({

                 type: "POST",

                 url:urllink,

                 success: function(response) {
					

                   searchdiamonds();

                },

				error: function(){alert('Error 1');}

             }) 

}

function setsession(svar , sval){

	urllink = base_url+'site/setsession/'+svar+'/'+ sval;
	$.ajax({
                 type: "POST",
                 url:urllink,
                 success: function(response) {
                },
				error: function(){ 
				}
             }) 
}

function Z(){

	 $("#searchresult").html('&nbsp;');

	 $("#searchresult").flexReload(); 

}









function toggleopacity(e, flxgrid){

	id = (e.id);

	

	if($('#'+id).hasClass('selected')){

		$('#'+id).removeClass('selected');

	}else{

	  $('#'+id).addClass('selected');	

	}

	

	var shape = '';

	

	if($('#B').hasClass('selected')) shape += 'B_';	

	if($('#R').hasClass('selected')) shape += 'R_';

	if($('#PR').hasClass('selected')) shape += 'PR_';

	if($('#C').hasClass('selected')) shape += 'C_';

	if($('#AS').hasClass('selected')) shape += 'AS_';

	if($('#H').hasClass('selected')) shape += 'H_';

	if($('#E').hasClass('selected')) shape += 'E_';

	if($('#M').hasClass('selected')) shape += 'M_';

	if($('#O').hasClass('selected')) shape += 'O_';

	if($('#P').hasClass('selected')) shape += 'P_';

	

	if(flxgrid){

		modifyresult('shape' , shape); 

		if(shape == 'B_'){ 

			$("#searchresult").flexToggleCol(6,false);

		}

		else $("#searchresult").flexToggleCol(6,true);		

	}

	else {		

		setsession('shape' , shape);

	}

	

	

}





           

function viewThreestoneRingsDetails(stockno,centerid,sidestone1,sidestoen2){

	urllink = base_url+'site/threestoneringdetails/'+stockno+'/'+centerid+'/'+sidestone1+'/'+sidestoen2;

	

	$.ajax({

                 type: "POST",

                 url:urllink,

                 success: function(response) {

    				//$.facebox('<div style="width: 700px">' + response + '</div>');

					$.facebox('<div >' + response + '</div>');

                },

				error: function(){alert('Error 2');}

             }) 



	

}



function viewChart(lot)

{

	urllink = base_url+'diamonds/viewchart/'+ lot;

	$.ajax({

                 type: "POST",

                 url:urllink,

				 

                 success: function(response) {

					    $.facebox('<div style="width: 880px">' + response + '</div>');

                },

				error: function(){alert('Error 3');}

             }) 



	// $.facebox('<div style="width: 500px"><img src="'+ response +'" width="500" /></div>');

		

}



function viewDiamondDetails(id ,addoption,settingsid,id2){

	urllink = base_url+'diamonds/diamonddetailsajax/'+id+'/'+addoption+'/'+settingsid;

	

	if(addoption == 'toearring'){		

		urllink = base_url+'jewelry/earringdiamondsajax/'+id+'/'+id2+'/'+settingsid+'/'+addoption;		

	}

	

	$.ajax({

                 type: "POST",

                 url:urllink,

                 success: function(response) {

                 	$.facebox('<div style="width: 850px">' + response + '</div>');

                },

				error: function(){alert('Error 4');}

             }) 

	

}



function viewSidestoneDetails(sidestone1id,sidestone2id,addoption,centerlot,pendentsettingsid){ 

	

	urllink = base_url+'diamonds/sidestonedetailsajax/'+sidestone1id+'/'+sidestone2id+'/'+addoption+'/'+centerlot+'/'+pendentsettingsid;

//	alert(urllink);

	$.ajax({

                 type: "POST",

                 url:urllink,

                 success: function(response) {

                 	$.facebox('<div style="width: 530px">' + response + '</div>');

                },

				error: function(){alert('Error 5');}

             }) 

	

}

function viewRingsDetails(stockid,lot){

	urllink = base_url+'site/ringdetails/'+stockid+'/false/'+lot;

	

	$.ajax({

                 type: "POST",

                 url:urllink,

                 success: function(response) {

    				$.facebox('<div >' + response + '</div>');

                },

				error: function(){alert('Error 6');}

             }) 

}



function viewearringdiamonddetails(id){

	urllink = base_url+'jewelry/earringdiamondsajax/'+id;

	

	$.ajax({

                 type: "POST",

                 url:urllink,

                 success: function(response) {

    				$.facebox('<div style="width: 520px">' + response + '</div>');

                },

				error: function(){alert('Error 7');}

             }) 

}



function changeslider(sobj, v , i){

	 $('#'+sobj).slider( "moveTo", v, i)

}









function checkMinMaxPrice(e,e2, minvalue, maxvalue, minormax){

	id=e.id;

	if(minormax == 'min'){

		if((parseFloat($('#'+id).val()) < 0) || (parseFloat($('#'+id).val()) > parseFloat($('#'+e2).val()))) {

			$('#'+id).val(0);
			setsession('searchminprice' , 0);
			

		}

		else{

			setsession('searchminprice' , e.value);

		}

	}

	if(minormax == 'max'){

		if(parseFloat($('#'+id).val()) > 1000000 || parseFloat($('#'+id).val()) < parseFloat($('#'+e2).val())) {

			$('#'+id).val(1000000);

			setsession('searchmaxprice' , 1000000);

		}

		else{

			setsession('searchmaxprice' , e.value);

		}

	}

	

	

}



function showhide(e, isShow){

	

	if(isShow == 'true'){

		$('#'+e).show();

		$('#expand').hide();

		$('#minimize').show();

	}

	if(isShow == 'false'){

		$('#'+e).hide();

		$('#expand').show();

		$('#minimize').hide();

	}

	

}



function showhiderow(obj,e,svar, svalmin, svalmax, colid){	

             

	if(document.getElementById(obj).checked == true){

		$('#'+e).show();	

		$("#searchresult").flexToggleCol(colid,true);

		$(".flexigrid").width($(".flexigrid").width() + 40);

		$(".flexigrid .bDiv").height($(".flexigrid .bDiv").height() + 70);

		$(".flexigrid .nDiv").height($(".flexigrid .nDiv").height() + 70);

		$(".flexigrid .hGrip").height($(".flexigrid .hGrip").height() + 70);

		  

	}

	else{

		$('#'+e).hide();

		modifyresult(svar+'min',svalmin);

		modifyresult(svar+'max',svalmax);

		$("#searchresult").flexToggleCol(colid,false);

		$(".flexigrid").width($(".flexigrid").width() - 40);

		$(".flexigrid .bDiv").height($(".flexigrid .bDiv").height() - 70);

		$(".flexigrid .nDiv").height($(".flexigrid .nDiv").height() - 70);

		$(".flexigrid .hGrip").height($(".flexigrid .hGrip").height() - 70);

	} 

}



function showlist(event){

	$('#add_diamond_list').show();	

}



function hidelist(event){

	$('#add_diamond_list').hide();

}



$(function(){

	$('#adddiamond').hover(showlist,hidelist);

});



function showhide1(e, isShow,ex,min){



	if(isShow == 'true'){

		$('#'+e).show();

		$('#'+ex).hide();

		$('#'+min).show();

	}

	if(isShow == 'false'){

		$('#'+e).hide();

		$('#'+ex).show();

		$('#'+min).hide();

	}



}



function showdescription(e, isShow,per1,ex,min){

	

	if(isShow == 'true'){

		$('#'+e).show();

		$('#'+ex).hide();

		$('#'+per1).hide();

		$('#'+min).show();

	}

	if(isShow == 'false'){

		$('#'+e).hide();

		$('#'+ex).show();

		$('#'+per1).show();

		$('#'+min).hide();

	}

}



 

	function getdiamondlot(lot){

		alert('ndlkdsflkds');

		return lot;

	}

	

	function getringresults(page , rp, orderby){

		

		 lot = $('#hlot').val()

		 //alert(lot);

		 $('#searchresult').html('<div style="margin: 200px 0px 0px 300px; "><img src="'+base_url+'images/loading.gif"></div>');

		 var isPave,Solitaire,Sidestone,platinum,gold,whitegold,minprice,maxprice,isMrkt,isErd,isVatche,isDaussi,isAntique,isThreestone,isHalo,isMatching,isAnniversary;

		 isPave 	=  $('#pavsechk').attr("checked") ? 'pave' : 'notpave' ;

		 Solitaire 	=  $('#solitairechk').attr("checked") ? 'solitaire' : 'notsolitire' ;

		 Sidestone 	=   $('#sidestoneschk').attr("checked") ? 'sidestones' : 'notsidestone';

		 platinum 	=  $('#patinumchk').attr("checked") ? 'platinum' : 'notplatinum';

		 gold 		=  $('#goldchk').attr("checked") ? 'gold' : 'notgold'; 

		 whitegold 	=  $('#whitegoldchk').attr("checked") ? 'whitegold' : 'notwhitegold';

		 minprice 	= $('#pricerange1').val();

		 maxprice 	= $('#pricerange2').val();

		 dshape 	= $('#ringshape').val();

		 

		 isMrkt 	= $('#marktchk').attr("checked") ? 'marktcollection' : 'notmarktcollection'; 

		 isErd 		= $('#erdchk').attr("checked") ? 'erdcollection' : 'noterdcollection'; 

		 isVatche 	= $('#vatchechk').attr("checked") ? 'vatchecollection' : 'notvatchecollection'; 

		 isDaussi 	= $('#daussichk').attr("checked") ? 'daussicollection' : 'notdaussicollection'; 

		 isAntique 	= $('#antiquechk').attr("checked") ? 'antiquecollection' : 'notantiquecollection'; 

		 

		 isThreestone = $('#threestonechk').attr("checked") ? 'threestone' : 'notthreestone'; 

		 isHalo 	  = $('#halochk').attr("checked") ? 'halo' : 'nothalo'; 

		 isMatching   = $('#mathingchk').attr("checked") ? 'matching' : 'notmatching'; 

		 isAnniversary = $('#anniversarychk').attr("checked") ? 'anniversary' : 'notanniversary'; 

		 isWeddingband = $('#weddingbandchk').attr("checked") ? 'weddingband' : 'notweddingband';		 

		 urllink = base_url+'engagement/getringresults/'+isPave+'/'+Solitaire+'/'+Sidestone+'/'+platinum+'/'+gold+'/'+whitegold+'/'+isAnniversary+'/'+isWeddingband+'/'+minprice+'/'+maxprice+ '/' + dshape+'/'+page+'/'+isMrkt+'/'+isErd+'/'+isVatche+'/'+isDaussi+'/'+isAntique+'/'+isThreestone+'/'+isHalo+'/'+isMatching+'/'+lot;
		//alert(urllink);

		//prompt('a',urllink);
	     $.ajax({

                 type: "POST",

                 url:urllink,

                 success: function(response) {

                   $('#searchresult').html(response);

                },

				error: function(){alert('Error 8');}

             }) 

	}



	//----------------------------------Tamal------------------------------------------------

	 

	

	function setdiamondshape(e){

		shapeid = e.id;

		if(document.getElementById(shapeid).checked == true){

			//alert(shapeid);

			setsession('shape' , shapeid);			

		}		

	}

	

	function getmetalresult(e){

		xshape =  e.id;

		$('#searchresult').html('<div style="margin: 20px 0px 0px 200px; "><img src="'+base_url+'images/loading.gif"></div>');
		
		$.ajax({

                 type: "POST",

                 url:base_url+'jewelry/getMetalHtml/'+xshape,

                 success: function(response) {

                   $('#searchresult').html(response);

                },

				error: function(){alert('Error 9');}

             }) 

		

	} 

	

	function getstyleresult(metal, shape){

		metalname = metal.id; 

		$('#styleresult').html('<div style="margin: 20px 0px 0px 200px; "><img src="'+base_url+'images/loading.gif"></div>');
		$.ajax({

                 type: "POST",

                 url:base_url+'jewelry/getStyleHtmlResult/'+metalname+'/'+shape,

                 success: function(response) {

                   $('#styleresult').html(response);

                },

				error: function(){alert('Error 10');}

             }) 

		

	}

	

	function genericshowhide(e, isShow){



		if(isShow == 'true'){ $('#'+e).show();}

		else {$('#'+e).hide();}

	} 

	

	

	//------------------------------------Tamal end----------------------------------------------

	

	//-------------------------------------Shahadat------------------------------------------

	function setShapeInDiamondDetails(shape)

	{

		 var imgSrc = "url:('" + base_url + "images/tamal/diamond/top_"+ shape +".jpg')"; 

		 // alert(imgSrc);

		//$('#diamonddetails').CSS('background',base_url+'images/tamal/diamond/top_'+shape+'.jpg');

		//$('#diamonddetails').css('background-image','url('+ imgSrc+')'); 

		$('#diamonddetails').css({

			'backgroundImage': 'url(' + imgSrc +')',

			'backgroundRepeat': 'no-repeat',

			'backgroundPosition': 'top center'

		});

    }

	function vieworders(orderid)

	{

		urllink = base_url+'shoppingbasket/getOrderInfoByID/'+orderid;

	  //  alert(urllink);

	   

	

	$.ajax({

                 type: "POST",

                 url:urllink,

                 success: function(response) {

                 	$.facebox('<div style="width: 750px">' + response + '</div>');

                },

				error: function(){alert('Error 11');}

             }) 

	}

	function getwatchresults(page , rp, orderby){

		

		 lot = $('#hlot').val()

		 //alert(lot);

		 $('#searchresult').html('<div style="margin: 200px 0px 0px 300px; "><img src="'+base_url+'images/loading.gif"></div>');

		 var isPave,Solitaire, platinum,gold, minprice,maxprice,isMrkt, goldss;
		var designstr = '';
		 isPave 	=  $('#pavsechk').attr("checked") ? 'preowned' : 'notpreowned' ;

		 Solitaire 	=  $('#solitairechk').attr("checked") ? 'new' : 'notnew' ;

		 //Sidestone 	=   $('#sidestoneschk').attr("checked") ? 'sidestones' : 'notsidestone';

		 platinum 	=  $('#patinumchk').attr("checked") ? 'ss' : 'notss';

		 gold 		=  $('#goldchk').attr("checked") ? 'gold' : 'notgold'; 

		 goldss 	=  $('#goldsschk').attr("checked") ? 'gold_ss' : 'notgold_ss'; 

		 //whitegold 	=  $('#whitegoldchk').attr("checked") ? 'whitegold' : 'notwhitegold';

		 minprice 	= $('#pricerange1').val();

		 maxprice 	= $('#pricerange2').val();

		 //dshape 	= $('#ringshape').val();
		
		//alert(document.design.marktchk.length);
		for(var i=0; i < document.design.marktchk.length; i++){
			if(document.design.marktchk[i].checked) {
				if(document.design.marktchk[i].id) {
					designstr +=document.design.marktchk[i].id + "___";
				}
			}
		}
		//alert(designstr);
//return;
		 //isMrkt 	= $('#marktchk').attr("checked") ? 'rolex' : 'notrolex'; 

		 //isErd 		= $('#erdchk').attr("checked") ? 'erdcollection' : 'noterdcollection'; 

		 //isVatche 	= $('#vatchechk').attr("checked") ? 'vatchecollection' : 'notvatchecollection'; 

		 //isDaussi 	= $('#daussichk').attr("checked") ? 'daussicollection' : 'notdaussicollection'; 

		 //isAntique 	= $('#antiquechk').attr("checked") ? 'antiquecollection' : 'notantiquecollection'; 

		 

		 //isThreestone = $('#threestonechk').attr("checked") ? 'threestone' : 'notthreestone'; 

		 //isHalo 	  = $('#halochk').attr("checked") ? 'halo' : 'nothalo'; 

		 //isMatching   = $('#mathingchk').attr("checked") ? 'matching' : 'notmatching'; 

		 //isAnniversary = $('#anniversarychk').attr("checked") ? 'anniversary' : 'notanniversary'; 

		 		 

		 urllink = base_url+'watches/getwatchresults/'+isPave+'/'+Solitaire+'/'+platinum+'/'+gold+'/'+goldss+'/'+minprice+'/'+maxprice+ '/'+page+'/'+designstr+'/'+lot;
		//prompt('link', urllink);
	     $.ajax({

                 type: "POST",

                 url:urllink,

                 success: function(response) {

                   $('#searchresult').html(response);

                },

				error: function(){alert('Error 12');}

             }) 

	}

	function viewWatchDetails(stockid){

	urllink = base_url+'watches/watchdetails/'+stockid+'/addwatch';

	

	$.ajax({

                 type: "POST",

                 url:urllink,

                 success: function(response) {

    				$.facebox('<div >' + response + '</div>');

                },

				error: function(){alert('Error 13');}

             }) 

}

	function displayother(str)

	{
		//alert('a'+str);
		if(str=='-1') 
			document.getElementById('otherbrand').style.display = 'block';
		else
			document.getElementById('otherbrand').style.display = 'none';

	
	}
	
	function displaystate(str)

	{
		//if(thi)
		if(str.id == 'country') {
			if(str.value == 'US') {
				document.getElementById('citydiv').style.display = 'block';
				document.getElementById('citydivtxt').style.display = 'none';
			} else {
				document.getElementById('citydiv').style.display = 'none';
				document.getElementById('citydivtxt').style.display = 'block';
			}	
		} else {
			if(str.value == 'US') {
				document.getElementById('rcvcitydiv').style.display = 'block';
				document.getElementById('rcvcitydivtxt').style.display = 'none';
			} else {
				document.getElementById('rcvcitydiv').style.display = 'none';
				document.getElementById('rcvcitydivtxt').style.display = 'block';
			}	
		}
		shippingDiv();
	}

	function validate_shipping()

	{
		if(document.getElementById('shipaddress').checked) {
			var shipaddress = document.getElementById('shipaddress').value;
		} else {
			var shipaddress = document.getElementById('shipaddressn').value;
		}
		if(shipaddress == 'yes') {
			
			var city = document.getElementById('city').value;
			var country = document.getElementById('country').value;
			
			if(country == 'US') {
				var state = document.getElementById('state').value;
			} else {
				var state = document.getElementById('statetxt').value;			
			}
			
			var postcode = document.getElementById('postcode').value;
			var street_adddress = document.getElementById('address1').value;
		} else {

			var city = document.getElementById('rcvcity').value; 
			
			var country = document.getElementById('rcvcountry').value;

			if(country == 'US') {
				var state = document.getElementById('rcvstate').value;
			} else {
				var state = document.getElementById('rcvstatetxt').value;			
			}

			var postcode = document.getElementById('rcvpostcode').value;	
			
			var street_adddress = document.getElementById('rcvaddress1').value;

		}	

		
		
		if(country == '') {
			alert('Please select a country');
			return false;
		}
		if(city == '') {
			alert('Please enter city');
			return false;
		}
		if(state == '') {
			alert('Please select a state');
			return false;
		}
		if(postcode == '') {
			alert('Please enter Post Code');
			return false;
		}

		if(document.getElementById('INTERNATIONALPRIORITY').checked) {
			var service = document.getElementById('INTERNATIONALPRIORITY').value;
		} else {
			var service = document.getElementById('INTERNATIONALECONOMY').value;
		}
		
		$('#shipping_details').html('<div style="margin: 200px 0px 0px 300px; "><img src="'+base_url+'images/loading.gif"></div>');

		urllink = base_url+'shoppingbasket/estimateShipping/'+service+'/'+country+'/'+postcode+'/'+state+'/'+city+'/'+street_adddress;
		//urllink = base_url+'shoppingbasket/estimateCurlShipping/'+service+'/'+country+'/'+postcode+'/'+state+'/'+city+'/'+street_adddress;

		//prompt('a',urllink);
		$.ajax({

                 type: "POST",

                 url:urllink,

                 success: function(response) {
				var responseArr = response.split('~');
				// $('#shipping_details').html(response);
				$('#shipping_details').html(responseArr[0]);
				document.getElementById('shipping_amoount').value = responseArr[1];
				//$.facebox('<div >' + response + '</div>');

                },

				error: function(){alert('Error 14');}

             }) 
	}

    function shippingDiv() 
	{
		if(document.getElementById('shipaddress').checked) {
			var shipaddress = document.getElementById('shipaddress').value;
		} else {
			var shipaddress = document.getElementById('shipaddressn').value;
		}
		//alert(shipaddress);
		if(shipaddress =='yes') {
			var country = document.getElementById('country').value;
			if(country == 'US') {
				document.getElementById('international').style.display = 'none';
				document.getElementById('local').style.display = 'block';
				document.getElementById('fedex').checked = true;
				
			} else {
				document.getElementById('international').style.display = 'block';
				document.getElementById('local').style.display = 'none';
				document.getElementById('INTERNATIONALPRIORITY').checked = true;
			}
		} else {
			var country = document.getElementById('rcvcountry').value;
			if(country == 'US') {
				document.getElementById('international').style.display = 'none';
				document.getElementById('local').style.display = 'block';
				document.getElementById('fedex').checked = true;
				
			} else {
				document.getElementById('international').style.display = 'block';
				document.getElementById('local').style.display = 'none';
				document.getElementById('INTERNATIONALPRIORITY').checked = true;
			}
		}

		document.getElementById('shipping_amoount').value = 0.0;
		$('#shipping_details').html('');
	}

	function  validate_form() {
		if(document.getElementById('shipaddress').checked) {
			var country = document.getElementById('country').value;
		} else {
			var country = document.getElementById('rcvcountry').value;
		}
		
		if(country !='US') {
			if(document.getElementById('shipping_amoount').value=='0' || document.getElementById('shipping_amoount').value=='undefined') {
				alert('Please Estimate the shipping before proceeding');
				return false;
			}
		}

		return true;
	}

	function clearValues() {
		document.getElementById('shipping_amoount').value = 0;
		$('#shipping_details').html('');
	}