function jewelrydetails(e){
	 
	urllink = base_url+'admin/jewelrydetails/'+e;
	$.ajax({
                 type: "POST",
                 url:urllink,
                 success: function(response) {
                   $.facebox(response);
                },
				error: function(){alert('Error ');}
             }) 
}

 