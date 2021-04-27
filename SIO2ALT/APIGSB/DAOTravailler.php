<?php
include "cnx.php";
$request_method = $_SERVER["REQUEST_METHOD"];

function getLesTravailleurs()
{
    global $cnx;
    $reponse = array();
    $sql = $cnx->prepare("SELECT regnom, visiteur.vismatricule, visnom FROM travailler, region, visiteur where travailler.regcode = region.regcode and visiteur.vismatricule = travailler.vismatricule ");
    $sql->execute();
    $lesTravailleurs = $sql->fetchAll(PDO::FETCH_NUM);
    //var_dump($lesTravailleurs);
    foreach($lesTravailleurs as $row)
    {
        $unTravailleur = [
            'Regnom' => $row[0],
            'Vismatricule' => $row[1],
            'Visnom' => $row[2],
        ];
        $reponse[] = $unTravailleur;
    }
    echo json_encode($reponse);
}
function getLeTravailleur($id)
{
    global $cnx;
    $sql = $cnx->prepare("SELECT regnom, vismatricule, visnom FROM travailler, region, visiteur  where vismatricule = ? and travailler.regcode = region.regcode and visiteur.vismatricule = travailler.vismatricule");
    $sql->bindValue(1,$id);
    $sql->execute();
    $row = $sql->fetch(PDO::FETCH_NUM);
    $leTravailleur = [
        'Regnom' => $row[0],
        'Vismatricule' => $row[1],
        'Visnom' => $row[2],
    ];
    echo json_encode($leTravailleur);
}
switch($request_method)
{
    case 'GET':
        if(!empty($_GET["Regnom"]))
        {
            getLeTravailleur($_GET["Regnom"]);
        }
        else
        {
            getLesTravailleurs();
        }
        break;
    default:
        header("HTTP/1.0 405 Method Not Allowed");
        break;
}
?>
