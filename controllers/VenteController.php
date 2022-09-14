<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;

class VenteController extends Controller
{
  private $pg = Null;
  private $msg = Null;


  #******************************************
  #           NOMENCLATURE  DES ACTIONS
  # Nom de l'action : NomDOssier_Nomdufichier
  #******************************************

  /** Action : Vente **/
  public function actionSimple()
  {
    $userPrimaryData = $request = $productVenduDtls = $msg = $ventesDtls = $listeProduit = $ProductdtlsForSale = $listeClient = $dataPosted = $banques = Null;
    $this->pg = '/vente/simple/ventesimple.php';
    $UserAuthPrimaryInfo = Yii::$app->mainCLass->getUserAuthPrimaryInfo();

    /** Selectionne la liste des produits disponible a la vente ainsi que leur informations **/
    $adminarray = Yii::$app->params['usersToEditSales'];
    $idservicesuser = base64_encode(in_array($UserAuthPrimaryInfo['typeUser'], $adminarray) ? '0' : $UserAuthPrimaryInfo['idEntite']);
    $idEntite = Yii::$app->parametreClass->listeserviceforrepport($idservicesuser, $UserAuthPrimaryInfo['idEntreprise'], $UserAuthPrimaryInfo['typeUser'], $UserAuthPrimaryInfo['idEntite']);

    //preparer les datas primaires
    $userPrimaryData = Yii::$app->mainCLass->getUserAuthPrimaryInfo();
    $actor_idEntreprise = $userPrimaryData['idEntreprise'];
    $idactor = $userPrimaryData['auhId'];
    $userLevel = $userPrimaryData['typeUser'];
    $actor_idEntite = $userPrimaryData['idEntite'];

    //die($idEntite);
    if($userPrimaryData['typeUser']==9){
        $ProductdtlsForSale = Yii::$app->inventaireClass->getProductdtlsForSaleBaseEntreprise($idEntite);
    }else{
        $ProductdtlsForSale = Yii::$app->inventaireClass->getProductdtlsForSaleBaseEntreprise($UserAuthPrimaryInfo['idEntite']);
    }
    
   

    // motif client
    $clientEnrgMotifs = Yii::$app->parametreClass->clientEnrgMotifs($UserAuthPrimaryInfo['idEntreprise']);

    $listeClient = Yii::$app->venteClass->getAllClient($UserAuthPrimaryInfo['idEntreprise']);

    $getProductElement = null;

    // Ajax : Restaurer une qte d'article deja vendue
    if (isset($_GET['action_key']) && $_GET['action_key'] == md5('restoreqte')) {
      $restaureQteVendu = Yii::$app->inventaireClass->restaureQteVendu($_GET['idArticle'], $_GET['idLigneVente'], $_GET['qteDispo'], $_GET['qteVendu'], $_GET['productUdm'], $_GET['spvtotal']);
      return $restaureQteVendu;
    }

    // Ajax : Restaurer une qte d'article deja vendue
    if (isset($_GET['articlecode']) && $_GET['action_key'] == 'getarticleBasearticlecode') {
      $stmt = Yii::$app->inventaireClass->getProductdtlByCode($_GET['articlecode']);

      $getProductElement = Yii::$app->inventaireClass->getProductdtlByCode($_GET['articlecode']);

      $selectectedELement = json_decode(base64_decode($stmt));

      // recuperation de la quantite de l'article
      $quantite = yii::$app->venteClass->getQte($selectectedELement->slimproductid);

      if ($quantite > 0) {

        $rslt = '<tr id="row_' . $selectectedELement->slimproductid . '" class="eachrow">
                      <td><input type="hidden" Class="totalBought" name="sousTotalAchat[]" id="sousTotalAchat_' . $selectectedELement->slimproductid . '" value="' . $selectectedELement->prixUnitaireAchat . '">&#8226;<input autocomplete="off" type="hidden" name="selectectedELement[]" id="selectectedELement" value="' . $stmt . '"></td>
                      <td>' . $selectectedELement->productCode . ' : ' . $selectectedELement->libelle . '</td>
                      <td>' . Yii::$app->inventaireClass->getUdmLabel($selectectedELement->udm) . '</td>
                      <td>' . $selectectedELement->qteDispo . '</td>
                      <td class="text-center">
                      <div class="form-group text-center" style="margin-left: 3%;">
											<div class="input-group">
                     
                      <input autocomplete="off" class="form-control qtes text-center" style="width: 50%;" onKeyup="calculSubTotal(this.id,' . $selectectedELement->qteDispo . ',  ' . $selectectedELement->prixUnitaireVente . ', ' . $selectectedELement->slimproductid . ',' . $selectectedELement->prixUnitaireAchat . ')" name="qteToBeSale[]" id="qteToBeSale_' . $selectectedELement->slimproductid . '" value="1">
                      
                      <a href="#" onclick="decQte(this.id,' . $selectectedELement->qteDispo . ',  ' . $selectectedELement->prixUnitaireVente . ', ' . $selectectedELement->slimproductid . ',' . $selectectedELement->prixUnitaireAchat . ',' . $selectectedELement->slimproductid . ')" name="qteToBeSale[]" id="qteToBeSale_' . $selectectedELement->slimproductid . '" class="input-group-text btn btn-warning qtebtn dec" id="btn1">-</a>
                      &nbsp;
											<a href="#" onclick="incQte(this.id,' . $selectectedELement->qteDispo . ',  ' . $selectectedELement->prixUnitaireVente . ', ' . $selectectedELement->slimproductid . ',' . $selectectedELement->prixUnitaireAchat . ',' . $selectectedELement->slimproductid . ')" name="qteToBeSale[]" id="qteToBeSale_' . $selectectedELement->slimproductid . '" class="input-group-text btn btn-success qtebtn inc" id="btn2">+</a>
                      </div>
                      </div>
                      </td>
                      <td><input autocomplete="off" onchange="positifSubMontant(' . $selectectedELement->slimproductid . ')" class="form-control totalmustpay" type="number"  name="sousTotalLabel[]" id="sousTotalLabel_' . $selectectedELement->slimproductid . '" value="' . $selectectedELement->prixUnitaireVente . '"></td>
                      <td><a id="' . $selectectedELement->slimproductid . '" class="btn btn-danger" onclick="deletThisRow(this.id)"><i Class="fa fa-times">&nbsp;</i></a></td>
                    </tr>
                  ';
      }
      return $rslt;
    }

    // Ajax : ajouter le produit au panier de vente
    if (isset($_GET['selectedDiv']) && isset($_GET['addToSaleBaquet']) && $_GET['addToSaleBaquet'] == md5('1')) {

      // Recuperrons la valeur de l'element selectionner
      $selectectedELement = json_decode(base64_decode($_GET['selectedDiv']));

      // recuperation de la quantite de l'article
      $quantite = yii::$app->venteClass->getQte($selectectedELement->slimproductid);

      if ($quantite > 0) {

        $rslt = '<tr id="row_' . $selectectedELement->slimproductid . '" class="eachrow">
          <td><input type="hidden" Class="totalBought" name="sousTotalAchat[]" id="sousTotalAchat_' . $selectectedELement->slimproductid . '" value="' . $selectectedELement->prixUnitaireAchat . '">&#8226;<input autocomplete="off" type="hidden" name="selectectedELement[]" id="selectectedELement' . $selectectedELement->slimproductid . '" class="eachHidden" value="' . $_GET['selectedDiv'] . '"></td>
          <td>' . $selectectedELement->productCode . ' : ' . $selectectedELement->libelle . '</td>
          <td>' . Yii::$app->inventaireClass->getUdmLabel($selectectedELement->udm) . '</td>
          <td>' . $selectectedELement->qteDispo . '</td>
          <td class="text-center">
          <div class="form-group text-center" style="margin-left: 3%;">
          <div class="input-group">
        
          <input autocomplete="off" class="form-control qtes text-center" style="width: 50%;" onKeyup="calculSubTotal(this.id,' . $selectectedELement->qteDispo . ',  ' . $selectectedELement->prixUnitaireVente . ', ' . $selectectedELement->slimproductid . ',' . $selectectedELement->prixUnitaireAchat . ')" name="qteToBeSale[]" id="qteToBeSale_' . $selectectedELement->slimproductid . '" value="1">
          
          <a href="#" onclick="decQte(this.id,' . $selectectedELement->qteDispo . ',  ' . $selectectedELement->prixUnitaireVente . ', ' . $selectectedELement->slimproductid . ',' . $selectectedELement->prixUnitaireAchat . ',' . $selectectedELement->slimproductid . ')" name="qteToBeSale[]" id="qteToBeSale_' . $selectectedELement->slimproductid . '" class="input-group-text btn btn-warning qtebtn dec" id="btn1">-</a>
          &nbsp;
          <a href="#" onclick="incQte(this.id,' . $selectectedELement->qteDispo . ',  ' . $selectectedELement->prixUnitaireVente . ', ' . $selectectedELement->slimproductid . ',' . $selectectedELement->prixUnitaireAchat . ',' . $selectectedELement->slimproductid . ')" name="qteToBeSale[]" id="qteToBeSale_' . $selectectedELement->slimproductid . '" class="input-group-text btn btn-success qtebtn inc" id="btn2">+</a>
          </div>
          </div>
          </td>
          <td><input autocomplete="off" onchange="positifSubMontant(' . $selectectedELement->slimproductid . ')" class="form-control totalmustpay" type="number"  name="sousTotalLabel[]" id="sousTotalLabel_' . $selectectedELement->slimproductid . '" value="' . $selectectedELement->prixUnitaireVente . '"></td>
          <td><a id="' . $selectectedELement->slimproductid . '" class="btn btn-danger" onclick="deletThisRow(this.id)"><i Class="fa fa-times">&nbsp;</i></a></td>
        </tr>
      ';
      }
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

    //analyse de l'imput action_key
    if (Yii::$app->request->isPost) {
      $request = $_POST;
      /** Analysons la valeur attribue a action_key **/
      switch ($request['action_key']) {

          //--------------------------- Edite Vente ---------------------------//
        case md5('deletesale'):
          $data = Null;
          $saleid = base64_decode($request['action_on_this']);
          //Get product in sale
          $product_in_sale = yii::$app->venteClass->getProductinsale($saleid);

          //update product quantite

          if (isset($product_in_sale) && sizeof($product_in_sale) > 0) {
            foreach ($product_in_sale as $key => $prod_dts) {
              //Update la quantite de produit
              $stmt = Yii::$app->inventaireClass->updateProductQte(base64_encode($prod_dts['idProduit']), base64_encode($prod_dts['qteDispo']), $prod_dts['qteVendu'], base64_encode($prod_dts['udm']));
              //update le statut du produit la dtls de produit
              $stmt = yii::$app->inventaireClass->deleteproductrecord($prod_dts['idProduit'], $saleid);
            }
          }

          //Update sale statut
          $sale_updated = yii::$app->venteClass->deletesale($saleid);

          $critere = (isset($request['selectCriteria'])) ? $_POST['selectCriteria'] : 1;
          $donneeRecherche = (isset($request[Yii::$app->params['donneeRecherche']])) ? $_POST[Yii::$app->params['donneeRecherche']] : '';
          $limit = (isset($request[Yii::$app->params['limit']])) ? $_POST[Yii::$app->params['limit']] : 1;
          #RECCUPERONS LES INFOS PRIMAIRE DE L'UTILISATEUR CONNECTE
          $userPrimaryData = Yii::$app->mainCLass->getUserAuthPrimaryInfo();
          # Recuperrons les infos des ventes
          $ventesDtls = Yii::$app->venteClass->getVentes($critere, $donneeRecherche, $limit);
          $this->pg = '/vente/simple/listeventes.php';
          break;

          //--------------------------- Retrouver Vente ---------------------------//

        case md5('retreviewVente'):
          $msg = Null;
          $request = $_POST;
          $critere = (isset($request['selectCriteria'])) ? $_POST['selectCriteria'] : 10;
          $donneeRecherche = (isset($request[Yii::$app->params['donneeRecherche']])) ? $_POST[Yii::$app->params['donneeRecherche']] : '';
          $limit = (isset($request[Yii::$app->params['limit']])) ? $_POST[Yii::$app->params['limit']] : 10;
          #RECCUPERONS LES INFOS PRIMAIRE DE L'UTILISATEUR CONNECTE
          $userPrimaryData = Yii::$app->mainCLass->getUserAuthPrimaryInfo();
          # Recuperrons les infos des ventes
          $ventesDtls = Yii::$app->venteClass->getVentes($critere, $donneeRecherche, $limit);
          $this->pg = '/vente/simple/listeventes.php';
          break;

          //--------------------------- Vente Simple ---------------------------//

        case md5("enrgventeproduit"):
          //Initialiser les variables du choix
          $requirement = $bill_saved = $numero_facture = $pj_mode_paiement = Null;

          //Analyser si les inperatifs sont respectes
          if (isset($request['codeVente'])) {
            $requirement = 1;
          } else {
            Yii::$app->session->setFlash('flashmsg', '<div class="alert alert-danger"> <strong> <i class="fa fa-exclamation-circle">&nbsp;</i> </strong>&nbsp;' . Yii::t("app", "champObligatoire") . '</div>');
            return $this->redirect(Yii::$app->request->baseUrl . '/' . md5('vente_simple'));
          }

          //Enregistrer la facture
          if ($requirement == 1) {
            $categorie_autre_partie = 1;
            //Generer le numéro de la facture
            $numero_facture = yii::$app->mainCLass->get_orderer_number(strtolower('facture_client'));

            //Continuer l'enregistrement
            $bill_saved = yii::$app->inventaireClass->enrg_bill($request, $actor_idEntreprise, $actor_idEntite, $idactor, $numero_facture, $categorie_autre_partie);
          }

          //Enregistrer le paiement
          if ($bill_saved) {
            if (isset($request['banque_denomination']) && $request['banque_denomination'] != '') {
              $pj_mode_paiement = $request['banque_denomination'];
            }

            if (isset($request['numero_cheque']) && $request['numero_cheque'] != '') {
              $pj_mode_paiement = $request['numero_cheque'];
            }

            $bill_paiement = yii::$app->inventaireClass->enrg_bill_paiement($request['acheteur'], $bill_saved, (float)($request['montantPercu']), (float)($request['dettevente']), $request['mode_paiement'], $actor_idEntreprise, $actor_idEntite, $idactor, $numero_facture, $pj_mode_paiement);
          }

          //Enregistrer l'historique de reaprovisionement
          if ($bill_paiement && is_array($request['qteToBeSale'])) {
            for ($i = 0; $i < sizeof($request['qteToBeSale']); $i++) {
              //Preparer la tableau de données à enregistrer
              $product_selected = json_decode(base64_decode($request['selectectedELement'][$i]));
              // if (json_decode(base64_decode($request['selectectedELement'][$i])) != null) {

              //   $operation_date = Yii::$app->nonSqlClass->convert_date_to_sql_form($request['ventedate'], "M/D/Y");

              //   $idMaptype = 2;
              //   $product_in_array[$i] = [intval($product_selected->slimproductid), $idMaptype, $numero_facture, $operation_date, intval($request['qteToBeSale'][$i]), $actor_idEntreprise, $actor_idEntite, $idactor];

              //   $product_in_array_for_update[$i] = [intval($product_selected->slimproductid), $idMaptype, $numero_facture, $operation_date, intval($request['qteToBeSale'][$i]), intval($product_selected->qteDispo), $actor_idEntreprise, $actor_idEntite, $idactor];
              // } else {
              //   $operation_date = Yii::$app->nonSqlClass->convert_date_to_sql_form($request['ventedate'], "M/D/Y");

              //   $idMaptype = 2;
              //   $product_in_array[$i] = [intval($product_selected['slimproductid']), $idMaptype, $numero_facture, $operation_date, intval($request['qteToBeSale'][$i]), $actor_idEntreprise, $actor_idEntite, $idactor];

              //   $product_in_array_for_update[$i] = [intval($product_selected['slimproductid']), $idMaptype, $numero_facture, $operation_date, intval($request['qteToBeSale'][$i]), intval($product_selected['qteDispo']), $actor_idEntreprise, $actor_idEntite, $idactor];
              // }

              $operation_date = Yii::$app->nonSqlClass->convert_date_to_sql_form($request['ventedate'], "M/D/Y");

              $idMaptype = 2;
              $product_in_array[$i] = [intval($product_selected->slimproductid), $idMaptype, $numero_facture, $operation_date, intval($request['qteToBeSale'][$i]), $actor_idEntreprise, $actor_idEntite, $idactor];

              $product_in_array_for_update[$i] = [intval($product_selected->slimproductid), $idMaptype, $numero_facture, $operation_date, intval($request['qteToBeSale'][$i]), intval($product_selected->qteDispo), $actor_idEntreprise, $actor_idEntite, $idactor];
            }
          }

          //Mettre à jour les produits selectionés
          if ($product_in_array) {
            //Enregistrer l'historique des stocks reaprovisionnés
            yii::$app->inventaireClass->enrg_as_map_stocktopedup($product_in_array);
          }

          //Formater la date de vente
          $ventedate = Yii::$app->nonSqlClass->convert_date_to_sql_form($request['ventedate'], "M/D/Y");
          $ventedate = $ventedate . ' ' . date('H:m:s');

          $stmt = Yii::$app->venteClass->addVenteDtls($numero_facture, $request['acheteur'], $request['ta'], $request['totalMonetaire'], $request['remiseMonetaire'], $request['montantFinal'], $request['montantPercu'], $request['dettevente'], $ventedate);

          if ($request['dettevente'] > 0) {
            // RECUPERONS LES INFOS PRIMAIRE D'UN UTILISATEUR
            $userPrimaryData = Yii::$app->mainCLass->getUserAuthPrimaryInfo();
            $categorie = 1;
            $dateEcheance = Yii::$app->nonSqlClass->convert_date_to_sql_form($request['dateEcheance'], "M/D/Y");
            yii::$app->paiementClass->preparer_next_paiement($categorie, $numero_facture, $request['acheteur'], $dateEcheance, $userPrimaryData['idEntreprise'], $userPrimaryData['auhId']);
          }
          //Analyser 
          switch ($stmt) {
            case '92':
              // Recuperons l'id de la vente effectuee a linstant en fonction du code de la vente
              $RecentVenteid = Yii::$app->venteClass->getVenteId($numero_facture);
              foreach ($request['selectectedELement'] as $key => $value) {
                $qteSold = $request['qteToBeSale'][$key];
                //$sub_selectectedELement = json_decode(base64_decode($value));

                // if (json_decode(base64_decode($value)) != null) {
                //   $sub_selectectedELement = json_decode(base64_decode($value));
                //   $stmt = Yii::$app->venteClass->updateProductSold($RecentVenteid, $sub_selectectedELement->slimproductid, $sub_selectectedELement->qteDispo, $qteSold, $sub_selectectedELement->udm, $sub_selectectedELement->prixUnitaireVente, $request['sousTotalLabel'][$key], $ventedate);
                // } else {

                //   $sub_selectectedELement = unserialize($value);
                //   $stmt = Yii::$app->venteClass->updateProductSold($RecentVenteid, $sub_selectectedELement['slimproductid'], $sub_selectectedELement['qteDispo'], $qteSold, $sub_selectectedELement['udm'], $sub_selectectedELement['prixUnitaireVente'], $request['sousTotalLabel'][$key], $ventedate);
                // }

                $sub_selectectedELement = json_decode(base64_decode($value));
                $stmt = Yii::$app->venteClass->updateProductSold($RecentVenteid, $sub_selectectedELement->slimproductid, $sub_selectectedELement->qteDispo, $qteSold, $sub_selectectedELement->udm, $sub_selectectedELement->prixUnitaireVente, $request['sousTotalLabel'][$key], $ventedate);
              }

              if ($RecentVenteid) {
                # EVENEMENT : TYPE : MISE A JOUR DE PRODUIT ###
                $event_msg = Yii::t('app', 'msg_enrg_nvente_produit') . '&nbsp;' . $request['codeVente'];
                Yii::$app->mainCLass->creerEvent('013', $event_msg);
                # Prepare le message de l'operation avec success
                $msg = serialize(['type' => 'alert alert-success', 'strong' => Yii::t('app', 'success'), 'text' => Yii::t('app', 'operatSucess')]); ##### Message d'operation avec success
                Yii::$app->session->set('msg', $msg);
                return $this->redirect(Yii::$app->request->baseUrl . '/' . md5('vente_simple'));
              }

              break;
          }

          $this->pg = '/vente/simple/nventesimple.php';
          break;

        case md5('nvente'):
          $UserAuthPrimaryInfo = Yii::$app->mainCLass->getUserAuthPrimaryInfo();
          $adminarray = Yii::$app->params['usersToEditSales'];
          $idservicesuser = base64_encode(in_array($UserAuthPrimaryInfo['typeUser'], $adminarray) ? '0' : $UserAuthPrimaryInfo['idEntite']);
          $idEntite = Yii::$app->parametreClass->listeserviceforrepport($idservicesuser, $UserAuthPrimaryInfo['idEntreprise'], $UserAuthPrimaryInfo['typeUser'], $UserAuthPrimaryInfo['idEntite']);
          $listeProduit = Yii::$app->venteClass->getProductBaseEntreprise($idEntite);
          $listeClient = Yii::$app->venteClass->getAllClient($UserAuthPrimaryInfo['idEntreprise']);
          $banques = yii::$app->mainCLass->charger_banques_entreprise($UserAuthPrimaryInfo['idEntreprise']);
          $this->pg = '/vente/simple/nventesimple.php';
          break;

          //liste detail vente
        case md5('detailvente'):
          $id = base64_decode($request['action_on_this']);
          $prod = Yii::$app->venteClass->getDetailVente($id);
          return $this->render('/vente/simple/detailvendu.php', ['prod' => $prod]);
          break;

          //nouveau client
        case md5('enreg_client'):
          if (Yii::$app->request->isPost) {
            $request = $_POST;
            $UserAuthPrimaryInfo = Yii::$app->mainCLass->getUserAuthPrimaryInfo();

            $statutcontact =            $request['statutClient'];
            $naturecontact =            $request['segmentClient'];
            $appelation =            $request['appelation'];
            $dtebirth =            $request['dtebirth'];
            $tel =            $request['tel'];
            $email =            $request['email'];
            $enrgmotif =            $request['enrgmotif'];
            $dtlsmotif =            $request['dtlsmotif'];
            $idEntreprise =            $UserAuthPrimaryInfo['idEntreprise'];
            $idactor =            $UserAuthPrimaryInfo['auhId'];

            $stmt = Yii::$app->venteClass->enrgclient($statutcontact, $naturecontact, $appelation, $dtebirth, $tel, $email, $enrgmotif, $dtlsmotif, $idEntreprise, $idactor);


            // motif client
            $clientEnrgMotifs = Yii::$app->parametreClass->clientEnrgMotifs($UserAuthPrimaryInfo['idEntreprise']);
            $listeClient = Yii::$app->venteClass->getAllClient($UserAuthPrimaryInfo['idEntreprise']);
            $this->pg = '/vente/simple/nventesimple.php';
          }
          break;

        case md5('charger_facture_data'):
          $dataposted = serialize($_POST);
          $asbsoluteUrlData = 'repport/facture';
          yii::$app->session->set('formdataposted', $dataposted);
          $this->pg = '/recouvrement/facture_mainreportframe.php';
          return $this->render($this->pg, ['asbsoluteUrlData' => $asbsoluteUrlData]);
          break;
      }
    }
    return $this->render($this->pg, ['userPrimaryData' => serialize($userPrimaryData), 'productVenduDtls' => $productVenduDtls, 'ventesDtls' => $ventesDtls, 'dataPosted' => $dataPosted, 'msg' => $msg, 'listeClient' => $listeClient, 'listeProduit' => $listeProduit, 'ProductdtlsForSale' => $ProductdtlsForSale, 'banques' => $banques, 'clientEnrgMotifs' => $clientEnrgMotifs]);
  }
}
