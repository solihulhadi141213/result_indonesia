//Setting Pavicon
$('#ProsesUpdateFavicon').submit(function(){
    $('#NotifikasiUpdateFavicon').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
    var form = $('#ProsesUpdateFavicon')[0];
    var data = new FormData(form);
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/Favicon/ProsesUpdateFavicon.php',
        data 	    :  data,
        cache       : false,
        processData : false,
        contentType : false,
        enctype     : 'multipart/form-data',
        success     : function(data){
            $('#NotifikasiUpdateFavicon').html(data);
            var NotifikasiUpdateFaviconBerhasil=$('#NotifikasiUpdateFaviconBerhasil').html();
            if(NotifikasiUpdateFaviconBerhasil=="Success"){
                window.location.href = "index.php?Page=Favicon";
            }
        }
    });
});