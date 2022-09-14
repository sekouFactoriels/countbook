<?php

namespace app\components;

use Yii;
use yii\base\component;
use yii\web\Controller;
use yii\base\InvalidConfigException;

class inventaireClass extends Component
{
  public $connect = Null;

  public function __construct()
  {
    $this->connect = \Yii::$app->db;
  }

  /** methode : Generer un nouveau code de produit en fonction de l'espace de gestion **/
  public function get_espace_g_product_code($id_entreprise=''){
    
    $last_rec = $prefixe = $newCode = $genCode = Null;

    $last_rec = $this->connect->createCommand('select productCode from slim_product where idEntreprise=:idEntreprise order by productCode desc')
                              ->bindValue(':idEntreprise', $id_entreprise)
                              ->queryOne();

      //Générer le nouveaux code                         
    if(isset($last_rec['productCode']) && sizeof($last_rec['productCode'])>0)
    {
      $last_rec = $last_rec['productCode'];
    }

    if(isset($last_rec) && ($last_rec != Null)){
      $lastCode = substr($last_rec,2,6);
      $newCode = $lastCode + 1;

      if(strlen($newCode)==1){
        $genCode= "00000".$newCode;
        }elseif (strlen($newCode)==2){
           $genCode= "0000".$newCode;
        }elseif (strlen($newCode)==3){
            $genCode= "000".$newCode;
        }elseif (strlen($newCode)==4){
            $genCode= "00".$newCode;
        }elseif (strlen($newCode)==5){
            $genCode= "0".$newCode;
        }else{
            $genCode= $newCode;
        }
    }else{
      $genCode = '00001';
    }
    return $genCode;
  }


  /** Methode : Générer un nouveau code de produit **/
  public function get_sys_product_code() {
    $last_rec = $prefixe = $newCode = $genCode = Null;

    $last_rec = $this->connect->createCommand('select productCode from slim_product order by datetimeMiseJour desc')
                              ->queryOne();

    if(isset($last_rec['productCode']) && sizeof($last_rec['productCode'])>0)
    {
      $last_rec = $last_rec['productCode'];
    }
    if(isset($last_rec) && ($last_rec != Null)){
      $lastCode = substr($last_rec,8,6);
      $newCode = $lastCode + 1;

      if(strlen($newCode)==1){
        $genCode= "00000".$newCode;
        }elseif (strlen($newCode)==2){
           $genCode= "0000".$newCode;
        }elseif (strlen($newCode)==3){
            $genCode= "000".$newCode;
        }elseif (strlen($newCode)==4){
            $genCode= "00".$newCode;
        }elseif (strlen($newCode)==5){
            $genCode="0".$newCode;
        }else{
            $genCode= $newCode;
        }
    }else{
      $genCode = '00001';
    }
    return $genCode;
  } 


  /** Methode : Charger articles mouvementes en fonction du numero de la facture **/
  public function get_mvt_article_par_facture_num($bill_number = '')
  {
    $rslt = $this->connect->createCommand('select * from slim_stockentrepotmap where bill_bill_number=:bill_bill_number')
      ->bindValue(':bill_bill_number', $bill_number)
      ->queryAll();
    if ($rslt) return $rslt;
    return;
  }

  /** Methode : Mettre a jour le stock reaprovisionné **/
  public function update_in_entrepot_stocktopedup($product_in_array = Null, $idactor = 0)
  {
    $rslt = Null;
    if (isset($product_in_array) && sizeof($product_in_array) > 0) {
      foreach ($product_in_array as $data) {
        $qte = intval($data[4]) + intval($data[5]);
        $this->connect->createCommand('UPDATE slim_stockentrepot SET qteDispo=:qteDispo , idActionneur=:idActionneur WHERE idProduct=:idProduct')
          ->bindValues([':idProduct' => intval($data[0]), ':qteDispo' => intval($qte), ':idActionneur' => $idactor])
          ->execute();
      }
    }
    return true;
  }


  /** Methode : Enregistrer les produits  **/
  public function enrg_as_map_stocktopedup($data = Null)
  {
    $rslt = Null;
    if (isset($data) && is_array($data)) {
      $rslt = $this->connect->createCommand()->batchInsert('slim_stockentrepotmap', ['idProduit', 'idMaptype', 'bill_bill_number', 'mapDte', 'qteMaped', 'idEntreprise', 'idEntite', 'idActionneur'], $data)
        ->execute();
    }
    if ($rslt) return $rslt;
    return;
  }

  /** Methode : Enregistrer le paiement  **/
  public function enrg_bill_paiement($bill_autre_partie_id = '', $bill_saved = '', $montantpaye = '', $montantrestant = '', $paiement_methode = '', $idEntreprise = '', $idEntite = '', $idactor = '', $numero_facture = '', $pj_mode_paiement)
  {
    $rslt = $this->connect->createCommand('INSERT INTO slim_bill_paiement_historique (bill_autre_partie_id, bill_id, montantpaye, montantrestant, mode_paiement, idEntreprise, idEntite, idActeur, pj_mode_paiement) VALUES (:bill_autre_partie_id, :bill_id, :montantpaye, :montantrestant, :mode_paiement, :idEntreprise, :idEntite, :idActeur, :pj_mode_paiement)')
      ->bindValues([':idActeur' => $idactor, ':idEntite' => $idEntite, ':idEntreprise' => $idEntreprise, ':mode_paiement' => $paiement_methode, ':montantrestant' => $montantrestant, ':montantpaye' => $montantpaye, ':bill_id' => $bill_saved, ':bill_autre_partie_id' => $bill_autre_partie_id, ':pj_mode_paiement' => $pj_mode_paiement])
      ->execute();
    if ($rslt) return 1;
    return;
  }



  /** Methode : Enregistrer la facture du reaprovisionement du stock **/
  public function enrg_bill($data = '', $idEntreprise = '', $idEntite = '', $idactor = '', $numero_facture = '', $categorie_autre_partie = 2, $est_bondcommand = 0)
  {
    $rslt = $facture_solde = $autre_partie = $operation_date = Null;
    if (isset($data) && sizeof($data) > 0) {
      switch ($categorie_autre_partie) {
        case 2:
          if ($data['montantpaye'] == $data['montantFinal']) {
            $facture_solde = 1;
          } else {
            $facture_solde = 2;
          }
          $autre_partie = $data['fournisseur'];
          $operation_date = Yii::$app->nonSqlClass->convert_date_to_sql_form($data['operationdate'], "M/D/Y");
          break;

        case 1:
          if ($data['montantPercu'] == $data['montantFinal']) {
            $facture_solde = 1;
          } else {
            $facture_solde = 2;
          }
          $autre_partie = $data['acheteur'];
          $operation_date = Yii::$app->nonSqlClass->convert_date_to_sql_form($data['ventedate'], "M/D/Y");
          break;
      }

      if ($est_bondcommand == 1) {
        $tbl_label = 'slim_bill';
      }

      $rslt = $this->connect->createCommand('insert into slim_bill (categorie_autre_partie, autre_partie_id, bill_number, billAmount, remiseMonetaire, montantTotalPayer, idEntreprise, idEntite, idActeur, date_topup, statut) VALUES (:categorie_autre_partie, :autre_partie_id, :bill_number, :billAmount, :remiseMonetaire, :montantTotalPayer, :idEntreprise, :idEntite, :idActeur, :date_topup, :statut)')
        ->bindValues([':statut' => $facture_solde, ':date_topup' => $operation_date, ':idActeur' => $idactor, ':idEntite' => $idEntite, ':idEntreprise' => $idEntreprise, ':montantTotalPayer' => $data['montantFinal'], ':remiseMonetaire' => $data['remiseMonetaire'], ':billAmount' => $data['totalMonetaire'], ':bill_number' => $numero_facture, ':autre_partie_id' => $autre_partie, ':categorie_autre_partie' => $categorie_autre_partie])
        ->execute();
    }

    if ($rslt) {
      $stocktopup_bill = $this->connect->createCommand('select id from slim_bill where idEntite=:idEntite and bill_number=:bill_number')
        ->bindValues([':bill_number' => $numero_facture, ':idEntite' => $idEntite])
        ->queryOne();
    }
    if (isset($stocktopup_bill)) return $stocktopup_bill['id'];
    return;
  }


  /** Function : Renvoie l'id de la table entrepro mat **/

