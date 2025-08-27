<div class="pagetitle">
    <h1>
        <a href="">
            <i class="bi bi-grid"></i> Dashboard
        </a>
    </h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item active">Dashboard</li>
        </ol>
    </nav>
</div>
<section class="section dashboard">
    <div class="row">
        <div class="col-md-12" id="notifikasi_proses">
            <!-- Kejadian Kegagalan Menampilkan Data Akan Ditampilkan Disini -->
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="row">
                <div class="col-md-12">
                    <div class="card" id="card_jam_menarik">
                        <div class="card-body">
                            <div id="tanggal_menarik">Hari, 01 Januari 1900</div>
                            <div id="jam_menarik">00:00:00</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-xxl-3 col-md-12 col-12">
                            <div class="card info-card sales-card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center gap-3">
                                        <!-- Icon -->
                                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-arrow-90deg-down"></i>
                                        </div>
                                        <!-- Data Info -->
                                        <div>
                                            <div class="d-flex">
                                                <h5>Total Hit</h5>
                                            </div>
                                            <h4 class="text-muted" id="total_hit">-</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-3 col-md-12 col-12">
                            <div class="card info-card revenue-card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center gap-3">
                                        <!-- Icon -->
                                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-send"></i>
                                        </div>
                                        <!-- Data Info -->
                                        <div>
                                            <div class="d-flex">
                                                <h5>Blog Posting</h5>
                                            </div>
                                            <h4 class="text-muted" id="total_blog">-</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-3 col-md-12 col-12">
                            <div class="card info-card customers-card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center gap-3">
                                        <!-- Icon -->
                                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-collection"></i>
                                        </div>
                                        <!-- Data Info -->
                                        <div>
                                            <div class="d-flex">
                                                <h5>Laman Mandiri</h5>
                                            </div>
                                            <h4 class="text-muted" id="total_laman">-</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-3 col-md-12 col-12">
                            <div class="card info-card blue-card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center gap-3">
                                        <!-- Icon -->
                                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-newspaper"></i>
                                        </div>
                                        <!-- Data Info -->
                                        <div>
                                            <div class="d-flex">
                                                <h5>Newslatter</h5>
                                            </div>
                                            <h4 class="text-muted" id="total_newslater">-</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <!-- Reports -->
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-10">
                                    <b class="card-title">Grafik Pemirsa</b>
                                </div>
                                <div class="col-2 text-end">
                                    <button type="button" class="btn btn-md btn-secondary btn-floating"  data-bs-toggle="modal" data-bs-target="#ModalFilterGrafik">
                                        <i class="bi bi-filter"></i>
                                    </button>
                                </div>
                            </div>
                            
                        </div>
                        <div class="card-body">
                            <h5 class="card-title" id="NamaTitleData"></h5>
                            <div id="chart"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <b class="card-title"># Popular Post</b> 
                        </div>
                        <div class="card-body" id="ShowPopularPost">
                            <!-- Menampilkan Pemberitahuan Sistem -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
