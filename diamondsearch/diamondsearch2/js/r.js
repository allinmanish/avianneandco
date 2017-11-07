$(document).ready(function(){
$.depthtable = {
	gdepthmin:57,
	gdepthmax:73,
	gtablemin:52,
	gtablemax:67,
	
	B: {depthmin:56.5,depthmax:67,tablemin:50,tablemax:67} ,
	PR: {depthmin:55,depthmax:86,tablemin:51,tablemax:89} ,
	E: {depthmin:54,depthmax:79,tablemin:51,tablemax:79} ,
	AS: {depthmin:54,depthmax:79,tablemin:51,tablemax:79} ,
	M: {depthmin:50,depthmax:74,tablemin:50,tablemax:71} ,
	O: {depthmin:50,depthmax:74,tablemin:50,tablemax:71} ,
	R: {depthmin:55,depthmax:86,tablemin:51,tablemax:70} ,
	P: {depthmin:50,depthmax:74,tablemin:50,tablemax:71} ,
	H: {depthmin:45,depthmax:65,tablemin:51,tablemax:70} ,
	C: {depthmin:54,depthmax:73,tablemin:49,tablemax:71} ,
	getData:function(prop){
		return eval("$.depthtable."+prop);
	},
	advsetter:function()
	{
		$('#depth').slider('option', 'min', $.depthtable.gdepthmin*100);
		$('#depth').slider('option', 'max', $.depthtable.gdepthmax*100);
		
		$('#tablerange').slider('option', 'min', $.depthtable.gtablemin*100);
		$('#tablerange').slider('option', 'max', $.depthtable.gtablemax*100);
		
		$('#depthmin').val($.depthtable.gdepthmin);
		
		$('#depthmax').val($.depthtable.gdepthmax);
		$('#depth').slider('option','values',[$.depthtable.gdepthmin*100,$.depthtable.gdepthmax*100]);


		$('#tablemin').val($.depthtable.gtablemin);
		
		$('#tablemax').val($.depthtable.gtablemax);
		$('#tablerange').slider('option','values',[$.depthtable.gtablemin*100,$.depthtable.gtablemax*100]);
			
	},
	calc:function()
	{
	
		$.depthtable.gdepthmin=57;
		$.depthtable.gdepthmax = 73;
		$.depthtable.gtablemin = 52;
		$.depthtable.gtablemax = 67;
		$(".searchdiamondsshape").find("img.selected").each(function(index) {
			xdata = $.depthtable.getData($(this).attr("id"));
			if ($.depthtable.gdepthmin >  xdata.depthmin)
				$.depthtable.gdepthmin = xdata.depthmin;
			
			if ($.depthtable.gdepthmax >  xdata.depthmax)
				$.depthtable.gdepthmax = xdata.depthmax;

			if ($.depthtable.gtablemin >  xdata.tablemin)
				$.depthtable.gtablemin = xdata.tablemin;
			
			if ($.depthtable.gtablemax >  xdata.tablemax)
				$.depthtable.gtablemax = xdata.tablemax;			
		});
	}
}

/*	$("body").append("<input type=\"button\" id=\"qwerty\" value=\"CheckIt!\">");
	$("#qwerty").bind("click",function(){
		window.setTimeout("$.depthtable.calc()",200);
		window.setTimeout("$.depthtable.advsetter()",600);
	});*/
	
	$(".searchdiamondsshape").bind("click",function(){
		window.setTimeout("$.depthtable.calc()",200);
		window.setTimeout("$.depthtable.advsetter()",600);
	});
	
	$("#tablex").bind("click",function(){
		window.setTimeout("$.depthtable.calc()",200);
		window.setTimeout("$.depthtable.advsetter()",600);
	});
	
	$("#depthx").bind("click",function(){
		window.setTimeout("$.depthtable.calc()",200);
		window.setTimeout("$.depthtable.advsetter()",600);
	});
	

});


/*b: {depthmin:56.5,depthmax:67,tablemin:50,tablemax:67} table -67 for round 
pr o Depth 55-86 and table 51-89 for princess 
e o Depth 54-79 and table 51-79 for Emerald 
as o Depth 54-79 and table 51-79 for Asscher 
m o Depth 50-74 and table 50-71 for Marquise 
o o Depth 50-74 and table 50-71 for Oval 
r o Depth 55-86 and table 51-89 for Radiant 
p o Depth 50-74 and table 50-71 for pear 
h o Depth 45-65 and table 51-70 for heart 
c o Depth 54-73 and table 49-71 for cushion 


???o Depth 54-80 and table 53-83 for Pearl

*/