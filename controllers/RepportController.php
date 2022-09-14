<?php
namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;

class RepportController extends Controller {
  private $pg = Null;
  private $msg = Null;

    #******************************************
    #           NOMENCLATURE  DES ACTIONS
    # Nom de l'action : NomDOssier_Nomdufichier
    #*******************************************

    // CHARGER LES DATA D'UNE FACTURE
  public function actionFacture()
  {
    $request=$autre_partie_id=$autre_partie=$mvt_article=$bill_paid=Null;
    $userPrimaryData = Yii::$app->mainCLass->getUserAuthPrimaryInfo();
    $request = $_POST;
    Yii::$app->layout = 'report_layout';
    $this->pg = '/recouvrement/facture_content.php';
    $formdataposted = (Yii::$app->session['formdataposted']) ? unserialize(Yii::$app->session['formdataposted']) : Null;
    $bill_id = (isset($formdataposted['action_on_this'])) ? $formdataposted['action_on_this'] : Null;
    $categorie_autre_partie = (isset($formdataposted['action_on_this_val'])) ? $formdataposted['action_on_this_val'] : Null; 
    $bill = yii::$app->paiementClass->get_bill_data($bill_id);
    $paiement_historique = yii::$app->paiementClass->get_paiement_historique($bill_id);

    if(isset($bill) && is_array($bill))
    {
      foreach ($bill as $each_bill) 
      {
        $autre_partie_id = (isset($each_bill['autre_partie_id'])) ? $each_bill['autre_partie_id'] : Null;
        $mvt_article = yii::$app->inventaireClass->get_mvt_article_par_facture_num($each_bill['bill_number']);
        $bill_paid = yii::$app->paiementClass->bill_ttpaid($bill);
      }
    }

    switch($categorie_autre_partie)
    {
      case 1:
      $typeOperation=1;
      $autre_partie = yii::$app->clientClass->getclientdata($autre_partie_id);
      return $this->render($this->pg, ['paiement_historique'=>$paiement_historique, 'bill'=>$bill, 'autre_partie'=>$autre_partie, 'userPrimaryData'=>$userPrimaryData, 'categorie_autre_partie'=>$categorie_autre_partie, 'mvt_article'=>$mvt_article,'bill_paid'=>$bill_paid,'typeOperation'=>$typeOperation]);
      break;

      case 2:
      $typeOperation=2;
      $autre_partie = yii::$app->fournisseurClass->get_fournisseur($autre_partie_id);
      return $this->render($this->pg, ['paiement_historique'=>$paiement_historique, 'bill'=>$bill, 'autre_partie'=>$autre_partie, 'userPrimaryData'=>$userPrimaryData, 'categorie_autre_partie'=>$categorie_autre_partie, 'mvt_article'=>$mvt_article, 
        'bill_paid'=>$bill_paid,'typeOperation'=>$typeOperation]);
      break;
    }

      // Recuperrons les infos basiques sur l'utilisateur connecte
    return $this->redirect(Yii::$app->request->baseUrl.'/'.md5('fournisseur_themain'));
  }

        // CHARGER LES DATA D'UN DEVIS
  public function actionDevis()
  {
    $request=$autre_partie_id=$autre_partie=$mvt_article=$bill_paid=Null;
    $userPrimaryData = Yii::$app->mainCLass->getUserAuthPrimaryInfo();
    $request = $_POST;
    Yii::$app->layout = 'report_layout';
    $this->pg = '/themain/devis_printer_content.php';
    $formdataposted = (Yii::$app->session['formdataposted']) ? unserialize(Yii::$app->session['formdataposted']) : Null;
    $bill_id = (isset($formdataposted['action_on_this'])) ? $formdataposted['action_on_this'] : Null;
    $categorie_autre_partie = (isset($formdataposted['action_on_this_val'])) ? $formdataposted['action_on_this_val'] : Null; 
    $bill = yii::$app->paiementClass->get_bill_data($bill_id);
    
    if(isset($bill) && is_array($bill))
    {
      foreach ($bill as $each_bill) 
      {
        $autre_partie_id = (isset($each_bill['autre_partie_id'])) ? $each_bill['autre_partie_id'] : Null;
        $mvt_article = yii::$app->inventaireClass->get_mvt_article_par_facture_num($each_bill['bill_number']);
        $bill_paid = yii::$app->paiementClass->bill_ttpaid($bill);
      }
    }
    
    switch($categorie_autre_partie)
    {
      case 1:
      die(print_r('bingooooo'));
      $autre_partie = yii::$app->clientClass->getclientdata($autre_partie_id);
      return $this->render($this->pg, ['autre_partie'=>$autre_partie]);
      break;
    }

          // Recuperrons les infos basiques sur l'utilisateur connecte
          // return $this->redirect(Yii::$app->request->baseUrl.'/'.md5('fournisseur_themain'));
  }

