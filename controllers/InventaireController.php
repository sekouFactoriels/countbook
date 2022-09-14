<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\helpers\Console;

class InventaireController extends Controller
{
  private $pg = Null;
  private $msg = Null;


  // METHODE : REAPROVISIONEMENT
  public function actionReaprovision()
  {
    $dataPosted = null;
    $UserAuthPrimaryInfo = Yii::$app->mainCLass->getUserAuthPrimaryInfo();
    $entite_id = $UserAuthPrimaryInfo['idEntite'];
    $adminarray = Yii::$app->params['usersToEditSales'];
    $idservicesuser = base64_encode(in_array($UserAuthPrimaryInfo['typeUser'], $adminarray) ? '0' : $UserAuthPrimaryInfo['idEntite']);


    $userPrimaryData = Yii::$app->mainCLass->getUserAuthPrimaryInfo();
    $idEntreprise = $userPrimaryData['idEntreprise'];
    $idactor = $userPrimaryData['auhId'];
    $userLevel = $userPrimaryData['typeUser'];
    $idEntite = $userPrimaryData['idEntite'];

    /*****************/
    /** GET REQUEST **/
    /*****************/
    if (Yii::$app->request->isGet) {
      // Ajax : ajouter le produit au panier de vente
      if (isset($_GET['selectedDiv']) && isset($_GET['addToReaprovisionnementBaquet']) && $_GET['addToReaprovisionnementBaquet'] == md5('onemore')) {
        // Recuperrons la valeur de l'element selectionner
        $selectectedELement = json_decode(base64_decode($_GET['selectedDiv']));
        $rslt = '<tr id="row_' . $selectectedELement->slimproductid . '" class="eachrow">
                      <td><input type="hidden" Class="totalBought" name="sousTotalAchat[]" id="sousTotalAchat_' . $selectectedELement->slimproductid . '" value="">&#8226;<input autocomplete="off" type="hidden" name="selectectedELement[]" id="selectectedELement" value="' . $_GET['selectedDiv'] . '"></td>
                      <td>' . $selectectedELement->productCode . ' : ' . $selectectedELement->libelle . '</td>
                      <td>' . Yii::$app->inventaireClass->getUdmLabel($selectectedELement->udm) . '</td>
                      <td>' . $selectectedELement->qteDispo . '</td>
                      <td><input autocomplete="off" class="form-control" onKeyup="calculSubTotal(this.id,' . $selectectedELement->qteDispo . ',  ' . $selectectedELement->prixUnitaireVente . ', ' . $selectectedELement->slimproductid . ',' . $selectectedELement->prixUnitaireAchat . ')" name="qte_reaprovisionner[]" id="qte_reaprovisionner_' . $selectectedELement->slimproductid . '" value=""></td>
                      <td><input autocomplete="off" class="form-control totalmustpay" onKeypress="return false;" name="sousTotalLabel[]" id="sousTotalLabel_' . $selectectedELement->slimproductid . '"></td>
                      <td><a id="' . $selectectedELement->slimproductid . '" class="btn btn-danger" onclick="delete_Row(this.id)"><i Class="fa fa-times">&nbsp;</i></a></td>
                    </tr>
                  ';
        return $rslt;
      }

      // Ajax : get product unit price
      if (isset($_GET['getProductUnitPrice']) && $_GET['getProductUnitPrice'] == '1' && isset($_GET['productValue'])) {
        $productValue = $_GET['productValue'];
        $productValue = json_decode(base64_decode($productValue));
        return $productValue->prixUnitaireVente;
      }

      // Ajax : recuper la quantite
      if (isset($_GET['getProductUnitPrice']) && $_GET['getProductUnitPrice'] == '2' && isset($_GET['quantiteProduit'])) {
        $quantiteProduit = $_GET['quantiteProduit'];
        $quantiteProduit = json_decode(base64_decode($productValue));
        return $quantiteProduit->qteDispo;
      }

      # AJAX STATEMENT : GENERER UN NOUVEAU CODE
      if (isset($_GET[Yii::$app->params['newVenteSimple']])) {
        $ajax_action_rslt = Null;
        $connect  = \Yii::$app->db;
        $rslt = $connect->createCommand('SELECT code FROM slim_generatedCode WHERE status=:status')
          ->bindValue(':status', 1)
          ->queryOne();
        if (isset($rslt)) {
          $ajax_action_rslt = $rslt['code'];
        }
        return $ajax_action_rslt;
      }
    }

    /******************/
    /** POST REQUEST **/
    /******************/
    if (Yii::$app->request->isPost) {
      $request = $_POST;
      /** Analysons la valeur attribue a action_key **/

      switch ($request['action_key']) {
          /** ENREGISTRER LE REAPROVISIONNEMENT **/
        case md5('enregistrer_reaprovisionnement'):
          //Initialiser les variables du choix
          $requirement = $stocktopup_bill_saved = $pj_mode_paiement = $product_selected = $product_selected = $bill_paiement = $product_in_array = $product_in_array_for_update = Null;

          //Analyser si les inperatifs sont respectes
          if ((isset($request['fournisseur']) && ($request['fournisseur'] > 0)) && (isset($request['totalMonetaire']) && ($request['totalMonetaire'] != '')) && (isset($request['remiseMonetaire']) && ($request['remiseMonetaire'] != '')) && (isset($request['montantFinal']) && ($request['montantFinal'] != '')) && (isset($request['montantpaye']) && ($request['montantpaye'] != ''))) {
            $requirement = 1;
          } else {
            Yii::$app->session->setFlash('flashmsg', '<div class="alert alert-danger"> <strong> <i class="fa fa-exclamation-circle">&nbsp;</i> </strong>&nbsp;' . Yii::t("app", "champObligatoire") . '</div>');
            return $this->redirect(Yii::$app->request->baseUrl . '/' . md5('inventaire_reaprovision'));
          }

          //Enregistrer la facture
          if ($requirement == 1) {
            //Generer le numéro de la facture
            $numero_facture = yii::$app->mainCLass->get_orderer_number(strtolower('facture_fournisseur'));

            //Continuer l'enregistrement
            $stocktopup_bill_saved = yii::$app->inventaireClass->enrg_bill($request, $idEntreprise, $idEntite, $idactor, $numero_facture);
          }

          //Enregistrer le paiement
          if ($stocktopup_bill_saved) {
            if (isset($request['banque_denomination']) && $request['banque_denomination'] != '') {
              $pj_mode_paiement = $request['banque_denomination'];
            }

            if (isset($request['numero_cheque']) && $request['numero_cheque'] != '') {
              $pj_mode_paiement = $request['numero_cheque'];
            }

            $bill_paiement = yii::$app->inventaireClass->enrg_bill_paiement($request['fournisseur'], $stocktopup_bill_saved, (float)($request['montantpaye']), (float)($request['montantdette']), $request['mode_paiement'], $idEntreprise, $idEntite, $idactor, $numero_facture, $pj_mode_paiement);
          }


          //Enregistrer l'historique de reaprovisionement
          if ($bill_paiement && is_array($request['qte_reaprovisionner'])) {
            for ($i = 0; $i < sizeof($request['qte_reaprovisionner']); $i++) {
              //Preparer la tableau de données à enregistrer
              $product_selected = json_decode(base64_decode($request['selectectedELement'][$i]));
              $operation_date = Yii::$app->nonSqlClass->convert_date_to_sql_form($request['operationdate'], "M/D/Y");

              $idMaptype = 1;
              $product_in_array[$i] = [intval($product_selected->slimproductid), $idMaptype, $numero_facture, $operation_date, intval($request['qte_reaprovisionner'][$i]), $idEntreprise, $idEntite, $idactor];

              $product_in_array_for_update[$i] = [intval($product_selected->slimproductid), $idMaptype, $numero_facture, $operation_date, intval($request['qte_reaprovisionner'][$i]), intval($product_selected->qteDispo), $idEntreprise, $idEntite, $idactor];
            }
          }

          //Mettre à jour les produits selectionés
          if ($product_in_array) {
            //Enregistrer l'historique des stocks reaprovisionnés
            yii::$app->inventaireClass->enrg_as_map_stocktopedup($product_in_array);

            //Mettre à jour les articles dans le stock
            yii::$app->inventaireClass->update_in_entrepot_stocktopedup($product_in_array_for_update, $idactor);
          }

          //Preparer le message d'affiche 
          Yii::$app->session->setFlash('flashmsg', '<div class="alert alert-success"> <strong> <i class="fa fa-exclamation-circle">&nbsp;</i> </strong>&nbsp;' . Yii::t("app", "operatSucess") . '</div>');
          return $this->redirect(Yii::$app->request->baseUrl . '/' . md5('inventaire_reaprovision'));
          break;

        case md5('enregistrer_fournisseur'):

          $UserAuthPrimaryInfo = Yii::$app->mainCLass->getUserAuthPrimaryInfo();
          $user_id = $UserAuthPrimaryInfo['auhId'];
          $entreprise_id = $UserAuthPrimaryInfo['idEntreprise'];
          $entite_id = $UserAuthPrimaryInfo['idEntite'];

          if (isset($request['raison_sociale']) && $request['raison_sociale'] != '' && isset($request['denomination']) && $request['denomination'] != '' && isset($request['telephone']) && $request['telephone'] != '') {
            /** Verifier si le fournisseur n'est pas deja enregistré **/
            $nouveau_fourn = yii::$app->fournisseurClass->fournisseur_est_nouveau($request);

            //Afficher le msg de duplication
            if (!isset($nouveau_fourn)) {
              Yii::$app->session->setFlash('flashmsg', '<div class="alert alert-danger"> <strong> <i class="fa fa-exclamation-circle">&nbsp;</i> </strong>&nbsp;' . Yii::t("app", "operatSucess") . '</div>');
              $this->pg = '/inventaire/produit/reaprovision.php';
              return $this->render($this->pg, ['request' => $request]);
            }

            /** Enregistrer le fournisseur **/
            $saved_fourn = yii::$app->fournisseurClass->enregisrer_fourn($request, $user_id, $entreprise_id, $entite_id);

            //AFFICHER LE MSG D'ENREGISTREMENT 
            Yii::$app->session->setFlash('flashmsg', '<div class="alert alert-success"> <strong> <i class="fa fa-exclamation-circle">&nbsp;</i> </strong>&nbsp;' . Yii::t("app", "operatSucess") . '</div>');
            $this->pg = '/inventaire/produit/reaprovision.php';
          } else {
            //Afficher le msg de champs vides
            Yii::$app->session->setFlash('flashmsg', '<div class="alert alert-warning"> <strong> <i class="fa fa-exclamation-circle">&nbsp;</i> </strong>&nbsp;' . Yii::t("app", "doneesForcesVides") . '</div>');
            $this->pg = '/inventaire/produit/reaprovision.php';
          }
          break;
      }
    }

    /********************/
    /** DEFAULT ACTION **/
    /********************/

    $idservices_user = base64_encode(in_array($UserAuthPrimaryInfo['typeUser'], $adminarray) ? '0' : $UserAuthPrimaryInfo['idEntite']);
    $id_entite = Yii::$app->parametreClass->listeserviceforrepport($idservices_user, $UserAuthPrimaryInfo['idEntreprise'], $UserAuthPrimaryInfo['typeUser'], $UserAuthPrimaryInfo['idEntite']);

    if($userPrimaryData['typeUser']==9){
       $produits = Yii::$app->inventaireClass->getProductdtlsForSaleBaseEntreprise($id_entite);
    }else{
        $produits = Yii::$app->inventaireClass->getProductdtlsForSaleBaseEntreprise($UserAuthPrimaryInfo['idEntite']);
    }
    
    
    $fournisseurs = yii::$app->fournisseurClass->lister_fournisseurs($entite_id);
    $this->pg = '/inventaire/produit/reaprovision.php';
    $numero_facture = yii::$app->mainCLass->get_orderer_number(strtolower('facture_fournisseur'));
    $banques = yii::$app->mainCLass->charger_banques_entreprise($idEntreprise);
    return $this->render($this->pg, ['fournisseurs' => $fournisseurs, 'produits' => $produits, 'dataPosted' => $dataPosted, 'numero_facture' => $numero_facture, 'banques' => $banques]);
  }

