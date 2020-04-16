$( function() {

    var Response;

    function asyncFormSubmit( form, callback ) {
        $.ajax( {
            type : form.attr( "method" ),
            url : form.attr( "action" ),
            data : form.serialize(),
            success : function( response ) {
                Response = JSON.parse( response );
            },
            error : function() {
                alert( "Something went wrong." );
            }
        } );
    }

    $( ".--confirm" ).on( "click", function( event ) {
        var default_message = "Are you sure you want to continue?"
        var confirmation_message = default_message;

        data_confirm_message = $( this ).data( "confirm_message" );
        
        if ( data_confirm_message !== undefined ) {
            confirmation_message = data_confirm_message;
        }

        confirmation = confirm( confirmation_message );

        if ( confirmation === false ) {
            event.preventDefault();
        }
    } );

    $( ".--toggle-input-type" ).on( "click", function () {
        var password_input = document.getElementById( "password" );

        if ( password_input.type === "password" ) {
            password_input.type = "text";

            return;
        }

        password_input.type = "password";

        return;
    } );

    $( ".--c-hide" ).on( "click", function () {
        $( this ).hide();
    } );

    $( ".--c-confirm" ).on( "click", function( event ) {
        confirmation = confirm( "Are you sure you want to continue? This action is permanant." );
        if ( confirmation === false ) {
            event.preventDefault();
        }
    } );

    $( "input:file" ).change(
        function() {
            if ( $( this ).val() ) {
                $( ".file-upload-button" ).show();
                $( ".file-upload-container" ).show();
                $( ".file-upload-field-container" ).show();
            }
        }
    );
 
    // Trigger modals with based on the buttons id
    $( ".--modal-trigger" ).on( "click", function () {
        if ( this.id != "" ) {
            $( "#" + this.id + "-modal" ).show( 0, function () {
                $( "#" + this.id + " > div.--modal-content" ).effect( "slide" );
            } );

            return;
        }

        var modal = $( this ).data( "modal" );
        $( "#" + modal + "-modal" ).show( 0, function () {
            $( "#" + modal + "-modal > div.--modal-content" ).effect( "slide" );
        } );

        return;
    } );

    $( ".--modal-close" ).on( "click", function () {
        var modal = $( this ).data( "modal" );
        $( "#" + modal + "-modal" ).hide();
    } );

    $( ".--modal-overlay" ).on( "click", function ( e ) {
        //Do nothing if modal overlay was not directly clicked
        if( e.target !== e.currentTarget ) return;
        $( this ).hide();
    } );

    $( ".row-link" ).on( "click", function () {
        window.location = $( this ).data( "href" );
    } );

    $( ".con-message-success" ).delay( 5000 ).fadeOut( 1000 );

    // Phone number formatting
    $( "input[type='tel'][name='national_number']" ).on( "keyup", function () {
        $( this ).val( $( this ).val().replace( /[^0-9]/g, "" ) );
    } );

    $( ".--format-e164-usa" ).text( function( i, text ) {
        return text.replace( /(\d{1,4})(\d{3})(\d{3})(\d{4})/, "+$1 ($2) $3-$4" );
    } );

} );