    // Analyse d'article
  public function actionValeurdetailleestock(){
    $request=$msg=$datefrom=$dateto=$listarticles=Null;
    $request = $_POST;
    Yii::$app->layout = 'report_layout';
    $this->pg = '/rggeneral/rg_invent/valeurdetailleestock/reportdtls.php';
    $formdataposted = (Yii::$app->session['formdataposted']) ? unserialize(Yii::$app->session['formdataposted']) : Null;

      // Recuperrons les articles enregistres dans cet interval de temps
    $userPrimaryData = Yii::$app->mainCLass->getUserAuthPrimaryInfo();

    $listeservice = Yii::$app->parametreClass->listeserviceforrepport($formdataposted['listeservices'], $userPrimaryData['idEntreprise'], $userPrimaryData['typeUser'], $userPrimaryData['idEntite']);

    $listarticles = Yii::$app->inventaireClass->showvaleurdetailleestock($userPrimaryData['idEntreprise'],$listeservice);
      // Recuperrons les infos basiques sur l'utilisateur connecte
    return $this->render($this->pg,['msg'=>$msg, 'formdataposted'=>serialize($formdataposted), 'listarticles'=>serialize($listarticles), 'userPrimaryData'=>serialize($userPrimaryData)]);
  }

  /** Chargeons les data du rapport du fond de roulement **/
  public function actionFondroulement()
  {
    $request=$msg=$datefrom=$dateto=$listarticles=$dtlsdata=$catid=$charges=$charges_approvisionement_tt = Null;
    $request = $_POST;
    Yii::$app->layout = 'report_layout';
    $this->pg = '/rggeneral/rg_vente/fondroulement/reportdtls.php';
    $formdataposted = (Yii::$app->session['formdataposted']) ? unserialize(Yii::$app->session['formdataposted']) : Null;
      // Convertissons les dates a des format conventionnels a la recherche
    $datefrom = Yii::$app->nonSqlClass->convert_date_to_sql_form($formdataposted['datefrom'], "M/D/Y");
    $dateto = Yii::$app->nonSqlClass->convert_date_to_sql_form($formdataposted['dateto'], "M/D/Y");
      // Recuperrons les infos basiques sur l'utilisateur connecte
    $userPrimaryData = Yii::$app->mainCLass->getUserAuthPrimaryInfo();

    $listeservice = Yii::$app->parametreClass->listeserviceforrepport($formdataposted['listeservices'], $userPrimaryData['idEntreprise'], $userPrimaryData['typeUser'], $userPrimaryData['idEntite']);

      //Chargeons les data
    $benefice_brute = Yii::$app->inventaireClass->showMargeLignesdeventeSumary($userPrimaryData['idEntreprise'], $datefrom, $dateto,$catid,$listeservice);



      //Get charges
    $charges_tt = $montant_caisse = 0;

      //Get charge approvisionnement
    $charges_approvisionement = Yii::$app->inventaireClass->showhargesapprovisionement($userPrimaryData['idEntreprise'], $datefrom, $dateto);

    if ($charges_approvisionement) {
      $charges_approvisionement = isset($charges_approvisionement) && sizeof($charges_approvisionement)>0 ? $charges_approvisionement['prixachattt'] : 0; 
    }else{
     $charges_approvisionement=0; 
   }

   // die(print_r($charges_approvisionement));


   $charges = Yii::$app->diverClass->showDepenseCoreDataForFondRoul($userPrimaryData['idEntreprise'], $datefrom, $dateto,'',$listeservice);
   if(isset($charges) && sizeof($charges) > 0){
    for ($i=0; $i < sizeof($charges) ; $i++) { 
      $charges_tt = $charges_tt + $charges[$i]['montant'];
    }
  }

  $ttpa = (isset($benefice_brute[0]['ttpa']) && $benefice_brute[0]['ttpa'] >0) ? $benefice_brute[0]['ttpa'] : 0;
  $montant_caisse = $ttpa - $charges_approvisionement;

  $product_inventaire = Yii::$app->inventaireClass->showlisteArticleInStockandCoutForFondRoul($userPrimaryData['idEntreprise'],$listeservice);
  $cout_stock = $valeur_produit = 0;

  $bank = Yii::$app->venteClass->getallbakamountForFondRoul($userPrimaryData['idEntreprise'],$listeservice);


  $montant_caisse = $montant_caisse + $bank[0]['montant'];

  for ($i=0; $i < sizeof($product_inventaire) ; ++$i) { 
    $valeur_produit = 0;
    $valeur_produit = $product_inventaire[$i]['qteDispo'] *  $product_inventaire[$i]['prixUnitaireAchat'];
    $cout_stock = $valeur_produit + $cout_stock;
    //var_dump($valeur_produit .'  '. $cout_stock);
  }
    //die();
  $bank = (isset($bank) && sizeof($bank)>0) ? $bank[0]['montant'] : 0;
  return $this->render($this->pg,['msg'=>$msg, 'formdataposted'=>serialize($formdataposted), 'cout_stock'=>$cout_stock, 'dtlsdata'=>$dtlsdata, 'montant_caisse'=>$montant_caisse, 'userPrimaryData'=>serialize($userPrimaryData), 'bank'=>$bank]);
}

/** Chargeons les data du rapport des utulisateurs **/
public function actionUtulisateurdiver(){
  $request=$msg=$datefrom=$dateto=$usertypeid=Null;
  $request = $_POST;
  Yii::$app->layout = 'report_layout';
  $this->pg = '/rggeneral/rg_diver/utulisateur/reportdtls.php';
  $formdataposted = (Yii::$app->session['formdataposted']) ? unserialize(Yii::$app->session['formdataposted']) : Null;
      // Recuperrons les infos basiques sur l'utilisateur connecte
  $userPrimaryData = Yii::$app->mainCLass->getUserAuthPrimaryInfo();
      // Formattons le type d'utulisateur, ssi selectionner
  $usertypeid = ($formdataposted['usertypeid'] > 0) ? $formdataposted['usertypeid'] : '';
      //Chargeons les data
  $coredata = Yii::$app->diverClass->showUserCoreData($userPrimaryData['idEntreprise'], $usertypeid);
      //Chargeons les charges dans la periode donnee
  return $this->render($this->pg,['msg'=>$msg, 'formdataposted'=>serialize($formdataposted), 'usertypeid'=>$usertypeid, 'coredata'=>$coredata,'userPrimaryData'=>serialize($userPrimaryData)]);
}


public function actionEvenementdiver(){
  $request=$msg=$datefrom=$dateto=$eventcode=Null;
  $request = $_POST;
  Yii::$app->layout = 'report_layout';
  $this->pg = '/rggeneral/rg_diver/evenement/reportdtls.php';
  $formdataposted = (Yii::$app->session['formdataposted']) ? unserialize(Yii::$app->session['formdataposted']) : Null;
      // Convertissons les dates a des format conventionnels a la recherche
  $datefrom = Yii::$app->nonSqlClass->convert_date_to_sql_form($formdataposted['datefrom'], "M/D/Y");
  $dateto = Yii::$app->nonSqlClass->convert_date_to_sql_form($formdataposted['dateto'], "M/D/Y");
      // Recuperrons les infos basiques sur l'utilisateur connecte
  $userPrimaryData = Yii::$app->mainCLass->getUserAuthPrimaryInfo();

  $listeservice = Yii::$app->parametreClass->listeserviceforrepport($formdataposted['listeservices'], $userPrimaryData['idEntreprise'], $userPrimaryData['typeUser'], $userPrimaryData['idEntite']);

      // Formattons le code de l'article saisie dans le formulaire
  $eventcode = ($formdataposted['listevenement'] > 0) ? $formdataposted['listevenement'] : '';
      //Chargeons les data
  $coredata = Yii::$app->diverClass->showEventCoreData($userPrimaryData['idEntreprise'], $datefrom, $dateto, $eventcode,$listeservice);
      //Chargeons les charges dans la periode donnee
  return $this->render($this->pg,['msg'=>$msg, 'formdataposted'=>serialize($formdataposted), 'eventcode'=>$eventcode, 'coredata'=>$coredata,'userPrimaryData'=>serialize($userPrimaryData)]);
}


public function actionUserdiver(){
  $request=$msg=$usercode=Null;
  $request=$_Post;
  yii::$app->layout = 'report_layout';
  $this->pg = '/rggeneral/rg_diver/utilisateur/reportdtls.php';
  $formdataposted = (yii::$app->session['formdataposted']) ?
  unserialize(yii::$app->session['formdataposted']): Null;
     // requiperons les onfos basiques sur l'utilisateur connecté
  $userPrimaryData = yii::$app->mainClass->getUserAuthPrimaryInfo();
      //formatons les code saisie dans le formulaire
  $usercode = ($formdataposted ['listeuser'] > 0 )? $formdataposted['listeuser'] : '';
      //chargeon les datas
  $coredata = yii:: $app->diverClass->showUserCoreData($userPrimaryData['idEntreprise'], $usercode);
      //chargeons les charges dans la periode donne
  return $this->render($this->pg,['msg'=>$msg, 'formdataposted'=>
    serialize($formdataposted), 'usercode'=>$usercode, 'coredata'=>$coredata, 'userPrimaryData'=>serialize($userPrimaryData)]);

}

public function actionDepensediver(){
 $msg=$request=$datefrom=$dateto=$depensecode=Null;
 $resquest=$_POST;
 yii::$app->layout= 'report_layout';
 $this->pg = '/rggeneral/rg_diver/depense/reportdtls.php';
 $formdataposted = (yii::$app->session['formdataposted']) ? unserialize(yii::$app->session['formdataposted']) : Null;
           // convertissons les dates a des format conventionnels a la recherche
 $datefrom = yii::$app->nonSqlClass->convert_date_to_sql_form($formdataposted['datefrom'], "M/D/Y");
 $dateto = Yii::$app->nonSqlClass->convert_date_to_sql_form($formdataposted['dateto'], "M/D/Y");
           // Recuperrons les infos basiques sur l'utilisateur connecte
 $userPrimaryData = Yii::$app->mainCLass->getUserAuthPrimaryInfo();

 $listeservice = Yii::$app->parametreClass->listeserviceforrepport($formdataposted['listeservices'], $userPrimaryData['idEntreprise'], $userPrimaryData['typeUser'], $userPrimaryData['idEntite']);

           // Formattons le code de l'article saisie dans le formulaire
 $depensecode = ($formdataposted['depensetypeid'] > 0) ? $formdataposted['depensetypeid'] : '';
           //Chargeons les data
 $coredata = Yii::$app->diverClass->showDepenseCoreDataSecond($userPrimaryData['idEntreprise'], $datefrom, $dateto, $depensecode,$listeservice);
           //Chargeons les charges dans la periode donnee
 return $this->render($this->pg,['msg'=>$msg, 'formdataposted'=>serialize($formdataposted), 'depensecode'=>$depensecode, 'coredata'=>$coredata,'userPrimaryData'=>serialize($userPrimaryData)]);
}

