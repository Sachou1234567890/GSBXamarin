<?php
include "cnx.php";
$request_method = $_SERVER["REQUEST_METHOD"];

function getLesVisiteurs()
{
    global $cnx;
    $reponse = array();
    $sql = $cnx->prepare("SELECT vismatricule, visnom, visprenom, seccode, labcode  FROM visiteur");
    $sql->execute();
    $lesVisiteurs = $sql->fetchAll(PDO::FETCH_NUM);
    //var_dump($lesVisiteurs);
    foreach($lesVisiteurs as $row)
    {
        $unVisiteur = [
            'id' => $row[0],
            'nom' => $row[1],
            'prenom' => $row[2],
            'seccode' => $row[3],
            'labcode' => $row[4],
        ];
        $reponse[] = $unVisiteur;
    }
    echo json_encode($reponse);
}
function getLeVisiteur($id)
{
    global $cnx;
    $sql = $cnx->prepare("SELECT vismatricule, visnom, visprenom, seccode, labcode  FROM visiteur where vismatricule= ?");
    $sql->bindValue(1,$id);
    $sql->execute();
    $row = $sql->fetch(PDO::FETCH_NUM);
    $leVisiteur = [
        'id' => $row[0],
        'nom' => $row[1],
        'prenom' => $row[2],
        'seccode' => $row[3],
        'labcode' => $row[4],
    ];
    echo json_encode($leVisiteur);
}

function AddVisiteur()
{
    global $cnx;
    $json_str = file_get_contents('php://input');
    $nom = json_decode($json_str);
    $prenom = json_decode($json_str);
    $seccode = json_decode($json_str);
    $labcode = json_decode($json_str);
    $sql = $cnx->prepare("select max(vismatricule) from visiteur");
    $sql->execute();
    $row = $sql->fetch(PDO::FETCH_NUM);
    $sql = $cnx->prepare("insert into visiteur values(?,?,?,?,?,?,?,?,?)");
    $sql->bindValue(1,intval($row[0]) + 1);
    $sql->bindValue(2,$nom->Vis);
    $sql->bindValue(3,$prenom->Visi);
    $sql->bindValue(4,"Paris");
    $sql->bindValue(5,"75002");
    $sql->bindValue(6,"Paris");
    $sql->bindValue(7,"2019-12-12");
    $sql->bindValue(8,$seccode->Visa);
    $sql->bindValue(9,$labcode->Viso);
    $sql->execute();
}

function UpdateVisiteur()
{
    global $cnx;
    $json_str = file_get_contents('php://input');
    $leVisiteur = json_decode($json_str);
    $sql = $cnx->prepare("update visiteur set visnom = ? , visprenom= ?, seccode= ?, labcode= ? where vismatricule = ?");
    $sql->bindValue(1,$leVisiteur->Nom);
    $sql->bindValue(2,$leVisiteur->Prenom);
    $sql->bindValue(3,$leVisiteur->Seccode);
    $sql->bindValue(4,$leVisiteur->Labcode);
    $sql->bindValue(5,$leVisiteur->Id);
    $sql->execute();
}
switch($request_method)
{
    case 'GET':
        if(!empty($_GET["Id"]))
        {
            getLeVisiteur($_GET["Id"]);
        }
        else
        {
            getLesVisiteurs();
        }
        break;
    case 'POST':
        AddVisiteur();
        break;
    case 'PUT':
        UpdateVisiteur();
        break;
    default:
        header("HTTP/1.0 405 Method Not Allowed");
        break;
}
?>