  // LISTE DES UDMS PAS PRODUIT
  public function actionUdms()
  {
    $entrepriseUdms = Null;
    # INITIALISATION OF SOME VARIABLES
    $this->pg = '/inventaire/productUdms/productUdms';
    $connect = \Yii::$app->db;
    # GET USER AUTH PRIMARY INFOS
    $UserAuthPrimaryInfo = Yii::$app->mainCLass->getUserAuthPrimaryInfo();
    // if (isset($_GET['productUdmName'])){
    //   echo $_GET['productUdmName'];
    // }

    # AJAX STATEMENT : NEW CATEORIE
    if (!empty($_GET['productUdmName']) && Yii::$app->inventaireClass->avoiDoublonUdm($_GET['productUdmName'], $UserAuthPrimaryInfo['idEntreprise']) == Null) {

      $groupData = Null;
      $productUdmName = $_GET['productUdmName'];
      $productUdmDesc = $_GET['productUdmDesc'];

      $insertStmt = Yii::$app->inventaireClass->insertProductUdm($productUdmName, $productUdmDesc);

      # INSERTION PROCESS
      /* $insertStmt = $connect->createCommand('INSERT INTO slim_productudm (nom, description, idEntreprise) VALUES (:nom, :description, :idEntreprise)')
        ->bindValues([':nom' => $productUdmName, ':description' => $productUdmDesc, ':idEntreprise' => $UserAuthPrimaryInfo['idEntreprise']])
        ->execute();
      if (isset($insertStmt) && $_GET[Yii::$app->params['ajax_action_key']] == md5('newGroup')) {
        #### EVENEMENT : TYPE : Creation d'un group de produit ###
        $event_msg = Yii::t('app', 'msg_creationgroup') . '&nbsp;:&nbsp;' . $productGroupName;
        Yii::$app->mainCLass->creerEvent('010', $event_msg);
        # ACTION TO DO WHEN THE CURRENT ACTION IS CATS (IN THE CATEGORIE PANEL)
        if (Yii::$app->controller->action->id == 'groups' && $_GET[Yii::$app->params['action_key']] == md5('listGroups')) {
          return 'refreshPage';
        }
        # GET ALL GROUPS
        $entrepriseCats = Yii::$app->inventaireClass->getGroupBaseEntrepriseId($UserAuthPrimaryInfo['idEntreprise']);
        if (is_array($entrepriseCats) && sizeof($entrepriseCats) > 0) {
          $groupData = '<option value="0">Selectionne Un</option>';
          foreach ($entrepriseCats as $data) {
            $groupData .= '<option value="' . $data["id"] . '">' . $data["nom"] . '</option>';
          }
        }
      }
      return $groupData; */
    }

    if (Yii::$app->request->isPost) {
      $request = $_POST;
      //Analyse de l'action_key
      switch ($request['action_key']) {
        case md5('inventaire_updateUdm'):
          $action_on_this = base64_decode($request['action_on_this']);
          $nom = $request['productUdmName'];
          $desc = $request['productUdmDesc'];
          $updateStmt = Yii::$app->inventaireClass->updateProductUdm($nom, $desc, $action_on_this);
          break;
      }
    }

    # GET ALL PRODUCTS
    $entrepriseUdms = Yii::$app->inventaireClass->getUdmBaseEntrepriseId($UserAuthPrimaryInfo['idEntreprise']);
    return $this->render($this->pg, ['entrepriseUdms' => $entrepriseUdms]);
  }


