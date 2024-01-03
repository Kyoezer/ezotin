/*Datepicker Start*/
jQuery(document).ready(function() {
    ComponentsPickers.init();
});
/*Datepicker End*/

/*Tinymcs Plugin Start*/
    tinymce.init({
		selector:'textarea',
		height: 200, 
		resize: false,
		plugins: "table",
		tools: "inserttable"
    });
/*Tinymce Plugin End*/

/*Flexslider js Start*/
$(function(){
	SyntaxHighlighter.all();
});

$(window).load(function(){
	$('.flexslider').flexslider({
		animation: "slide",
		animationLoop: true,
		itemWidth:250,
		itemMargin: 15
	});
});
/*Flexslider js End*/

/*Firehose webchat Start*/
var FHChat = {product_id: "9e0cbff06cbd"};
FHChat.properties={};FHChat.set=function(key,data){this.properties[key]=data};!function(){var a,b;return b=document.createElement("script"),a=document.getElementsByTagName("script")[0],b.src="https://chat-client-js.firehoseapp.com/chat-min.js",b.async=!0,a.parentNode.insertBefore(b,a)}();
/*Firehose webchat End*/

/*Scroll To Top Start*/
$(window).scroll(function() {
	if ($(this).scrollTop() > 200) {
		$('#toTop').fadeIn();
	} else {
		$('#toTop').fadeOut();
	}
});
/*Scroll To Top End*/
