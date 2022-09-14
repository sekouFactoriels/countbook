<?php

namespace app\components;

use Yii;
use yii\base\component;
use yii\web\Controller;
use yii\base\InvalidConfigException;

class venteClass extends Component
{
  public $connect = Null;

  public function __construct()
  {
    $this->connect = \Yii::$app->db;
  }
  /** Delete sale **/
  public function deletesale($saleid)
  {
    $saledeleted = $this->connect->createCommand('UPDATE slim_dtlsvente SET statut=:statut WHERE id=:saleid')
      ->bindValues([':saleid' => $saleid, ':statut' => 0])
      ->execute();
    return true;
  }

  public function getallbakamount($idEntreprise)
  {
    $stmt = $this->connect->createCommand('select SUM(montant) as montant from slim_bank where idEntreprise=:idEntreprise')
      ->bindValue(':idEntreprise', $idEntreprise)
      ->queryAll();
    return $stmt;
  }

  public function getallbakamountForFondRoul($idEntreprise,$listeservice)
  {
    $stmt = $this->connect->createCommand('select SUM(montant) as montant from slim_bank where idEntreprise=:idEntreprise and idEntite IN ('.$listeservice.')')
      ->bindValue(':idEntreprise', $idEntreprise)
      ->queryAll();
    return $stmt;
  }