    // Marge Vente par produit
public function actionMargenetventeparproduit(){
  $request=$msg=$datefrom=$dateto=$listarticles=$sumarydata=$coredata=$dtlsdata=$catid=$charges=Null;
  $request = $_POST;
  Yii::$app->layout = 'report_layout';
  $this->pg = '/rggeneral/rg_vente/margenetlignevente/reportdtls.php';
  $formdataposted = (Yii::$app->session['formdataposted']) ? unserialize(Yii::$app->session['formdataposted']) : Null;
      // Convertissons les dates a des format conventionnels a la recherche
  $datefrom = Yii::$app->nonSqlClass->convert_date_to_sql_form($formdataposted['datefrom'], "M/D/Y");
  $dateto = Yii::$app->nonSqlClass->convert_date_to_sql_form($formdataposted['dateto'], "M/D/Y");
      // Recuperrons les infos basiques sur l'utilisateur connecte
  $userPrimaryData = Yii::$app->mainCLass->getUserAuthPrimaryInfo();
      // Formattons le code de l'article saisie dans le formulaire
  $catid = ($formdataposted['listcategories'] > 0) ? $formdataposted['listcategories'] : '';

  $listeservice = Yii::$app->parametreClass->listeserviceforrepport($formdataposted['listeservices'], $userPrimaryData['idEntreprise'], $userPrimaryData['typeUser'], $userPrimaryData['idEntite']);

      //Chargeons les data
  $sumarydata = Yii::$app->inventaireClass->showMargeLignesdeventeSumary($userPrimaryData['idEntreprise'], $datefrom, $dateto,$catid,$listeservice);
  $coredata = Yii::$app->inventaireClass->showVenteCoreDataBenefNet($userPrimaryData['idEntreprise'], $datefrom, $dateto, $catid,$listeservice);
      //Chargeons les charges dans la periode donnee
  $charges = Yii::$app->inventaireClass->showChargesSumaryBenefiNet($userPrimaryData['idEntreprise'], $datefrom, $dateto,$listeservice);
  return $this->render($this->pg,['msg'=>$msg, 'formdataposted'=>serialize($formdataposted), 'charges'=>$charges['montantTotal'], 'dtlsdata'=>$dtlsdata, 'coredata'=>$coredata, 'sumarydata'=>serialize($sumarydata), 'userPrimaryData'=>serialize($userPrimaryData)]);
}

