$.each({
    dynamictabs: function(){
        this.tab_counter=1;
        this.tabs = this.jquery.tabs({
            tabTemplate: "<li><a href='#{href}'>#{label}</a> <a href='#' onclick='$(this).univ().closeTab();return false'><span class='ui-icon ui-icon-close dynamictabsclose'>Remove Tab</span></a></li>",
            cache: false,
            add: function( event, ui ) {
                $panel=$(ui.panel);

                // Start loading
                $panel.append('<div/>').children().atk4_load($.univ().urlhack);

                var index = $panel.parent().children().index( $panel )-1;

                $.univ().tabs.tabs('select',index);
            }
        }).addClass('dynamictab');

    },
    closeTab: function(){
        var index = $( "li", this.tabs ).index( this.jquery.parent() );
        this.tabs.tabs( "remove", index );
    },
    closeThisTab: function(){
        $panel = this.jquery.closest('ui-tabs-panel');
        var index = $panel.parent().children().index( $panel );
        this.tabs.tabs( "remove", index );
    },
    addTab: function(url,title){
        this.urlhack = url;
        this.tabs.tabs( "add", "#tabs-" + this.tab_counter, title );
        this.tab_counter++;
    }
},$.univ._import);
