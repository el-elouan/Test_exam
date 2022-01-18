<?php

use PHPUnit\Framework\TestCase;

require_once "Constantes.php";
include_once "PDO/connectionPDO.php";
require_once "metier/Adresse.php";
require_once "PDO/AdresseDB.php";

class AdresseDBTest extends TestCase {

    /**
     * @var AdresseDB
     */
    protected $adresse;
    protected $pdodb;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    
      /**
       * 
* @backupGlobals disabled
* @backupStaticAttributes disabled
* @coversNothing
*/ 

    protected function setUp():void {
        //parametre de connexion à la bae de donnée
     $strConnection = Constantes::TYPE.':host='.Constantes::HOST.';dbname='.Constantes::BASE; 
    $arrExtraParam= array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
    $this->pdodb = new PDO($strConnection, Constantes::USER, Constantes::PASSWORD, $arrExtraParam); //Ligne 3; Instancie la connexion
    $this->pdodb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   
    }

    /**
     *@coversNothing
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() : void{
        
    }

    /**
     * @covers AdresseDB::ajout
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/     
    public function testAjout() {
       try{ 
   $this->adresse = new AdresseDB($this->pdodb);
   
   $a = new Adresse(18,15,"Rue de la soif",50000, "Merville", 5);
//insertion en bdd
$this->adresse->ajout($a);

$adr=$this->adresse->selectAdresse($this->pdodb->lastInsertId());
$this->assertEquals($a->getNumero(), $adr->getNumero());
$this->assertEquals($a->getRue(), $adr->getRue());
$this->assertEquals($a->getCodepostal(), $adr->getCodepostal());
$this->assertEquals($a->getVille(), $adr->getVille());
$this->assertEquals($a->getId(), $adr->getId());


    }
    catch  (Exception $e) {
    echo 'Exception recue : ',  $e->getMessage(), "\n";
}

    }  

  /**
   * @covers AdresseDB::suppression
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/ 
    public function testSuppression() {
        try{
  $this->adresse = new AdresseDB($this->pdodb);

  $adr=$this->adresse->selectAdresse(1);
$this->adresse->suppression($adr);
  $adr2=$this->adresse->selectAdresse(1);
if($adr2!=null){
      $this->markTestIncomplete(
                "La suppression de l'enreg adresse a echoué"
        );
}
    }  catch (Exception $e){
        //verification exception
        $exception="RECORD PERSONNE not present in DATABASE";
        $this->assertEquals($exception,$e->getMessage());

    }
        
    }

    /**
     * @covers AdresseDB::selectionAdresse
     */
      /**
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/ 
    public function testSelectionAdresse() {
     $this->adresse = new AdresseDB($this->pdodb);
     $a = new Adresse(18,15, "Rue de la soif",50000, "Merville", 5);
$this->adresse->ajout($a);

$adr=$this->adresse->selectAdresse($this->pdodb->lastInsertId());
$this->assertEquals($a->getNumero(), $adr->getNumero());
$this->assertEquals($a->getRue(), $adr->getRue());
$this->assertEquals($a->getCodepostal(), $adr->getCodepostal());
$this->assertEquals($a->getVille(), $adr->getVille());
$this->assertEquals($a->getId(), $adr->getId());


    }

    /**
     * @covers AdresseDB::selectAll
     *
     */
     /**
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/ 
    public function testSelectAll() {
         $this->adresse = new AdresseDB($this->pdodb);
         $result = $this->adresse->selectAll();
         if (count($result)>0) {
            $etat = true;
        } else {
            $etat = false;
        }
        $this->assertTrue($etat);        
    }

    /*
     * @covers AdresseDB::convertPdoPers
     * @backupGlobals disabled
* @backupStaticAttributes disabled
*/
/*
    public function testConvertPdoPers() {
     $tab["id"]="34";
     $tab["nom"]="Dupont";
     $tab["prenom"]="Jacques";
     $tab["email"]="dupont@free.fr";
     $tab["telephone"]="0645342312";
     $tab["datenaissance"]="2002-12-12";
     $tab["login"]="jdupont";
     $tab["pwd"]="4755edd32703675c6a021322f9ca0433";
     $this->adresse = new AdresseDB($this->pdodb);
     $pers= $this->adresse->convertPdoPers($tab);
     $this->assertEquals($tab["nom"],$pers->getNom());
$this->assertEquals($tab["prenom"],$pers->getPrenom());
$this->assertEquals( $tab["datenaissance"],$pers->getDatenaissance()->format('Y-m-d'));
$this->assertEquals($tab["telephone"],$pers->getTelephone());
$this->assertEquals(  $tab["email"],$pers->getEmail());
$this->assertEquals(  $tab["login"],$pers->getLogin());
$this->assertEquals(  $tab["pwd"],$pers->getPwd());
     
    } */

    /**
     * @covers AdresseDB::update
       * @backupGlobals disabled
* @backupStaticAttributes disabled
*/
    public function testUpdate() {
        
      $this->adresse = new AdresseDB($this->pdodb);
     $a = new Adresse(18,15, "Rue de la soif",50000, "Merville", 10);
$this->adresse->ajout($a);

$this->assertEquals($a->getNumero(), $adr->getNumero());
$this->assertEquals($a->getRue(), $adr->getRue());
$this->assertEquals($a->getCodepostal(), $adr->getCodepostal());
$this->assertEquals($a->getVille(), $adr->getVille());
$this->assertEquals($a->getId(), $adr->getId());
//instanciation de l'objet pour mise ajour

 $a = new Adresse(30,15, "Soif de la rue",60000, "Merville SUR Mer", 10);
//update pers 
$lastId = $this->pdodb->lastInsertId();
$a->setId($lastId);
$this->adresse->update($a);  
$adr=$this->adresse->selectAdresse($a->getId());
$this->assertEquals($a->getNumero(), $adr->getNumero());
$this->assertEquals($a->getRue(), $adr->getRue());
$this->assertEquals($a->getCodepostal(), $adr->getCodepostal());
$this->assertEquals($a->getVille(), $adr->getVille());
$this->assertEquals($a->getId(), $adr->getId());      

    }

}