    // Marge Vente par produit
public function actionMargeventeparproduit(){
  $request=$msg=$datefrom=$dateto=$listarticles=$sumarydata=$coredata=$dtlsdata=$catid=Null;
  $request = $_POST;
  Yii::$app->layout = 'report_layout';
  $this->pg = '/rggeneral/rg_vente/margelignevente/reportdtls.php';
  $formdataposted = (Yii::$app->session['formdataposted']) ? unserialize(Yii::$app->session['formdataposted']) : Null;
      // Convertissons les dates a des format conventionnels a la recherche
  $datefrom = Yii::$app->nonSqlClass->convert_date_to_sql_form($formdataposted['datefrom'], "M/D/Y");
  $dateto = Yii::$app->nonSqlClass->convert_date_to_sql_form($formdataposted['dateto'], "M/D/Y");
      // Recuperrons les infos basiques sur l'utilisateur connecte
  $userPrimaryData = Yii::$app->mainCLass->getUserAuthPrimaryInfo();
      // Formattons le code de l'article saisie dans le formulaire
  $catid = ($formdataposted['listcategories'] > 0) ? $formdataposted['listcategories'] : '';

  $listeservice = Yii::$app->parametreClass->listeserviceforrepport($formdataposted['listeservices'], $userPrimaryData['idEntreprise'], $userPrimaryData['typeUser'], $userPrimaryData['idEntite']);

      // Chargeons les data
  $sumarydata = Yii::$app->inventaireClass->showMargeLignesdeventeSumary($userPrimaryData['idEntreprise'], $datefrom, $dateto,$catid,$listeservice);
  $coredata = Yii::$app->inventaireClass->showVenteCoreDataBenefBrut($userPrimaryData['idEntreprise'], $datefrom, $dateto, $catid,$listeservice);
      //$dtlsdata = Yii::$app->inventaireClass->showLignesdeventeDtlsData($userPrimaryData['idEntreprise'], $datefrom, $dateto);
  return $this->render($this->pg,['msg'=>$msg, 'formdataposted'=>serialize($formdataposted), 'dtlsdata'=>$dtlsdata, 'coredata'=>$coredata, 'sumarydata'=>serialize($sumarydata), 'userPrimaryData'=>serialize($userPrimaryData)]);
}

