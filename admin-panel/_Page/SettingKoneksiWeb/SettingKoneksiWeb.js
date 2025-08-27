//Fungsi Menampilkan Setting Pertama Kali
function ShowSettingStatus() {
    $('#ShowSettingStatus').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');

    // Mulai pencatatan waktu mulai
    const startTime = Date.now();

    $.ajax({
        type: 'POST',
        url: '_Page/SettingKoneksiWeb/ShowSettingStatus.php',
        success: function(data) {
            const elapsed = Date.now() - startTime;
            const delay = Math.max(2000 - elapsed, 0); // Sisa waktu untuk mencapai 2 detik

            setTimeout(function() {
                $('#ShowSettingStatus').html(data);
            }, delay);
        }
    });
}
$(document).ready(function() {
    //Panggil Fungsi Pertama Kali
    ShowSettingStatus();

    //Panggil Fungsi Saat Reload
    $('#ReloadKoneksiWeb').click(function(){
       ShowSettingStatus();
    });

    //Ketika Modal Detail Entitias Akses
    $('#ModalSettingKoneksiWeb').on('show.bs.modal', function (e) {
        $('#FormSettingKoneksiWeb').html("Loading...");
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/SettingKoneksiWeb/FormSettingKoneksiWeb.php',
            success     : function(data){
                $('#FormSettingKoneksiWeb').html(data);
                //Bersihkan Notifikasi
                $('#NotifikasiSettingKoneksiWeb').html('');
            }
        });
    });

    //Proses Simpan Pengaturan
    $('#ProsesSettingKoneksiWeb').submit(function(){
        $('#NotifikasiSettingKoneksiWeb').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
        var ProsesSettingKoneksiWeb = $('#ProsesSettingKoneksiWeb').serialize();
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/SettingKoneksiWeb/ProsesSettingKoneksiWeb.php',
            data 	    :  ProsesSettingKoneksiWeb,
            enctype     : 'multipart/form-data',
            success     : function(data){
                $('#NotifikasiSettingKoneksiWeb').html(data);
                var NotifikasiSettingKoneksiWebBerhasil=$('#NotifikasiSettingKoneksiWebBerhasil').html();
                if(NotifikasiSettingKoneksiWebBerhasil=="Success"){
                    //Tutup Modal
                    $('#ModalSettingKoneksiWeb').modal('hide');

                    //Tampilkan Swal
                    Swal.fire(
                        'Success!',
                        'Pengaturan Berhasil Disimpan!',
                        'success'
                    )
                    //Menampilkan Setting
                    ShowSettingStatus();
                }
            }
        });
    });

});
