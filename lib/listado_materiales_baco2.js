
$(document).ready(function(){
    $('#tabla_listado_materiales').dataTable( {
        "sPaginationType": "full_numbers" ,
        "aaSorting": [[ 6, "desc" ]]
    } );
})