    // Lignes de ventes
Public function actionLignesdevente(){
  $request=$msg=$datefrom=$dateto=$listarticles=$listeservice=$sumarydata=$coredata=$dtlsdata=Null;
  $request = $_POST;
  Yii::$app->layout = 'report_layout';
  $this->pg = '/rggeneral/rg_vente/lignesdevente/reportdtls.php';
  $formdataposted = (Yii::$app->session['formdataposted']) ? unserialize(Yii::$app->session['formdataposted']) : Null;
      // Convertissons les dates a des format conventionnels a la recherche
  $datefrom = Yii::$app->nonSqlClass->convert_date_to_sql_form($formdataposted['datefrom'], "M/D/Y");
  $dateto = Yii::$app->nonSqlClass->convert_date_to_sql_form($formdataposted['dateto'], "M/D/Y");
      // Recuperrons les infos basiques sur l'utilisateur connecte
  $userPrimaryData = Yii::$app->mainCLass->getUserAuthPrimaryInfo();

  $listeservice = Yii::$app->parametreClass->listeserviceforrepport($formdataposted['listeservices'], $userPrimaryData['idEntreprise'], $userPrimaryData['typeUser'], $userPrimaryData['idEntite']);


  $sumarydata = Yii::$app->inventaireClass->showLignesdeventeSumary($userPrimaryData['idEntreprise'], $datefrom, $dateto,$listeservice);
  $coredata = Yii::$app->inventaireClass->showVenteCoreDataForHistorique($userPrimaryData['idEntreprise'], $datefrom, $dateto,$listeservice);
  $dtlsdata = Yii::$app->inventaireClass->showLignesdeventeDtlsDataForHistorique($userPrimaryData['idEntreprise'], $datefrom, $dateto,$listeservice);
  return $this->render($this->pg,['msg'=>$msg, 'formdataposted'=>serialize($formdataposted), 'dtlsdata'=>$dtlsdata, 'coredata'=>$coredata, 'sumarydata'=>serialize($sumarydata), 'userPrimaryData'=>serialize($userPrimaryData)]);
}

