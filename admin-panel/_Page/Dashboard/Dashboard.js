
// Fungsi Untuk Menampilkan Informasi Dashboard
function GetDashboardInformation() {
    $.ajax({
        type        : 'POST',
        url         : '_Page/Dashboard/ShowDashboard.php',
        dataType    : "JSON",
        success: function(response) {
            if (response.status === "success") {
                var total_hit        = parseInt(response.total_hit).toLocaleString("id-ID");
                var total_blog       = parseInt(response.total_blog).toLocaleString("id-ID");
                var total_laman      = parseInt(response.total_laman).toLocaleString("id-ID");
                var total_newslater  = parseInt(response.total_newslater).toLocaleString("id-ID");
            } else {
                var total_hit        = "Error";
                var total_blog       = "Error";
                var total_laman      = "Error";
                var total_newslater  = "Error";
            }
            //Tempelkan ke masing-masing element
            $('#total_hit').html(total_hit);
            $('#total_blog').html(total_blog);
            $('#total_laman').html(total_laman);
            $('#total_newslater').html(total_newslater);
        }
    });
}

//Fungsi Untuk Handle toggle Periode
function togglePeriode() {
    var periode = $("#periode").val();
    if (periode === "Tahun") {
        $("#FormTahun").closest(".row").show();
        $("#FormBulan").hide();
    } else if (periode === "Bulan") {
        $("#FormTahun").show();
        $("#FormBulan").show();
    }
}

// Fungsi Untuk Menampilkan Grafik
function GetGraphData() {
    var ProsesFilterGrafik = $('#ProsesFilterGrafik').serialize();
    $.ajax({
        type        : 'POST',
        url         : '_Page/Dashboard/RequestGraphData.php',
        data        : ProsesFilterGrafik,
        dataType    : "JSON",
        success: function(response) {
            if(response.status === "success") {
                var categories = [];
                var values = [];

                // Ambil periode dari form agar tahu tampil per Tahun / Bulan
                var periode = $('#periode').val(); 
                var judul = "";

                if(periode === "Tahun"){
                    var tahun = $('#tahun').val(); // ambil tahun dari form
                    judul = "Periode " + tahun;

                    // Loop data per bulan
                    $.each(response.data, function(i, item){
                        categories.push(item.bulan_label);
                        values.push(item.viewer);
                    });
                }else if(periode === "Bulan"){
                    var tahun = $('#tahun').val(); 
                    var bulan = $('#bulan').val(); 
                    // Array nama bulan (bisa Indonesia/Inggris sesuai kebutuhan)
                    var namaBulan = [
                        "", "Januari", "Februari", "Maret", "April", "Mei", "Juni",
                        "Juli", "Agustus", "September", "Oktober", "November", "Desember"
                    ];
                    var bulanInt = parseInt(bulan); // ubah ke integer agar index sesuai
                    if (bulanInt >= 1 && bulanInt <= 12) {
                        judul = namaBulan[bulanInt] + " " + tahun;
                    }
                    judul = "Periode " + judul;

                    // Loop data per hari
                    $.each(response.data, function(i, item){
                        categories.push(item.tanggal);
                        values.push(item.viewer);
                    });
                }

                // Konfigurasi chart
                var options = {
                    chart: {
                        type: 'line',
                        height: 350
                    },
                    series: [{
                        name: 'Viewer',
                        data: values
                    }],
                    xaxis: {
                        categories: categories
                    },
                    yaxis: {
                        labels: {
                            show: false // sembunyikan angka di garis Y
                        }
                    },
                    dataLabels: {
                        enabled: false // hilangkan label angka di titik grafik
                    },
                    colors: ['#4154f1'],
                    title: {
                        text: judul,
                        align: 'center'
                    },
                    stroke: {
                        curve: 'smooth'
                    }
                };

                // Render chart
                $("#chart").html(""); // reset isi div chart
                var chart = new ApexCharts(document.querySelector("#chart"), options);
                chart.render();
            } else {
                $("#chart").html("<div class='alert alert-danger'>"+response.message+"</div>");
            }
        },
        error: function(xhr, status, error){
            $("#chart").html("<div class='alert alert-danger'>Gagal mengambil data grafik</div>");
        }
    });
}

//Fungsi Untuk Menampilkan Popular Post
function ShowPopularPost() {
    $.ajax({
        type        : 'POST',
        url         : '_Page/Dashboard/ListPopularPost.php',
        success: function(response) {
            $('#ShowPopularPost').html(response);
        }
    });
}


// Fungsi untuk menampilkan jam digital
function tampilkanJam() {
    const waktu = new Date();
    let jam = waktu.getHours().toString().padStart(2, '0');
    let menit = waktu.getMinutes().toString().padStart(2, '0');
    let detik = waktu.getSeconds().toString().padStart(2, '0');

    $('#jam_menarik').text(`${jam}:${menit}:${detik}`);
}

// Fungsi untuk menampilkan tanggal
function tampilkanTanggal() {
    const waktu = new Date();
    const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    const tanggal = waktu.toLocaleDateString('id-ID', options);
    
    $('#tanggal_menarik').text(tanggal);
}

//Ketika Halaman Dashboard MunculPertama Kali
$(document).ready(function () {
    //Menampilkan Data Pertama Kali
    GetDashboardInformation();
    GetGraphData();
    ShowPopularPost();
    
    //Jam Menarik
    tampilkanTanggal(); // Tampilkan tanggal saat halaman dimuat
    tampilkanJam();     // Tampilkan jam pertama kali
    setInterval(tampilkanJam, 1000); // Perbarui jam setiap detik
    setInterval(tampilkanTanggal, 3600000); // Perbarui tanggal setiap jam

    // Jalankan saat pertama kali halaman dimuat
    togglePeriode();

    // Jalankan setiap kali select periode berubah
    $("#periode").change(function() {
        togglePeriode();
    });

    //Kondisi saat filter di submit
    $('#ProsesFilterGrafik').submit(function(){
       GetGraphData();

       //Tutup modal
       $('#ModalFilterGrafik').modal('hide');
    });
});