<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;

class RgController extends Controller
{
  private $pg = Null;
  private $msg = Null;


  /** Action : Rapport des paiements/recouvrements & factures **/
  public function actionPaiement()
  {
    $this->pg = '/rggeneral/rg_paiementrecouvrement/reportdtls.php';
  }

  /** action sur le rapport diver**/
  public function actionDiver()
  {
    $msg = $allEventType = $asbsoluteUrlData = $service = $dataposted = $userType = $depens = null;
    $request = $_POST;
    $this->pg = '/rggeneral/rg_diver/liste.php';
    // Recuperrons les infos basic de l;utilisateur connected -- aliou/13/07/2018 --
    $userPrimaryData = Yii::$app->mainCLass->getUserAuthPrimaryInfo();
    if (is_array($request) && sizeof($request) > 0) {
      switch ($request['action_key']) {
          /** chargeons le rapport d'evenements **/
        case md5('rg_diver_event'):
          switch ($request['action_on_this']) {
            case md5('rg_diver_event_repport'):
              $dataposted = serialize($_POST);
              if (isset($request['action_key']) && $request['datefrom'] != Null && $request['dateto'] != Null && Yii::$app->nonSqlClass->date_compare($request['datefrom'], $request['dateto']) == true) {
                $asbsoluteUrlData = 'repport/evenementdiver';
                Yii::$app->session->set('formdataposted', $dataposted);
                $this->pg = '/rggeneral/rg_vente/mainreportframe.php';
              } else {
                $msg = serialize(['type' => 'alert alert-danger', 'strong' => Yii::t('app', 'erreur'), 'text' => Yii::t('app', 'invaliddategiven')]); ##### PREPARATION DU MESSAGE
                $service =  Yii::$app->parametreClass->listservices_entreprises($userPrimaryData['idEntreprise'], $userPrimaryData['typeUser'], $userPrimaryData['idEntite']);
                $this->pg = '/rggeneral/rg_diver/evenement/filtre.php';
              }

              break;
            default:
              $service =  Yii::$app->parametreClass->listservices_entreprises($userPrimaryData['idEntreprise'], $userPrimaryData['typeUser'], $userPrimaryData['idEntite']);
              $allEventType = yii::$app->mainCLass->renvEventType();
              $this->pg = '/rggeneral/rg_diver/evenement/filtre.php';
              break;
          }
          break;

          /** chargeons le rapport des utulisateurs **/
        case md5('rg_diver_user'):
          switch ($request['action_on_this']) {
            case md5('rg_diver_user_repport'):
              $dataposed = serialize($_POST);
              if (isset($request['action_key'])) {
                $asbsoluteUrlData = 'repport/utulisateurdiver';
                yii::$app->session->set('formdataposted', $dataposted);
                $this->pg = '/rggeneral/rg_diver/mainreportframe.php';
              } else {
                $msg = serialize(['type' => 'alert alert-danger', 'strong'
                => yii::t('app', 'erreur')]);
                $this->pg = '/rggeneral/rg_diver/utulisateur/filtre.php';
              }
              break;

            default:
              $userType = yii::$app->mainCLass->renvUserType();
              $this->pg = '/rggeneral/rg_diver/utulisateur/filtre.php';
              break;
          }
          break;

          /** chargeons le rapport des depenses **/
        case md5('rg_diver_depense'):
          switch ($request['action_on_this']) {
            case md5('rg_diver_depense_repport'):
              $dataposted = serialize($_POST);
              if (isset($request['action_key']) && $request['datefrom'] != Null && $request['dateto'] != Null && Yii::$app->nonSqlClass->date_compare($request['datefrom'], $request['dateto']) == true) {
                $asbsoluteUrlData = 'repport/depensediver';
                Yii::$app->session->set('formdataposted', $dataposted);
                $this->pg = '/rggeneral/rg_diver/mainreportframe.php';
              } else {
                $msg = serialize(['type' => 'alert alert-danger', 'strong' => Yii::t('app', 'erreur'), 'text' => Yii::t('app', 'invaliddategiven')]); ##### PREPARATION DU MESSAGE
                $service =  Yii::$app->parametreClass->listservices_entreprises($userPrimaryData['idEntreprise'], $userPrimaryData['typeUser'], $userPrimaryData['idEntite']);
                $this->pg = '/rggeneral/rg_diver/depense/filtre.php';
              }
              break;

            default:
              $service =  Yii::$app->parametreClass->listservices_entreprises($userPrimaryData['idEntreprise'], $userPrimaryData['typeUser'], $userPrimaryData['idEntite']);
              $depens = yii::$app->mainCLass->renvCharge($userPrimaryData['idEntreprise']);
              $this->pg = '/rggeneral/rg_diver/depense/filtre.php';
              break;
          }
          break;
      }
    }
    return $this->render($this->pg, ['msg' => $msg, 'asbsoluteUrlData' => $asbsoluteUrlData, 'allEventType' => $allEventType, 'userType' => $userType, 'depens' => $depens, 'dataposted' => $dataposted, 'services' => $service]);
  }
  /** Action raport sur les ventes **/
  public function actionVente()
  {
    $msg =  $service = $dataposted = $asbsoluteUrlData = $productCategories = $listcategories = null;
    $request = $_POST;
    $this->pg = '/rggeneral/rg_vente/liste.php';
    // Recuperrons les infos basic de l;utilisateur connected
    $userPrimaryData = Yii::$app->mainCLass->getUserAuthPrimaryInfo();

    if (is_array($request) && sizeof($request) > 0) {
      switch ($request['action_key']) {

          /**Fond de roulement**/
        case md5('rg_vente_fondroulement'):
          switch ($request['action_on_this']) { // Analysons l'indice de reference pour captiver les donnees
            case md5('rg_vente_fondroulement_repport'):
              $dataposted = serialize($_POST);
              if (isset($request['action_key']) && $request['datefrom'] != Null && $request['dateto'] != Null && Yii::$app->nonSqlClass->date_compare($request['datefrom'], $request['dateto']) == true) {
                $asbsoluteUrlData = 'repport/fondroulement';
                Yii::$app->session->set('formdataposted', serialize($_POST));
                $this->pg = '/rggeneral/rg_vente/mainreportframe.php';
              } else {
                $msg = serialize(['type' => 'alert alert-danger', 'strong' => Yii::t('app', 'erreur'), 'text' => Yii::t('app', 'invaliddategiven')]); ##### PREPARATION DU MESSAGE
                $service =  Yii::$app->parametreClass->listservices_entreprises($userPrimaryData['idEntreprise'], $userPrimaryData['typeUser'], $userPrimaryData['idEntite']);
                $this->pg = '/rggeneral/rg_vente/margelignevente/filtre.php';
              }
              break;

            default:
              $service =  Yii::$app->parametreClass->listservices_entreprises($userPrimaryData['idEntreprise'], $userPrimaryData['typeUser'], $userPrimaryData['idEntite']);
              $listcategories = Yii::$app->inventaireClass->getCategories($userPrimaryData['idEntreprise']);
              $this->pg = '/rggeneral/rg_vente/fondroulement/filtre.php';
              break;
          }
          break;

          /** Marge brute par ligne de vente**/
        case md5('rg_vente_margenetventeparproduit'):
          switch ($request['action_on_this']) { // Analysons l'indice de reference pour captiver les donnees
            case md5('rg_vente_margenetventeparproduit_repport'):
              $dataposted = serialize($_POST);
              if (isset($request['action_key']) && $request['datefrom'] != Null && $request['dateto'] != Null && Yii::$app->nonSqlClass->date_compare($request['datefrom'], $request['dateto']) == true) {
                $asbsoluteUrlData = 'repport/margenetventeparproduit';
                Yii::$app->session->set('formdataposted', serialize($_POST));
                $this->pg = '/rggeneral/rg_vente/mainreportframe.php';
              } else {
                $msg = serialize(['type' => 'alert alert-danger', 'strong' => Yii::t('app', 'erreur'), 'text' => Yii::t('app', 'invaliddategiven')]); ##### PREPARATION DU MESSAGE
                $service =  Yii::$app->parametreClass->listservices_entreprises($userPrimaryData['idEntreprise'], $userPrimaryData['typeUser'], $userPrimaryData['idEntite']);
                $this->pg = '/rggeneral/rg_vente/margelignevente/filtre.php';
              }
              break;

            default:
              $service =  Yii::$app->parametreClass->listservices_entreprises($userPrimaryData['idEntreprise'], $userPrimaryData['typeUser'], $userPrimaryData['idEntite']);
              $listcategories = Yii::$app->inventaireClass->getCategories($userPrimaryData['idEntreprise']);
              $this->pg = '/rggeneral/rg_vente/margenetlignevente/filtre.php';
              break;
          }
          break;

          /** Vente par article = vente par produit **/
        case md5('rg_vente_venteparproduit'): // Rapport General sur les inventaires : liste articles
          switch ($request['action_on_this']) { // Analysons l'indice de reference pour captiver les donnees
            case md5('rg_vente_venteparproduit_repport'):
              $dataposted = serialize($_POST);
              if (isset($request['action_key']) && $request['datefrom'] != Null && $request['dateto'] != Null && Yii::$app->nonSqlClass->date_compare($request['datefrom'], $request['dateto']) == true) {
                $asbsoluteUrlData = 'repport/venteparproduit';
                Yii::$app->session->set('formdataposted', serialize($_POST));
                $this->pg = '/rggeneral/rg_vente/mainreportframe.php';
              } else {
                $msg = serialize(['type' => 'alert alert-danger', 'strong' => Yii::t('app', 'erreur'), 'text' => Yii::t('app', 'invaliddategiven')]); ##### PREPARATION DU MESSAGE
                $service =  Yii::$app->parametreClass->listservices_entreprises($userPrimaryData['idEntreprise'], $userPrimaryData['typeUser'], $userPrimaryData['idEntite']);
                $this->pg = '/rggeneral/rg_vente/venteparproduit/filtre.php';
              }
              break;

            default:
              $service =  Yii::$app->parametreClass->listservices_entreprises($userPrimaryData['idEntreprise'], $userPrimaryData['typeUser'], $userPrimaryData['idEntite']);
              $this->pg = '/rggeneral/rg_vente/venteparproduit/filtre.php';
              break;
          }
          break;

          /** Ligne de vente des articles**/
        case md5('rg_vente_lignesdevente'):
          switch ($request['action_on_this']) { // Analysons l'indice de reference pour captiver les donnees
            case md5('rg_vente_lignesdevente_repport'):
              $dataposted = serialize($_POST);
              if (isset($request['action_key']) && $request['datefrom'] != Null && $request['dateto'] != Null && Yii::$app->nonSqlClass->date_compare($request['datefrom'], $request['dateto']) == true) {
                $asbsoluteUrlData = 'repport/lignesdevente';
                Yii::$app->session->set('formdataposted', serialize($_POST));
                $this->pg = '/rggeneral/rg_vente/mainreportframe.php';
              } else {
                $msg = serialize(['type' => 'alert alert-danger', 'strong' => Yii::t('app', 'erreur'), 'text' => Yii::t('app', 'invaliddategiven')]); ##### PREPARATION DU MESSAGE
                $service =  Yii::$app->parametreClass->listservices_entreprises($userPrimaryData['idEntreprise'], $userPrimaryData['typeUser'], $userPrimaryData['idEntite']);
                $this->pg = '/rggeneral/rg_vente/venteparproduit/filtre.php';
              }
              break;

            default:
              $service =  Yii::$app->parametreClass->listservices_entreprises($userPrimaryData['idEntreprise'], $userPrimaryData['typeUser'], $userPrimaryData['idEntite']);
              $this->pg = '/rggeneral/rg_vente/lignesdevente/filtre.php';
              break;
          }
          break;

          /** Marge brute par ligne de vente**/
        case md5('rg_vente_margeventeparproduit'):
          switch ($request['action_on_this']) { // Analysons l'indice de reference pour captiver les donnees
            case md5('rg_vente_margeventeparproduit_repport'):
              $dataposted = serialize($_POST);
              if (isset($request['action_key']) && $request['datefrom'] != Null && $request['dateto'] != Null && Yii::$app->nonSqlClass->date_compare($request['datefrom'], $request['dateto']) == true) {
                $asbsoluteUrlData = 'repport/margeventeparproduit';
                Yii::$app->session->set('formdataposted', serialize($_POST));
                $this->pg = '/rggeneral/rg_vente/mainreportframe.php';
              } else {
                $msg = serialize(['type' => 'alert alert-danger', 'strong' => Yii::t('app', 'erreur'), 'text' => Yii::t('app', 'invaliddategiven')]); ##### PREPARATION DU MESSAGE
                $service =  Yii::$app->parametreClass->listservices_entreprises($userPrimaryData['idEntreprise'], $userPrimaryData['typeUser'], $userPrimaryData['idEntite']);
                $this->pg = '/rggeneral/rg_vente/margelignevente/filtre.php';
              }
              break;

            default:
              $service =  Yii::$app->parametreClass->listservices_entreprises($userPrimaryData['idEntreprise'], $userPrimaryData['typeUser'], $userPrimaryData['idEntite']);
              $listcategories = Yii::$app->inventaireClass->getCategories($userPrimaryData['idEntreprise']);
              $this->pg = '/rggeneral/rg_vente/margelignevente/filtre.php';
              break;
          }
          break;

        default:
          $this->pg = '/rggeneral/rg_vente/liste.php';
          break;
      }
    }
    return $this->render($this->pg, ['msg' => $msg, 'services' => $service, 'listcategories' => $listcategories, 'dataposted' => $dataposted, 'asbsoluteUrlData' => $asbsoluteUrlData, 'productCategories' => $productCategories]);
  }