  /****** Funtion : renoie les produits avec les quantites disponibles suivi de la valeur du stock *****/
  public function showvaleurdetailleestock($idEntreprise,$listeservice)
  {
    $listarticles = '';
    if (isset($listeservice) && !empty($listeservice)) {
      $stmt = $this->connect->createCommand('SELECT slim_product.id as idproduct, productCode, type, libelle, categoryId, prixUnitaireAchat, prixUnitaireVente,
                                                        slim_productcategorie.id as prodcatid, slim_productcategorie.nom,
                                                        slim_stockentrepot.id, udm, qteDispo, qteMinimal,
                                                        slim_productudm.nom as produdm
                                                  FROM slim_product
                                                  JOIN slim_productcategorie on slim_product.categoryId = slim_productcategorie.id
                                                  JOIN slim_stockentrepot ON slim_stockentrepot.idProduct = slim_product.id
                                                  JOIN slim_productudm ON slim_productudm.id = slim_stockentrepot.udm
                                                  WHERE slim_product.idEntreprise =' . $idEntreprise .'
                                                  AND slim_product.idEntite in (' . $listeservice . ')
                                                  ORDER BY libelle ASC')
        ->queryAll();
      if (is_array($stmt)) {
        $listarticles = $stmt;
      }
    }
    return $listarticles;
  }



  //Methode : get total des charges dues a l'approvisionnement
  public function showhargesapprovisionement($idEntreprise, $datefrom, $dateto)
  {
    $data = '';
    if (isset($idEntreprise)) {
      $stmt = $this->connect->createCommand('SELECT SUM(prixachattt) AS prixachattt FROM slim_charges_approvision WHERE idEntreprise=:idEntreprise AND LastUpdatedOn>=:datefrom and LastUpdatedOn<=:dateto and statut=:statut GROUP BY idEntreprise')
        ->bindValues([':statut' => '1', ':idEntreprise' => $idEntreprise, ':datefrom' => $datefrom, ':dateto' => $dateto])
        ->queryOne();
      if (is_array($stmt) && sizeof($stmt) > 0) {
        $data = $stmt;
      }
    }
    return $data;
  }

  //Methode qui renvois le prix de vente total d'une ligne de vente
  public function getPvtotal_lignevente($idLigneVente)
  {
    $pvTotal = Null;
    if (isset($idLigneVente)) {
      $stmt = $this->connect->createCommand('SELECT prixTotalVente FROM slim_dtlsvente WHERE id=:id')
        ->bindValue(':id', $idLigneVente)
        ->queryOne();
      if ($stmt) {
        $pvTotal = $stmt['prixTotalVente'];
      }
    }
    return $pvTotal;
  }

  //Methode qui renvois le prix d'achat total d'une ligne de vente
  public function getPatotal_lignevente($idLigneVente)
  {
    $paTotal = Null;
    if (isset($idLigneVente)) {
      $stmt = $this->connect->createCommand('SELECT prixTotalAchat FROM slim_dtlsvente WHERE id=:id')
        ->bindValue(':id', $idLigneVente)
        ->queryOne();
      if ($stmt) {
        $paTotal = $stmt['prixTotalAchat'];
      }
    }
    return $paTotal;
  }

  //Methode qui calcul le prix d'achat total d'un produit donnee
  public function calculLePrixAchatTotal($idProduit, $qteEnCommand)
  {
    $prixAchatTotal = $prixUnitaireAchat = Null;
    if (isset($idProduit)) {
      $stmt = $this->connect->createCommand('SELECT prixUnitaireAchat FROM slim_product WHERE id=:id')
        ->bindValue(':id', $idProduit)
        ->queryOne();
      if ($stmt) {
        $prixUnitaireAchat = $stmt['prixUnitaireAchat'];
      }
    }
    $prixTotalAchat = $prixUnitaireAchat * $qteEnCommand;
    return $prixTotalAchat;
  }

  //Methode qui met a jour la qte disponible d'un article
  public function updateQteDispo($idArticle, $productUdm, $qteArestorer)
  {
    $updatestmt1 = $this->connect->createCommand('UPDATE `slim_stockentrepot` SET qteDispo=:qteDispo where idProduct=:idProduct and udm=:productUdm')
      ->bindValues([':qteDispo' => $qteArestorer, ':idProduct' => $idArticle, ':productUdm' => $productUdm])
      ->execute();
    return $updatestmt1;
  }

  //Methode qui met a jour la
  public function updateprixAchatVenteTotal($idLigneVente, $npaTotal_lignevente, $npvtotal_lignevente)
  {
    $updatestmt2 = $this->connect->createCommand('UPDATE `slim_dtlsvente` SET prixTotalAchat=:prixTotalAchat and prixTotalVente=:prixTotalVente where id=:id')
      ->bindValues([':id' => $idLigneVente, ':prixTotalAchat' => $npaTotal_lignevente, ':prixTotalVente' => $npvtotal_lignevente])
      ->execute();
    return $updatestmt2;
  }

  //Methode qui restaure une qte d'un produit precedement vendus a sa qte disponible
  public function restaureQteVendu($idArticle, $idLigneVente, $qteDispo, $qteVendu, $productUdm, $spvtotal)
  {
    $qteArestorer = $qteDispo + $qteVendu;
    if (isset($idArticle) && isset($idLigneVente)) {
      $updatestmt1 = 1;
      //$updatestmt1 = inventaireClass::updateQteDispo($idArticle, $productUdm, $qteArestorer);
      if ($updatestmt1) {
        //Calculons le prix d'achat total du produit en cours de restauration
        $prixVenteArestorer = $prixAchatArestorer = Null;
        $spatotal = inventaireClass::calculLePrixAchatTotal($idArticle, $qteVendu);

        //Renvoyons le prix total vente & Achat de la ligne de vente
        $pvtotal_lignevente = inventaireClass::getPvtotal_lignevente($idLigneVente);
        $paTotal_lignevente = inventaireClass::getPatotal_lignevente($idLigneVente);

        //Calculons la valeur du nouveau prix d'achat & vente total de la ligne de vente
        $npvtotal_lignevente = $pvtotal_lignevente - $spvtotal;
        $npaTotal_lignevente = $paTotal_lignevente - $spatotal;

        return $npaTotal_lignevente;
        //Mettons a jour le prix d'achat / vente total d'une ligne de vente
        $updatestmt2 = inventaireClass::updateprixAchatVenteTotal($idLigneVente, $npaTotal_lignevente, $npvtotal_lignevente);

        return $spatotal;
      }
    }
  }

  //Methode qui renvois les charges dans une periode donnee
  public function showChargesSumary($idEntreprise, $from, $to)
  {
    $data = Null;
    if (isset($idEntreprise)) {
      $stmt = $this->connect->createCommand('SELECT SUM(montant) AS montantTotal FROM slim_charges WHERE idEntreprise=:idEntreprise AND LastUpdatedOn>=:datefrom and LastUpdatedOn<=:dateto and statut=:statut GROUP BY idEntreprise')
        ->bindValues([':statut' => '1', ':idEntreprise' => $idEntreprise, ':datefrom' => $from, ':dateto' => $to])
        ->queryOne();
      if (is_array($stmt) && sizeof($stmt) > 0) {
        $data = $stmt;
      }
    }
    return $data;
  }

    //Methode qui renvois les charges dans une periode donnee
    public function showChargesSumaryBenefiNet($idEntreprise, $from, $to,$listeservice)
    {
      $data = Null;
      if (isset($idEntreprise)) {
        $stmt = $this->connect->createCommand('SELECT SUM(montant) AS montantTotal FROM slim_charges WHERE idEntreprise=:idEntreprise AND LastUpdatedOn>=:datefrom and LastUpdatedOn<=:dateto and statut=:statut and idEntite IN ('.$listeservice.') GROUP BY idEntreprise')
          ->bindValues([':statut' => '1', ':idEntreprise' => $idEntreprise, ':datefrom' => $from, ':dateto' => $to])
          ->queryOne();
        if (is_array($stmt) && sizeof($stmt) > 0) {
          $data = $stmt;
        }
      }
      return $data;
    }

  //Methode qui renvois la marge effectuee
  public function showMargeLignesdeventeSumary($idEntreprise, $from, $to, $catid,$listeservice)
  {
    $data = Null;
    $cat_rquest = (!empty($catid)) ? "AND slim_product.categoryId='" . $catid . "' " : "";
    if (isset($idEntreprise)) {
      $stmt = $this->connect->createCommand('SELECT SUM(prixTotalAchat) AS ttpa,
                                                      	SUM(prixTotalVente) AS ttpv,
                                                      	SUM(remiseMonetaire) AS ttremise,
                                                      	SUM(prixVenteAccorde) AS ttpva,
                                                      	SUM(montantpercu) AS ttmpercu,
                                                      	SUM(detteVente) AS ttdette
                                                      FROM
                                                      	slim_dtlsvente
                                                      WHERE
                                                      	slim_dtlsvente.idEntreprise = :idEntreprise
                                                      AND slim_dtlsvente.statut IN (1,2)
                                                      AND slim_dtlsvente.lastUpdate >=:datefrom
                                                      AND slim_dtlsvente.lastUpdate <= :dateto
                                                      and slim_dtlsvente.statut IN ("1", "2")
                                                      and slim_dtlsvente.idEntite IN ('.$listeservice.')
                                                      GROUP BY
                                                      	slim_dtlsvente.idEntreprise')
        ->bindValues([':idEntreprise' => $idEntreprise, ':datefrom' => $from, ':dateto' => $to])
        ->queryAll();
      $data = $stmt;
    }
    return $data;
  }

  // Function qui renvois les lignes de ventes
  public function showLignesdeventeDtlsData($idEntreprise, $from, $to)
  {
    $data = Null;
    if (isset($idEntreprise)) {
      $stmt = $this->connect->createCommand("SELECT slim_product.productCode, slim_product.libelle,  slim_dtlsproductvendu.id_dtlsvente,slim_dtlsproductvendu.idProduit, slim_dtlsproductvendu.qteVendu, slim_dtlsproductvendu.pvunitaire, slim_dtlsproductvendu.spvtotal
                                                from slim_dtlsproductvendu
                                                inner join slim_product on slim_product.id = slim_dtlsproductvendu.idProduit
                                                inner join slim_dtlsvente on slim_dtlsvente.id = slim_dtlsproductvendu.id_dtlsvente
                                                where slim_dtlsproductvendu.lastUpdate >=:datefrom
                                                and slim_dtlsproductvendu.lastUpdate <=:dateto
                                                and slim_dtlsvente.statut IN ('1', '2')
                                                and slim_dtlsvente.idEntreprise =:idEntreprise")
        ->bindValues([':idEntreprise' => $idEntreprise, ':datefrom' => $from, ':dateto' => $to])
        ->queryAll();
      $data = $stmt;
    }
    return $data;
  }

  // Function qui renvois les lignes de ventes
  public function showLignesdeventeDtlsDataForHistorique($idEntreprise, $from, $to,$listeservice)
  {
    $data = Null;
    if (isset($idEntreprise)) {
      $stmt = $this->connect->createCommand('SELECT slim_product.productCode, slim_product.libelle,  slim_dtlsproductvendu.id_dtlsvente,slim_dtlsproductvendu.idProduit, slim_dtlsproductvendu.qteVendu, slim_dtlsproductvendu.pvunitaire, slim_dtlsproductvendu.spvtotal
                                                from slim_dtlsproductvendu
                                                inner join slim_product on slim_product.id = slim_dtlsproductvendu.idProduit
                                                inner join slim_dtlsvente on slim_dtlsvente.id = slim_dtlsproductvendu.id_dtlsvente
                                                where slim_dtlsproductvendu.lastUpdate >=:datefrom
                                                and slim_dtlsproductvendu.lastUpdate <=:dateto
                                                and slim_dtlsvente.statut IN ("1", "2")
                                                and slim_dtlsvente.idEntreprise =:idEntreprise
                                                AND slim_dtlsvente.idEntite in (' . $listeservice . ')')
        ->bindValues([':idEntreprise' => $idEntreprise, ':datefrom' => $from, ':dateto' => $to])
        ->queryAll();
      $data = $stmt;
    }
    return $data;
  }


  // Function renvois les ventes
  public function showVenteCoreData($idEntreprise, $from, $to, $catid = Null)
  {
    $data = Null;
    $cat_rquest = (!empty($catid)) ? "AND slim_product.categoryId='" . $catid . "' " : "";
    if (isset($idEntreprise)) {
      $stmt = $this->connect->createCommand('SELECT DISTINCT (codeVente),
                                                            	slim_dtlsvente.id,
                                                            	id_client,
                                                            	slim_dtlsvente.prixTotalAchat,
                                                            	slim_dtlsvente.prixTotalVente,
                                                            	remiseMonetaire,
                                                            	prixVenteAccorde,
                                                            	montantpercu,
                                                            	detteVente,
                                                            	idActeur,
                                                            	slim_dtlsvente.lastUpdate
                                                            FROM
                                                            	slim_dtlsvente
                                                            JOIN slim_dtlsproductvendu ON slim_dtlsproductvendu.id_dtlsvente = slim_dtlsvente.id
                                                            JOIN slim_product ON slim_product.id = slim_dtlsproductvendu.idProduit
                                                            WHERE
                                                            	slim_dtlsvente.idEntreprise = :idEntreprise
                                                            AND slim_dtlsvente.statut IN ("1", "2")
                                                            AND slim_dtlsvente.lastUpdate >= :datefrom
                                                            AND slim_dtlsvente.lastUpdate <= :dateto
                                                            ' . $cat_rquest . '
                                                            GROUP BY
                                                            	slim_dtlsvente.codeVente')
        ->bindValues([':idEntreprise' => $idEntreprise, ':datefrom' => $from, ':dateto' => $to])
        ->queryAll();
      $data = $stmt;
    }
    return $data;
  }

  // Function renvois les ventes
  public function showVenteCoreDataBenefBrut($idEntreprise, $from, $to, $catid = Null,$listeservice)
  {
    $data = Null;
    $cat_rquest = (!empty($catid)) ? "AND slim_product.categoryId='" . $catid . "' " : "";
    if (isset($idEntreprise)) {
      $stmt = $this->connect->createCommand('SELECT DISTINCT (codeVente),
                                                            	slim_dtlsvente.id,
                                                            	id_client,
                                                            	slim_dtlsvente.prixTotalAchat,
                                                            	slim_dtlsvente.prixTotalVente,
                                                            	remiseMonetaire,
                                                            	prixVenteAccorde,
                                                            	montantpercu,
                                                            	detteVente,
                                                            	idActeur,
                                                            	slim_dtlsvente.lastUpdate
                                                            FROM
                                                            	slim_dtlsvente
                                                            JOIN slim_dtlsproductvendu ON slim_dtlsproductvendu.id_dtlsvente = slim_dtlsvente.id
                                                            JOIN slim_product ON slim_product.id = slim_dtlsproductvendu.idProduit
                                                            WHERE
                                                            	slim_dtlsvente.idEntreprise = :idEntreprise
                                                            AND slim_dtlsvente.statut IN ("1", "2")
                                                            AND slim_dtlsvente.lastUpdate >= :datefrom
                                                            AND slim_dtlsvente.lastUpdate <= :dateto
                                                            AND slim_dtlsvente.idEntite IN ('.$listeservice.')
                                                            ' . $cat_rquest . '
                                                            GROUP BY
                                                            	slim_dtlsvente.codeVente')
        ->bindValues([':idEntreprise' => $idEntreprise, ':datefrom' => $from, ':dateto' => $to])
        ->queryAll();
      $data = $stmt;
    }
    return $data;
  }

  // Function renvois les ventes
  public function showVenteCoreDataBenefNet($idEntreprise, $from, $to, $catid = Null,$listeservice)
  {
    $data = Null;
    $cat_rquest = (!empty($catid)) ? "AND slim_product.categoryId='" . $catid . "' " : "";
    if (isset($idEntreprise)) {
      $stmt = $this->connect->createCommand('SELECT DISTINCT (codeVente),
                                                            	slim_dtlsvente.id,
                                                            	id_client,
                                                            	slim_dtlsvente.prixTotalAchat,
                                                            	slim_dtlsvente.prixTotalVente,
                                                            	remiseMonetaire,
                                                            	prixVenteAccorde,
                                                            	montantpercu,
                                                            	detteVente,
                                                            	idActeur,
                                                            	slim_dtlsvente.lastUpdate
                                                            FROM
                                                            	slim_dtlsvente
                                                            JOIN slim_dtlsproductvendu ON slim_dtlsproductvendu.id_dtlsvente = slim_dtlsvente.id
                                                            JOIN slim_product ON slim_product.id = slim_dtlsproductvendu.idProduit
                                                            WHERE
                                                            	slim_dtlsvente.idEntreprise = :idEntreprise
                                                            AND slim_dtlsvente.statut IN ("1", "2")
                                                            AND slim_dtlsvente.lastUpdate >= :datefrom
                                                            AND slim_dtlsvente.lastUpdate <= :dateto
                                                            AND slim_dtlsvente.idEntite IN ('.$listeservice.')
                                                            ' . $cat_rquest . '
                                                            GROUP BY
                                                            	slim_dtlsvente.codeVente')
        ->bindValues([':idEntreprise' => $idEntreprise, ':datefrom' => $from, ':dateto' => $to])
        ->queryAll();
      $data = $stmt;
    }
    return $data;
  }


  // Function renvois les ventes
  public function showVenteCoreDataForHistorique($idEntreprise, $from, $to, $listeservice)
  {
    $data = Null;
    if (isset($idEntreprise)) {
      $stmt = $this->connect->createCommand('SELECT DISTINCT (codeVente),
                                                            	slim_dtlsvente.id,
                                                            	id_client,
                                                            	slim_dtlsvente.prixTotalAchat,
                                                            	slim_dtlsvente.prixTotalVente,
                                                            	remiseMonetaire,
                                                            	prixVenteAccorde,
                                                            	montantpercu,
                                                            	detteVente,
                                                            	idActeur,
                                                            	slim_dtlsvente.lastUpdate
                                                            FROM
                                                            	slim_dtlsvente
                                                            JOIN slim_dtlsproductvendu ON slim_dtlsproductvendu.id_dtlsvente = slim_dtlsvente.id
                                                            JOIN slim_product ON slim_product.id = slim_dtlsproductvendu.idProduit
                                                            WHERE
                                                            	slim_dtlsvente.idEntreprise = :idEntreprise
                                                            AND slim_dtlsvente.statut IN ("1", "2")
                                                            AND slim_dtlsvente.lastUpdate >= :datefrom
                                                            AND slim_dtlsvente.lastUpdate <= :dateto
                                                            AND slim_dtlsvente.idEntite IN ('.$listeservice.')
                                                            GROUP BY
                                                            	slim_dtlsvente.codeVente')
        ->bindValues([':idEntreprise' => $idEntreprise, ':datefrom' => $from, ':dateto' => $to])
        ->queryAll();
      $data = $stmt;
    }
    return $data;
  }

  // Function qui renvois la somme de toutes le lignes de ventes en fonction des dates selectionnes
  public function showLignesdeventeSumary($idEntreprise, $from, $to,$listeservice)
  {
    $data = Null;
    if (isset($idEntreprise)) {
      $stmt = $this->connect->createCommand('SELECT id_client,
                                                        sum(slim_dtlsvente.prixTotalVente) as ttpv ,
                                                        sum(remiseMonetaire) as ttremise,
                                                        sum(prixVenteAccorde) as ttpva,
                                                        sum(montantpercu) as ttmpercu,
                                                        sum(detteVente) as ttdette,
                                                        idActeur
                                                  FROM slim_dtlsvente
                                                  WHERE slim_dtlsvente.idEntreprise =:idEntreprise
                                                  and slim_dtlsvente.statut in ("1","2")
                                                  and slim_dtlsvente.lastUpdate >= :datefrom
                                                  and slim_dtlsvente.lastUpdate <= :dateto
                                                  and slim_dtlsvente.statut = :statut
                                                  and slim_dtlsvente.idEntite in (' . $listeservice . ')
                                                  group by slim_dtlsvente.idEntreprise')
        ->bindValues([':idEntreprise' => $idEntreprise, ':datefrom' => $from, ':dateto' => $to,  ':statut' => 1])
        ->queryAll();
      $data = $stmt;
    }
    return $data;
  }

  public function old_showLignesdeventeCoreData($idEntreprise, $from, $to)
  {
    $data = Null;
    if (isset($idEntreprise)) {
      $stmt = $this->connect->createCommand('SELECT slim_dtlsvente.codeVente,
                                                    	slim_dtlsvente.lastUpdate,
                                                    	slim_product.productCode,
                                                    	slim_product.libelle,
                                                    	slim_dtlsproductvendu.qteVendu,
                                                    	slim_dtlsproductvendu.pvunitaire,
                                                    	slim_dtlsproductvendu.spvtotal,
                                                    	slim_dtlsvente.detteVente,
                                                    	slim_dtlsvente.prixTotalVente,
                                                    	slim_dtlsvente.prixVenteAccorde,
                                                    	slim_dtlsvente.remiseMonetaire
                                                    FROM
                                                    	slim_dtlsproductvendu
                                                    INNER JOIN slim_dtlsvente ON slim_dtlsvente.id = slim_dtlsproductvendu.id_dtlsvente
                                                    INNER JOIN slim_product ON slim_product.id = slim_dtlsproductvendu.idProduit
                                                    WHERE
                                                    	slim_dtlsvente.idEntreprise =:idEntreprise
                                                    AND slim_dtlsproductvendu.lastUpdate >=:datefrom
                                                    AND slim_dtlsproductvendu.lastUpdate <=:dateto
                                                    AND slim_dtlsproductvendu.statut =:statut
                                                    ORDER BY  slim_dtlsvente.codeVente ASC')
        ->bindValues([':idEntreprise' => $idEntreprise, ':datefrom' => $from, ':dateto' => $to, ':statut' => '1'])
        ->queryAll();
      $data = $stmt;
    }
    return $data;
  }

  // Function : liste de ventes / article //
  public function showVenteparproduitCoreData($idEntreprise, $from, $to, $listeservice)
  {
    $data = Null;
    if (isset($idEntreprise)) {
      $stmt = $this->connect->createCommand('SELECT SUM(slim_dtlsproductvendu.spvtotal) AS sousprixvente,
                                                    	slim_product.productCode,
                                                    	slim_product.libelle
                                                    FROM
                                                    	slim_dtlsproductvendu
                                                    INNER JOIN slim_product ON slim_product.id = slim_dtlsproductvendu.idProduit
                                                    WHERE slim_product.idEntreprise =:idEntreprise
                                                    AND slim_dtlsproductvendu.statut = :statut
                                                    AND slim_product.idEntite IN ('.$listeservice.')
                                                    AND slim_dtlsproductvendu.lastUpdate > :datefrom AND slim_dtlsproductvendu.lastUpdate < :dateto
                                                    GROUP BY
                                                    	slim_dtlsproductvendu.idProduit ')
        ->bindValues([':idEntreprise' => $idEntreprise, ':datefrom' => $from, ':dateto' => $to, ':statut' => 1])
        ->queryAll();
      if (sizeof($stmt) > 0) {
        $data = $stmt;
      }
    }
    return $data;
  }

  // Function :  top 5 des articles vendus
  public function getTop5DesArticlesVendus($idEntreprise)
  {
    $data = Null;
    if (isset($idEntreprise)) {
      $stmt = $this->connect->createCommand("SELECT slim_product.productCode, COUNT(slim_stockentrepotmap.idProduit) AS nombre, slim_product.libelle, slim_productudm.nom as undname
                                                  FROM slim_product
                                                  JOIN slim_stockentrepotmap ON(slim_product.id = slim_stockentrepotmap.idProduit)
                                                  JOIN slim_stockentrepot ON (slim_stockentrepot.idProduct=slim_product.id)
                                                  JOIN slim_productudm ON slim_productudm.id = slim_stockentrepot.udm
                                                  WHERE slim_product.idEntreprise =:idEntreprise
                                                  AND slim_stockentrepotmap.idMaptype=:idMaptype
                                                  GROUP BY slim_product.id
                                                  ORDER BY COUNT(slim_stockentrepotmap.idProduit) DESC
                                                  limit 4")
        ->bindValues([':idMaptype' => '2', ':idEntreprise' => $idEntreprise])
        ->queryAll();
      if (sizeof($stmt) > 0) {
        $data = $stmt;
      }
    }
    return $data;
  }

  // Funtion : renoie le resultat de la liste des articles dont le stock initial n'a pas changer
  public function showArticlestokinitialCoreData($idEntreprise, $categoryId)
  {
    $data = Null;
    $dt1 = (!empty($categoryId)) ? 'slim_product.categoryId = :categoryId' : '';
    $stmt = $this->connect->createCommand('SELECT count(
                                                      		slim_stockentrepotmap.idProduit
                                                      	) AS nombremouveprod,
                                                      	slim_product.productCode,
                                                      	slim_product.libelle,
                                                      	slim_product.categoryId,
                                                      	slim_stockentrepot.qteDispo
                                                      FROM
                                                      	slim_stockentrepotmap
                                                      JOIN slim_product ON slim_product.id=slim_stockentrepotmap.idProduit
                                                      JOIN slim_stockentrepot ON slim_stockentrepot.idProduct = slim_stockentrepotmap.idProduit
                                                      WHERE slim_stockentrepot.idEntreprise =:idEntreprise
                                                      ' . $dt1 . '
                                                      GROUP BY
                                                      	slim_stockentrepotmap.idProduit');
    if (!empty($categoryId) && $categoryId > 0) {
      $stmt = $stmt->bindValues([':categoryId' => $categoryId, ':idEntreprise' => $idEntreprise]);
    } else {
      $stmt = $stmt->bindValues([':idEntreprise' => $idEntreprise]);
    }
    $stmt = $stmt->queryAll();
    if (is_array($stmt)) {
      $data = $stmt;
    }
    return $data;
  }

  /****** Funtion : renvoie le nom du de la category en fonction de l'id *****/
  public function getCatNameBaseId($catId)
  {
    $catName = Null;
    if (isset($catId) && !empty($catId)) {
      $stmt = $this->connect->createCommand('SELECT nom FROM slim_productcategorie WHERE id=:id')
        ->bindValue(':id', $catId)
        ->queryOne();
      if (is_array($stmt)) {
        $catName = $stmt['nom'];
      }
    }
    return $catName;
  }

  // Funtion : renoie le resultat de la liste des articles en alert de stock
  public function showArticleAlertstokCoreData($idEntreprise, $categoryId)
  {
    $data = Null;
    $dt1 = (!empty($categoryId)) ? 'AND categoryId = :categoryId' : '';
    $stmt = $this->connect->createCommand('SELECT productCode, libelle, categoryId, qteDispo, qteMinimal
                                                FROM slim_product
                                                JOIN slim_stockentrepot ON slim_stockentrepot.idProduct = slim_product.id
                                                WHERE qteDispo<=qteMinimal
                                                ' . $dt1 . '
                                                AND slim_product.idEntreprise=:idEntreprise');
    if (!empty($categoryId) && $categoryId > 0) {
      $stmt = $stmt->bindValues([':categoryId' => $categoryId, ':idEntreprise' => $idEntreprise]);
    } else {
      $stmt = $stmt->bindValues([':idEntreprise' => $idEntreprise]);
    }

    $stmt = $stmt->queryAll();
    if (is_array($stmt)) {
      $data = $stmt;
    }
    return $data;
  }

  // Funtion : renoie le resultat de la liste des articles en alert de stock
  public function showArticleSecurestokCoreData($idEntreprise,$idEntite)
  {
    $data = Null;
    
    $stmt = $this->connect->createCommand('SELECT productCode, libelle, categoryId, qteDispo, qteMinimal,qteSecure
                                                FROM slim_product
                                                JOIN slim_stockentrepot ON slim_stockentrepot.idProduct = slim_product.id
                                                WHERE slim_product.id = slim_stockentrepot.idProduct
                                                AND slim_stockentrepot.qteDispo<=slim_stockentrepot.qteSecure or slim_stockentrepot.qteDispo=0
                                                AND slim_product.idEntreprise=:idEntreprise
                                                AND slim_product.idEntreprise=:idEntite');

    $stmt = $stmt->bindValues([':idEntreprise' => $idEntreprise,':idEntite'=>$idEntite]);
    $stmt = $stmt->queryAll();
    if (is_array($stmt)) {
      $data = $stmt;
    }
    return $data;
  }


  // Funtion : renoie le resultat de l'historique des articles
  public function showhistoriqueArticleCoreData($idEntreprise, $datefrom, $dateto, $code, $listeservice)
  {
    $data = Null;
    $stmt = $this->connect->createCommand('SELECT slim_product.id as productid, productCode, libelle, slim_productudm.nom as produdm
                                                  FROM slim_product
																									JOIN slim_stockentrepotmap on slim_stockentrepotmap.idProduit = slim_product.id
                                                  JOIN slim_stockentrepot ON slim_stockentrepot.idProduct = slim_product.id
                                                  JOIN slim_productudm ON slim_productudm.id = slim_stockentrepot.udm
                                                  WHERE slim_stockentrepotmap.mapDte >  :datefrom AND slim_stockentrepotmap.mapDte  < :dateto
                                                    AND slim_product.productCode like :productCode
                                                    AND slim_product.idEntreprise=:idEntreprise
                                                    AND slim_product.idEntite in (' . $listeservice . ')
                                                  GROUP BY productCode
                                                  ORDER BY mapDte ASC')
      ->bindValues([':idEntreprise' => $idEntreprise, ':datefrom' => $datefrom, ':dateto' => $dateto, ':productCode' => '%' . $code . '%'])
      ->queryAll();
    if (is_array($stmt)) {
      $data = $stmt;
    }
    return $data;
  }


  public function showArticleHistoriqueDetail($productId, $from, $to)
  {
    $data = Null;
    $stmt = $this->connect->createCommand('SELECT idMaptype, mapDte, qteMaped, idActionneur FROM slim_stockentrepotmap WHERE idProduit=:idProduit AND mapDte>=:datefrom and mapDte<:dateto')
      ->bindValues([':idProduit' => $productId, ':datefrom' => $from, ':dateto' => $to])
      ->queryAll();
    if (is_array($stmt)) {
      $data = $stmt;
    }
    return $data;
  }

  //----------------------------------- 05-06-205018 ------------------------------------------//

  /****** Funtion : renvoie le nom du type de mouvement *****/
  public function mouvTypeName($mouvTypeId)
  {
    $name = Null;
    if (isset($mouvTypeId) && !empty($mouvTypeId)) {
      $stmt = $this->connect->createCommand('SELECT mapName FROM slim_maptype WHERE id=:id')
        ->bindValue(':id', $mouvTypeId)
        ->queryOne();
      if (is_array($stmt)) {
        $name = $stmt['mapName'];
      }
    }
    return $name;
  }

  /****** Funtion : renoie le resultat de l'analyse des articles *****/
  public function showAnalyseArticleBaseDate($idEntreprise,$listeservice)
  {
    $listarticles = Null;
    if (isset($listeservice) && !empty($listeservice)) {
      $stmt = $this->connect->createCommand('SELECT slim_product.id as idproduct, productCode, type, libelle, categoryId, prixUnitaireAchat, prixUnitaireVente,
                                                        slim_productcategorie.id as prodcatid, slim_productcategorie.nom,
                                                        slim_stockentrepot.id, udm, qteDispo, qteMinimal,
                                                        slim_productudm.nom as produdm
                                                  FROM slim_product
                                                  JOIN slim_productcategorie on slim_product.categoryId = slim_productcategorie.id
                                                  JOIN slim_stockentrepot ON slim_stockentrepot.idProduct = slim_product.id
                                                  JOIN slim_productudm ON slim_productudm.id = slim_stockentrepot.udm
                                                  WHERE slim_product.idEntreprise =:idEntreprise
                                                  AND slim_product.idEntite in (' . $listeservice . ')')
        ->bindValues([':idEntreprise'=>$idEntreprise])
        ->queryAll();
      if (is_array($stmt)) {
        $listarticles = $stmt;
      }
    }
    return $listarticles;
  }

  //----------------------------------- 02-06-2018 ------------------------------------------//
  public function showlisteArticleInStockandCout($entite)
  {
    $listarticles = Null;
    if (isset($entite) && !empty($entite)) {
      $stmt = $this->connect->createCommand('SELECT slim_product.id as idproduct, productCode, type, libelle, categoryId, prixUnitaireAchat, prixUnitaireVente,qteDispo
                                                  FROM slim_product 
                                                  JOIN slim_stockentrepot on slim_stockentrepot.idProduct = slim_product.id
                                                  WHERE slim_product.idEntreprise = :entite')
        ->bindValue(':entite', $entite)
        ->queryAll();
      if (is_array($stmt)) {
        $listarticles = $stmt;
      }
    }
    return $listarticles;
  }

  //----------------------------------- 09-06-2022 ------------------------------------------//
  public function showlisteArticleInStockandCoutForFondRoul($entite,$listeservice)
  {
    $listarticles = Null;
    if (isset($entite) && !empty($entite)) {
      $stmt = $this->connect->createCommand('SELECT slim_product.id as idproduct, productCode, type, libelle, categoryId, prixUnitaireAchat, prixUnitaireVente,qteDispo
                                                  FROM slim_product 
                                                  JOIN slim_stockentrepot on slim_stockentrepot.idProduct = slim_product.id
                                                  WHERE slim_product.idEntreprise = :entite
                                                  AND slim_product.idEntite IN ('.$listeservice.')')
        ->bindValue(':entite', $entite)
        ->queryAll();
      if (is_array($stmt)) {
        $listarticles = $stmt;
      }
    }
    return $listarticles;
  }


  public function showlisteArticleBaseDate($listeservice, $datefrom, $dateto)
  {
    $listarticles = Null;
    if (isset($listeservice) && !empty($listeservice)) {
      $stmt = $this->connect->createCommand('SELECT slim_product.id as idproduct, productCode, type, libelle, categoryId, prixUnitaireAchat, prixUnitaireVente, slim_productcategorie.id as prodcatid, nom
                                                  FROM slim_product
                                                  JOIN slim_productcategorie on slim_product.categoryId = slim_productcategorie.id
                                                  WHERE datetimeMiseJour > :datefrom AND datetimeMiseJour < :dateto
                                                  AND slim_product.idEntite in (' . $listeservice . ')')
        ->bindValues([':datefrom' => $datefrom, ':dateto' => $dateto])
        ->queryAll();
      if (is_array($stmt)) {
        $listarticles = $stmt;
      }
    }
    return $listarticles;
  }

  //----------------------------------- 29-05-2018 ------------------------------------------//
  public function showsales($idEntreprise, $from, $to)
  {
    return true;
  }
  //----------------------------------- 29-05-2018 ------------------------------------------//
  public function makeInventoryAnalysis($idEntreprise, $from, $to)
  {
    if (isset($idEntreprise)) {
      // Recuperrons la liste des produits

    }
  }
  //----------------------------------- 09-05-2018 ------------------------------------------//
  public function getProductdtlsForSaleBaseEntreprise($listentite)
  {
    $productdtls = Null;
    $productTblElement = ',productCode, type, libelle, categoryId, groupId, markId,generiqueId, paysFabriquant, attributeAssociated, prixUnitaireAchat, prixUnitaireVente, ajouterParUserId, datetimeMiseJour';
    $qtetbleElement = 'idProduct, udm, qteDispo, qteMinimal';
    if (isset($listentite) && !empty($listentite)) {
      $stmt = $this->connect->createCommand('SELECT slim_product.id as slimproductid ' . $productTblElement . ' , ' . $qtetbleElement . '
                                                    FROM slim_product
                                                    JOIN slim_stockentrepot ON slim_stockentrepot.idProduct = slim_product.id
                                                    WHERE slim_product.idEntite IN (' . $listentite . ')')
        ->queryAll();
      if ($stmt) {
        $productdtls = serialize($stmt);
      }
    }
    return $productdtls;
  }

  //----------------------------------- 09-05-2018 ------------------------------------------//
  public function getProductdtlByCode($code)
  {
    $productdtls = Null;
    $productTblElement = ',productCode, type, libelle, categoryId, groupId, markId,generiqueId, paysFabriquant, attributeAssociated, prixUnitaireAchat, prixUnitaireVente, ajouterParUserId, datetimeMiseJour';
    $qtetbleElement = 'idProduct, udm, qteDispo, qteMinimal';
    if (isset($code) && !empty($code)) {
      $stmt = $this->connect->createCommand('SELECT slim_product.id as slimproductid ' . $productTblElement . ' , ' . $qtetbleElement . '
                                                    FROM slim_product
                                                    JOIN slim_stockentrepot ON slim_stockentrepot.idProduct = slim_product.id
                                                    WHERE slim_product.productCode =:code')
        ->bindValue(':code', $code)
        ->queryOne();
      if ($stmt) {
        $productdtls = base64_encode(json_encode($stmt));
      }
    }
    return $productdtls;
  }
  //----------------------------------- 08-05-2018 ------------------------------------------//

  //Deleted product record in produit vendus
  public function deleteproductrecord($productid, $saleid)
  {
    $deleted = $this->connect->createCommand('update slim_dtlsproductvendu set statut=:statut where idProduit=:idProduit and id_dtlsvente=:saleid')
      ->bindValues([':statut' => 0, ':idProduit' => $productid, ':saleid' => $saleid])
      ->execute();
    return true;
  }

  //Mise a jour de la quantite de produit
  public function approvisionementamount($productId, $qteToBeUpdated,  $ttprixachat, $entrepriseId)
  {
    if (isset($productId) && isset($ttprixachat)) {
      $stmt = $this->connect->createCommand('insert into slim_charges_approvision (productid, qteadded, prixachattt, idEntreprise) values (:productid, :qteadded, :prixachattt, :idEntreprise)')
        ->bindValues([':productid' => $productId, ':prixachattt' => $ttprixachat, ':idEntreprise' => $entrepriseId, ':qteadded' => $qteToBeUpdated])
        ->execute();
      return true;
    }
  }

  public function updateProductQte($productId, $qteDispo, $qteAajouter, $udmToBeSend, $ttprixacheter = Null, $entreprise = Null, $operationdate = Null)
  {
    if (isset($operationdate) && $operationdate == Null) $operationdate = date('Y-m-d');
    $rslt = false;
    $qteDispo = base64_decode($qteDispo);
    $productId = base64_decode($productId);
    $udmToBeSend = base64_decode($udmToBeSend);
    $qteToBeUpdated = Null;
    $qteToBeUpdated = $qteDispo + $qteAajouter;

    $updateCodeStmt = $this->connect->createCommand('UPDATE `slim_stockentrepot` SET qteDispo =:qteDispo WHERE idProduct=:idProduct AND udm=:udm')
      ->bindValues([':qteDispo' => $qteToBeUpdated, ':udm' => $udmToBeSend, ':idProduct' => $productId])
      ->execute();
    if (isset($updateCodeStmt)) {
      $rslt = inventaireClass::approvisionementamount($productId, $qteAajouter, $ttprixacheter, $entreprise, $operationdate);
      Yii::$app->inventaireClass->newEntrepotStockMap($productId, $udmToBeSend, '1', $qteAajouter, $operationdate);
    }
    return $rslt;
  }
  //----------------------------------- 07-05-2018 ------------------------------------------//
  public function getUdmLabel($udmId)
  {
    $udmLabel = Null;
    if (isset($udmId)) {
      $stmt = $this->connect->createCommand('SELECT * FROM slim_productudm WHERE id=:id')
        ->bindValue(':id', $udmId)
        ->queryOne();
      if (isset($stmt)) {
        $udmLabel = $stmt['nom'];
      }
    }
    return $udmLabel;
  }
  public function updateProduct($productId, $type, $libelle, $categoryId, $groupId, $markId, $generiqueId, $prixUnitaireAchat, $prixUnitaireVente)
  {
    $rslt = false;
    $productId = base64_decode($productId);
    $updateCodeStmt = $this->connect->createCommand('UPDATE `slim_product` SET type =:type, libelle =:libelle, categoryId=:categoryId, groupId=:groupId, markId =:markId , generiqueId =:generiqueId, prixUnitaireAchat=:prixUnitaireAchat, prixUnitaireVente=:prixUnitaireVente WHERE id=:id')
      ->bindValues([':type' => $type, ':libelle' => $libelle, ':categoryId' => $categoryId, ':groupId' => $groupId, ':markId' => $markId, ':generiqueId' => $generiqueId, ':prixUnitaireAchat' => $prixUnitaireAchat,  ':prixUnitaireVente' => $prixUnitaireVente, ':id' => $productId])
      ->execute();
    if (isset($updateCodeStmt)) {
      $rslt = true;
    }
    return $rslt;
  }

  // reajustement du stock
  public function reajustementeQte($id, $qte)
  {
    //$productId = base64_decode($id);
    $updateQte = $this->connect->createCommand('UPDATE `slim_stockentrepot` SET qteDispo =:qteDispo WHERE id=:id')
      ->bindValues([':qteDispo' => $qte, ':id' => $id])
      ->execute();
  }

  // reajustement du stock
  public function reajustementeQtess($idSlimEntrepoMap, $qte)
  {
    
    $updateQte = $this->connect->createCommand('UPDATE slim_stockentrepot SET qteDispo=:qteDispo WHERE id=:id')
    ->bindValues([':qteDispo' => $qte,':id' => $idSlimEntrepoMap])
    ->execute();
    return true;
  }

  /** Function : Renvois le detail sur un partiulier produit ppour son edition **/
  public function produitDtls($productId)
  {
    $produitDtls = Null;
    if (isset($productId)) {
      $productId = base64_decode($productId);
      $productTblElement = ',productCode, type, libelle, categoryId, groupId, markId,generiqueId, paysFabriquant, attributeAssociated, prixUnitaireAchat, prixUnitaireVente, ajouterParUserId, datetimeMiseJour';
      $qtetbleElement = 'idProduct, udm, qteDispo, qteMinimal';
      $stmt = $this->connect->createCommand('SELECT slim_product.id as slimproductid ' . $productTblElement . ' , ' . $qtetbleElement . '
                                                    FROM slim_product
                                                    JOIN slim_stockentrepot ON slim_stockentrepot.idProduct = slim_product.id
                                                    WHERE slim_product.id=:slimproductid')
        ->bindValue(':slimproductid', $productId)
        ->queryOne();
      if (sizeof($stmt) > 0) {
        $produitDtls = json_encode($stmt);
      }
    }
    return $produitDtls;
  }

  #

  public function getPriceProductVendu($codeVente)
  {
    $productVendu = Null;

    $stmt = $this->connect->createCommand('SELECT slim_dtlsproductvendu.* FROM slim_dtlsproductvendu,slim_dtlsvente
                            WHERE slim_dtlsvente.id =slim_dtlsproductvendu.id_dtlsvente
                            AND slim_dtlsvente.codeVente=:codeVente')
      ->bindValue(':codeVente', $codeVente)
      ->queryAll();
    if (isset($stmt)) {
      $productVendu = ($stmt);
    }

    return $productVendu;
  }

  public function getPriceProductAchete($codeAchat)
  {
    $productVendu = Null;

    $stmt = $this->connect->createCommand('SELECT slim_dtlsproductvendu.* FROM slim_dtlsproductvendu,slim_dtlsvente
                            WHERE slim_dtlsvente.id =slim_dtlsproductvendu.id_dtlsvente
                            AND slim_dtlsvente.codeVente=:codeAchat')
      ->bindValue(':codeAchat', $codeAchat)
      ->queryAll();
    if (isset($stmt)) {
      $productVendu = ($stmt);
    }

    return $productVendu;
  }

  /** FUNCTION : RENVOIS LE STOCK DISPONIBLE POUR UN PRODUIT **/
  public function getStokQteDispo($thisProductId, $thisProductUdm, $actorEntiteId)
  {
    $qteStok = Null;
    if (isset($thisProductId) && isset($actorEntiteId)) {
      $rslt = $this->connect->createCommand('SELECT qteDispo
                                                  FROM slim_stockentrepot
                                                  WHERE idProduct=:thisProductId
                                                  AND idEntite=:thisEntiteId
                                                  AND udm=:thisProductUdm')
        ->bindValues([
          ':thisProductId' => $thisProductId,
          ':thisEntiteId' => $actorEntiteId,
          ':thisProductUdm' => $thisProductUdm
        ])
        ->queryOne();
      if (isset($rslt) && sizeof($rslt) > 0) {
        $qteStok = $rslt['qteDispo'];
      }
    }
    return $qteStok;
  }

  /*** FUNCTION : OPERATION D'AJUSTEMENT ***/
  public function operationAjustment($thisProductId, $thisProductUdm, $typeAjustation, $qteAjouter)
  {
    $newQte = Null;
    # get information l'id de l'entite de l'acteur
    $actorAuthPrimaryInfo = Yii::$app->mainCLass->getUserAuthPrimaryInfo();
    $actorEntiteId = $actorAuthPrimaryInfo['idEntreprise'];

    # Recuperons la qte du stock d'entrepot disponible
    $qteDispo = Yii::$app->inventaireClass->getStokQteDispo($thisProductId, $thisProductUdm, $actorEntiteId);
    # determinons la nature de l'ajustment
    switch ($typeAjustation) {
      case 1: # Ajustement par augmentation
        $newQte = $qteDispo + $qteAjouter;
        break;

      case 2: # ajustement par deduction
        if ($qteDispo > $qteAjouter) {
          $newQte = $qteDispo - $qteAjouter;
        } else {
          return false;
        }
        break;

      default: # Annuler le reste des processus puis sortir
        return false;
        break;
    }

    # Procedons a la amise a jour de la table entrepot
    if (isset($thisProductId) && isset($thisProductUdm) && isset($typeAjustation)) {
      $updatStmt = $this->connect->createCommand('UPDATE `slim_stockentrepot` SET qteDispo=:qteDispo
                                                      WHERE idProduct=:idProduct
                                                      AND udm=:udm
                                                      AND idEntite=:idEntite')
        ->bindValues([
          ':qteDispo' => $newQte,
          ':idProduct' => $thisProductId,
          ':udm' => $thisProductUdm,
          ':idEntite' => $actorEntiteId
        ])
        ->execute();
      if (isset($updatStmt)) {

        /* enregistrons le map correspondant a l'action realise */
        $mapInsertion = inventaireClass::newEntrepotStockMap($thisProductId, $thisProductUdm, $typeAjustation, $qteAjouter);
        if ($mapInsertion) {
          return $mapInsertion;
        }
      }
    }
  }

  #Methode : avoid duplication of udm
  public function avoiDoublonUdm($udmName, $idEntreprise)
  {
    $isDublon = Null;
    if (isset($udmName) && isset($idEntreprise)) {
      $stmt = $this->connect->createCommand('SELECT id FROM slim_productudm WHERE nom=:nom AND idEntreprise=:idEntreprise')
        ->bindValues([':nom' => $udmName, ':idEntreprise' => $idEntreprise])
        ->queryOne();
      if (is_array($stmt) && sizeof($stmt) > 0) {
        $isDublon = '26';
      }
    }
    return $isDublon;
  }

  # FUNCTION : VERIFIE SI LES UNITE DE PRODUIT SAISIE SONT DISPONIBLES
  public function uniteProductValidation($thisProductId, $unitProduct, $typeAjustation)
  {
    $unitProductValider = false;
    if (!empty($thisProductId) && !empty($unitProduct)) {
      $rslt = $this->connect->createCommand('SELECT id, idProduct, qteDispo FROM slim_stockentrepot WHERE idProduct=:idProduct')
        ->bindValue(':idProduct', $thisProductId)
        ->queryOne();
      if (!empty($rslt['qteDispo'])) {
        if ($rslt['qteDispo'] >= $unitProduct) { // STOK DISPONIBLE EST INFERIEUR A LA DEMANDE
          # PASSSONS A LA DEDUCTION DIRECTEMENT

        } else $unitProductValider = true; // STOCK DISPONIBLE EST SUPERIEUR A LA DEMANDE
      }
    }
    return $unitProductValider;
  }

  public function getUdmBaseProductId($idProduct)
  {
    $udmList = $rslt = Null;
    if (isset($idProduct)) {
      $rslt = $this->connect->createCommand('SELECT slim_productudm.id, udm, nom
                                                  FROM slim_stockentrepot
                                                  JOIN slim_productudm ON slim_productudm.id = udm
                                                  where idProduct=:idProduct')
        ->bindValue(':idProduct', $idProduct)
        ->queryAll();
      if (isset($rslt) && sizeof($rslt) > 0) {
        $udmList = $rslt;
      }
    }
    return $udmList;
  }

  # FUNCTION : RENVOIS LES UDM BASE ENTRERISE ID
  public function getUdmBaseEntrepriseId($idEntreprise, $statut = 1)
  {
    $udmList = $rslt = Null;
    if (isset($idEntreprise)) {
      $rslt = $this->connect->createCommand('SELECT *
                                                  FROM slim_productudm
                                                  where idEntreprise=:idEntreprise
                                                  And statut=:statut')
        ->bindValues([':idEntreprise' => $idEntreprise, ':statut' => $statut])
        ->queryAll();
      if (isset($rslt) && sizeof($rslt) > 0) {
        $udmList = $rslt;
      }
    }
    return $udmList;
  }

  # FUNCTION QUI RENVOIS LA LISTE DES GROUPS
  public function getGroupBaseEntrepriseId($idEntreprise)
  {
    $groupData = Null;
    if (isset($idEntreprise)) {
      $rslt = $this->connect->createCommand('SELECT id, nom, description, statut FROM slim_productgroup
                                            WHERE entrepriseId=:entrepriseId
                                              AND statut=:statut
                                            ORDER BY DernierMiseJour DESC ')
        ->bindValues([':entrepriseId' => $idEntreprise, ':statut' => 1])
        ->queryAll();

      if (isset($rslt) && sizeof($rslt) > 0) {
        $groupData = $rslt;
      }
    }
    return $groupData;
  }

  # FUNCTION QUI RENVOIS LA LISTE DES CATEGORIES
  public function getCatBaseEntrepriseId($idEntreprise)
  {
    $catData = Null;
    if (isset($idEntreprise)) {
      $rslt = $this->connect->createCommand('SELECT id, nom, description, statut FROM slim_productcategorie
                                          WHERE entrepriseId=:entrepriseId
                                          AND
                                                statut=:statut
                                          ORDER BY DernierMiseJour DESC ')
        ->bindValues([':entrepriseId' => $idEntreprise, ':statut' => 1])
        ->queryAll();

      if (isset($rslt) && sizeof($rslt) > 0) {
        $catData = $rslt;
      }
    }
    return $catData;
  }

  # FUNCTION QUI RENVOIS LA QUANTITE DE PRODUIT DANS UN ENTREPOT
  public function getAllProductQteInEntrepot($idEntreprise)
  {
    $givingData = Null;
    if (isset($idEntreprise)) {
      $rslt = $this->connect->createCommand('SELECT idProduct, qteDispo FROM slim_stockentrepot WHERE idEntreprise=:idEntreprise')
        ->bindValue(':idEntreprise', $idEntreprise)
        ->queryAll();
      if (isset($rslt)) {
        $givingData = $rslt;
      }
    }
    return $givingData;
  }

  # FUNCTION QUI FILTRE LA VALEUR MONAETAIRE
  public function sanitizeMoneyDatat($amount)
  {
    return $amount;
  }

  /* nouvelle action de map sur le stock en entrepot */
  public function newEntrepotStockMap($productId = 0, $udmProduct = 0, $idMaptype = 0, $qteMaped = 0, $ventedate = Null)
  {
    if (isset($ventedate) && $ventedate == Null) $ventedate = date('Y-m-d');
    $savingProcess = Null;
    $qteMaped = ($qteMaped > 0) ? $qteMaped : 0;
    if (isset($productId) && isset($idMaptype)) {
      #RECUPERONS LES INFOS PRIMAIRE D'UN UTILISATEUR
      $userPrimaryData = Yii::$app->mainCLass->getUserAuthPrimaryInfo();
      #PROCEDE A L'INSERTION
      $rslt = $this->connect->createCommand('INSERT INTO slim_stockentrepotmap (idProduit, idMaptype, mapDte, qteMaped, idEntreprise, idEntite, idActionneur)
                                            VALUES (:idProduct, :idMaptype, :mapDte, :qteMaped, :idEntreprise, :idEntite, :idActionneur)')
        ->bindValues([':idProduct' => $productId, ':idMaptype' => $idMaptype, ':mapDte' => $ventedate, ':qteMaped' => $qteMaped, ':idEntreprise' => $userPrimaryData['idEntreprise'], ':idEntite' => $userPrimaryData['idEntite'], ':idActionneur' => $userPrimaryData['auhId']])
        ->execute();
      if (isset($rslt)) {
        $savingProcess = true;
      }
    }
    return $savingProcess;
  }

  # CREATION AN ENTREPOT STOCK FOR NEW PRODUCT
  public function newEntrepotStock($productId, $qteStock, $qteMinimal,$qteSecure, $udm)
  {
    $savingProcess = false;
    if (isset($productId) && isset($qteStock) && isset($qteMinimal)) {
      #RECUPERONS LES INFOS PRIMAIRE D'UN UTILISATEUR
      $userPrimaryData = Yii::$app->mainCLass->getUserAuthPrimaryInfo();
      #PROCEDE A L'INSERTION
      $rslt = $this->connect->createCommand('INSERT INTO slim_stockentrepot (idProduct, qteDispo, udm, qteMinimal,qteSecure, dateInsertion, idEntreprise, idEntite, idActionneur)
                                            VALUES (:idProduct, :qteDispo, :udm, :qteMinimal,:qteSecure, :dateInsertion, :idEntreprise, :idEntite, :idActionneur)')
        ->bindValues([':idProduct' => $productId, ':qteDispo' => $qteStock, ':udm' => $udm, ':qteMinimal' => $qteMinimal,':qteSecure'=>$qteSecure, ':dateInsertion' => date('Y-m-d'), ':idEntreprise' => $userPrimaryData['idEntreprise'], ':idEntite' => $userPrimaryData['idEntite'], ':idActionneur' => $userPrimaryData['auhId']])
        ->execute();
    }
    return $savingProcess;
  }

  # UPDATE UN UN CODE DEJA UTILISE PAR UN PRODUIT
  public function updateUsedCode($code)
  {
    $rslt = false;
    $updateCodeStmt = $this->connect->createCommand('UPDATE `slim_generatedcode` SET status =:status WHERE code=:code')
      ->bindValues([':status' => 2, ':code' => $code])
      ->execute();
    if (isset($updateCodeStmt)) {
      $rslt = true;
    }
    return $rslt;
  }

  # RENVOIS L'ID DU PRODUCT EN FONCTION DU PRODUCT CODE
  public function getProductIdFromCode($productCode)
  {
    $idProduct = Null;
    if (isset($productCode)) {
      $connect = Yii::$app->db;
      $rslt = $connect->createCommand('SELECT id FROM slim_product WHERE productCode=:productCode')
        ->bindValues([':productCode' => $productCode])
        ->queryOne();
      if (is_array($rslt) && sizeof($rslt) > 0) {
        $idProduct = $rslt['id'];
      }
    }
    return $idProduct;
  }

  # RENVOIS PRODUCT FABRIQUANT DE MARQUE
  public function getProductGenricName($idEntreprise)
  {
    $productGenericName = Null;
    if (!empty($idEntreprise)) {
      $rsltGeneric = $this->connect->createCommand('SELECT id, nom FROM slim_productgenericname WHERE entrepriseId=:entrepriseId')
        ->bindValue(':entrepriseId', $idEntreprise)
        ->queryAll();
      if (is_array($rsltGeneric) && sizeof($rsltGeneric) > 0) {
        $productGenericName = $rsltGeneric;
      }
    }
    return $productGenericName;
  }

  # RENVOIS PRODUCT FABRIQUANT DE MARQUE
  public function getProductMarqueFabricant($idEntreprise)
  {
    $productFabricant = Null;
    if (!empty($idEntreprise)) {
      $rsltFabricant = $this->connect->createCommand('SELECT id, nomFabricant FROM slim_productmarquefabricant WHERE entrepriseId=:entrepriseId')
        ->bindValue(':entrepriseId', $idEntreprise)
        ->queryAll();
      if (is_array($rsltFabricant) && sizeof($rsltFabricant) > 0) {
        $productFabricant = $rsltFabricant;
      }
    }
    return $productFabricant;
  }

  # RENVOIS PRODUCT MARQUE
  public function getProductMarque($idEntreprise)
  {
    $productMarque = Null;
    if (!empty($idEntreprise)) {
      $rsltMarque = $this->connect->createCommand('SELECT id, nom, description FROM slim_productmarque WHERE entrepriseId=:entrepriseId')
        ->bindValue(':entrepriseId', $idEntreprise)
        ->queryAll();
      if (is_array($rsltMarque) && sizeof($rsltMarque) > 0) {
        $productMarque = $rsltMarque;
      }
    }
    return $productMarque;
  }

  # RENVOIS PRODUCTS GROUP
  public function getProductGroup($idEntreprise)
  {
    $productGroup = Null;
    if (!empty($idEntreprise)) {
      $rsltGroupes = $this->connect->createCommand('SELECT id, nom, description FROM slim_productgroup WHERE entrepriseId=:entrepriseId')
        ->bindValue(':entrepriseId', $idEntreprise)
        ->queryAll();
      if (is_array($rsltGroupes) && sizeof($rsltGroupes) > 0) {
        $productGroup = $rsltGroupes;
      }
    }
    return $productGroup;
  }

  # RENVOIS LES CATEGORIES
  public function getCategories($idEntreprise, $statut = 1)
  {
    $categories = Null;
    if (!empty($idEntreprise)) {
      $rsltCategories = $this->connect->createCommand('SELECT id, nom, description FROM slim_productcategorie WHERE entrepriseId=:entrepriseId and statut=:statut')
        ->bindValues([':entrepriseId' => $idEntreprise, ':statut' => 1])
        ->queryAll();
      if (is_array($rsltCategories) && sizeof($rsltCategories) > 0) {
        $categories = $rsltCategories;
      }
    }
    return $categories;
  }

  # RENVOIS LA LIMIT REELE
  public function getRealLimit($limit)
  {
    $rLimit = Null;
    if (isset($limit)) {
      switch ($limit) {
        case 1:
          $rLimit = 10;
          break;
        case 2:
          $rLimit = 20;
          break;
        case 3:
          $rLimit = 30;
          break;
        case 4:
          $rLimit = 40;
          break;
        case 5:
          $rLimit = 50;
          break;
        case 10:
          $rLimit = 10000;
          break;
      }
    }
    return $rLimit;
  }

  # RENVOIS LA LISTE DES PRODUITS AVEC BORNES
  public function getProducts($critere, $donneeRecherche, $limit, $entite = Null)
  {
    $data = $orderByDate = $orderByQte = $productTblElement = $qtetbleElement = Null;
    if (isset($critere) &&  isset($limit)) {
      # RECUPERONS LES INFOS PRIMAIRE D'UN UTILISATEUR
      $userPrimaryData = Yii::$app->mainCLass->getUserAuthPrimaryInfo();
      switch ($critere) {
        case '1':
          $orderByDate = 'DESC';
          break;
        case '2':
          $orderByQte = 'ASC';
          break;
        case '3':
          $orderByQte = 'DESC';
          break;
        default:
          $orderByDate = $orderByQte = 'DESC';
          break;
      }
      $productTblElement = ',productCode, type, libelle, categoryId, groupId, markId,generiqueId, paysFabriquant, attributeAssociated, prixUnitaireAchat, prixUnitaireVente, ajouterParUserId, datetimeMiseJour';
      $qtetbleElement = 'idProduct, udm, qteDispo, qteMinimal';
      $limit = inventaireClass::getRealLimit($limit);
      if (isset($limit) && $limit > 0) {
        $limit = 'LIMIT ' . $limit;
      }
      $rslt = $this->connect->createCommand('SELECT slim_product.id as slimproductid ' . $productTblElement . ' , ' . $qtetbleElement . '
                                                    FROM slim_product
                                                    JOIN slim_stockentrepot ON slim_stockentrepot.idProduct = slim_product.id
                                                    WHERE (slim_product.idEntite IN (' . $entite . '))
                                                    AND (libelle LIKE :donneerecherche
                                                          OR prixUnitaireAchat LIKE :donneerecherche
                                                          OR prixUnitaireVente LIKE :donneerecherche
                                                          OR productCode LIKE :donneerecherche
                                                        )
                                            ORDER BY datetimeMiseJour ' . $orderByDate . ', qteDispo ' . $orderByQte . '
                                            ' . $limit . '')
        ->bindValues([':donneerecherche' => '%' . $donneeRecherche . '%'])
        ->queryAll();
      if ($rslt) {
        $data = $rslt;
      }
    }
    return $data;
  }

  # RENVOIS LA LISTE DES PRODUITS A reajustementER
  public function getreajustementProd($critere, $donneeRecherche, $limit, $entite = Null)
  {
    $data = $orderByDate = $orderByQte = $productTblElement = $qtetbleElement = Null;
    if (isset($critere) &&  isset($limit)) {
      # RECUPERONS LES INFOS PRIMAIRE D'UN UTILISATEUR
      $userPrimaryData = Yii::$app->mainCLass->getUserAuthPrimaryInfo();
      switch ($critere) {
        case '1':
          $orderByDate = 'DESC';
          break;
        case '2':
          $orderByQte = 'ASC';
          break;
        case '3':
          $orderByQte = 'DESC';
          break;
        default:
          $orderByDate = $orderByQte = 'DESC';
          break;
      }
      $productTblElement = ',productCode, type, libelle, categoryId, groupId, markId,generiqueId, paysFabriquant, attributeAssociated, prixUnitaireAchat, prixUnitaireVente, ajouterParUserId, datetimeMiseJour';
      $qtetbleElement = 'idProduct, udm, qteDispo, qteMinimal';
      $limit = inventaireClass::getRealLimit($limit);
      if (isset($limit) && $limit > 0) {
        $limit = 'LIMIT ' . $limit;
      }
      $rslt = $this->connect->createCommand('SELECT slim_product.id as slimproductid, slim_stockentrepot.id as slimstockentrepotid ' . $productTblElement . ' , ' . $qtetbleElement . '
                                                    FROM slim_product
                                                    JOIN slim_stockentrepot ON slim_stockentrepot.idProduct = slim_product.id
                                                    WHERE (slim_product.idEntite IN (' . $entite . '))
                                                    AND (libelle LIKE :donneerecherche
                                                          OR prixUnitaireAchat LIKE :donneerecherche
                                                          OR prixUnitaireVente LIKE :donneerecherche
                                                          OR productCode LIKE :donneerecherche
                                                        )
                                            ORDER BY datetimeMiseJour ' . $orderByDate . ', qteDispo ' . $orderByQte . '
                                            ' . $limit . '')
        ->bindValues([':donneerecherche' => '%' . $donneeRecherche . '%'])
        ->queryAll();
      if ($rslt) {
        $data = $rslt;
      }
    }
    return $data;
  }

  # RENVOIS LA LISTE DES PRODUITS AVEC BORNES
  public function getProductsBaseidEntreprise($idEntreprise)
  {
    $data = Null;
    #RECUPERONS LES INFOS PRIMAIRE D'UN UTILISATEUR
    $userPrimaryData = Yii::$app->mainCLass->getUserAuthPrimaryInfo();
    $rslt = $this->connect->createCommand('SELECT * FROM slim_product WHERE idEntreprise=:idEntreprise
                                        ORDER BY datetimeMiseJour DESC ')
      ->bindValue('idEntreprise', $userPrimaryData['idEntreprise'])
      ->queryAll();
    if (isset($rslt) && sizeof($rslt) > 0) {
      $data = $rslt;
    }
    return $data;
  }

  //insert into productUdm
  public function insertProductUdm($productUdmName, $productUdmDesc)
  {
    $rslt = Null;
    $UserAuthPrimaryInfo = Yii::$app->mainCLass->getUserAuthPrimaryInfo();

    if (isset($productUdmName) && isset($productUdmDesc)) {
      $stmt = $this->connect->createCommand('INSERT INTO slim_productudm (nom, description, idEntreprise) VALUES (:nom, :description, :idEntreprise)')
        ->bindValues([':nom' => $productUdmName, ':description' => $productUdmDesc, ':idEntreprise' => $UserAuthPrimaryInfo['idEntreprise']])
        ->execute();
    }
    return $rslt;
  }

  //update productUdm
  public function updateProductUdm($nom, $desctiption, $action_on_this)
  {
    $rslt = Null;
    if (isset($action_on_this)) {
      $stmt = $this->connect->createCommand('UPDATE slim_productudm SET nom=:nom, description=:description WHERE id=:id')
        ->bindValues([':nom' => $nom, ':description' => $desctiption, ':id' => $action_on_this])
        ->execute();
      if ($stmt) {
        $rslt = '2692';
      }
    }
    return $rslt;
  }

  //update productCategorie
  public function updateProductCat($nom, $desctiption, $statut, $action_on_this)
  {
    $rslt = Null;
    if (isset($action_on_this)) {
      $stmt = $this->connect->createCommand('UPDATE slim_productcategorie SET nom=:nom, description=:description,statut=:statut WHERE id=:id')
        ->bindValues([':nom' => $nom, ':description' => $desctiption, ':statut' => $statut, ':id' => $action_on_this])
        ->execute();
      if ($stmt) {
        $rslt = '2692';
      }
    }
    return $rslt;
  }

  public function histoReappro($idProduct)
  {
    $data = Null;
    #RECUPERONS LES INFOS PRIMAIRE D'UN UTILISATEUR
    $userPrimaryData = Yii::$app->mainCLass->getUserAuthPrimaryInfo();
    $rslt = $this->connect->createCommand('SELECT slim_stockentrepotmap.*,slim_entite.nom AS nomEntite ,slim_utulisateursysteme.nom AS nomActeur,slim_utulisateursysteme.prenom AS prenomActeur, slim_userauth.id AS idAut FROM slim_stockentrepotmap, slim_userauth, slim_utulisateursysteme,slim_entite
                                          WHERE idProduit=:idProduit
                                          AND slim_stockentrepotmap.idMaptype=1
                                          AND slim_stockentrepotmap.idActionneur=slim_userauth.id
                                          AND slim_stockentrepotmap.idEntite=slim_entite.id
                                          AND slim_userauth.id=slim_utulisateursysteme.idUserAuth
                                          AND slim_stockentrepotmap.idEntreprise=:idEntreprise
                                          AND slim_stockentrepotmap.idEntite=:idEntite')
      ->bindValues([':idProduit' => $idProduct, ':idEntreprise' => $userPrimaryData['idEntreprise'], ':idEntite' => $userPrimaryData['idEntite']])
      ->queryAll();
    if (isset($rslt) && sizeof($rslt) > 0) {
      $data = $rslt;
    }
    return $data;
  }
}
