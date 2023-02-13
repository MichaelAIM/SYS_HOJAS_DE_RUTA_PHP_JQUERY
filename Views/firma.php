<?php
// var_dump($_GET['id']);
// $_GET['id'] = 'Yw==';
if ($_GET['id'] != "") {
    require_once("../Assets/encript.php");
    require_once("../Models/anexo.php");
    $an = new Anexo();
    $id = decrypt($_GET['id']);
    // echo "id = ".$id;
    $firmas = $an->buscar_firmas_id($id);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Infomación de firmas</title>
    <meta http-equiv='cache-control' content='no-cache'>
    <meta http-equiv='expires' content='0'>
    <meta http-equiv='pragma' content='no-cache'>
    <!-- Bootstrap CSS -->
    <link href="../Assets/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../Assets/bootstrap-icons.css">
    <link rel="stylesheet" href="../Assets/css/sweetalert2.css">
    <link rel="stylesheet" href="../Assets/css/app2.css">
</head>
<body>

    <aside class="profile-card">
        <header>
        <!-- here’s the avatar -->
            <a target="_blank" href="#"><img src="../Assets/img/firmar.png" class="hoverZoomLink"></a>
            <!-- and role or location -->
            <h2>Documento firmado por:</h2>
            <!-- the username -->
            <h1><?=$firmas[0]['per_nombre'];?></h1>
        </header>

    <!-- bit of a bio; who are you? -->
        <div class="profile-bio">
            <p>FECHA: <strong><?=$firmas[0]['created_at'];?></strong></p>
            <p>DIRECCIÓN IP: <strong><?=$firmas[0]['ip'];?></strong></p>
        </div>
    </aside>

    <script src="../Assets/js/jquery-3.6.0.min.js"></script>
    <script src="../Assets/js/bootstrap.bundle.min.js"></script>
    <script src="../Assets/js/sweetalert2.min.js"></script>
</body>
</html>
<?php 
}else{
    echo "ERROR 404";
}
?>
