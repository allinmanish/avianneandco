function getsidestoneresult(start){

	       var lot ;

		   lot = $('#hlot').val();

		   pendanttingsid = $('#hsettings').val();

		   addoption = $('#haddoption').val()

		   if(pendanttingsid == '') pendanttingsid = 'false';

		   $.facebox('<div style="width:200px; text-align:center;"><img src="'+base_url+'images/loading.gif">Please wait...</div>');

			$.ajax({

                 type: "POST",

                 url:base_url+'diamonds/getsidestoneresult/'+lot+'/'+start+'/'+pendanttingsid+'/'+addoption,

                 success: function(response) { 

				 	$.facebox.close();

                   $('#sidestoneresult').html(response);

                },

				error: function(){alert('Error ');}

             }) 

	}

	

	function viewearringstuddetails(stockno,price){

		//alert('addearringstud');

		//var addoption = 'addearringstud';

		urllink = base_url+'jewelry/earringstuddetailsajax/'+stockno+'/addearringstud/'+price;

	

		$.ajax({

					 type: "POST",

					 url:urllink,

					 success: function(response) {

						$.facebox('<div style="width: 600px">' + response + '</div>');

					},

					error: function(){alert('Error ');}

				 }) 

	

	} 

	

	function getpendantresult(){

			$.facebox('<div style="width:200px; text-align:center;"><img src="'+base_url+'images/loading.gif">Please wait...</div>');

			$.ajax({

                 type: "POST",

                 url:base_url+'jewelry/getpendantresult',

                 success: function(response) { 

				 	$.facebox.close();

                   $('#pendantresules').html(response);

                },

				error: function(){alert('Error ');}

             }) 

	}

	

	function viewpendantdetails(id, style){

		//alert(style);

		urllink = base_url+'jewelry/pendantdetailsajax/'+id+'/'+style;

	

		$.ajax({

					 type: "POST",

					 url:urllink,

					 success: function(response) {

						$.facebox('<div style="width: 530px">' + response + '</div>');

					},

					error: function(){alert('Error ');}

				 }) 

	}

	

	function getpendantsettingsresult(){

		$.facebox('<div style="width:200px; text-align:center;"><img src="'+base_url+'images/loading.gif">Please wait...</div>');

			$.ajax({

                 type: "POST",

                 url:base_url+'jewelry/getpendantsettingsresult',

                 success: function(response) { 

				 	$.facebox.close();

                   $('#pendantresules').html(response);

                },

				error: function(){alert('Error ');}

             }) 

	}

	

	

	

	function getpendantsettings(){		

		$('#pendantresules').html('<div style="margin: 20px 0px 0px 220px; "><img src="'+base_url+'images/loading.gif"></div>');	 

		 var isPlatinum, isYellowgold, isWhitegold, isSolitaire, isThreestone;

		 

		 isPlatinum 	=  $('#platinumchk').attr("checked") ? 'platinum' : 'notplatinum' ;

		 isYellowgold 	=  $('#yelloegoldchk').attr("checked") ? 'yellowgold' : 'notyellowgold' ;

		 isWhitegold 	=  $('#whitegoldchk').attr("checked") ? 'whitegold' : 'notwhitegold' ;

		 isSolitaire 	=  $('#solitirechk').attr("checked") ? 'solitaire' : 'notsolitaire' ;

		 isThreestone 	=  $('#threestonechk').attr("checked") ? 'threestone' : 'notthreestone' ;

		 

		 urllink = base_url+'jewelry/getpendantsettingsresult/'+isPlatinum+'/'+isYellowgold+'/'+isWhitegold+'/'+isSolitaire+'/'+isThreestone;

	     $.ajax({

                 type: "POST",

                 url:urllink,

                 success: function(response) {

                   $('#pendantresules').html(response);

                },

				error: function(){alert('Error ');}

             })

	}

	

	function selectsettingsid(id){

			$('#hid').val(id);

	}

	

	function addtocart(addoption,lot,sidestone1,sidestone2,settingsid,price,dsize){

		

			$.ajax({

                 type: "POST",

                 url:base_url+'cart/addtocart/'+addoption+'/'+lot+'/'+sidestone1+'/'+sidestone2+'/'+settingsid+'/'+price+'/'+dsize,

                 success: function(response) { 

				 	

                    $.facebox(response);

                },

				error: function(){alert('Error ');}

             }) 	

	}

	

	

	function updatecart(cartid,price){

		var qty;

		qty = $('#'+cartid).val(); 

			$.ajax({

                 type: "POST",

                 url:base_url+'cart/updatecart/'+cartid+'/'+price+'/'+qty,

                 success: function(response) { 

                    $.facebox(response);

					getcarthtml();

					//$('#frmshoppingcart').reload();

                },

				error: function(){alert('Error ');}

             }) 	

	}

	

	function deletcartitembyid(cartid){			

			$.ajax({

                 type: "POST",

                 url:base_url+'cart/deletecart/'+cartid,

                 success: function(response) { 

                    $.facebox(response);

					getcarthtml();

					//$('#frmshoppingcart').reload();

                },

				error: function(){alert('Error ');}

             })

	}

	

	 

	function getcarthtml(){

		$('#carthtml').html('<div style="margin: 20px 0px 0px 200px; "><img src="'+base_url+'images/loading.gif"></div>');

		$.ajax({

                 type: "POST",

                 url:base_url+'shoppingbasket/mybasket/true',

                 success: function(response) {

                   $('#carthtml').html(response);

                },

				error: function(){alert('Error ');}

             }) 

		

	}

	

	function clearreceiverdetails(){



		$('#rcvname').val('');

		//$('#rcvlname').val('');

		$('#rcvcompany').val('');

		$('#rcvaddress1').val('');

		$('#rcvaddress2').val('');

		$('#rcvcity').val('');

		$('#rcvcountry').val('');

		$('#rcvpostcode').val('');

		$('#rcvcountry').val('');

		$('#rcvphonecode').val('');

		$('#rcvphone').val('');

		$('#rcvextension').val('');

		

	}

	

	 function getweddingrings(section, metal, sortby){

		 //alert(metal);

		 $('#menringsresult').html('<div style="margin: 20px 0px 0px 220px; "><img src="'+base_url+'images/loading.gif"></div>'); 

		 

		 urllink = base_url+'jewelry/getweddingringsresult/'+section+'/'+metal+'/'+sortby;

	     $.ajax({

                 type: "POST",

                 url:urllink,

                 success: function(response) {

                   $('#menringsresult').html(response);

                },

				error: function(){alert('Error ');}

             })

		 

	 }

	

	

	

	function writebigimg(angle, imgsrc){

		 $('#flashanimation').html('<div><img src="'+imgsrc +'" alt="" style="margin: 5px;width:300px;height:250px;border:1px solid #0B81A5;"></div>');

		  $('#smallflash').show();

		  $('#flashanimation').show();

		  $('#bigflash').hide();	

		  $('#toggleflash').val('Close(X)');

		  $('#detailcontainer').css('width','400px'); 

		}

		

	function writebigimg2(angle, imgsrc){

	 $.facebox('<div><img src="'+ imgsrc +'" alt="" style="margin: 5px auto; border:1px solid #0B81A5;"></div>');

	  

	}	

	

	function showbigflash(){

		$.facebox('<div id="bigflash"></div>');

		writeswf(2);

		

	}

	

	function loadshoppingbasket(){

		urllink = base_url+'shoppingbasket/mybasket';

	     $.ajax({

                 type: "POST",

                 url:urllink,

                 success: function(response) {

                  

                },

				error: function(){alert('Error ');}

             })

	}

	

 	

	function setcollection(){

		sectionname = $('#section').val();

		collection = $('#collection'); 

		 

		switch(sectionname){

			case 'Earrings': 

			        optionstr = '<option value="DiamondStud">Diamond Stud Earrings</option> ' +

					            '<option value="BuildEarring">Build Your Own Earrings</option> ';

					collection.children().remove();

					collection.append(optionstr);

					

					$('#pearllength').show();

					$('#pearlmm').show();

					$('#semi').show();

					$('#sidediv').show();

				break;

			case 'EngagementRings':

					optionstr = '<option value="Milano Collection">Milano Collection</option> ';

					           

					collection.children().remove();

					collection.append(optionstr);

 

					$('#pearllength').hide();

					$('#pearlmm').hide();

					$('#semi').hide();

					$('#sidediv').hide();

					

					$('#pearl_length').val('');					

					$('#pearl_mm').val('');					

					$('#semi_mounted').val('');					

					$('#side').val('');

				break;

			case 'Jewelry':

					optionstr = '<option value="MensWeddingRing">Men\'s Wedding Rings</option> ' +

					            '<option value="WomensWeddingRing">Women\'s Wedding Rings</option> ' +

								'<option value="WomensAnniversaryRing">Women\'s Anniversary Rings</option>';

					collection.children().remove();

					collection.append(optionstr);

					

					$('#pearllength').show();

					$('#pearlmm').show();

					$('#semi').show();

					$('#sidediv').show();

				break;

			case 'Pendants':

					optionstr = '<option value="BuildPendant">Build your own Pendants</option> ';

					collection.children().remove();

					collection.append(optionstr);

					

					$('#pearllength').show();

					$('#pearlmm').show();

					$('#semi').show();

					$('#sidediv').show();

				break;

			 

			default:

				collection.children().remove();

				collection.append('<option value="">Select Collection</option>');

				$('#pearllength').show();

				$('#pearlmm').show();

				$('#semi').show();

				$('#sidediv').show();

				break;

		}

		

	}

	

	

	function setringtype(){

		collection = $('#collection').val();

		section = $('#section').val();

		type = $('#ringtype');

		

		

		if(section == 'Jewelry'){ 

		

			switch (collection){

				case 'WomensAnniversaryRing':

					optionstr = '<option vlaue="Channel">Channel</option>' +

								'<option vlaue="HalfWayArround">Half Way Arround</option>'

					$('#typediv').show();				

					type.children().remove();

					type.append(optionstr);

					

					$('#shapediv').show();

					$('#caratdiv').show();

					$('#totalcaratdiv').show();

					$('#diamondcountdiv').show();

					$('#diamondsizediv').show();

					$('#pearllength').show();

					$('#semi').show();

					$('#sidediv').show();

					

					$('#pearlmmlabel').html('Pearl mm:');

					break;

				case 'MensWeddingRing':

				case 'WomensWeddingRing':

					$('#shapediv').hide();

					$('#caratdiv').hide();

					$('#totalcaratdiv').hide();

					$('#diamondcountdiv').hide();

					$('#diamondsizediv').hide();

					$('#pearllength').hide();

					$('#semi').hide();

					$('#sidediv').hide();

					

					$('#pearlmmlabel').html('Width:');

					break;

				case '':

				default:

					type.val('');

					$('#typediv').hide();

					

					$('#shapediv').show();

					$('#caratdiv').show();

					$('#totalcaratdiv').show();

					$('#diamondcountdiv').show();

					$('#diamondsizediv').show();

					$('#pearllength').show();

					$('#semi').show();

					$('#sidediv').show();

					

					$('#pearlmmlabel').html('Pearl mm:');

					break;

			}

		}

		

	}

	

	function setDiamondToCompare(sval){

		urllink = base_url+'site/setValueInStringInSession/svar_lot/'+ sval;

		$.ajax({

					 type: "POST",

					 url:urllink,

					 success: function(response) {

					   

					},

					error: function(){alert('Error ');}

				 }) 

	}

	

	

	

	

	

	

	

	

	