<?php
include "cnx.php";
$request_method = $_SERVER["REQUEST_METHOD"];

function getLesstatsregions()
{
    global $cnx;
    $reponse = array();
    $sql = $cnx->prepare("select Count(region.seccode),regnom from region,secteur where secteur.seccode = region.seccode GROUP BY seclibelle ORDER BY COUNT(region.seccode)DESC LIMIT 1");
    $sql->execute();
    $Lesstatsregions = $sql->fetchAll(PDO::FETCH_NUM);
    //var_dump($lesstatsregions);
    foreach($Lesstatsregions as $row)
    {
        $unestatregion= [
            'nombre' => $row[0],
            'nom' => $row[1],
        ];
        $reponse[] = $unestatregion;
    }
    echo json_encode($reponse);
}
function getunestatregion($id)
{
    global $cnx;
    $sql = $cnx->prepare("select Count(region.seccode),seclibelle from region,secteur where secteur.seccode = region.seccode and region.seccode = ? GROUP BY seclibelle ORDER BY COUNT(region.seccode)DESC LIMIT 1");
    $sql->bindValue(1,$id);
    $sql->execute();
    $row = $sql->fetch(PDO::FETCH_NUM);
    $unestatregion = [
        'nombre' => $row[0],
        'nom' => $row[1],
    ];
    echo json_encode($unestatregion);
}
switch($request_method)
{
    case 'GET':
        if(!empty($_GET["nombre"]))
        {
            getunestatregion($_GET["nombre"]);
        }
        else
        {
            getLesstatsregions();
        }
        break;
    default:
        header("HTTP/1.0 405 Method Not Allowed");
        break;
}
?>