  # LISTE DES CATEGORIES
  public function actionCats()
  {
    # INITIALISATION OF SOME VARIABLES
    $this->pg = '/inventaire/productCategory/prodcutCategories';
    $connect = \Yii::$app->db;
    # GET USER AUTH PRIMARY INFOS
    $UserAuthPrimaryInfo = Yii::$app->mainCLass->getUserAuthPrimaryInfo();
    # AJAX STATEMENT : NEW CATEORIE
    if (!empty($_GET[Yii::$app->params['productCatName']])) {
      $catData = Null;
      $productCatName = $_GET[Yii::$app->params['productCatName']];
      $productCatDesc = $_GET[Yii::$app->params['productCatDesc']];
      # INSERTION PROCESS
      $insertStmt = $connect->createCommand('INSERT INTO slim_productcategorie (nom, description, entrepriseId) VALUES (:nom, :description, :entrepriseId)')
        ->bindValues([':nom' => $productCatName, ':description' => $productCatDesc, ':entrepriseId' => $UserAuthPrimaryInfo['idEntreprise']])
        ->execute();
      if (isset($insertStmt) && $_GET[Yii::$app->params['ajax_action_key']] == md5('newCat')) {
        #### EVENEMENT : TYPE : Creation d'une categorie de produit ###
        $event_msg = Yii::t('app', 'msg_creationcat') . '&nbsp;:&nbsp;' . $productCatName;
        Yii::$app->mainCLass->creerEvent('011', $event_msg);
        # ACTION TO DO WHEN THE CURRENT ACTION IS CATS (IN THE CATEGORIE PANEL)
        if (Yii::$app->controller->action->id == 'cats' && $_GET[Yii::$app->params['action_key']] == md5('listCats')) {
          return $_GET[Yii::$app->params['action_key']];
        }
        $entrepriseCats = Yii::$app->inventaireClass->getCatBaseEntrepriseId($UserAuthPrimaryInfo['idEntreprise']);
        if (is_array($entrepriseCats) && sizeof($entrepriseCats) > 0) {
          $catData = '<option value="0">Selectionne Un</option>';
          foreach ($entrepriseCats as $data) {
            $catData .= '<option value="' . $data["id"] . '">' . $data["nom"] . '</option>';
          }
        }
      }
      return $catData;
    }

    #Update product Categorie
    if (Yii::$app->request->isPost) {
      $request = $_POST;
      //Analyse de l'action_key
      switch ($request['action_key']) {
        case md5('inventaire_updateCat'):
          $action_on_this = base64_decode($request['action_on_this']);
          $nom = $request['productCatName'];
          $desc = $request['productCatDesc'];
          $statut = $request['statutCat'];
          $updateStmt = Yii::$app->inventaireClass->updateProductCat($nom, $desc, $statut, $action_on_this);
          break;
      }
    }

    # GET ALL PRODUCTS
    $entrepriseCats = Yii::$app->inventaireClass->getCatBaseEntrepriseId($UserAuthPrimaryInfo['idEntreprise']);
    return $this->render($this->pg, ['entrepriseCats' => $entrepriseCats]);
  }

