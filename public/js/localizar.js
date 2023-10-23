
function localizar(elemento ){


    var content = document.getElementById('ubic');

    if (navigator.geolocation)
    {
        navigator.geolocation.getCurrentPosition(function(objPosition)
        {
            var lon = objPosition.coords.longitude;
            var lat = objPosition.coords.latitude;

            content.innerHTML = " " + lat + " , " + lon ;

        }, function(objPositionError)
        {
            switch (objPositionError.code)
            {
                case objPositionError.PERMISSION_DENIED:
                    content.innerHTML = "Permiso Negado.";
                    break;
                case objPositionError.POSITION_UNAVAILABLE:
                    content.innerHTML = "No se pudo localizar.";
                    break;
                case objPositionError.TIMEOUT:
                    content.innerHTML = "demaciado lento.";
                    break;
                default:
                    content.innerHTML = "Error desconocido.";
            }
        }, {
            maximumAge: 75000,
            timeout: 10000
        });
    }
    else
    {
        content.innerHTML = "No soportado.";
    }
    console.log(content.innerHTML);

    //   if (elemento instanceof String) {
        $(elemento).val(content.innerHTML);
  //  }
    content.innerHTML="";
}