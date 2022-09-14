<?php

namespace app\components;

use Yii;
use yii\base\component;
use yii\web\Controller;
use yii\base\InvalidConfigException;

class devisClass extends Component
{
  public $connect = Null;

  public function __construct()
  {
    $this->connect = \Yii::$app->db;
  }

  public function addDevis($number_bill, $idclient, $dateDevis, $montantGlobal, $montantRemise, $prixTotal, $idEntreprise, $idEntite, $idAuteur)
  {

    $stmt = $this->connect->createCommand('INSERT INTO slim_devis (codeDevis,idClient,dateDevis,montantGlobal,montantRemise,prixTotal,idEntreprise,idEntite,idActeur)
          VALUES (:codeDevis,:idclient,:dateDevis,:montantGlobal,:montantRemise,:prixTotal,:idEntreprise,:idEntite,:idActeur)')
      ->bindValues([':codeDevis' => $number_bill, ':idclient' => $idclient, ':dateDevis' => $dateDevis, ':montantGlobal' => $montantGlobal, ':montantRemise' => $montantRemise, ':prixTotal' => $prixTotal, ':idEntreprise' => $idEntreprise, ':idEntite' => $idEntite, ':idActeur' => $idAuteur])
      ->execute();

    $stmt = 92;
    return $stmt;
  }

  public function addDetailDevis($idProd, $idDevis, $quatite, $prixUnitaire, $prixTotal, $idEntreprise, $idEntite)
  {
    $rslt = null;
    $insertstmt = $this->connect->createCommand('INSERT INTO slim_detail_devis(idProduit,idDevis,quantite,prixUnitaire,prixTotal,idEntreprise,idEntite)
          VALUES (:idProduit,:idDevis,:quantite,:prixUnitaire,:prixTotal,:idEntreprise,:idEntite)')
      ->bindValues([':idProduit' => $idProd, ':idDevis' => $idDevis, ':quantite' => $quatite, ':prixUnitaire' => $prixUnitaire, ':prixTotal' => $prixTotal, ':idEntreprise' => $idEntreprise, ':idEntite' => $idEntite])
      ->execute();

    return $rslt;
  }

  public function enrgBillDevis($number, $idEntreprise, $idEntite)
  {

    $rslt = $this->connect->createCommand('INSERT INTO slim_bill_devis(bill_number,idEntreprise,idEntite)
          VALUES (:bill_number,:idEntreprise,:idEntite)')
      ->bindValues([':bill_number' => $number, ':idEntreprise' => $idEntreprise, ':idEntite' => $idEntite])
      ->execute();
    if ($rslt) return 1;
  }

  public function getDevisId($codeDevis)
  {
    $data = Null;
    if (isset($codeDevis)) {
      $rslt = $this->connect->createCommand('SELECT id FROM slim_devis WHERE codeDevis=:codeDevis')
        ->bindValue(':codeDevis', $codeDevis)
        ->queryOne();
      if (sizeof($rslt) > 0) {
        $data = $rslt['id'];
      }
    }
    return $data;
  }

  public function getAllDevis($idEntreprise, $idEntite)
  {
    $data = Null;
    $rslt = $this->connect->createCommand('SELECT * FROM slim_devis WHERE idEntreprise=:idEntreprise AND idEntite=:idEntite')
      ->bindValues([':idEntreprise' => $idEntreprise, ':idEntite' => $idEntite])
      ->queryAll();
    if (sizeof($rslt) > 0) {
      $data = $rslt;
    }
    return $data;
  }
}
