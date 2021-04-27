 <?php
	include "cnx.php";
	$request_method = $_SERVER["REQUEST_METHOD"];
	
	function getLesSecteurs()
	{
		global $cnx;
		$reponse = array();
		$sql = $cnx->prepare("select seccode, seclibelle from secteur order by seccode");
		$sql->execute();
		$lesSecteurs = $sql->fetchAll(PDO::FETCH_NUM);
		//var_dump($lesSecteurs);
		foreach($lesSecteurs as $row)
		{
			$unSecteur = [
				'Id' => $row[0],
				'Nom' => $row[1],
			];
			$reponse[] = $unSecteur;
		}
		echo json_encode($reponse);
	}
	function getLeSecteur($id)
	{
		global $cnx;
		$sql = $cnx->prepare("select seccode, seclibelle from secteur where seccode = ?");
		$sql->bindValue(1,$id);
		$sql->execute();
		$row = $sql->fetch(PDO::FETCH_NUM);
		$leSecteur = [
			'Id' => $row[0],
			'Nom' => $row[1],
		];
		echo json_encode($leSecteur);
	}

	function AddSecteur()
	{
		global $cnx;
		$json_str = file_get_contents('php://input');
		$nom = json_decode($json_str);
		$sql = $cnx->prepare("select max(seccode) from secteur");
		$sql->execute();
		$row = $sql->fetch(PDO::FETCH_NUM);
		$sql = $cnx->prepare("insert into secteur values(?,?)");
		$sql->bindValue(1,intval($row[0]) + 1);
		$sql->bindValue(2,$nom->Sec);
		$sql->execute();
	}

	function UpdateSecteur()
	{
		global $cnx;
		$json_str = file_get_contents('php://input');
		$leSecteur = json_decode($json_str);
		$sql = $cnx->prepare("update secteur set seclibelle = ? where seccode = ?");
		$sql->bindValue(1,$leSecteur->Nom);
	 	$sql->bindValue(2,$leSecteur->Id);
		$sql->execute();
	}
	switch($request_method)
	{
		case 'GET':
			if(!empty($_GET["id"]))
			{
				getLeSecteur($_GET["id"]);
			}
			else
			{
				getLesSecteurs();
			}
			break;
		case 'POST':
			AddSecteur();
			break;
		case 'PUT':
			UpdateSecteur();
			break;
		default:
			header("HTTP/1.0 405 Method Not Allowed");
			break;
	}
?>