  /** Methode de mise a jour d'un produit **/
  public function actionProduit()
  {
    /** Ajax action : ajustement du stock d'un produit **/
    if (Yii::$app->request->isGet) {
      if (isset($_GET[Yii::$app->params['action_key']]) && $_GET[Yii::$app->params['action_key']] == md5('ajustmentunitaireproduit') && isset($_GET[Yii::$app->params['thisProductId']]) && isset($_GET[Yii::$app->params['udmProduct']]) && isset($_GET[Yii::$app->params['qteAjouter']])  && isset($_GET[Yii::$app->params['typeAjustation']])) {
        $operationAjustment = Yii::$app->inventaireClass->operationAjustment($_GET[Yii::$app->params['thisProductId']], $_GET[Yii::$app->params['udmProduct']], $_GET[Yii::$app->params['typeAjustation']], $_GET[Yii::$app->params['qteAjouter']]);
        if ($operationAjustment) { # APRES AJUSTEMENT DU ST
        }
        return $operationAjustment;
      }
    }
    return $this->redirect(Yii::$app->request->baseUrl . '/' . md5('inventaire_produits'));
  }

  /** Methode : Liste des produits **/
  public function actionProduits()
  {
    $msg = $produitDtls = $produitUdm = $productFabricant = $productGenericName =  $productGroupes = $historReapp = $Article = Null;
    $request = $_POST;

    #RECUPERONS LES INFOS PRIMAIRE D'UN UTILISATEUR
    $userPrimaryData = Yii::$app->mainCLass->getUserAuthPrimaryInfo();

    $critere = (isset($request['selectCriteria'])) ? $_POST['selectCriteria'] : 1;
    $donneeRecherche = (isset($request[Yii::$app->params['donneeRecherche']])) ? $_POST[Yii::$app->params['donneeRecherche']] : '';
    $limit = (isset($request[Yii::$app->params['limit']])) ? $_POST[Yii::$app->params['limit']] : 1;

    $adminarray = Yii::$app->params['usersToEditSales'];

    $idservicesuser = base64_encode(in_array($userPrimaryData['typeUser'], $adminarray) ? '0' : $userPrimaryData['idEntite']);

    $idEntite = Yii::$app->parametreClass->listeserviceforrepport($idservicesuser, $userPrimaryData['idEntreprise'], $userPrimaryData['typeUser'], $userPrimaryData['idEntite']);

    if($userPrimaryData['typeUser']==9){
       $listProduits = Yii::$app->inventaireClass->getProducts($critere, $donneeRecherche, $limit, $idEntite);
    }else{
       $listProduits = Yii::$app->inventaireClass->getProducts($critere, $donneeRecherche, $limit, $userPrimaryData['idEntite']);
    }
    
    // RECUPERONS LES PRODUCTS CATEGORIES
    $productCategories = Yii::$app->inventaireClass->getCategories($userPrimaryData['idEntreprise']);

    // RECUPERONS LES PRODUCTS MARQUES
    $productMarque = Yii::$app->inventaireClass->getProductMarque($userPrimaryData['idEntreprise']);

    // RECUPERONS LA LISTE DE TOUS LES PRODUITS DANS UN ENTREPOT
    $entrepotProductQte = Yii::$app->inventaireClass->getAllProductQteInEntrepot($userPrimaryData['idEntreprise']);
    // Recuperons l'udm du produit
    $productUdm = Yii::$app->inventaireClass->getUdmBaseEntrepriseId($userPrimaryData['idEntreprise']);
    $this->pg = '/inventaire/produit/produits';

    /** Condition quand nous demarrons le processus d'enregistrement de la nouvelle quantite de stock de produit **/
    if (isset($request[Yii::$app->params['action_key']]) && isset($request['ttprixachat']) && $request['ttprixachat'] != Null && $request[Yii::$app->params['action_key']] == base64_encode(strtolower("enrgAjoutsQte")) && isset($request[Yii::$app->params['action_on_this']])) {
      if (Yii::$app->session['token2'] != $_POST['token2']) {
        $request = $_POST;

        //CONVERT DATE
        $operationdate = Yii::$app->nonSqlClass->convert_date_to_sql_form($request['operationdate'], "M/D/Y");

        $stmt = Yii::$app->inventaireClass->updateProductQte($request['action_on_this'], $request['qteDispoToBeSend'], $request['qteAajouter'], $request['udmToBeSend'],  $request['ttprixachat'], $userPrimaryData['idEntreprise'], $operationdate);

        if ($stmt) {
          #### EVENEMENT : TYPE : augmentation de la quantite entrepose en stok ###
          $event_msg = Yii::t('app', 'msg_augmentationqteproduct1') . '&nbsp;' . $request['productName'] . '&nbsp;' . Yii::t('app', 'msg_augmentationqteproduct2') . '&nbsp;' . $request['qteAajouter'];
          Yii::$app->mainCLass->creerEvent('009', $event_msg);
          /** Evitons la duplication du dernier enregistrement **/
          Yii::$app->session->set(Yii::$app->params['tok2'], $_POST[Yii::$app->params['tok2']]);
          $msg = serialize(['type' => 'alert alert-success', 'strong' => Yii::t('app', 'success'), 'text' => Yii::t('app', 'modifSuccess')]); ##### PREPARATION DU MESSAGE
        }
      }
    }
    /** Condition quand nous demarrons le processus d'ajout a la qte de stok d'un produit **/
    if (isset($request[Yii::$app->params['action_key']]) && $request[Yii::$app->params['action_key']] == base64_encode(strtolower("addToQteDispo")) && isset($request[Yii::$app->params['action_on_this']])) {
      $produitDtls = Yii::$app->inventaireClass->produitDtls($request[Yii::$app->params['action_on_this']]);
      $this->pg = '/inventaire/produit/addToQte_produit_dispo';
    }

    /** Condition quand nous demarrons le processus d'edition **/
    if (isset($request[Yii::$app->params['action_key']]) && $request[Yii::$app->params['action_key']] == base64_encode(strtolower("editionProduit")) && isset($request[Yii::$app->params['action_on_this']])) {
      $this->pg = '/inventaire/produit/produit';
      $produitDtls = Yii::$app->inventaireClass->produitDtls($request[Yii::$app->params['action_on_this']]);
      # Recuperons le groupe du produit
      $productGroupes = Yii::$app->inventaireClass->getProductGroup($userPrimaryData['idEntreprise']);
      # Recuperons les products generiques names
      $productGenericName = Yii::$app->inventaireClass->getProductGenricName($userPrimaryData['idEntreprise']);
      /*** RECUPERONS LES PRODUCTS FABRICANTS ***/
      $productFabricant = Yii::$app->inventaireClass->getProductMarqueFabricant($userPrimaryData['idEntreprise']);
      //liste des udm
      $productUdm = Yii::$app->inventaireClass->getUdmBaseEntrepriseId($userPrimaryData['idEntreprise']);
    }

    /** detailreappro**/
    if (isset($request[Yii::$app->params['action_key']]) && $request[Yii::$app->params['action_key']] == base64_encode(strtolower("detailappro")) && isset($request[Yii::$app->params['action_on_this']])) {

      // recuperation de l'article
      $connect  = \Yii::$app->db;
      $Article = $connect->createCommand('SELECT slim_product.libelle AS libelle, slim_productcategorie.nom AS categorie, slim_productudm.nom AS uva
                                       FROM slim_product,slim_productcategorie,slim_productudm,slim_stockentrepot
                                       WHERE slim_product.idEntreprise=:idEntreprise
                                       AND slim_product.categoryId=slim_productcategorie.id
                                       AND slim_productudm.id=slim_stockentrepot.udm
                                       AND slim_stockentrepot.idProduct=:idProd
                                       AND slim_product.idEntite=:idEntite
                                       AND slim_product.id=:idArticle')
        ->bindValues([':idEntreprise' => $userPrimaryData['idEntreprise'], ':idEntite' => $userPrimaryData['idEntite'], ':idArticle' => $request[Yii::$app->params['action_on_this']], ':idProd' => $request[Yii::$app->params['action_on_this']]])
        ->queryOne();

      // liste des reappros
      $historReapp = Yii::$app->inventaireClass->histoReappro($request[Yii::$app->params['action_on_this']]);
      $this->pg = '/inventaire/produit/detailreapro';
    }

    /** Condition quand nous enregistrons les modification apportees **/
    if (isset($request[Yii::$app->params['action_key']]) && $request[Yii::$app->params['action_key']] == base64_encode(strtolower("enrgmodifis")) && isset($request[Yii::$app->params['action_on_this']])) {

      if (Yii::$app->session['token2'] != $_POST['token2']) {
        // die(print_r('bingooo'));
        $request = $_POST;
        $stmt = Yii::$app->inventaireClass->updateProduct($request['action_on_this'], $request[Yii::$app->params['productType']],  $request[Yii::$app->params['productName']], $request[Yii::$app->params['productCategory']],  $request[Yii::$app->params['group']],  $request[Yii::$app->params['productMarque']],  $request[Yii::$app->params['generiqueNameId']], $request[Yii::$app->params['productPrixAchat']], $request[Yii::$app->params['productPrixVente']]);
        if ($stmt) {
          #### EVENEMENT : TYPE : MISE A JOUR DE PRODUIT ###
          $event_msg = Yii::t('app', 'msg_updateproduct1') . '&nbsp;' . $request[Yii::$app->params['productName']];
          Yii::$app->mainCLass->creerEvent('008', $event_msg);
          // Eviter la duplication du dernier enregistrement
          Yii::$app->session->set(Yii::$app->params['tok2'], $_POST[Yii::$app->params['tok2']]);
          $msg = serialize(['type' => 'alert alert-success', 'strong' => Yii::t('app', 'success'), 'text' => Yii::t('app', 'modifSuccess')]); ##### PREPARATION DU MESSAGE
        }
      }
    }
    /** Accedons a la vue now **/
    return $this->render($this->pg, ['msg' => $msg, 'productFabricant' => $productFabricant, 'productGenericName' => $productGenericName, 'productGroupes' => $productGroupes, 'produitDtls' => $produitDtls, 'produitUdm' => $productUdm, 'listProduits' => $listProduits, 'productCategories' => $productCategories, 'productMarque' => $productMarque, 'entrepotProductQte' => $entrepotProductQte, 'historReapp' => $historReapp, 'Article' => $Article]);
  }

  /** Methode : Nouveau Produit **/
  public function actionNproduit()
  {
    $this->pg = '/inventaire/produit/nproduit';
    /*** RECUPERONS LES INFOS PRIMAIRE D'UN UTILISATEUR ***/
    $userPrimaryData = Yii::$app->mainCLass->getUserAuthPrimaryInfo();
    /*** RECUPERER LA LISTE DES ENTITES DUNE ENTREPRISE ***/
    $entite = Yii::$app->parametreClass->getentreprises_services($userPrimaryData['idEntreprise'], $userPrimaryData['iswiz']);
    /*** RECUPERONS LES PRODUCTS CATEGORIES ***/
    $productCategories = Yii::$app->inventaireClass->getCategories($userPrimaryData['idEntreprise']);
    /*** RECUPERONS LES PRODUCTS GROUPS ***/
    $productGroupes = Yii::$app->inventaireClass->getProductGroup($userPrimaryData['idEntreprise']);
    /***  RECUPERONS LES PRODUCTS MARQUES  ***/
    $productMarque = Yii::$app->inventaireClass->getProductMarque($userPrimaryData['idEntreprise']);
    /*** RECUPERONS LES PRODUCTS FABRICANTS ***/
    $productFabricant = Yii::$app->inventaireClass->getProductMarqueFabricant($userPrimaryData['idEntreprise']);
    /*** RECUPERONS LES NOMS GENRICS DES PRODUCTS ***/
    $ProductGenricName = Yii::$app->inventaireClass->getProductGenricName($userPrimaryData['idEntreprise']);
    /*** RECUPERONS LES UDM  ***/
    $productUdm = Yii::$app->inventaireClass->getUdmBaseEntrepriseId($userPrimaryData['idEntreprise']);


    # AJAX STATEMENT : GENERER UN NOUVEAU CODE
    if (isset($_GET['newCodeProduit']) && $_GET['newCodeProduit'] == '1') {
      $ajax_action_rslt = $new_sys_prod_code = $new_stock_prod_code = Null;

      $id_entreprise = $entite[0]['idEntreprise'];
      $first_word = strtoupper(substr($entite[0]['nom'], 0, 2));

        //Trouver nouveaux codes du systeme & l'espace de gestion
      $new_sys_prod_code = yii::$app->inventaireClass->get_sys_product_code();
      $new_stock_prod_code = yii::$app->inventaireClass->get_espace_g_product_code($id_entreprise);

      $new_code = $first_word.$new_stock_prod_code.$new_sys_prod_code;
      return $new_code;
    }

    # POSTING FORM
    if (Yii::$app->request->isPost) {
      if (Yii::$app->session['token2'] != $_POST['token2']) {
        $successfulSave = false;
        $request = $_POST;
        $params = Yii::$app->params;
        #CHECK IF REQUIRED FIELDS ARE FILLED
        if (
          !empty($request[$params['productCode']])
          && isset($request[$params['productType']])
          && !empty($request[$params['productName']])
          && !empty($request[$params['productCategory']])
        ) {
          # BEGIN : SAVING PROCESS
          #LETS FINDOUT MORE ABOUT THE STATUT OF THE PRODUCT
          $statut = ($request[$params['prodcutQte']] > 0) ? 1 : Null;
          # PREPARATION DE L'IMAGE

          # SAVING BASIC DATA
          $connect = \Yii::$app->db;
          $newProductStmt = $connect->createCommand('INSERT INTO slim_product (productCode, type, libelle, categoryId, groupId, markId, generiqueId, statut, prixUnitaireAchat, prixUnitaireVente, idEntreprise, idEntite)
                                                          VALUES (:productCode, :type, :libelle, :categoryId, :groupId, :markId, :generiqueId, :statut, :prixUnitaireAchat, :prixUnitaireVente, :idEntreprise, :idEntite)')
            ->bindValues([':productCode' => $request[$params['productCode']], ':type' => $request[$params['productType']], ':libelle' => $request[$params['productName']], ':categoryId' => $request[$params['productCategory']], ':groupId' => $request[$params['group']], ':markId' => $request[$params['productMarque']], ':generiqueId' => $request[$params['generiqueNameId']], ':statut' => $statut, ':prixUnitaireAchat' => $request[$params['productPrixAchat']], ':prixUnitaireVente' => $request[$params['productPrixVente']], ':idEntreprise' => $userPrimaryData['idEntreprise'], ':idEntite' => $request['entite']])
            ->execute();
          if (isset($newProductStmt)) {
            # UPDATE THE CODE GIVING TO THE PRODUCT
            $updateCodeStmt = Yii::$app->inventaireClass->updateUsedCode($request[$params['productCode']]);
            if (isset($updateCodeStmt)) {
              # Get new code
              $gotProductId = Yii::$app->inventaireClass->getProductIdFromCode($request[$params['productCode']]);
              if (isset($gotProductId)) {
                # CREATION AN ENTREPOT STOCK FOR NEW PRODUCT
                $newEntrepotStock = Yii::$app->inventaireClass->newEntrepotStock($gotProductId, $request[$params['prodcutQte']], $request[$params['prodcutMinQteEntrep']], $request[$params['prodcutMinQtePV']], $request[$params['udm']]);
                if (isset($newEntrepotStock)) {
                  $stockEntrepotMap = Yii::$app->inventaireClass->newEntrepotStockMap($gotProductId, $request[$params['udm']], 1,  $request[$params['prodcutQte']]);
                  #### EVENEMENT : TYPE : CREATION PRODUIT ###
                  $event_msg = Yii::t('app', 'newProduct') . '&nbsp;' . $request[$params['productCode']] . '&nbsp;' . Yii::t('app', 'msg_qteentrepose') . $request[$params['prodcutQte']];
                  Yii::$app->mainCLass->creerEvent('003', $event_msg, $userPrimaryData['idEntite']);
                  # AVOID THE DUPLICATION AND SET A MESSAGE SUCCESSFUL SAVING PROCESS
                  Yii::$app->session->set(Yii::$app->params['tok2'], $_POST[Yii::$app->params['tok2']]);
                  $this->msg = serialize(['type' => 'alert alert-success', 'strong' => Yii::t('app', 'success'), 'text' => Yii::t('app', 'enrgSuccess')]); ##### PREPARATION DU MESSAGE
                  unset($_POST);
                  # END OF SUCCESSFUL SAVING PROCESS
                } else $this->msg = serialize(['type' => 'alert alert-danger', 'strong' => Yii::t('app', 'erreur'), 'text' => Yii::t('app', 'invalidProcess')]);
              } else $this->msg = serialize(['type' => 'alert alert-danger', 'strong' => Yii::t('app', 'erreur'), 'text' => Yii::t('app', 'invalidProcess')]);
            } else $this->msg = serialize(['type' => 'alert alert-danger', 'strong' => Yii::t('app', 'erreur'), 'text' => Yii::t('app', 'invalidProcess')]);
          } else $this->msg = serialize(['type' => 'alert alert-danger', 'strong' => Yii::t('app', 'erreur'), 'text' => Yii::t('app', 'invalidProcess')]);
        } else {
          $this->msg = serialize(['type' => 'alert alert-danger', 'strong' => Yii::t('app', 'erreur'), 'text' => Yii::t('app', 'invalidData')]); ##### PREPARATION DU MESSAGE
        }
      }
    }
    unset($_POST); # END POST STATEMENT
    # display the form
    return $this->render($this->pg, ['entite' => $entite, 'msg' => $this->msg, 'productCategories' => $productCategories, 'productGroupes' => $productGroupes, 'productMarque' => $productMarque, 'productFabricant' => $productFabricant, 'productGenricName' => $ProductGenricName, 'productUdm' => $productUdm]);
  }

  # LISTE PRODUCT GENRIC
  public function actionGenericname()
  {
    # GET USER AUTH PRIMARY INFOS
    $UserAuthPrimaryInfo = Yii::$app->mainCLass->getUserAuthPrimaryInfo();
    # AJAX STATEMENT : NEW GENERIC NAME
    if (isset($_GET[Yii::$app->params['productGenericName']]) && $_GET[Yii::$app->params['ajax_action_key']] == md5('newGenericName')) {
      $genricNameData = Null;
      $productGenericName = $_GET[Yii::$app->params['productGenericName']];
      $productGenericDesc = $_GET[Yii::$app->params['productGenericDesc']];
      # INSERTION PROCESS
      $connect = \Yii::$app->db;
      $insertGeneric = $connect->createCommand('INSERT INTO slim_productgenericname (nom, description, entrepriseId) VALUES (:nom, :description, :entrepriseId)')
        ->bindValues([':nom' => $productGenericName, ':description' => $productGenericDesc, ':entrepriseId' => $UserAuthPrimaryInfo['idEntreprise']])
        ->execute();
      if (isset($insertGeneric) && $_GET[Yii::$app->params['ajax_action_key']] == md5('newGenericName')) {
        #### EVENEMENT : TYPE : Creation d'un nom generique de produit ###
        $event_msg = Yii::t('app', 'msg_creationnomgen') . '&nbsp;:&nbsp;' . $productGenericName;
        Yii::$app->mainCLass->creerEvent('012', $event_msg);
        // Recuperrons la liste des nom generiques deja crees
        $Genericrslt = $connect->createCommand('SELECT id, nom, description FROM slim_productgenericname WHERE entrepriseId=:entrepriseId')
          ->bindValue(':entrepriseId', $UserAuthPrimaryInfo['idEntreprise'])
          ->queryAll();
        if (is_array($Genericrslt) && sizeof($Genericrslt) > 0) {
          $genricNameData = '<option value="0">Selectionne Un</option>';
          foreach ($Genericrslt as $generixData) {
            $genricNameData .= '<option value="' . $generixData["id"] . '">' . $generixData["nom"] . '</option>';
          }
        }
      }
      return $genricNameData;
    }

    # AJAX STATEMENT : NEW FABRICANT
    if (isset($_GET[Yii::$app->params['nomNouveauFabriquant']]) && $_GET[Yii::$app->params['ajax_action_key']] == md5('newFabricant')) {
      $newInsertedFabricant = Null;
      # INSERTION PROCESS
      $connect = \Yii::$app->db;
      $insertStmt = $connect->createCommand('INSERT INTO slim_productmarquefabricant (nomFabricant, entrepriseId) VALUES (:nomFabricant, :entrepriseId)')
        ->bindValues([':nomFabricant' => $_GET[Yii::$app->params['nomNouveauFabriquant']], ':entrepriseId' => $UserAuthPrimaryInfo['idEntreprise']])
        ->execute();
      if (isset($insertStmt)) {
        $newInsertedFabricant = $connect->createCommand('SELECT id, nomFabricant
                                                            FROM slim_productmarquefabricant
                                                           WHERE nomFabricant=:nomFabricant
                                                             AND entrepriseId=:entrepriseId')
          ->bindValues([
            ':nomFabricant' => $_GET[Yii::$app->params['nomNouveauFabriquant']],
            ':entrepriseId' => $UserAuthPrimaryInfo['idEntreprise']
          ])
          ->queryOne();
      }
      return $newInsertedFabricant['id'];
    }
  }

  # LISTE PRODUCT MARQUES
  public function actionMarques()
  {
    # GET USER AUTH PRIMARY INFOS
    $UserAuthPrimaryInfo = Yii::$app->mainCLass->getUserAuthPrimaryInfo();
    # AJAX STATEMENT : NEW MARQUE
    if (!empty($_GET[Yii::$app->params['nom']]) && $_GET[Yii::$app->params['ajax_action_key']] == md5('inventaire_marques')) {
      $groupData = $marqueData = Null;
      $fabriquantId = $_GET[Yii::$app->params['idNewFabricant']];
      $productMarqueName = $_GET[Yii::$app->params['nom']];
      $productMarqueDesc = $_GET[Yii::$app->params['description']];

      # INSERTION PROCESS
      $connect = \Yii::$app->db;
      $marqInsertStmt = $connect->createCommand('INSERT INTO slim_productmarque (fabricantId, nom, description, entrepriseId) VALUES (:fabricantId ,:nom, :description, :entrepriseId)')
        ->bindValues([':10
                              fabricantId' => $fabriquantId, ':nom' => $productMarqueName, ':description' => $productMarqueDesc, ':entrepriseId' => $UserAuthPrimaryInfo['idEntreprise']])
        ->execute();
      if (isset($marqInsertStmt) && $_GET[Yii::$app->params['ajax_action_key']] == md5('inventaire_marques')) {
        $marqueRslt = $connect->createCommand('SELECT id, nom, description FROM slim_productmarque WHERE entrepriseId=:entrepriseId')
          ->bindValue(':entrepriseId', $UserAuthPrimaryInfo['idEntreprise'])
          ->queryAll();
        if (is_array($marqueRslt) && sizeof($marqueRslt) > 0) {
          $marqueData = '<option value="0">Selectionne Un</option>';
          foreach ($marqueRslt as $data) {
            $marqueData .= '<option value="' . $data["id"] . '">' . $data["nom"] . '</option>';
          }
        }
      }
      return $marqueData;
    }
  }

  # LISTE PRODUCT GROUP
  public function actionGroups()
  {
    # INITIALISATION OF SOME VARIABLES
    $this->pg = '/inventaire/productGroup/prodcutGroups';
    $connect = \Yii::$app->db;
    # GET USER AUTH PRIMARY INFOS
    $UserAuthPrimaryInfo = Yii::$app->mainCLass->getUserAuthPrimaryInfo();
    # AJAX STATEMENT : NEW CATEORIE
    if (!empty($_GET[Yii::$app->params['productGroupName']])) {
      $groupData = Null;
      $productGroupName = $_GET[Yii::$app->params['productGroupName']];
      $productGroupDesc = $_GET[Yii::$app->params['productGroupDesc']];
      # INSERTION PROCESS
      $insertStmt = $connect->createCommand('INSERT INTO slim_productgroup (nom, description, entrepriseId) VALUES (:nom, :description, :entrepriseId)')
        ->bindValues([':nom' => $productGroupName, ':description' => $productGroupDesc, ':entrepriseId' => $UserAuthPrimaryInfo['idEntreprise']])
        ->execute();
      if (isset($insertStmt) && $_GET[Yii::$app->params['ajax_action_key']] == md5('newGroup')) {
        #### EVENEMENT : TYPE : Creation d'un group de produit ###
        $event_msg = Yii::t('app', 'msg_creationgroup') . '&nbsp;:&nbsp;' . $productGroupName;
        Yii::$app->mainCLass->creerEvent('010', $event_msg);
        # ACTION TO DO WHEN THE CURRENT ACTION IS CATS (IN THE CATEGORIE PANEL)
        if (Yii::$app->controller->action->id == 'groups' && $_GET[Yii::$app->params['action_key']] == md5('listGroups')) {
          return 'refreshPage';
        }
        # GET ALL GROUPS
        $entrepriseCats = Yii::$app->inventaireClass->getGroupBaseEntrepriseId($UserAuthPrimaryInfo['idEntreprise']);
        if (is_array($entrepriseCats) && sizeof($entrepriseCats) > 0) {
          $groupData = '<option value="0">Selectionne Un</option>';
          foreach ($entrepriseCats as $data) {
            $groupData .= '<option value="' . $data["id"] . '">' . $data["nom"] . '</option>';
          }
        }
      }
      return $groupData;
    }
    # GET ALL PRODUCTS
    $entrepriseGroups = Yii::$app->inventaireClass->getGroupBaseEntrepriseId($UserAuthPrimaryInfo['idEntreprise']);
    return $this->render($this->pg, ['entrepriseGroups' => $entrepriseGroups]);
  }



  public function actionListereapro()
  {
  }
}
