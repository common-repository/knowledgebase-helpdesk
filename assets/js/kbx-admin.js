jQuery(document).ready(function($){
    //Drag and Drop ordering.
    $('table.posts #the-list, table.pages #the-list').sortable({
        'items': 'tr',
        'axis': 'y',
        'helper': kbxHelper,
        'update' : function(e, ui) {
            $.post( ajaxurl, {
                action: 'kbx-update-menu-order',
                order: $('#the-list').sortable('serialize'),
            });
        }
    });
    $('table.tags #the-list').sortable({
        'items': 'tr',
        'axis': 'y',
        'helper': kbxHelper,
        'update' : function(e, ui) {
            $.post( ajaxurl, {
                action: 'kbx-update-menu-order-tags',
                order: $('#the-list').sortable('serialize'),
            });
        }
    });
    var kbxHelper = function(e, ui) {
        ui.children().children().each(function() {
            $(this).width($(this).width());
        });
        return ui;
    };

    //Adding Articles Import Export Button.
    if ( 'undefined' === typeof kbx_admin ) {
        return;
    }
    $('.kbx-aricles-column-select').select2( );
    // Add buttons to product screen.
    if ( 'undefined' === typeof kbx_admin ) {
        return;
    }
   // $('.kbx-aricles-column-select').select2( );
    // Add buttons to product screen.
    var $articles_screen = $('.edit-php.post-type-kbx_knowledgebase'),
        $title_action   = $articles_screen.find( '.page-title-action:first' ),
        $blankslate     = $articles_screen.find( '.kbx-qc-BlankState' );

    if ( 0 === $blankslate.length ) {
        $title_action.after( kbx_admin.strings.export_articles );
        $title_action.after( kbx_admin.strings.import_articles  );
        //$title_action.after( '<a href="' + kbx_admin.urls.export_articles + '" class="page-title-action">' + kbx_admin.strings.export_articles + '</a>' );
        //$title_action.after( '<a href="' + kbx_admin.urls.import_articles + '" class="page-title-action">' + kbx_admin.strings.import_articles + '</a>' );
    } else {
        $title_action.hide();
    }
    
    jQuery(document).on('click','.danger kbx-more-query-remove',function (e) {
        if (confirm('Are you sure you want to remove this?')) {
            jQuery(this).parent().remove();
        }
    });

});
