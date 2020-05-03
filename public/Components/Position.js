$( ".--add-duty" ).on( "click", function () {
	$( "#" + $( this ).data( "duty_list_id" ) ).append(
		'\
		<div class="g gtc-mca">\
			<p class="mr10 fw6 fs16">•</p>\
			<p class="fs16 c-dark-gray fw6 mb10">Test description</p>\
		</div>\
		'
	);
} );