function couplemodifyresult(var1,val1,var2,val2)
{
	urllink = base_url+'site/setsession/'+var1+'/'+ val1;
	$.post(urllink,function(data){
		urllink = base_url+'site/setsession/'+var2+'/'+ val2;
		$.post(urllink,function(data){
			searchdiamonds();
		});
	});
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
				error: function(){alert('Error ');}
             }) 
}

function setsession(svar , sval){
	urllink = base_url+'site/setsession/'+svar+'/'+ sval;
	
	$.ajax({
                 type: "POST",
                 url:urllink,
                 success: function(response) {
                 //  alert(response);
                },
				error: function(){alert('Error ');}
             }) 
}

function searchdiamonds(){
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
				error: function(){alert('Error ');}
             }) 

	
}

function viewChart(url)
{
	$.facebox('<div style="width: 500px"><img src="'+ url +'" width="500" /></div>');
		
}

function viewDiamondDetails(id ,addoption,settingsid){
	urllink = base_url+'diamonds/diamonddetailsajax/'+id+'/'+addoption+'/'+settingsid;
	
	if(addoption == 'toearring'){		
		urllink = base_url+'jewelry/earringdiamondsajax/'+id+'/'+settingsid+'/'+addoption;		
	}
	
	$.ajax({
                 type: "POST",
                 url:urllink,
                 success: function(response) {
                 	$.facebox('<div style="width: 520px">' + response + '</div>');
                },
				error: function(){alert('Error ');}
             }) 
	
}

function viewSidestoneDetails(sidestoneid,addoption,centerlot,pendentsettingsid){ 
	
	urllink = base_url+'diamonds/sidestonedetailsajax/'+sidestoneid+'/'+addoption+'/'+centerlot+'/'+pendentsettingsid;
	
	$.ajax({
                 type: "POST",
                 url:urllink,
                 success: function(response) {
                 	$.facebox('<div style="width: 530px">' + response + '</div>');
                },
				error: function(){alert('Error ');}
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
				error: function(){alert('Error ');}
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
				error: function(){alert('Error ');}
             }) 
}

function changeslider(sobj, v , i){
	 $('#'+sobj).slider( "moveTo", v, i)
}




function checkMinMaxPrice(e,e2, minvalue, maxvalue, minormax){
	id=e.id;
	if(minormax == 'min'){
		if(parseFloat($('#'+id).val()) < minvalue || parseFloat($('#'+id).val()) > parseFloat($('#'+e2).val())) {
			$('#'+id).val(minvalue);
			setsession('searchminprice' , minvalue);
		}
		else{
			setsession('searchminprice' , e.value);
		}
	}
	if(minormax == 'max'){
		if(parseFloat($('#'+id).val()) > maxvalue || parseFloat($('#'+id).val()) < parseFloat($('#'+e2).val())) {
			$('#'+id).val(maxvalue);
			setsession('searchmaxprice' , maxvalue);
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
		 var isPave,Solitaire,Sidestone,platinum,gold,whitegold,minprice,maxprice,isMrkt,isErd,isVatche,isDaussi,isAntique,isThreestone,isHalo,isMatching;
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
		 		 
		 urllink = base_url+'engagement/getringresults/'+isPave+'/'+Solitaire+'/'+Sidestone+'/'+platinum+'/'+gold+'/'+whitegold+'/'+minprice+'/'+maxprice+ '/' + dshape+'/'+page+'/'+isMrkt+'/'+isErd+'/'+isVatche+'/'+isDaussi+'/'+isAntique+'/'+isThreestone+'/'+isHalo+'/'+isMatching+'/'+lot;
	     $.ajax({
                 type: "POST",
                 url:urllink,
                 success: function(response) {
                   $('#searchresult').html(response);
                },
				error: function(){alert('Error ');}
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
				error: function(){alert('Error ');}
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
				error: function(){alert('Error ');}
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