    // Ventes par Produit / article
Public function actionVenteparproduit(){
  $request=$msg=$datefrom=$dateto=$listarticles=Null;
  $request = $_POST;
  Yii::$app->layout = 'report_layout';
  $this->pg = '/rggeneral/rg_vente/venteparproduit/reportdtls.php';
  $formdataposted = (Yii::$app->session['formdataposted']) ? unserialize(Yii::$app->session['formdataposted']) : Null;
      // Convertissons les dates a des format conventionnels a la recherche
  $datefrom = Yii::$app->nonSqlClass->convert_date_to_sql_form($formdataposted['datefrom'], "M/D/Y");
  $dateto = Yii::$app->nonSqlClass->convert_date_to_sql_form($formdataposted['dateto'], "M/D/Y");
      // Recuperrons les infos basiques sur l'utilisateur connecte
  $userPrimaryData = Yii::$app->mainCLass->getUserAuthPrimaryInfo();

  $listeservice = Yii::$app->parametreClass->listeserviceforrepport($formdataposted['listeservices'], $userPrimaryData['idEntreprise'], $userPrimaryData['typeUser'], $userPrimaryData['idEntite']);

  $data = Yii::$app->inventaireClass->showVenteparproduitCoreData($userPrimaryData['idEntreprise'], $datefrom, $dateto,$listeservice);
  return $this->render($this->pg,['msg'=>$msg, 'formdataposted'=>serialize($formdataposted), 'data'=>serialize($data), 'userPrimaryData'=>serialize($userPrimaryData)]);
}