  /** Renvois les produits dans une ligne de vente **/
  public function getProductinsale($saleid)
  {
    $produits = $this->connect->createCommand('select idProduit, qteVendu, qteDispo, udm
                                                    from 
                                                      slim_dtlsproductvendu 
                                                    join slim_stockentrepot on slim_stockentrepot.idProduct = slim_dtlsproductvendu.idProduit
                                                    where 
                                                      id_dtlsvente =:saleid')
      ->bindValue(':saleid', $saleid)
      ->queryAll();
    return $produits;
  }
  /** Renvois le taux de vente mensuel / admin **/
  public function getMontlyadmingrowthbysaler($idEntreprise, $monthlytargetpoint, $salegrowthmustcalculate)
  {
    $salegrouwth = Null;
    $salegrouwth = $salegrowthmustcalculate * 100 / $monthlytargetpoint;
    return $salegrouwth;
  }

  public function adminmonthlysalersituation($idEntreprise)
  {
    $monthlysale = $thismonth = Null;
    $thismonth = date('m');
    if (isset($idEntreprise)) {
      $stmt = $this->connect->createCommand('SELECT SUM(prixVenteAccorde) as sommevente from slim_dtlsvente where idEntreprise=:idEntreprise and MONTH(lastUpdate)=:thismonth and statut in (1,2)')
        ->bindValues([':idEntreprise' => $idEntreprise, ':thismonth' => $thismonth])
        ->queryOne();
      if (isset($stmt) && sizeof($stmt) > 0) {
        $monthlysale = $stmt['sommevente'];
      }
    }
    return $monthlysale;
  }

  /** Renvois le taux de vente mensuel / saler **/
  public function getMontlysalegrowthbysaler($idEntreprise, $monthlytargetpoint, $salegrowthmustcalculate)
  {
    $salegrouwth = Null;
    if (isset($monthlytargetpoint) && $monthlytargetpoint > 0) {
      $salegrouwth = $salegrowthmustcalculate * 100 / $monthlytargetpoint;
    }

    return $salegrouwth;
  }

  /** Renvois la sommes des vente du mois en cours / saler **/
  public function getMontlysalebysaler($idsaler)
  {
    $monthlysale = $thismonth = Null;
    $thismonth = date('m');
    if (isset($idsaler)) {
      $stmt = $this->connect->createCommand('SELECT SUM(prixTotalVente) as sommevente from slim_dtlsvente 
                where idActeur=:idActeur and MONTH(lastUpdate)=:thismonth
                and statut=:statut')
        ->bindValues([':idActeur' => $idsaler, ':thismonth' => $thismonth, ':statut' => 1])
        ->queryOne();
      if (isset($stmt) && sizeof($stmt) > 0) {
        $monthlysale = $stmt['sommevente'];
      }
    }
    return $monthlysale;
  }

  # Renvoie la liste des produits dans le panier d'une vente deja effectuee
  public function getProductdtlsBaseSaleId($saleId)
  {
    $rslt = Null;
    if (isset($saleId)) {
      $stmt = $this->connect->createCommand('SELECT * FROM slim_dtlsproductvendu WHERE id_dtlsvente=:id_dtlsvente')
        ->bindValue(':id_dtlsvente', $saleId)
        ->queryAll();
    }
    if (is_array($stmt) && sizeof($stmt) > 0) {
      $rslt = $stmt;
    }
    return $rslt;
  }

  # Renvois le details sur une vente
  public function getVenteDtlsById($venteid)
  {
    $rslt = Null;
    if (isset($venteid)) {
      $venteElement = 'codeVente, id_client, prixTotalVente, remiseMonetaire, prixVenteAccorde, montantpercu, detteVente';
      //$productSoldElement = 'id_dtlsvente, idProduit, qteVendu';
      $stmt = $this->connect->createCommand('SELECT slim_dtlsvente.id AS ventesdtlsid, ' . $venteElement . '
                                                  FROM slim_dtlsvente
                                                  WHERE slim_dtlsvente.id=:ventesdtlsid')
        ->bindValue(':ventesdtlsid', $venteid)
        ->queryOne();
      if (is_array($stmt) && sizeof($stmt) > 0) {
        $rslt = $stmt;
      }
    }
    return $rslt;
  }

  # Renvois le nom du client
  public function getClientname($clientId)
  {
    $clientName = Null;
    $stmt = $this->connect->createCommand('SELECT nom_appellation FROM slim_client WHERE id=:id')
      ->bindValue(':id', $clientId)
      ->queryOne();
    if (is_array($stmt) && sizeof($stmt) > 0) {
      $clientName = $stmt['nom_appellation'];
    } else {
      if ($stmt['id'] == 0) {
        $clientName = Yii::t('app', 'clientordi');
      }
    }
    return $clientName;
  }

  # Renvois le nom du fournisseur
  public function getFournisseurName($fournisseurId)
  {
    $fournisseurName = Null;
    $stmt = $this->connect->createCommand('SELECT denomination FROM slim_fournisseur WHERE id=:id')
      ->bindValue(':id', $fournisseurId)
      ->queryOne();
    if (is_array($stmt) && sizeof($stmt) > 0) {
      $fournisseurName = $stmt['denomination'];
    }
    return $fournisseurName;
  }

  # RENVOIS LA LISTE DES PRODUITS AVEC BORNES
  public function getVentes($critere, $donneeRecherche, $limit)
  {
    $data = $orderByDate = $orderByQte = $productTblElement = $qtetbleElement = Null;
    if (isset($critere) &&  isset($limit)) {
      # RECUPERONS LES INFOS PRIMAIRE D'UN UTILISATEUR
      $userPrimaryData = Yii::$app->mainCLass->getUserAuthPrimaryInfo();
      switch ($critere) {
        case '1':
          $orderByQte = 'ASC';
          break;
        case '2':
          $orderByDate = 'DESC';
          break;
        default:
          $orderByDate = $orderByQte = 'DESC';
          break;
      }
      $limit = Yii::$app->inventaireClass->getRealLimit($limit);

      if (isset($limit) && $limit > 0) {
        $limit = 'LIMIT ' . $limit;
      }

      $rslt = $this->connect->createCommand('SELECT id, codeVente, id_client, prixTotalVente, remiseMonetaire, prixVenteAccorde, montantpercu, detteVente,lastUpdate, slim_dtlsvente.statut
                                                    FROM slim_dtlsvente
                                                    WHERE idEntreprise=:idEntreprise
                                                    AND statut IN (1, 2)
                                                    AND (codeVente LIKE :donneerecherche
                                                          OR prixTotalVente LIKE :donneerecherche
                                                          OR remiseMonetaire LIKE :donneerecherche
                                                          OR prixVenteAccorde LIKE :donneerecherche
                                                          OR montantpercu LIKE :donneerecherche
                                                          OR detteVente LIKE :donneerecherche)
                                            ORDER BY lastUpdate ' . $orderByQte . '
                                            ' . $limit . '')
        ->bindValues([':idEntreprise' => $userPrimaryData['idEntreprise'], ':donneerecherche' => '%' . $donneeRecherche . '%', ':statut' => 1])
        ->queryAll();
      if ($rslt) {
        $data = $rslt;
      }
    }
    return $data;
  }

  # RENVOIS LA LISTE DES PRODUITS VENDU DANS LE MOIS
  public function getVentesMois()
  {
    $data = $orderByDate = $orderByQte = $productTblElement = $qtetbleElement = Null;
    if (isset($critere) &&  isset($limit)) {
      # RECUPERONS LES INFOS PRIMAIRE D'UN UTILISATEUR
      $userPrimaryData = Yii::$app->mainCLass->getUserAuthPrimaryInfo();


      $rslt = $this->connect->createCommand('SELECT id, codeVente, id_client, prixTotalVente, remiseMonetaire, prixVenteAccorde, montantpercu, detteVente,lastUpdate, slim_dtlsvente.statut
                                                     FROM slim_dtlsvente
                                                     WHERE idEntreprise=:idEntreprise
                                                     AND statut IN (1, 2)')
        ->bindValues([':idEntreprise' => $userPrimaryData['idEntreprise'],  ':statut' => 1])
        ->queryAll();
      if ($rslt) {
        $data = $rslt;
      }
    }
    return $data;
  }

  /** Function qui renvois l'id d'une ligne de vente en fonction du code de la vente **/
  public function getVenteId($codeDeVente)
  {
    $data = Null;
    if (isset($codeDeVente)) {
      $rslt = $this->connect->createCommand('SELECT id FROM slim_dtlsvente WHERE codeVente=:codeVente')
        ->bindValue(':codeVente', $codeDeVente)
        ->queryOne();
      if (sizeof($rslt) > 0) {
        $data = $rslt['id'];
      }
    }
    return $data;
  }

  /** Function qui met a jour le detaisl des produits vendus **/
  public function updateProductSold($RecentVenteid, $slimproductid, $qteDispo, $qteSold, $productUdm, $puVente, $sptVente, $ventedate)
  {
    $qteAMettreAjour = Null;
    // Calculons la nouvelle qte a inserer
    $qteAMettreAjour = $qteDispo - $qteSold;

    //Inserons les details dans la table
    $rslt = $this->connect->createCommand('INSERT INTO slim_dtlsproductvendu (id_dtlsvente, idProduit, qteVendu, statut, pvunitaire, spvtotal, lastUpdate) VALUES (:id_dtlsvente, :idProduit, :qteVendu, :statut, :pvunitaire, :spvtotal, :lastUpdate)')
      ->bindValues([':id_dtlsvente' => $RecentVenteid, ':idProduit' => $slimproductid, ':qteVendu' => $qteSold, ':statut' => '1', ':pvunitaire' => $puVente, ':spvtotal' => $sptVente, ':lastUpdate' => $ventedate])
      ->execute();

    // Mettons a jour la qte disponible dans la table : slim_stockentrepot
    $rslt = $this->connect->createCommand('UPDATE slim_stockentrepot SET qteDispo=:qteDispo WHERE udm=:udm AND idProduct=:idProduct')
      ->bindValues([':qteDispo' => $qteAMettreAjour, ':udm' => $productUdm, ':idProduct' => $slimproductid])
      ->execute();
    //$newEntrepotStockMap = Yii::$app->inventaireClass->newEntrepotStockMap($slimproductid, $productUdm, '2', $qteSold, $ventedate);

    return true;
  }

  /**Function qui enregistre le detail d'une vente **/
  public function addVenteDtls($codeVente, $acheteur, $ta, $totalMonetaire, $remiseMonetaire, $montantFinal, $montantPercu, $dettevente, $ventedate)
  {

    $statut = $dette = $pvfinal = Null;
    $comparatif = false;
    // RECUPERONS LES INFOS PRIMAIRE D'UN UTILISATEUR
    $userPrimaryData = Yii::$app->mainCLass->getUserAuthPrimaryInfo();
    // Determinons le statut de la vente
    $dette = $montantFinal - $montantPercu;
    $comparatif = ($dette == $dettevente) ? true : false;
    $statut = ($dette <= 0) ? 1 : 2;
    switch ($comparatif) {
      case true:
        $stmt = $this->connect->createCommand('INSERT INTO slim_dtlsvente (codeVente, id_client, prixTotalAchat, prixTotalVente, remiseMonetaire, prixVenteAccorde, montantpercu, detteVente, idEntreprise, idEntite, idActeur, statut, lastUpdate)
                                                  VALUES (:codeVente, :id_client, :prixTotalAchat, :prixTotalVente, :remiseMonetaire, :prixVenteAccorde, :montantpercu, :detteVente, :idEntreprise, :idEntite, :idActeur, :statut, :lastUpdate)')
          ->bindValues([':codeVente' => $codeVente, ':id_client' => $acheteur, ':prixTotalAchat' => $ta, ':prixTotalVente' => $totalMonetaire, ':remiseMonetaire' => $remiseMonetaire, ':prixVenteAccorde' => $montantFinal, ':montantpercu' => $montantPercu, ':detteVente' => $dettevente, ':idEntreprise' => $userPrimaryData['idEntreprise'], ':idEntite' => $userPrimaryData['idEntite'], ':idActeur' => $userPrimaryData['auhId'], ':statut' => $statut, ':lastUpdate' => $ventedate])
          ->execute();
        $stmt = '92';
        break;

      case false:
        $stmt = '18';
        break;
    }
    return $stmt;
  }

  /* Function qui renvoi la liste des produits */
  public function getProductBaseEntreprise($listentite)
  {
    $listeProduit = Null;
    if (isset($listentite)) {
      $stmt = $this->connect->createCommand('SELECT slim_product.productCode,slim_product.type, slim_product.libelle, slim_product.categoryId, slim_product.groupId, slim_product.markId, slim_product.generiqueId, slim_product.paysFabriquant, slim_product.attributeAssociated, slim_product.prixUnitaireAchat, slim_product.prixUnitaireVente, slim_product.idEntreprise, slim_product.ajouterParUserId,slim_stockentrepot.qteDispo
                                                  FROM slim_product
                                                   INNER JOIN slim_stockentrepot
                                                  ON slim_product.id = slim_stockentrepot.idProduct
                                                  WHERE slim_product.idEntite IN (' . $listentite . ')')
        ->queryAll();
      if (isset($stmt) && sizeof($stmt) > 0) {
        $listeProduit = $stmt;
      }
    }
    return $listeProduit;
  }

  #fontion qui renvois la liste des clients
  public function getAllClient($idEntreprise)
  {
    $sel = null;
    if (isset($idEntreprise)) {
      $rslt = $this->connect->createCommand('SELECT * FROM slim_client WHERE idEntreprise=:idPri ')
        ->bindValue(':idPri', $idEntreprise)
        ->queryAll();
      if (isset($rslt)) {
        $sel = $rslt;
      }
    }
    return $sel;
  }

  #fontion qui renvois le detail d'une vente
  public function getDetailVente($idvendu)
  {

    return $this->connect->createCommand('SELECT slim_dtlsproductvendu.id, slim_dtlsproductvendu.id_dtlsvente,slim_product.libelle, slim_dtlsproductvendu.qteVendu, slim_dtlsproductvendu.pvunitaire, slim_dtlsproductvendu.spvtotal
        FROM slim_dtlsproductvendu, slim_product, slim_dtlsvente
        WHERE   slim_dtlsproductvendu.id_dtlsvente=slim_dtlsvente.id
        AND slim_dtlsproductvendu.idProduit=slim_product.id
        AND slim_dtlsproductvendu.id_dtlsvente=:idv')
      ->bindValue(':idv', $idvendu)
      ->queryAll();
  }

  #fontion qui renvois le detail top article vendu
  public function getDetailArticleVente($idProduit)
  {

    $thismonth = Null;
    $thismonth = date('m');
    return $this->connect->createCommand('SELECT slim_dtlsproductvendu.id,
                                slim_dtlsproductvendu.id_dtlsvente,
                                slim_dtlsvente.codeVente,
                                slim_dtlsvente.lastUpdate,
                                slim_dtlsvente.id_client,
                                slim_dtlsproductvendu.idProduit,
                                slim_dtlsproductvendu.qteVendu,
                                slim_dtlsproductvendu.pvunitaire,
                                slim_dtlsproductvendu.spvtotal
                            FROM slim_dtlsproductvendu, slim_product, slim_dtlsvente
                            WHERE   slim_dtlsproductvendu.id_dtlsvente=slim_dtlsvente.id
                            AND slim_dtlsproductvendu.idProduit=slim_product.id
                            AND MONTH(slim_dtlsvente.lastUpdate) =:thismonth
                            AND slim_dtlsproductvendu.idProduit=:idProduit')
      ->bindValues([':idProduit' => $idProduit, ':thismonth' => $thismonth])
      ->queryAll();
  }

  /** Methode enregstre un nouveau contact  **/
  public function enrgclient($statutcontact, $naturecontact, $appelation, $dtebirth, $tel, $email, $enrgmotif, $dtlsmotif, $idEntreprise, $idactor)
  {
    $rslt = $jr = $mois = $annee = Null;
    //Chech if data is not already saved
    $insererle = date('y-m-d');
    $stmt = $this->connect->createCommand('SELECT id FROM slim_client WHERE statutcontact=:statutcontact AND nom_appellation=:nom_appellation AND tel1=:tel1 AND email=:email')
      ->bindValues([':statutcontact' => $statutcontact, ':nom_appellation' => $appelation, ':tel1' => $tel, ':email' => $email])
      ->queryAll();
    if (sizeof($stmt) <= 0) {
      //Explode de date into : day,month,year
      if (!empty($dtebirth)) {
        $date = explode('/', $dtebirth);
        $jr = $date[1];
        $mois = $date[0];
        $annee = $date[2];
      }
      $insertstmt = $this->connect->createCommand('INSERT INTO slim_client (statutcontact, naturecontact, nom_appellation,tel1,email,dayBirth,monthBirth,yearBirth,id_client_enrg_motif,dtlsMotif,idEntreprise,idActor,insererle)
                VALUES (:statutcontact, :naturecontact, :nom_appellation,:tel1,:email,:dayBirth,:monthBirth,:yearBirth,:id_client_enrg_motif,:dtlsMotif,:idEntreprise,:idActor,:insererle)')
        ->bindValues([
          ':statutcontact' => $statutcontact, ':naturecontact' => $naturecontact, ':nom_appellation' => $appelation, ':tel1' => $tel, ':email' => $email, ':dayBirth' => $jr, ':monthBirth' => $mois, ':yearBirth' => $annee,
          ':id_client_enrg_motif' => $enrgmotif, ':dtlsMotif' => $dtlsmotif, ':idEntreprise' => $idEntreprise, ':idActor' => $idactor, ':insererle' => $insererle
        ])
        ->execute();
      if (isset($insertstmt)) $rslt = '2692';
    } else $rslt = '2626';
    return $rslt;
  }

  //TOP 10 DES CLIENT
  public function getTopDixClient($idEntreprise)
  {
    $thismonth = date('m');
    return $this->connect->createCommand('SELECT id_client, count(id_client),sum(prixVenteAccorde) as sommevente 
                                          from slim_dtlsvente 
                                          where idEntreprise=:idEntreprise 
                                          and MONTH(lastUpdate)=:thismonth
                                          and statut in (1,2)
                                          group by id_client
                                          order by sommevente DESC
                                          limit 10')
      ->bindValues([':idEntreprise' => $idEntreprise, ':thismonth' => $thismonth])
      ->queryAll();
  }

  //TOP 10 DES articles
  public function getTopDixArticle($idEntreprise)
  {
    $thismonth = date('m');
    return $this->connect->createCommand('SELECT slim_product.productCode, slim_dtlsproductvendu.id,
                                                slim_dtlsvente.codeVente,
                                                slim_dtlsproductvendu.idProduit,
                                                slim_stockentrepot.qteDispo,
                                                sum(slim_dtlsproductvendu.spvtotal) as prixVenteAccorde
                                          FROM slim_dtlsproductvendu, slim_product, slim_dtlsvente,slim_stockentrepot
                                          WHERE   slim_dtlsproductvendu.id_dtlsvente=slim_dtlsvente.id
                                          AND slim_dtlsproductvendu.idProduit=slim_product.id
                                          AND slim_dtlsproductvendu.idProduit=slim_stockentrepot.idProduct
                                          AND slim_product.idEntreprise=:idEntreprise
                                          AND MONTH(slim_dtlsvente.lastUpdate) =:thismonth
                                          GROUP BY slim_dtlsproductvendu.idProduit
                                          ORDER BY prixVenteAccorde DESC
                                          LIMIT 10')
      ->bindValues([':idEntreprise' => $idEntreprise, ':thismonth' => $thismonth])
      ->queryAll();
  }

  public function getProductname($produitId)
  {
    $productName = Null;
    $stmt = $this->connect->createCommand('SELECT libelle FROM slim_product WHERE id=:id')
      ->bindValue(':id', $produitId)
      ->queryOne();
    if (is_array($stmt) && sizeof($stmt) > 0) {
      $productName = $stmt['libelle'];
    }
    return $productName;
  }

  // cumul des dette
  public function getCumulDette($idEntreprise)
  {
    $cumulDette = $thismonth = Null;
    $thismonth = date('m');
    if (isset($idEntreprise)) {
      $stmt = $this->connect->createCommand('SELECT sum(slim_bill_paiement_historique.montantrestant) AS sommeDette
            FROM slim_bill_paiement_historique,slim_bill
            WHERE slim_bill_paiement_historique.bill_id=slim_bill.id
            AND slim_bill_paiement_historique.statut=1
            AND slim_bill_paiement_historique.idEntreprise=:idEntreprise
            AND slim_bill.categorie_autre_partie=2
            AND MONTH(date_maj)=:thismonth')
        ->bindValues([':idEntreprise' => $idEntreprise, ':thismonth' => $thismonth])
        ->queryOne();
      if (isset($stmt) && sizeof($stmt) > 0) {
        $cumulDette = $stmt['sommeDette'];
      }
    }
    return $cumulDette;
  }

  // cumul des creaces
  public function getCumulCreance($idEntreprise)
  {
    $cumulCreance = $thismonth = Null;
    $thismonth = date('m');
    if (isset($idEntreprise)) {
      $stmt = $this->connect->createCommand('SELECT sum(slim_dtlsvente.detteVente) AS sommeCreance
              FROM slim_dtlsvente, slim_bill
              WHERE slim_dtlsvente.codeVente=slim_bill.bill_number
              AND slim_dtlsvente.detteVente > 0
              AND slim_dtlsvente.idEntreprise=:idEntreprise
              AND slim_bill.categorie_autre_partie=1
              AND MONTH(slim_dtlsvente.lastUpdate)=:thismonth')
        ->bindValues([':idEntreprise' => $idEntreprise, ':thismonth' => $thismonth])
        ->queryOne();
      if (isset($stmt) && sizeof($stmt) > 0) {
        $cumulCreance = $stmt['sommeCreance'];
      }
    }
    return $cumulCreance;
  }

  // get Quantite de l'article
  public function getQte($idProduit)
  {
    $qte = null;
    if (isset($idProduit)) {
      $stmt = $this->connect->createCommand('SELECT slim_stockentrepot.qteDispo 
                                             FROM slim_product,slim_stockentrepot
                                             WHERE slim_product.id=slim_stockentrepot.idProduct
                                             AND slim_product.id=:id')
        ->bindValue(':id', $idProduit)
        ->queryOne();
      if (isset($stmt) && sizeof($stmt) > 0) {
        $qte = $stmt["qteDispo"];
      }
    }
    return $qte;
  }

  # RENVOIS LA LISTE DES PRODUITS VENDU DU MOIS AVEC BORNES
  public function getListeVentesMois($critere, $donneeRecherche, $limit)
  {
    $data = $orderByDate = $orderByQte = $thismonth = $productTblElement = $qtetbleElement = Null;

    $thismonth = date('m');

    if (isset($critere) &&  isset($limit)) {
      # RECUPERONS LES INFOS PRIMAIRE D'UN UTILISATEUR
      $userPrimaryData = Yii::$app->mainCLass->getUserAuthPrimaryInfo();
      switch ($critere) {
        case '1':
          $orderByQte = 'ASC';
          break;
        case '2':
          $orderByDate = 'DESC';
          break;
        default:
          $orderByDate = $orderByQte = 'DESC';
          break;
      }
      $limit = Yii::$app->inventaireClass->getRealLimit($limit);

      if (isset($limit) && $limit > 0) {
        $limit = 'LIMIT ' . $limit;
      }

      $rslt = $this->connect->createCommand('SELECT id, codeVente, id_client, prixTotalVente, remiseMonetaire, prixVenteAccorde, montantpercu, detteVente,lastUpdate, slim_dtlsvente.statut
                                                    FROM slim_dtlsvente
                                                    WHERE idEntreprise=:idEntreprise
                                                    AND statut IN (1, 2)
                                                    AND MONTH(lastUpdate)=:thismonth
                                                    AND (codeVente LIKE :donneerecherche
                                                          OR prixTotalVente LIKE :donneerecherche
                                                          OR remiseMonetaire LIKE :donneerecherche
                                                          OR prixVenteAccorde LIKE :donneerecherche
                                                          OR montantpercu LIKE :donneerecherche
                                                          OR detteVente LIKE :donneerecherche)
                                            ORDER BY lastUpdate ' . $orderByQte . '
                                            ' . $limit . '')
        ->bindValues([':idEntreprise' => $userPrimaryData['idEntreprise'], ':donneerecherche' => '%' . $donneeRecherche . '%', ':statut' => 1, ':thismonth' => $thismonth])
        ->queryAll();
      if ($rslt) {
        $data = $rslt;
      }
    }
    return $data;
  }

  //les charges du mois
  public function getChargeMois($idEntreprise)
  {
    $thismonth = null;

    $thismonth = date('m');

    $stmt = $this->connect->createCommand('SELECT slim_charges.id as chargeid, idMotif,dateOperation, montant, description, slim_charges_motif.nom as motifname, description from slim_charges 
    join slim_charges_motif on slim_charges_motif.id = slim_charges.idMotif 
    where slim_charges.idEntreprise =:idEntreprise
    AND MONTH(slim_charges.LastUpdatedOn)=:thismonth')
      ->bindValues([':idEntreprise' => $idEntreprise, ':thismonth' => $thismonth])
      ->queryAll();

    return $stmt;
  }

  //les approvisonnements du mois
  public function getApproMois($idEntreprise)
  {
    $thismonth = null;

    $thismonth = date('m');

    $stmt = $this->connect->createCommand('SELECT * FROM slim_bill 
                  WHERE categorie_autre_partie=2
                  AND idEntreprise=:idEntreprise
                  AND statut in(1,2)
                  AND MONTH(date_topup)=:thismonth')
      ->bindValues([':idEntreprise' => $idEntreprise, ':thismonth' => $thismonth])
      ->queryAll();

    return $stmt;
  }

  // valeur du stock dispo
  public function getValeurStock($idEntreprise)
  {
    $valeurStock = Null;
    if (isset($idEntreprise)) {
      $stmt = $this->connect->createCommand('SELECT SUM(slim_product.prixUnitaireVente*slim_stockentrepot.qteDispo) as montant
                    FROM slim_product,slim_stockentrepot
                    WHERE slim_product.id=slim_stockentrepot.idProduct
                    AND slim_product.idEntreprise=:idEntreprise
                    AND slim_stockentrepot.qteDispo !=0')
        ->bindValues([':idEntreprise' => $idEntreprise])
        ->queryOne();
      if (isset($stmt) && sizeof($stmt) > 0) {
        $valeurStock = $stmt['montant'];
      }
    }
    return $valeurStock;
  }

  // Funtion : renoie le resultat de la liste des articles en stock de securite
  public function getArticleAlertstokCoreData($idEntreprise, $categoryId)
  {
    $data = Null;
    $dt1 = (!empty($categoryId)) ? 'AND categoryId = :categoryId' : '';

    $productTblElement = ',productCode, type, libelle, categoryId, groupId, markId,generiqueId, paysFabriquant, attributeAssociated, prixUnitaireAchat, prixUnitaireVente, ajouterParUserId, datetimeMiseJour';
    $qtetbleElement = 'idProduct, udm, qteDispo,qteSecure, qteMinimal';

    $stmt = $this->connect->createCommand('SELECT slim_product.id as slimproductid ' . $productTblElement . ' , ' . $qtetbleElement . '
                                                    FROM slim_product
                                                    JOIN slim_stockentrepot ON slim_stockentrepot.idProduct = slim_product.id
                                                    WHERE qteDispo<=qteMinimal' . $dt1 . '
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

  // Funtion : renoie le resultat de la liste des articles en stock d'alerte'
  public function getArticleAlertstokData($idEntreprise, $categoryId,$idEntite)
  {
    $data = Null;
    $dt1 = (!empty($categoryId)) ? 'AND categoryId = :categoryId' : '';

    $productTblElement = ',productCode, type, libelle, categoryId, groupId, markId,generiqueId, paysFabriquant, attributeAssociated, prixUnitaireAchat, prixUnitaireVente, ajouterParUserId, datetimeMiseJour';
    $qtetbleElement = 'idProduct, udm, qteDispo, qteMinimal,qteSecure';

    $stmt = $this->connect->createCommand('SELECT slim_product.id as slimproductid ' . $productTblElement . ' , ' . $qtetbleElement . '
                                                    FROM slim_product
                                                    JOIN slim_stockentrepot ON slim_stockentrepot.idProduct = slim_product.id
                                                    WHERE qteDispo<=qteSecure or qteDispo=0' . $dt1 . '
                                                    AND slim_product.idEntreprise=:idEntreprise
                                                    AND slim_product.idEntite=:idEntite');
    if (!empty($categoryId) && $categoryId > 0) {
      $stmt = $stmt->bindValues([':categoryId' => $categoryId, ':idEntreprise' => $idEntreprise,':idEntite'=>$idEntite]);
    } else {
      $stmt = $stmt->bindValues([':idEntreprise' => $idEntreprise,':idEntite'=>$idEntite]);
    }

    $stmt = $stmt->queryAll();
    if (is_array($stmt)) {
      $data = $stmt;
    }
    return $data;
  }


  // cumul des recouvrement paye
  public function cumulRecouvrementPayer($idEntreprise)
  {
    $cumulrecouvrement = $thismonth = Null;
    $thismonth = date('m');
    if (isset($idEntreprise)) {
      $stmt = $this->connect->createCommand('select SUM(montantpercu) as montantpaye 
                        FROM slim_dtlsvente
                        WHERE slim_dtlsvente.statut in(1,2)
                        AND slim_dtlsvente.idEntreprise=:idEntreprise
                        AND MONTH(lastUpdate)=:thismonth')
        ->bindValues([':idEntreprise' => $idEntreprise, ':thismonth' => $thismonth])
        ->queryOne();

      if (isset($stmt) && sizeof($stmt) > 0) {
        $cumulrecouvrement = $stmt['montantpaye'];
      }
      return $cumulrecouvrement;
    }
  }
}
