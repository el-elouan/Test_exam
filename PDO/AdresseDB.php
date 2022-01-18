<?php
require_once "Constantes.php";
require_once "metier/Adresse.php";

/**
 * 
*Classe permettant d'acceder en bdd pour inserer supprimer modifier
 * selectionner l'objet Adresse
 * @author pascal Lamy
 *
 */
class AdresseDB 
{
	private $db; // Instance de PDO
	
	public function __construct($db)
	{
		$this->db=$db;;
	}
	/**
	 * 
	 * fonction d'Insertion de l'objet Adresse en base de donnee
	 * @param Adresse $p
	 */
	public function ajout(Adresse $a)
	{
	//TODO insertion de l'adresse en bdd
		$query = $this->db->prepare("INSERT INTO adresse(numero,rue,codepostal,ville) values(:numero,:rue,:codepostal,:ville)");

		$query->bindValue(":numero",$a->getNumero());
		$query->bindValue(":rue",$a->getRue());
		$query->bindValue(":codepostal",$a->getCodepostal());
		$query->bindValue(":ville",$a->getVille());
		$query->execute();
	}
    /**
     * 
     * fonction de Suppression de l'objet Adresse
     * @param Adresse $a
     */
	public function suppression(Adresse $a){
		 //TODO suppression de l'adresse en bdd
		$query = $this->db->prepare("DELETE FROM adresse WHERE numero=:numero AND rue=:rue AND codepostal=:codepostal AND ville=:ville");

		$query->bindValue(":numero",$a->getNumero());
		$query->bindValue(":rue",$a->getRue());
		$query->bindValue(":codepostal",$a->getCodepostal());
		$query->bindValue(":ville",$a->getVille());
		$query->execute();
	}
/** 
	 * Fonction de modification d'une adresse
	 * @param Adresse $a
	 * @throws Exception
	 */
public function update(Adresse $a)
	{
		try {
		//TODO mise a jour de l'adresse en bdd
			$query = $this->db->prepare("UPDATE adresse SET numero=:numero, rue=:rue, codepostal=:codepostal, ville=:ville WHERE id=:id");

			$query->bindValue(":numero",$a->getNumero());
			$query->bindValue(":rue",$a->getRue());
			$query->bindValue(":codepostal",$a->getCodepostal());
			$query->bindValue(":ville",$a->getVille());
			$query->bindValue(":id",$a->getId());
			$query->execute();
			}
		catch(Exception $e){
			//TODO definir constante de l'exception
			throw new Exception(); 
			
		}
	}
	/**
	 * 
	 * Fonction qui retourne toutes les adresses
	 * @throws Exception
	 */
	public function selectAll(){
		
		//TODO selection de l'adresse
		$query = $this->db->prepare("SELECT numero,rue,codepostal,ville FROM adresse");
		$query->execute();
		$result = $query->fetchAll(PDO::FETCH_CLASS);

	//TODO definir constante de l'exception
		if(empty($result)){
			throw new Exception();
		}

		return $result;
	}	
		/**
	 * 
	 * Fonction qui retourne l'adresse en fonction de son id
	 * @throws Exception
	 * @param $id
	 */
	public function selectAdresse($id){
		//TODO definir constante de l'exception
		if(empty($id)){
			throw new Exception();
		}

		$query = $this->db->prepare("SELECT * FROM adresse WHERE id=:id");
		$query->bindValue(":id",$id);
		$query->execute();
		$result = $query->fetchAll(PDO::FETCH_CLASS);

		if(empty($result)){
			throw new Exception();
		}

		return $this->convertPdoAdr($result);

			//TODO selection de l'adresse en fonction de son id
		
	}	
	/**
	* Fonction qui convertie un PDO Adresse en objet Adresse
	 * @param $pdoAdres
	 * @throws Exception
	 */
	public function convertPdoAdr($pdoAdresse)
	{
		//TODO conversion du PDO ADRESSE en objet adresse
		if (empty($pdoAdresse)) {
			throw new Exception(Constantes::EXCEPTION_DB_CONVERT_ADR);
		}
		$obj = (object)$pdoAdresse;
		$adr = new Adresse(
			$obj->numero, 
			$obj->rue, 
			$obj->codepostal, 
			$obj->ville, 
			$obj->id_pers
		);
		$adr->setId($obj->id);
		return $adr;
	}
}