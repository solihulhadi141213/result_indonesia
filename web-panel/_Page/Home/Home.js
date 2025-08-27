
$(document).ready(function() {
    // Tangkap event saat tombol "Lihat Detail" diklik
    $('#ModalDetailDokter').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Tombol yang diklik
        var id_dokter = button.data('id'); // Ambil data-id dari tombol

        // Tampilkan indikator loading
        $('#ShowDetailDokter').html('<div class="text-center p-4"><div class="spinner-border text-success" role="status"></div><p class="mt-2">Memuat data jadwal dokter...</p></div>');

        // Kirim permintaan AJAX untuk ambil detail jadwal dokter
        $.ajax({
            url     : '_Page/Home/DetailJadwalDokter.php',
            type    : 'POST',
            data    : { id_dokter: id_dokter },
            success: function(response) {
                $('#ShowDetailDokter').html(response);
            },
            error: function(xhr, status, error) {
                $('#ShowDetailDokter').html('<div class="alert alert-danger">Gagal memuat data jadwal dokter. Silakan coba lagi.</div>');
                console.error('Error:', error);
            }
        });
    });

    //Baca selengkapnya Blog
    $('.btn-elegant').on('click', function() {
        var targetUrl = $(this).attr('target-link');
        if (targetUrl) {
            window.location.href = targetUrl;
        }
    });

    $("#ProsesBerlangganan").on("submit", function(e){
        e.preventDefault();

        var formData = $(this).serialize();
        var tombol = $(".tombol_berlangganan");
        var tombolAwal = tombol.html();

        // Ubah tombol jadi spinner
        tombol.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Memproses...');
        tombol.prop("disabled", true);

        $.ajax({
            type: "POST",
            url: "_Page/Home/ProsesBerlangganan.php",
            data: formData,
            success: function(response){
                tombol.html(tombolAwal);
                tombol.prop("disabled", false);

                if(response.trim() === "success"){
                    $("#NotifikasiBerlangganan").html(
                        '<div class="alert alert-success alert-dismissible fade show" role="alert">' +
                            'Terima kasih, Anda berhasil berlangganan newsletter.' +
                            '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
                        '</div>'
                    );

                    // Reset form setelah sukses
                    $("#ProsesBerlangganan")[0].reset();
                } else {
                    $("#NotifikasiBerlangganan").html(
                        '<div class="alert alert-danger alert-dismissible fade show" role="alert">' +
                            response +
                            '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
                        '</div>'
                    );
                }
            },
            error: function(){
                tombol.html(tombolAwal);
                tombol.prop("disabled", false);

                $("#NotifikasiBerlangganan").html(
                    '<div class="alert alert-danger alert-dismissible fade show" role="alert">' +
                        'Terjadi kesalahan. Tidak dapat menghubungi server. Coba lagi nanti.' +
                        '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
                    '</div>'
                );
            }
        });
    });

});