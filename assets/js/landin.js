$(function() {
    // Creamos una sola instancia de tooltip
    var tooltip = $("<div>").addClass("ui-tooltip ui-state-highlight").hide().appendTo("body");

    $.widget( "custom.combobox", {
        _create: function() {
            this.wrapper = $( "<span>" )
                .addClass( "custom-combobox" )
                .insertAfter( this.element );

            this.element.hide();
            this._createAutocomplete();
            this._createShowAllButton();
        },

        _createAutocomplete: function() {
            var selected = this.element.children( ":selected" ),
                value = selected.val() ? selected.text() : "";

            this.input = $( "<input>" )
                .appendTo( this.wrapper )
                .val( value )
                .addClass( "custom-combobox-input ui-widget ui-widget-content ui-state-default ui-corner-left" )
                .attr( "title", "" )
                .attr( "id", "botonEncontrar" )

                
                .autocomplete({
                    delay: 0,
                    minLength: 0,
                    source: this._source.bind( this )
                })
                .on("focus", function() {
                    // Mostramos el tooltip al enfocar el input
                    tooltip.show();
                })
                .on("blur", function() {
                    // Ocultamos el tooltip al perder el foco del input
                    tooltip.hide();
                });

            this._on( this.input, {
                autocompleteselect: function( event, ui ) {
                    ui.item.option.selected = true;
                    this._trigger( "select", event, {
                        item: ui.item.option
                    });
                },

                autocompletechange: "_removeIfInvalid"
            });
        },

        _createShowAllButton: function() {
            var input = this.input,
                wasOpen = false;

            // Utilizamos un span en lugar de un anchor para contener el ícono
            var $button = $("<span>")
                .attr("tabIndex", -1)
                .attr("id", "botonPaises")

                .attr("title", "Ver Ciudades")
                .appendTo(this.wrapper)
                .button({
                    icons: {
                        primary: "ui-icon-triangle-1-s" // Icono para el botón
                    },
                    text: false
                })
                .removeClass("ui-corner-all")
                .addClass("ui-button ui-widget custom-combobox-toggle ui-corner-right")
                .append(" ▼")
                .on("mousedown", function() {
                    wasOpen = input.autocomplete("widget").is(":visible");
                })
                .on("click", function() {
                    input.trigger("focus");

                    // Cerrar si ya está visible
                    if (wasOpen) {
                        return;
                    }

                    // Pasar cadena vacía como valor para buscar, mostrando todos los resultados
                    input.autocomplete("search", "");
                });

            // Agregamos un contenedor span para el ícono dentro del botón
            $button.find(".ui-button-icon-primary").wrap("<span class='ui-button-icon-container'></span>");
        },

        _source: function( request, response ) {
            var matcher = new RegExp( $.ui.autocomplete.escapeRegex(request.term), "i" );
            response( this.element.children( "option" ).map(function() {
                var text = $( this ).text();
                if ( this.value && ( !request.term || matcher.test(text) ) )
                    return {
                        label: text,
                        value: text,
                        option: this
                    };
            }) );
        },

        _removeIfInvalid: function( event, ui ) {
            // Selected an item, nothing to do
            if ( ui.item ) {
                return;
            }

            // Search for a match (case-insensitive)
            var value = this.input.val(),
                valueLowerCase = value.toLowerCase(),
                valid = false;
            this.element.children( "option" ).each(function() {
                if ( $( this ).text().toLowerCase() === valueLowerCase ) {
                    this.selected = valid = true;
                    return false;
                }
            });

            // Found a match, nothing to do
            if ( valid ) {
                return;
            }

            // Remove invalid value
            this.input
                .val( "" )
                .attr( "title", value + " didn't match any item" );

            this.element.val( "" );
            this._delay(function() {
                this.input.attr( "title", "" );
            }, 2500 );
            this.input.autocomplete( "instance" ).term = "";
        },

        _destroy: function() {
            this.wrapper.remove();
            this.element.show();
        }
    });

    $("#combobox").combobox();
    $("#toggle").on( "click", function() {
        $("#combobox").toggle();
    });
});