    // Liste des article a stock initial
Public function actionArticlestokinitial(){
  $request=$msg=$datefrom=$dateto=$listarticles=Null;
  $request = $_POST;
  Yii::$app->layout = 'report_layout';
  $this->pg = '/rggeneral/rg_invent/articlestokinitial/reportdtls.php';
  $formdataposted = (Yii::$app->session['formdataposted']) ? unserialize(Yii::$app->session['formdataposted']) : Null;
      // Recuperrons les infos primaire de l'utilisateur connected
  $userPrimaryData = Yii::$app->mainCLass->getUserAuthPrimaryInfo();
      // Formattons le code de l'article saisie dans le formulaire
  $categoryId = (!empty($formdataposted['categoryId']) && $formdataposted['categoryId'] > 0) ? $formdataposted['categoryId'] : '';
  
  $data = Yii::$app->inventaireClass->showArticlestokinitialCoreData($userPrimaryData['idEntreprise'], $categoryId);
      // Recuperrons les infos basiques sur l'utilisateur connecte
  return $this->render($this->pg,['msg'=>$msg, 'formdataposted'=>serialize($formdataposted), 'data'=>serialize($data), 'userPrimaryData'=>serialize($userPrimaryData)]);
}

    // Liste des articles
public function actionInventlistarticle(){
  $request=$msg=$datefrom=$dateto=$listarticles=$listeservice=Null;
  $request = $_POST;
  Yii::$app->layout = 'report_layout';
  $this->pg = '/rggeneral/rg_invent/listearticle/reportdtls.php';
  $formdataposted = (Yii::$app->session['formdataposted']) ? unserialize(Yii::$app->session['formdataposted']) : Null;
      // Convertissons les dates a des format conventionnels a la recherche
  $datefrom = Yii::$app->nonSqlClass->convert_date_to_sql_form($formdataposted['datefrom'], "M/D/Y");
  $dateto = Yii::$app->nonSqlClass->convert_date_to_sql_form($formdataposted['dateto'], "M/D/Y");
      // Recuperrons les articles enregistres dans cet interval de temps
  $userPrimaryData = Yii::$app->mainCLass->getUserAuthPrimaryInfo();
      //Chargeons la liste des services
  $listeservice = Yii::$app->parametreClass->listeserviceforrepport($formdataposted['listeservices'], $userPrimaryData['idEntreprise'], $userPrimaryData['typeUser'], $userPrimaryData['idEntite']);
      //Chargeons la liste des articles
  $listarticles = Yii::$app->inventaireClass->showlisteArticleBaseDate($listeservice, $datefrom, $dateto);
      // Recuperrons les infos basiques sur l'utilisateur connecte
  return $this->render($this->pg,['msg'=>$msg, 'formdataposted'=>serialize($formdataposted), 'listarticles'=>serialize($listarticles), 'userPrimaryData'=>serialize($userPrimaryData)]);
}

