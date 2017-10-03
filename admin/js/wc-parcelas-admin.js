jQuery(document).ready(function ($) {

    (function () {

        var $this,
            selector;

        $('.color-field').wpColorPicker();

        /**
         * From here and on we deal with the tabs module.
         */
        function s_s_display_section( _this ) {

            $( '.section', '.wc-parcelas form' ).removeClass( 'active' );

            selector = _this.attr( 'href' );

            $( selector ).addClass( 'active' );

        }

        $( 'body' ).on( 'click', 'a[href$="-tab"]', function(e) {

            e.preventDefault();

            $this = $( this );

            $( 'a', '.wc-parcelas .nav-tab-wrapper' ).removeClass( 'nav-tab-active' );

            $this.addClass( 'nav-tab-active' );

            s_s_display_section( $this );

        } );

    })();

});