<?php
session_start();
if (empty($_SESSION['SESSION_USER']) && empty($_SESSION['SESSION_ID'])) {
    header('location:../../login/');
    exit;
} else {
    require_once '../../../sw-library/sw-config.php';
    require_once '../../login/login_session.php';
    include '../../../sw-library/sw-function.php';

    header('Content-Type: application/json');

    
    $aColumns = ['id_lokasi', 'nama_lokasi', 'deskripsi_lokasi', 'koordinat_gps'];
    $sIndexColumn = "id_lokasi";
    $sTable = "tbl_lokasi";

    $db = new mysqli(DB_HOST, DB_USER, DB_PASSWD, DB_NAME);

    
    $sLimit = "";
    if (isset($_GET['start']) && $_GET['length'] != '-1') {
        $sLimit = "LIMIT " . intval($_GET['start']) . ", " . intval($_GET['length']);
    }

    
    $sOrder = "ORDER BY id_lokasi DESC";


    $sWhere = "";
    if (!empty($_GET['search']['value'])) {
        $sWhere = "WHERE ";
        foreach ($aColumns as $col) {
            $sWhere .= "$col LIKE '%" . $db->real_escape_string($_GET['search']['value']) . "%' OR ";
        }
        $sWhere = rtrim($sWhere, " OR ");
    }

    
    $sQuery = "SELECT SQL_CALC_FOUND_ROWS " . implode(", ", $aColumns) . " FROM $sTable $sWhere $sOrder $sLimit";
    $rResult = $db->query($sQuery);

    
    $iFilteredTotal = $db->query("SELECT FOUND_ROWS()")->fetch_row()[0];

    
    $iTotal = $db->query("SELECT COUNT($sIndexColumn) FROM $sTable")->fetch_row()[0];

    $output = [
        "draw" => isset($_GET['draw']) ? intval($_GET['draw']) : 0,
        "recordsTotal" => $iTotal,
        "recordsFiltered" => $iFilteredTotal,
        "data" => []
    ];

    $no = isset($_GET['start']) ? intval($_GET['start']) : 0;

    while ($row = $rResult->fetch_assoc()) {
        $no++;
        $id = $row['id_lokasi'];
        $aksi = '
        <a href="?op=edit&id='.$row['id_lokasi'].'" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i></a>
        <button class="btn btn-xs btn-danger delete" data-id="'.$row['id_lokasi'].'"><i class="fa fa-trash"></i></button>
        ';

        $output['data'][] = [
            $no,
            htmlspecialchars($row['nama_lokasi']),
            htmlspecialchars($row['deskripsi_lokasi']),
            '<code>' . $row['koordinat_gps'] . '</code>',
            '<div class="text-center">' . $aksi . '</div>'
        ];
    }

    echo json_encode($output);
}
?>
