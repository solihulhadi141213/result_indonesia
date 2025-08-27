<?php
    header('Content-Type: application/json');

    // Fungsi bantu kirim response
    function sendResponse($data, $statusCode = 200) {
        http_response_code($statusCode);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    // Koneksi Database
    require_once '../_Config/Connection.php';
    require_once '../_Config/Function.php';
    require_once '../_Config/log_visitor.php';

    // Buat Koneksi
    $Conn = (new Database())->getConnection();

    // Tangkap token dari header
    $headers = getallheaders();
    $token = $headers['x-token'] ?? $headers['X-Token'] ?? '';

    // Validasi Jika Token Kosong
    if (empty($token)) {
        sendResponse(['status' => 'error', 'message' => 'Token tidak ditemukan.'], 401);
    }

    // Validasi token
    $validasi_token = validasi_x_token($Conn, $token);
    if ($validasi_token !== "Valid") {
        sendResponse(['status' => 'error', 'message' => $validasi_token], 401);
    }

    // Hanya izinkan metode POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        sendResponse(['status' => 'error', 'message' => 'Metode request tidak diizinkan. Gunakan POST'], 405);
    }

    // Tangkap input JSON
    $input = json_decode(file_get_contents("php://input"), true);
    if (json_last_error() !== JSON_ERROR_NONE || $input === null) {
        sendResponse(['status' => 'error', 'message' => 'Format JSON tidak valid'], 400);
    }

    if (empty($input['periode'])) {
        sendResponse(['status' => 'error', 'message' => 'Kategori Periode Data Harus Diisi'], 400);
    }
    if (empty($input['value'])) {
        sendResponse(['status' => 'error', 'message' => 'Nilai Periode Data Harus Diisi'], 400);
    }

    $periode = $input['periode'];
    $value   = $input['value'];

    $data = [];

    // Pemetaan bulan & hari ke Indonesia
    $mapBulan = [
        '01'=>'Jan','02'=>'Feb','03'=>'Mar','04'=>'Apr','05'=>'Mei','06'=>'Juni',
        '07'=>'Juli','08'=>'Ags','09'=>'Sep','10'=>'Okt','11'=>'Nov','12'=>'Des'
    ];
    $mapHari = [
        'Sunday'=>'Minggu','Monday'=>'Senin','Tuesday'=>'Selasa',
        'Wednesday'=>'Rabu','Thursday'=>'Kamis','Friday'=>'Jumat','Saturday'=>'Sabtu'
    ];

    if ($periode === "Tahun") {
        // value = tahun
        $tahun = intval($value);

        // Query jumlah viewer per bulan
        $sql = "
            SELECT LPAD(MONTH(timestamp),2,'0') AS bulan_number, COUNT(*) AS viewer
            FROM visitor_logs
            WHERE YEAR(timestamp) = :tahun
            GROUP BY bulan_number
        ";
        $stmt = $Conn->prepare($sql);
        $stmt->bindParam(':tahun', $tahun, PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_KEY_PAIR); // bulan => viewer

        // Generate bulan 01-12
        foreach ($mapBulan as $num => $label) {
            $viewer = isset($rows[$num]) ? (int)$rows[$num] : 0;
            $data[] = [
                'bulan_number' => $num,
                'bulan_label'  => $label,
                'viewer'       => $viewer
            ];
        }

    } elseif ($periode === "Bulan") {
        // value = "MM-YYYY"
        if (!preg_match('/^(0[1-9]|1[0-2])-\d{4}$/', $value)) {
            sendResponse(['status'=>'error','message'=>'Format value untuk Bulan harus MM-YYYY'],400);
        }
        list($bulan, $tahun) = explode("-", $value);
        $bulan = intval($bulan);
        $tahun = intval($tahun);

        // Query jumlah viewer per hari
        $sql = "
            SELECT LPAD(DAY(timestamp),2,'0') AS tanggal, DAYNAME(timestamp) AS hari, COUNT(*) AS viewer
            FROM visitor_logs
            WHERE YEAR(timestamp) = :tahun AND MONTH(timestamp) = :bulan
            GROUP BY tanggal, hari
        ";
        $stmt = $Conn->prepare($sql);
        $stmt->bindParam(':tahun', $tahun, PDO::PARAM_INT);
        $stmt->bindParam(':bulan', $bulan, PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Ubah ke array tanggal => [hari,viewer]
        $hasil = [];
        foreach ($rows as $r) {
            $hasil[$r['tanggal']] = [
                'hari'=>$mapHari[$r['hari']] ?? $r['hari'],
                'viewer'=>(int)$r['viewer']
            ];
        }

        // Cari jumlah hari dalam bulan
        $jumlahHari = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);

        for ($i=1; $i <= $jumlahHari; $i++) {
            $tgl = str_pad($i,2,"0",STR_PAD_LEFT);
            $hariInggris = date("l", strtotime("$tahun-$bulan-$tgl"));
            $data[] = [
                'tanggal' => $tgl,
                'hari'    => $hasil[$tgl]['hari'] ?? $mapHari[$hariInggris],
                'viewer'  => $hasil[$tgl]['viewer'] ?? 0
            ];
        }

    } else {
        sendResponse(['status'=>'error','message'=>'Periode hanya boleh Tahun atau Bulan'],400);
    }

    sendResponse(['status'=>'success','data'=>$data]);

?>