    // Analyse d'article
public function actionInventanalysearticle(){
  $request=$msg=$datefrom=$dateto=$listarticles=Null;
  $request = $_POST;
  Yii::$app->layout = 'report_layout';
  $this->pg = '/rggeneral/rg_invent/analysearticle/reportdtls.php';
  $formdataposted = (Yii::$app->session['formdataposted']) ? unserialize(Yii::$app->session['formdataposted']) : Null;
      // Recuperrons les articles enregistres dans cet interval de temps
  $userPrimaryData = Yii::$app->mainCLass->getUserAuthPrimaryInfo();

  $listeservice = Yii::$app->parametreClass->listeserviceforrepport($formdataposted['listeservices'], $userPrimaryData['idEntreprise'], $userPrimaryData['typeUser'], $userPrimaryData['idEntite']);

  $listarticles = Yii::$app->inventaireClass->showanalyseArticleBaseDate($userPrimaryData['idEntreprise'],$listeservice);
      // Recuperrons les infos basiques sur l'utilisateur connecte
  return $this->render($this->pg,['msg'=>$msg, 'formdataposted'=>serialize($formdataposted), 'listarticles'=>serialize($listarticles), 'userPrimaryData'=>serialize($userPrimaryData)]);
}

    //Historique d'articles
public function actionInventhistoriquearticle(){
  $request=$msg=$datefrom=$dateto=$listarticles=Null;
  $request = $_POST;
  Yii::$app->layout = 'report_layout';
  $this->pg = '/rggeneral/rg_invent/historiquearticle/reportdtls.php';
  $formdataposted = (Yii::$app->session['formdataposted']) ? unserialize(Yii::$app->session['formdataposted']) : Null;
      // Convertissons les dates a des format conventionnels a la recherche
  $datefrom = Yii::$app->nonSqlClass->convert_date_to_sql_form($formdataposted['datefrom'], "M/D/Y");
  $dateto = Yii::$app->nonSqlClass->convert_date_to_sql_form($formdataposted['dateto'], "M/D/Y");
      // Recuperrons les articles enregistres dans cet interval de temps
  $userPrimaryData = Yii::$app->mainCLass->getUserAuthPrimaryInfo();
      // Formattons le code de l'article saisie dans le formulaire
  $code = (!empty($formdataposted['code'])) ? $formdataposted['code'] : '';

  $listeservice = Yii::$app->parametreClass->listeserviceforrepport($formdataposted['listeservices'], $userPrimaryData['idEntreprise'], $userPrimaryData['typeUser'], $userPrimaryData['idEntite']);


  $data = Yii::$app->inventaireClass->showhistoriqueArticleCoreData($userPrimaryData['idEntreprise'], $datefrom, $dateto, $code, $listeservice);
      // Recuperrons les infos basiques sur l'utilisateur connecte
  return $this->render($this->pg,['msg'=>$msg, 'formdataposted'=>serialize($formdataposted), 'data'=>serialize($data), 'userPrimaryData'=>serialize($userPrimaryData)]);
}

    //article en alerte de stock
public function actionInventarticlealertstok(){
  $request=$msg=$reportdata = $categoryId = Null;
  $request = $_POST;
  Yii::$app->layout = 'report_layout';
  $this->pg = '/rggeneral/rg_invent/articlealertstok/reportdtls.php';
  $formdataposted = (Yii::$app->session['formdataposted']) ? unserialize(Yii::$app->session['formdataposted']) : Null;
      // Recuperrons les articles enregistres dans cet interval de temps
  $userPrimaryData = Yii::$app->mainCLass->getUserAuthPrimaryInfo();
      // Formattons le code de l'article saisie dans le formulaire
  $categoryId = (!empty($formdataposted['categoryId']) && $formdataposted['categoryId'] > 0) ? $formdataposted['categoryId'] : '';

  $listeservice = Yii::$app->parametreClass->listeserviceforrepport($formdataposted['listeservices'], $userPrimaryData['idEntreprise'], $userPrimaryData['typeUser'], $userPrimaryData['idEntite']);


  $data = Yii::$app->inventaireClass->showArticleAlertstokCoreData($userPrimaryData['idEntreprise'], $categoryId, $listeservice);
      // Recuperrons les infos basiques sur l'utilisateur connecte
  return $this->render($this->pg,['msg'=>$msg, 'formdataposted'=>serialize($formdataposted), 'data'=>serialize($data), 'userPrimaryData'=>serialize($userPrimaryData)]);
}

}
?>