  /** Action rapport sur inventaires **/
  public function actionInvent()
  {
    $msg = $listeservicesentite = $dataposted = $asbsoluteUrlData = $productCategories = null;
    $request = $_POST;
    $this->pg = '/rggeneral/rg_invent/liste.php';
    if (is_array($request) && sizeof($request) > 0) {
      #RECUPERONS LES INFOS PRIMAIRE D'UN UTILISATEUR
      $userPrimaryData = Yii::$app->mainCLass->getUserAuthPrimaryInfo();

      switch ($request['action_key']) {
          /** liste des articles **/
        case md5('rg_invent_listarticle'): // Rapport General sur les inventaires : liste articles
          switch ($request['action_on_this']) { // Analysons l'indice de reference pour captiver les donnees
            case md5('rg_invent_listarticle_repport'):
              $dataposted = serialize($_POST);
              if (isset($request['action_key']) && $request['datefrom'] != Null && $request['dateto'] != Null && Yii::$app->nonSqlClass->date_compare($request['datefrom'], $request['dateto']) == true) {
                $asbsoluteUrlData = 'repport/inventlistarticle';
                Yii::$app->session->set('formdataposted', $dataposted);
                $this->pg = '/rggeneral/rg_invent/mainreportframe.php';
              } else {
                $msg = serialize(['type' => 'alert alert-danger', 'strong' => Yii::t('app', 'erreur'), 'text' => Yii::t('app', 'invaliddategiven')]); ##### PREPARATION DU MESSAGE
                $this->pg = '/rggeneral/rg_invent/listearticle/filtre.php';
              }
              break;

            default:
              $listeservicesentite = Yii::$app->parametreClass->listservices_entreprises($userPrimaryData['idEntreprise'], $userPrimaryData['typeUser'], $userPrimaryData['idEntite']);
              $this->pg = '/rggeneral/rg_invent/listearticle/filtre.php';
              break;
          }
          break;

          /** Analyse des articles **/
        case md5('rg_invent_analysearticle'): // Rapport General sur les inventaires : liste articles
          switch ($request['action_on_this']) { // Analysons l'indice de reference pour captiver les donnees
            case md5('rg_invent_analysearticle_repport'):
              $dataposted = serialize($_POST);
              if (isset($request['action_key'])) {
                $asbsoluteUrlData = 'repport/inventanalysearticle';
                Yii::$app->session->set('formdataposted', serialize($_POST));
                $this->pg = '/rggeneral/rg_invent/mainreportframe.php';
              } else {
                $msg = serialize(['type' => 'alert alert-danger', 'strong' => Yii::t('app', 'erreur'), 'text' => Yii::t('app', 'invaliddategiven')]); ##### PREPARATION DU MESSAGE
                $this->pg = '/rggeneral/rg_invent/analysearticle/filtre.php';
              }
              break;

            default:
              $listeservicesentite = Yii::$app->parametreClass->listservices_entreprises($userPrimaryData['idEntreprise'], $userPrimaryData['typeUser'], $userPrimaryData['idEntite']);
              $this->pg = '/rggeneral/rg_invent/analysearticle/filtre.php';
              break;
          }
          break;


          /** Valeur detaillee du stock **/
        case md5('rg_invent_valeurdetailleestock'): // Rapport General sur les inventaires : liste articles
          switch ($request['action_on_this']) { // Analysons l'indice de reference pour captiver les donnees
            case md5('rg_invent_valeurdetailleestock_repport'):
              $dataposted = serialize($_POST);
              if (isset($request['action_key'])) {
                $asbsoluteUrlData = 'repport/valeurdetailleestock';
                Yii::$app->session->set('formdataposted', serialize($_POST));
                $this->pg = '/rggeneral/rg_invent/mainreportframe.php';
              } else {
                $msg = serialize(['type' => 'alert alert-danger', 'strong' => Yii::t('app', 'erreur'), 'text' => Yii::t('app', 'invaliddategiven')]); ##### PREPARATION DU MESSAGE
                $this->pg = '/rggeneral/rg_invent/analysearticle/filtre.php';
              }
              break;

            default:
              $listeservicesentite = Yii::$app->parametreClass->listservices_entreprises($userPrimaryData['idEntreprise'], $userPrimaryData['typeUser'], $userPrimaryData['idEntite']);
              $this->pg = '/rggeneral/rg_invent/valeurdetailleestock/filtre.php';
              break;
          }
          break;


          /** Historique du stock des articles **/
        case md5('rg_invent_historiquearticle'):
          switch ($request['action_on_this']) { // Analysons l'indice de reference pour captiver les donnees
            case md5('rg_invent_historiquearticle_repport'):
              $dataposted = serialize($_POST);
              if (isset($request['action_key']) && $request['datefrom'] != Null && $request['dateto'] != Null && Yii::$app->nonSqlClass->date_compare($request['datefrom'], $request['dateto']) == true) {
                $asbsoluteUrlData = 'repport/inventhistoriquearticle';
                Yii::$app->session->set('formdataposted', serialize($_POST));
                $this->pg = '/rggeneral/rg_invent/mainreportframe.php';
              } else {
                $msg = serialize(['type' => 'alert alert-danger', 'strong' => Yii::t('app', 'erreur'), 'text' => Yii::t('app', 'invaliddategiven')]); ##### PREPARATION DU MESSAGE
                $listeservicesentite = Yii::$app->parametreClass->listservices_entreprises($userPrimaryData['idEntreprise'], $userPrimaryData['typeUser'], $userPrimaryData['idEntite']);
                $this->pg = '/rggeneral/rg_invent/historiquearticle/filtre.php';
              }
              break;

            default:
              $listeservicesentite = Yii::$app->parametreClass->listservices_entreprises($userPrimaryData['idEntreprise'], $userPrimaryData['typeUser'], $userPrimaryData['idEntite']);
              $this->pg = '/rggeneral/rg_invent/historiquearticle/filtre.php';
              break;
          }
          break;

          /** Article en alerte de stock **/
        case md5('rg_invent_articlealertstok'):
          #RECUPERONS LES INFOS PRIMAIRE D'UN UTILISATEUR
          $userPrimaryData = Yii::$app->mainCLass->getUserAuthPrimaryInfo();
          # RECUPERONS LES PRODUCTS CATEGORIES
          $productCategories = Yii::$app->inventaireClass->getCategories($userPrimaryData['idEntreprise']);

          switch ($request['action_on_this']) { // Analysons l'indice de reference pour captiver les donnees
            case md5('rg_invent_articlealertstok_repport'):
              $dataposted = serialize($_POST);
              if (isset($request['action_key'])) {
                $asbsoluteUrlData = 'repport/inventarticlealertstok';
                Yii::$app->session->set('formdataposted', serialize($_POST));
                $this->pg = '/rggeneral/rg_invent/mainreportframe.php';
              } else {
                $msg = serialize(['type' => 'alert alert-danger', 'strong' => Yii::t('app', 'erreur'), 'text' => Yii::t('app', 'invaliddategiven')]); ##### PREPARATION DU MESSAGE
                $this->pg = '/rggeneral/rg_invent/articlealertstok/filtre.php';
              }
              break;

            default:
              $listeservicesentite = Yii::$app->parametreClass->listservices_entreprises($userPrimaryData['idEntreprise'], $userPrimaryData['typeUser'], $userPrimaryData['idEntite']);
              $this->pg = '/rggeneral/rg_invent/articlealertstok/filtre.php';
              break;
          }
          break;

          /** Article a stock initial **/
        case md5('rg_invent_articlestokinitial'):
          #RECUPERONS LES INFOS PRIMAIRE D'UN UTILISATEUR
          $userPrimaryData = Yii::$app->mainCLass->getUserAuthPrimaryInfo();
          # RECUPERONS LES PRODUCTS CATEGORIES
          $productCategories = Yii::$app->inventaireClass->getCategories($userPrimaryData['idEntreprise']);
          switch ($request['action_on_this']) { // Analysons l'indice de reference pour captiver les donnees
            case md5('rg_invent_articlestokinitial_repport'):
              $dataposted = serialize($_POST);
              if (isset($request['action_key'])) {
                $asbsoluteUrlData = 'repport/articlestokinitial';
                Yii::$app->session->set('formdataposted', serialize($_POST));
                $this->pg = '/rggeneral/rg_invent/mainreportframe.php';
              } else {
                $msg = serialize(['type' => 'alert alert-danger', 'strong' => Yii::t('app', 'erreur'), 'text' => Yii::t('app', 'invaliddategiven')]); ##### PREPARATION DU MESSAGE
                $this->pg = '/rggeneral/rg_invent/articlealertstok/filtre.php';
              }
              break;

            default:
              $listeservicesentite = Yii::$app->parametreClass->listservices_entreprises($userPrimaryData['idEntreprise'], $userPrimaryData['typeUser'], $userPrimaryData['idEntite']);
              $this->pg = '/rggeneral/rg_invent/articlestokinitial/filtre.php';
              break;
          }
          break;

        default:
          $this->pg = '/rggeneral/rg_invent/liste.php';
          break;
      }
    }
    return $this->render($this->pg, ['listeservicesentite' => $listeservicesentite, 'msg' => $msg, 'dataposted' => $dataposted, 'asbsoluteUrlData' => $asbsoluteUrlData, 'productCategories' => $productCategories]);
  }
}
