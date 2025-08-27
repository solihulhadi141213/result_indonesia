//Proses Edit
$('#ProsesUpdate').submit(function(){
    $('#NotifikasiUpdate').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
    var ProsesUpdate = $('#ProsesUpdate').serialize();
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/GoogleMap/ProsesUpdate.php',
        data 	    :  ProsesUpdate,
        enctype     : 'multipart/form-data',
        success     : function(data){
            $('#NotifikasiUpdate').html(data);
            var NotifikasiUpdateBerhasil=$('#NotifikasiUpdateBerhasil').html();
            if(NotifikasiUpdateBerhasil=="Success"){
                window.location.href = "index.php?Page=GoogleMap";
            }
        }
    });
});