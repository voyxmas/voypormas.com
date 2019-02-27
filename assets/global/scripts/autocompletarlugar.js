// autocomplete lugar
$(document).ready(function(){
    
    var input = document.getElementById('lugar');
    
    var ubicacion = new google.maps.places.Autocomplete(input);
    
    google.maps.event.addListener(ubicacion, 'place_changed', function () 
    {
    
        var input_val = input.value;
        var place = ubicacion.getPlace();
        var address_components = place.address_components;

        // saco todos los valores para tener el formulario limpioo cuando reemplazo
        $('[name=numero_casa],[name=calle],[name=ciudad],[name=departamento],[name=provincia],[name=pais]').val('');   

        for(i=0; i < address_components.length ;i++)
        {
            var elemento  = address_components[i].types[0];
            var valor     = address_components[i].long_name;
        
            // busco numero de casa
            if(elemento == "street_number") $('[name=numero_casa]').val(valor);
            // busco calle
            if(elemento == "route") $('[name=calle]').val(valor);
            // busco ciudad
            if(elemento == "locality") $('[name=ciudad]').val(valor);
            // busco departamento
            if(elemento == "administrative_area_level_2") $('[name=departamento]').val(valor);
            // busco provincia
            if(elemento == "administrative_area_level_1") $('[name=provincia]').val(valor);
            // busco pais
            if(elemento == "country") $('[name=pais]').val(valor);  
        }

        // veo si mando provincia,pais para evitar que busque la ciudad cuando se buscan solo dos elementos y la ciudad y la provincia tienen el mismo nombre
        let geo = input_val.split(', ');
        if(
            geo.length == 2 &
            $('[name=provincia]').val() == geo[0] &&
            $('[name=pais]').val() == geo[1] &&
            $('[name=provincia]').val() == $('[name=ciudad]').val()
        ){
            $('[name=numero_casa]').val(null);
            $('[name=calle]').val(null);
            $('[name=ciudad]').val(null);
            $('[name=departamento]').val(null);
        } 

        // set latitud
        if( typeof place.geometry.location.lat != undefined) $('[name=latitud]').val(place.geometry.location.lat); 
        // set longitud
        if( typeof place.geometry.location.lng != undefined) $('[name=longitud]').val(place.geometry.location.lng); 

    });

});