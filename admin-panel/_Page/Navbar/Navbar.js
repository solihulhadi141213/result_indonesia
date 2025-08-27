//Setting Meta Tag
$('#ProsesUpdateNavbar').submit(function(){
    $('#NotifikasiUpdateNavbar').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
    var form = $('#ProsesUpdateNavbar')[0];
    var data = new FormData(form);
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/Navbar/ProsesUpdateNavbar.php',
        data 	    :  data,
        cache       : false,
        processData : false,
        contentType : false,
        enctype     : 'multipart/form-data',
        success     : function(data){
            $('#NotifikasiUpdateNavbar').html(data);
            var NotifikasiUpdateNavbarBerhasil=$('#NotifikasiUpdateNavbarBerhasil').html();
            if(NotifikasiUpdateNavbarBerhasil=="Success"){
                window.location.href = "index.php?Page=Navbar";
            }
        }
    });
});