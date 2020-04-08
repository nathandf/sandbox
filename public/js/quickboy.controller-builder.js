$( function () {
	var qbControllerBuilder = {
		i: 0,
		newMethod: function () {
			return "<div class=\"border-std pad-sml theme-tertiary-light push-t-sml\">\n<input type=\"checkbox\" name=\"actions[" + this.i + "][is-action]\" value=\"true\" class=\"checkbox checkbox-med push-r-sml\"><label class=\"label\">use 'Action' suffix</label><button type=\"button\" class=\"button-inline bg-red tc-white --delete-method floatright\"><i class=\"fas fa-trash\"></i></button><div class=\"clear\"></div>\n<p class=\"label push-t-sml\">Method name<span class=\"text-sml\"> (id string format)</span></p>\n<input type=\"text\" name=\"actions[" + this.i + "][method]\" value=\"\" class=\"inp inp-med-plus-plus --id-string\" required=\"required\">\n<p class=\"label push-t-sml\">Commands<span class=\"text-sml\"> (leave blank for null)</span></p>\n<hr>\n<div class=\"floatleft col-25\">\n<p class=\"label push-t-sml\">Model</p>\n<input type=\"text\" name=\"actions[" + this.i + "][model]\" value=\"\" class=\"inp inp-full\">\n</div>\n<div class=\"floatleft col-25\">\n<p class=\"label push-t-sml\">Model method</p>\n<input type=\"text\" name=\"actions[" + this.i + "][model-method]\" value=\"\" class=\"inp inp-full\">\n</div>\n<div class=\"floatleft col-25\">\n<p class=\"label push-t-sml\">View</p>\n<input type=\"text\" name=\"actions[" + this.i + "][view]\" value=\"\" class=\"inp inp-full\">\n</div>\n<div class=\"floatleft col-25\">\n<p class=\"label push-t-sml\">View method</p>\n<input type=\"text\" name=\"actions[" + this.i + "][view-method]\" value=\"\" class=\"inp inp-full\">\n</div>\n<div class=\"clear\"></div>\n</div>";
		},
		iterate: function () {
			this.i++;
		}
	};

	$( "#add-method" ).on( "click", function () {
		$( "#methods" ).append( qbControllerBuilder.newMethod() );
		qbControllerBuilder.iterate();
		$( ".--delete-method" ).on( "click", function () {
			$( this ).parent().remove();
		} );
		$( ".--id-string" ).on( "keyup", function() {
			$( this ).val( $( this ).val().toLowerCase().replace( /[^a-zA-Z0-9\- ]+/g, "").split( " " ).join( "-" ).replace( /[\-]+/g, "-") );
	    });
	} );
} );