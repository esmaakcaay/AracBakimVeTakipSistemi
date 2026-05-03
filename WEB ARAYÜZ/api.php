<?php
require_once 'db.php';
header('Content-Type: application/json; charset=utf-8');

// İşlemi belirle (Oturum kontrolünden önce almalıyız)
$action = $_POST['action'] ?? $_GET['action'] ?? '';

// =========================================================
//  YARDIMCI FONKSİYONLAR (Dashboard stats için gerekli)
// =========================================================
function fetchAll($conn, $sql, $params = []) {
    $stmt = sqlsrv_query($conn, $sql, $params);
    if (!$stmt) return [];
    $rows = [];
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        foreach ($row as $k => $v) {
            if ($v instanceof DateTime) $row[$k] = $v->format('d.m.Y H:i');
        }
        $rows[] = $row;
    }
    return $rows;
}

function fetchOne($conn, $sql, $params = []) {
    $rows = fetchAll($conn, $sql, $params);
    return $rows[0] ?? null;
}

function exec_query($conn, $sql, $params = []) {
    $stmt = sqlsrv_query($conn, $sql, $params);
    return $stmt !== false;
}

// =========================================================
//  HERKESE AÇIK İŞLEMLER (Giriş yapmadan görülebilir)
// =========================================================
if ($action === 'dashboard_stats') {
    $stats = [
        'arac_sayisi'    => fetchOne($conn, "SELECT COUNT(*) AS c FROM Araclar")['c'],
        'musteri_sayisi' => fetchOne($conn, "SELECT COUNT(*) AS c FROM Musteriler")['c'],
        'bakim_sayisi'   => fetchOne($conn, "SELECT COUNT(*) AS c FROM BakimKayitlari")['c'],
        'gelir'          => fetchOne($conn, "SELECT ISNULL(SUM(ToplamUcret),0) AS c FROM BakimKayitlari")['c'],
        'son_bakimlar'   => fetchAll($conn, "
            SELECT TOP 5 b.KayitID, a.Plaka, a.Marka+' '+a.Model AS AracAdi,
                   h.HizmetAdi, b.ToplamUcret, b.GelisTarihi, p.AdSoyad AS Personel
            FROM BakimKayitlari b
            JOIN Araclar a ON b.AracID = a.AracID
            JOIN Hizmetler h ON b.HizmetID = h.HizmetID
            JOIN Personel p ON b.PersonelID = p.PersonelID
            ORDER BY b.GelisTarihi DESC
        "),
    ];
    echo json_encode($stats);
    exit;
}

// =========================================================
//  GÜVENLİK KONTROLÜ (Bundan sonraki işlemler giriş gerektirir)
// =========================================================
if (empty($_SESSION['rol'])) {
    echo json_encode(['error' => 'Oturum açılmamış.']);
    exit;
}

$rol    = $_SESSION['rol'];
$uid    = $_SESSION['user_id'];

// ----- MÜŞTERİLER -----
if ($action === 'musteriler_list') {
    $rows = fetchAll($conn, "
        SELECT m.*, (SELECT COUNT(*) FROM Araclar WHERE MusteriID=m.MusteriID) AS AracSayisi
        FROM Musteriler m ORDER BY m.MusteriID DESC
    ");
    echo json_encode($rows); exit;
}

// ... (Geri kalan tüm if ($action === ...) kodlarını buraya ekle)
if ($action === 'musteri_ekle' && $rol === 'admin') {
    $ok = exec_query($conn,
        "INSERT INTO Musteriler (Ad,Soyad,Telefon,Mail,Adres) VALUES (?,?,?,?,?)",
        [$_POST['ad'], $_POST['soyad'], $_POST['telefon'], $_POST['mail'], $_POST['adres']]
    );
    echo json_encode(['success' => $ok]); exit;
}

if ($action === 'musteri_sil' && $rol === 'admin') {
    $ok = exec_query($conn, "DELETE FROM Musteriler WHERE MusteriID=?", [(int)$_POST['id']]);
    echo json_encode(['success' => $ok]); exit;
}

// ----- ARAÇLAR -----
if ($action === 'araclar_list') {
    if ($rol === 'admin') {
        $rows = fetchAll($conn, "
            SELECT a.*, m.Ad+' '+m.Soyad AS MusteriAdi
            FROM Araclar a JOIN Musteriler m ON a.MusteriID=m.MusteriID
            ORDER BY a.AracID DESC
        ");
    } else {
        $rows = fetchAll($conn, "
            SELECT a.*, m.Ad+' '+m.Soyad AS MusteriAdi
            FROM Araclar a JOIN Musteriler m ON a.MusteriID=m.MusteriID
            WHERE a.MusteriID=?
            ORDER BY a.AracID DESC
        ", [$uid]);
    }
    echo json_encode($rows); exit;
}

if ($action === 'arac_ekle') {
    $musteriID = ($rol === 'admin') ? (int)$_POST['musteri_id'] : $uid;
    $ok = exec_query($conn,
        "INSERT INTO Araclar (Plaka,Marka,Model,Yil,SasiNo,MusteriID) VALUES (?,?,?,?,?,?)",
        [
            strtoupper(trim($_POST['plaka'])),
            trim($_POST['marka']),
            trim($_POST['model']),
            (int)$_POST['yil'],
            trim($_POST['sasi_no']),
            $musteriID
        ]
    );
    echo json_encode(['success' => $ok]); exit;
}

if ($action === 'arac_sil' && $rol === 'admin') {
    $ok = exec_query($conn, "DELETE FROM Araclar WHERE AracID=?", [(int)$_POST['id']]);
    echo json_encode(['success' => $ok]); exit;
}

// ----- HİZMETLER -----
if ($action === 'hizmetler_list') {
    echo json_encode(fetchAll($conn, "SELECT * FROM Hizmetler ORDER BY HizmetAdi")); exit;
}

if ($action === 'hizmet_ekle' && $rol === 'admin') {
    $ok = exec_query($conn,
        "INSERT INTO Hizmetler (HizmetAdi,StandartUcret) VALUES (?,?)",
        [trim($_POST['adi']), (float)$_POST['ucret']]
    );
    echo json_encode(['success' => $ok]); exit;
}

if ($action === 'hizmet_sil' && $rol === 'admin') {
    $ok = exec_query($conn, "DELETE FROM Hizmetler WHERE HizmetID=?", [(int)$_POST['id']]);
    echo json_encode(['success' => $ok]); exit;
}

// ----- PERSONEL -----
if ($action === 'personel_list') {
    echo json_encode(fetchAll($conn, "SELECT * FROM Personel ORDER BY AdSoyad")); exit;
}

if ($action === 'personel_ekle' && $rol === 'admin') {
    $ok = exec_query($conn,
        "INSERT INTO Personel (AdSoyad,UzmanlikAlani) VALUES (?,?)",
        [trim($_POST['adsoyad']), trim($_POST['uzmanlik'])]
    );
    echo json_encode(['success' => $ok]); exit;
}

if ($action === 'personel_sil' && $rol === 'admin') {
    $ok = exec_query($conn, "DELETE FROM Personel WHERE PersonelID=?", [(int)$_POST['id']]);
    echo json_encode(['success' => $ok]); exit;
}

// ----- BAKIM KAYITLARI -----
if ($action === 'bakimlar_list') {
    if ($rol === 'admin') {
        $rows = fetchAll($conn, "
            SELECT b.KayitID, a.Plaka, a.Marka+' '+a.Model AS AracAdi,
                   h.HizmetAdi, b.GelisTarihi, b.CikisTarihi,
                   b.YapilanDetaylar, b.ToplamUcret, p.AdSoyad AS Personel,
                   m.Ad+' '+m.Soyad AS MusteriAdi
            FROM BakimKayitlari b
            JOIN Araclar a ON b.AracID=a.AracID
            JOIN Hizmetler h ON b.HizmetID=h.HizmetID
            JOIN Personel p ON b.PersonelID=p.PersonelID
            JOIN Musteriler m ON a.MusteriID=m.MusteriID
            ORDER BY b.GelisTarihi DESC
        ");
    } else {
        $rows = fetchAll($conn, "
            SELECT b.KayitID, a.Plaka, a.Marka+' '+a.Model AS AracAdi,
                   h.HizmetAdi, b.GelisTarihi, b.CikisTarihi,
                   b.YapilanDetaylar, b.ToplamUcret, p.AdSoyad AS Personel
            FROM BakimKayitlari b
            JOIN Araclar a ON b.AracID=a.AracID
            JOIN Hizmetler h ON b.HizmetID=h.HizmetID
            JOIN Personel p ON b.PersonelID=p.PersonelID
            WHERE a.MusteriID=?
            ORDER BY b.GelisTarihi DESC
        ", [$uid]);
    }
    echo json_encode($rows); exit;
}

if ($action === 'bakim_ekle' && $rol === 'admin') {
    $ok = exec_query($conn,
        "INSERT INTO BakimKayitlari (AracID,HizmetID,GelisTarihi,CikisTarihi,YapilanDetaylar,ToplamUcret,PersonelID)
         VALUES (?,?,?,?,?,?,?)",
        [
            (int)$_POST['arac_id'],
            (int)$_POST['hizmet_id'],
            $_POST['gelis_tarihi'],
            $_POST['cikis_tarihi'],
            trim($_POST['detay']),
            (float)$_POST['ucret'],
            (int)$_POST['personel_id']
        ]
    );
    echo json_encode(['success' => $ok]); exit;
}

if ($action === 'bakim_sil' && $rol === 'admin') {
    $ok = exec_query($conn, "DELETE FROM BakimKayitlari WHERE KayitID=?", [(int)$_POST['id']]);
    echo json_encode(['success' => $ok]); exit;
}

echo json_encode(['error' => 'Bilinmeyen işlem: '.$action]);
?>
