//Setting Meta Tag
$('#ProsesSettingMetatag').submit(function(){
    $('#NotifikasiSimpanMetatag').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
    var form = $('#ProsesSettingMetatag')[0];
    var data = new FormData(form);
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/Metatag/ProsesSettingMetatag.php',
        data 	    :  data,
        cache       : false,
        processData : false,
        contentType : false,
        enctype     : 'multipart/form-data',
        success     : function(data){
            $('#NotifikasiSimpanMetatag').html(data);
            var NotifikasiSimpanMetatagBerhasil=$('#NotifikasiSimpanMetatagBerhasil').html();
            if(NotifikasiSimpanMetatagBerhasil=="Success"){
                window.location.href = "index.php?Page=Metatag";
            }
        }
    });
});