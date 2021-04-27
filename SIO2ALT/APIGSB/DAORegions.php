<?php
include "cnx.php";
$request_method = $_SERVER["REQUEST_METHOD"];

function getLesRegions()
{
    global $cnx;
    $reponse = array();
    $sql = $cnx->prepare("select regcode, regnom, seccode from region order by regcode");
    $sql->execute();
    $lesRegions = $sql->fetchAll(PDO::FETCH_NUM);
    //var_dump($lesRegions);
    foreach($lesRegions as $row)
    {
        $uneRegion = [
            'Id' => $row[0],
            'Nom' => $row[1],
            'Seccode' => $row[2],
        ];
        $reponse[] = $uneRegion;
    }
    echo json_encode($reponse);
}
function getLaRegion($id)
{
    global $cnx;
    $sql = $cnx->prepare("select regcode, regnom, seccode from region where regcode = ?");
    $sql->bindValue(1,$id);
    $sql->execute();
    $row = $sql->fetch(PDO::FETCH_NUM);
    $laRegion = [
        'Id' => $row[0],
        'Nom' => $row[1],
        'Seccode' => $row[2],
    ];
    echo json_encode($laRegion);
}

function AddRegion()
{
    global $cnx;
    $json_str = file_get_contents('php://input');
    $nom = json_decode($json_str);
    $seccode = json_decode($json_str);
    $sql = $cnx->prepare("select max(regcode) from region");
    $sql->execute();
    $row = $sql->fetch(PDO::FETCH_NUM);
    $sql = $cnx->prepare("insert into region values(?,?,?)");
    $sql->bindValue(1,intval($row[0]) + 1);
    $sql->bindValue(2,$nom->Reg);
    $sql->bindValue(3,$seccode->Regi);
    $sql->execute();
}

function UpdateRegion()
{
    global $cnx;
    $json_str = file_get_contents('php://input');
    $laRegion = json_decode($json_str);
    $sql = $cnx->prepare("update region set regnom = ? , seccode = ?  where regcode = ?");
    $sql->bindValue(1,$laRegion->Nom);
    $sql->bindValue(2,$laRegion->Seccode);
    $sql->bindValue(3,$laRegion->Id);
    $sql->execute();
}
switch($request_method)
{
    case 'GET':
        if(!empty($_GET["Id"]))
        {
            getLaRegion($_GET["Id"]);
        }
        else
        {
            getLesRegions();
        }
        break;
    case 'POST':
        AddRegion();
        break;
    case 'PUT':
        UpdateRegion();
        break;
    default:
        header("HTTP/1.0 405 Method Not Allowed");
        break;
}
?>