//Fungsi Menampilkan Data
function filterAndLoadTable() {
    var ProsesFilter = $('#ProsesFilter').serialize();
    $.ajax({
        type: 'POST',
        url: '_Page/AksesFitur/TabelAksesFitur.php',
        data: ProsesFilter,
        success: function(data) {
            $('#MenampilkanTabelFitur').html(data);
        }
    });
}

//Fungsi Generate Kode
function generateRandomString(length) {
    var result = '';
    var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    var charactersLength = characters.length;
    for (var i = 0; i < length; i++) {
        result += characters.charAt(Math.floor(Math.random() * charactersLength));
    }
    return result;
}
//Menampilkan Data Pertama Kali
$(document).ready(function() {
    filterAndLoadTable();

    //Pagging
    $(document).on('click', '#next_button', function() {
        var page_now = parseInt($('#page').val(), 10); // Pastikan nilai diambil sebagai angka
        var next_page = page_now + 1;
        $('#page').val(next_page);
        filterAndLoadTable(0);
    });
    $(document).on('click', '#prev_button', function() {
        var page_now = parseInt($('#page').val(), 10); // Pastikan nilai diambil sebagai angka
        var next_page = page_now - 1;
        $('#page').val(next_page);
        filterAndLoadTable(0);
    });
});
//Filter Data
$('#ProsesFilter').submit(function(){
    $('#page').val("1");
    filterAndLoadTable();
    $('#ModalFilter').modal('hide');
});
//Ketika KeywordBy Diubah
$('#KeywordBy').change(function(){
    var KeywordBy = $('#KeywordBy').val();
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/AksesFitur/FormFilter.php',
        data        : {KeywordBy: KeywordBy},
        success     : function(data){
            $('#FormFilter').html(data);
        }
    });
});
//Generate Kode
$('#GenerateKode').click(function(){
    var randomString = generateRandomString(19);
    $('#kode').val('Loading...');
    $('#kode').val(randomString);
});
//Proses Tambah Fitur
$('#ProsesTambahFitur').submit(function(){
    $('#NotifikasiTambahAksesFitur').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
    var ProsesTambahFitur = $('#ProsesTambahFitur').serialize();
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/AksesFitur/ProsesTambahFitur.php',
        data 	    :  ProsesTambahFitur,
        enctype     : 'multipart/form-data',
        success     : function(data){
            $('#NotifikasiTambahAksesFitur').html(data);
            var NotifikasiTambahAksesFiturBerhasil=$('#NotifikasiTambahAksesFiturBerhasil').html();
            if(NotifikasiTambahAksesFiturBerhasil=="Success"){
                $('#NotifikasiTambahAksesFitur').html('Pastikan data yang anda input sudah benar');
                $('#page').val("1");
                $("#ProsesFilter")[0].reset();
                $("#ProsesTambahFitur")[0].reset();
                $('#ModalTambahFitur').modal('hide');
                Swal.fire(
                    'Success!',
                    'Tambahh Fitur Akses Berhasil!',
                    'success'
                )
                //Menampilkan Data
                filterAndLoadTable();
            }
        }
    });
});
//Ketika Modal Hapus Fitur Muncul
$('#ModalHapusFitur').on('show.bs.modal', function (e) {
    var GetData = $(e.relatedTarget).data('id');
    $('#FormHapusFitur').html("Loading...");
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/AksesFitur/FormHapusFitur.php',
        data        : {id_akses_fitur: GetData},
        success     : function(data){
            $('#FormHapusFitur').html(data);
            $('#NotifikasiHapusFitur').html('');
        }
    });
});
//Proses Hapus Fitur
$('#ProsesHapusFitur').submit(function(){
    $('#NotifikasiHapusFitur').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
    var ProsesHapusFitur = $('#ProsesHapusFitur').serialize();
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/AksesFitur/ProsesHapusFitur.php',
        data 	    :  ProsesHapusFitur,
        enctype     : 'multipart/form-data',
        success     : function(data){
            $('#NotifikasiHapusFitur').html(data);
            var NotifikasiHapusFiturBerhasil=$('#NotifikasiHapusFiturBerhasil').html();
            if(NotifikasiHapusFiturBerhasil=="Success"){
                $("#ProsesHapusFitur")[0].reset();
                $('#ModalHapusFitur').modal('hide');
                Swal.fire(
                    'Success!',
                    'Hapus Fitur Akses Berhasil!',
                    'success'
                )
                //Menampilkan Data
                filterAndLoadTable();
            }
        }
    });
});
//Ketika Modal Edit Fitur Muncul
$('#ModalEditFitur').on('show.bs.modal', function (e) {
    var GetData = $(e.relatedTarget).data('id');
    $('#FormEditFitur').html("Loading...");
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/AksesFitur/FormEditFitur.php',
        data        : {id_akses_fitur: GetData},
        success     : function(data){
            $('#FormEditFitur').html(data);
            $('#NotifikasiEditFitur').html('Pastikan data yang anda input sudah sesuai');
            $('#GenerateKodeEdit').click(function(){
                var randomString = generateRandomString(19);
                $('#kodeEdit').val('Loading...');
                $('#kodeEdit').val(randomString);
            });
        }
    });
});
//Proses Edit Fitur
$('#ProsesEditFitur').submit(function(){
    $('#NotifikasiEditFitur').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
    var ProsesEditFitur = $('#ProsesEditFitur').serialize();
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/AksesFitur/ProsesEditFitur.php',
        data 	    :  ProsesEditFitur,
        enctype     : 'multipart/form-data',
        success     : function(data){
            $('#NotifikasiEditFitur').html(data);
            var NotifikasiEditFiturBerhasil=$('#NotifikasiEditFiturBerhasil').html();
            if(NotifikasiEditFiturBerhasil=="Success"){
                $('#NotifikasiEditFitur').html('Pastikan data yang anda input sudah benar');
                $('#ModalEditFitur').modal('hide');
                Swal.fire(
                    'Success!',
                    'Edit Fitur Akses Berhasil!',
                    'success'
                )
                //Menampilkan Data
                filterAndLoadTable();
            }
        }
    });
});

//Modal Detail Fitur
$('#ModalDetailFitur').on('show.bs.modal', function (e) {
    var id_akses_fitur = $(e.relatedTarget).data('id');
    $('#FormDetailFitur').html("Loading...");
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/AksesFitur/FormDetailFitur.php',
        data        : {id_akses_fitur: id_akses_fitur},
        success     : function(data){
            $('#FormDetailFitur').html(data);
        }
    });
});