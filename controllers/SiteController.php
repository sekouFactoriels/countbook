<?php

namespace app\controllers;

use Yii;
use yii\base\View;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
require_once(dirname(__FILE__) . '/AuthController.php');
class SiteController extends Controller
{
  private $pg = Null;

  /** Methode : Accepter les conditions générales d'utilisation **/
  public function actionSignagreement()
  {
    //analyse de l'imput action_key
    if (Yii::$app->request->isPost) {
      $request = $_POST;
      /** Analysons la valeur attribue a action_key **/
      switch ($request['action_key']) {
        case md5('do_agreement'):
          $do_agreement = yii::$app->mainCLass->do_agreement($request['action_on_this'], 0, 0);
          if ($do_agreement) { //Charger le message de reussite de l'operation
            Yii::$app->session->setFlash('flashmsg', '<div class="alert alert-success"> <strong> <i class="fa fa-exclamation-circle">&nbsp;</i> </strong>&nbsp;' . Yii::t("app", "activation_espace_gestion") . '</div>');
          }
          break;
      }
    }
    return $this->redirect(Yii::$app->request->baseUrl . '/' . md5('site_index'));
  }

  # ACTION INDEX
  public function actionIndex()
  {
    $montlychargeviewbyadmin = $montlysaleviewbyadmin = $montlyclientgrowthbysaler = $montlysalegrowthbysaler = $montlyclientbysaler = $monthlytargetpoint = $targetspoint = $msg = $dataPosted = $userLevel = $idactor = $currency = $montlysalegrowthbysaler = $montlysalebysaler = $montlysalegrowthbyadmin = $facture_recouvrir = $solde = $cumulCharge = $topDixClient = $topDixArticle = $cumulDette = $cumulCreance = $valeurStock = $artLowStok = $artLowStokNull = $cumulRecouvPaye = $stockAlerte = $idEntreprise = $objectifVente = $montlysalebysaler = Null;

    /**  **/
    $userPrimaryData = Yii::$app->mainCLass->getUserAuthPrimaryInfo();
    if($userPrimaryData){
      $idEntreprise = $userPrimaryData['idEntreprise'];
      $idactor = $userPrimaryData['auhId'];
      $userLevel = $userPrimaryData['typeUser'];
      $currency = $userPrimaryData['currency'];
      $objectifVente = $userPrimaryData['objectifVente'];
      $objectifClientele = $userPrimaryData['objectifClientele'];
    }else{
      Yii::$app->layout = 'login_layout';
    }

    /** Determinons le mois **/
    //if(isset($_POST['action_on_this']) )

    /** Situation Mensuelle vue : Administrateur **/
    if (isset($_GET[Yii::$app->params['ajax_action_key']]) && $_GET[Yii::$app->params['ajax_action_key']] == md5("adminmonthlysalersituation")) {
      // Recuperons les objectifs fixes par l'entreprise
      $targetspoint = Yii::$app->mainCLass->gettargetpoints($idEntreprise);
      $monthlysaletargetpoint = $targetspoint['objectifVente'];
      $monthlyclienttargetpoint = $targetspoint['objectifClientele'];
      $adminmonthlysalersituation = Yii::$app->venteClass->adminmonthlysalersituation($idEntreprise);
      $getMontlyadmingrowthbysaler = Yii::$app->venteClass->getMontlyadmingrowthbysaler($idEntreprise, $monthlysaletargetpoint, $adminmonthlysalersituation);
      return $getMontlyadmingrowthbysaler;
    }

    // Top 5 des articles vendus
    if (isset($_GET[Yii::$app->params['ajax_action_key']]) && $_GET[Yii::$app->params['ajax_action_key']] == md5("top5articleventeOnIndex")) {
      $subdata = $data = $key2 = Null;
      // Recuperrons les articles enregistres dans cet interval de temps
      $userPrimaryData = Yii::$app->mainCLass->getUserAuthPrimaryInfo();
      // Recuperrons les tops 5 des articles
      $top5desarticlesVendus = Yii::$app->inventaireClass->getTop5DesArticlesVendus($userPrimaryData['idEntreprise']);
      if (sizeof($top5desarticlesVendus) > 0) {
        // chargeons les datas
        foreach ($top5desarticlesVendus as $key => $value) {
          $key2 = ++$key;
          $subdata .= '<tr>
              <td>' . $key2 . '</td>
              <td>' . $value["productCode"] . '
              <td>' . $value["libelle"] . '</td>
              <td>' . $value["undname"] . '</td>
              <td>' . $value["nombre"] . '</td>
            </tr>';
        }
      } else {
        $subdata = '<tr><td colspan="3">Pas de donn&#233;es trouv&#233;s</td></tr>';
      }
      $data = '<table class="table table-striped mb30">
            <thead>
              <tr>
                <th>#</th>
                <th>Code</th>
                <th>Libell&#233;</th>
                <th>UDM</th>
                <th>Record de Vente</th>
              </tr>
            </thead>
            <tbody>' . $subdata . '</tbody></table>';
      return $data;
    }

    // Detail des articles
    if (isset($_GET[Yii::$app->params['ajax_action_key']]) && $_GET["ajax_action_key"] == md5("articleDtlsOnIndex")) {
      // Recuperrons les articles enregistres dans cet interval de temps
      $userPrimaryData = Yii::$app->mainCLass->getUserAuthPrimaryInfo();
      // Statistique des articles a faible stock
      $artLowStok = Yii::$app->inventaireClass->showArticleAlertstokCoreData($userPrimaryData['idEntreprise'], '');
      $artLowStok = count($artLowStok);
      // Statistique de toutes les categories
      $allCat = Yii::$app->inventaireClass->getCatBaseEntrepriseId($userPrimaryData['idEntreprise']);
      $allCat = count($allCat);
      // statistiques de tous les produits
      $adminarray = Yii::$app->params['usersToEditSales'];
      $idservicesuser = base64_encode(in_array($userPrimaryData['typeUser'], $adminarray) ? '0' : $userPrimaryData['idEntite']);
      $idEntite = Yii::$app->parametreClass->listeserviceforrepport($idservicesuser, $userPrimaryData['idEntreprise'], $userPrimaryData['typeUser'], $userPrimaryData['idEntite']);

      $data = '<ul>
          <li>
            <label class="pull-right" style="font-size: 15px;">' . $artLowStok . '</label>
            <h4 class="sender" style="color: red;">' . Yii::t("app", "stocksecurity") . '</h4>
            <small>' . Yii::t("app", "stat_stocksecurity_desc") . '</small>
          </li>
          <li>
            <label class="pull-right" style="font-size: 15px;">' . $allCat . '</label>
            <h4 class="sender">' . Yii::t("app", "cat_produit") . '</h4>
            <small>' . Yii::t("app", "stat_cat_produit") . '</small>
          </li>
          

        </ul>';
      return $data;
    }


    /** Preparer les stats en fonction du profil connecté **/
    switch (true) {
      
      //Cas du proprietaire de l'entreprise
      case in_array($userLevel, Yii::$app->params['usersToEditSales']):

        $montlysaleviewbyadmin = Yii::$app->venteClass->adminmonthlysalersituation($idEntreprise);
        $getTotalchargesmensuelbyadmin = Yii::$app->diverClass->getTotalreappromensuelbyadmin($idEntreprise);

        $montlysalegrowthbyadmin = Yii::$app->venteClass->getMontlysalegrowthbysaler($idEntreprise, $objectifVente,  $montlysaleviewbyadmin);

        $topDixClient = Yii::$app->venteClass->getTopDixClient($idEntreprise);
        $topDixArticle = Yii::$app->venteClass->getTopDixArticle($idEntreprise);

        $cumulDette = Yii::$app->venteClass->getCumulDette($idEntreprise);
        $cumulCreance = Yii::$app->venteClass->getCumulCreance($idEntreprise);

        $valeurStock = Yii::$app->venteClass->getValeurStock($idEntreprise);
        $cumulRecouvPaye = Yii::$app->venteClass->cumulRecouvrementPayer($idEntreprise);

        $cumulCharge = $getTotalchargesmensuelbyadmin + $getTotalchargesmensuelbyadmin;
        $solde = $montlysaleviewbyadmin - $cumulCharge;

        $artLowStok = Yii::$app->inventaireClass->showArticleAlertstokCoreData($userPrimaryData['idEntreprise'], '');
        $artLowStok = count($artLowStok);

        $stockAlerte = Yii::$app->venteClass->getArticleAlertstokData($userPrimaryData['idEntreprise'],'',$userPrimaryData['idEntite']);
        $stockAlerte = count($stockAlerte);
      break;


      default:
        /** Prepare dashboard data **/
        $montlysalebysaler = Yii::$app->venteClass->getMontlysalebysaler($idactor);
        $montlysalegrowthbysaler = Yii::$app->venteClass->getMontlysalegrowthbysaler($idEntreprise, $objectifVente,  $montlysalebysaler);
      break;
    }



    /** analyse de l'imput action_key **/
    if (Yii::$app->request->isPost) {
      $request = $_POST;
      /** Analysons la valeur attribue a action_key **/
      switch ($request['action_key']) {
        case md5('traiter_rappel'):
          $rappel_id = (isset($request['action_on_this'])) ? $request['action_on_this'] : Null;
          $rslt = Yii::$app->mainCLass->traiter_rappel($rappel_id);

        break;

        case md5('detail_top_dix_client'):

          $client = (isset($request['action_on_this']) && $request['action_on_this'] != '') ? $request['action_on_this'] : '';

          //Facturier
          $bills = yii::$app->paiementClass->lister_factures_du_mois($client, $idEntreprise);

          $each_bill_ttpaid = yii::$app->paiementClass->bill_ttpaid($bills);

          //Charger renseignements généraux du client
          $client_id = $client;

          $client = yii::$app->clientClass->getclientdata($client);

          return $this->render('/site/_indexBaseUserLevel/detail/listeTopDixClient.php', ['bills' => $bills, 'each_bill_ttpaid' => $each_bill_ttpaid, 'client' => $client]);
        break;

        case md5('detail_top_dix_article'):

          $idProduit = (isset($request['action_on_this']) && $request['action_on_this'] != '') ? $request['action_on_this'] : '';
          $listes = yii::$app->venteClass->getDetailArticleVente($idProduit);
          // recuperation de l'article
          $connect  = \Yii::$app->db;
          $Article = $connect->createCommand('SELECT slim_product.libelle AS libelle, slim_productcategorie.nom AS categorie, slim_productudm.nom AS uva
                                       FROM slim_product,slim_productcategorie,slim_productudm,slim_stockentrepot
                                       WHERE slim_product.categoryId=slim_productcategorie.id
                                       AND slim_productudm.id=slim_stockentrepot.udm
                                       AND slim_stockentrepot.idProduct=:idProd
                                       AND slim_product.id=:idArticle')
            ->bindValues([':idArticle' => $idProduit, ':idProd' => $idProduit])
            ->queryOne();

          return $this->render('/site/_indexBaseUserLevel/detail/listeTopDixArticle.php', ['Article' => $Article, 'listes' => $listes]);

        break;

        case md5('cumul_creance'):

          $client = (isset($request['action_on_this']) && $request['action_on_this'] != '') ? $request['action_on_this'] : '';

          //Facturier
          $bills = yii::$app->paiementClass->lister_creance_du_mois($idEntreprise);

          $each_bill_ttpaid = yii::$app->paiementClass->bill_ttpaid($bills);

          //Charger renseignements généraux du client
          $client_id = $client;
          $client = yii::$app->clientClass->getclientdata($client);

          return $this->render('/site/_indexBaseUserLevel/detail/listeCreance.php', ['bills' => $bills, 'each_bill_ttpaid' => $each_bill_ttpaid, 'client' => $client]);

        break;

        case md5('cumul_dette'):

          $client = (isset($request['action_on_this']) && $request['action_on_this'] != '') ? $request['action_on_this'] : '';

          //Facturier
          $bills = yii::$app->paiementClass->lister_dette_du_mois($idEntreprise);

          $each_bill_ttpaid = yii::$app->paiementClass->bill_ttpaid($bills);

          //Charger renseignements généraux du client
          $client_id = $client;
          $client = yii::$app->clientClass->getclientdata($client);

          return $this->render('/site/_indexBaseUserLevel/detail/listeDette.php', ['bills' => $bills, 'each_bill_ttpaid' => $each_bill_ttpaid, 'client' => $client]);

        break;

        case md5('vente'):
          $msg = Null;
          $request = $_POST;
          $critere = (isset($request['selectCriteria'])) ? $_POST['selectCriteria'] : 10;
          $donneeRecherche = (isset($request[Yii::$app->params['donneeRecherche']])) ? $_POST[Yii::$app->params['donneeRecherche']] : '';
          $limit = (isset($request[Yii::$app->params['limit']])) ? $_POST[Yii::$app->params['limit']] : 10;
          #RECCUPERONS LES INFOS PRIMAIRE DE L'UTILISATEUR CONNECTE
          $userPrimaryData = Yii::$app->mainCLass->getUserAuthPrimaryInfo();
          # Recuperrons les infos des ventes
          $ventesDtls = Yii::$app->venteClass->getListeVentesMois($critere, $donneeRecherche, $limit);

          return $this->render('/site/_indexBaseUserLevel/detail/cumulVente.php', ['ventesDtls' => $ventesDtls, 'msg' => $msg, 'userPrimaryData' => serialize($userPrimaryData)]);
        break;
        case md5('charge'):

          $userPrimaryData = Yii::$app->mainCLass->getUserAuthPrimaryInfo();

          $listeCharge = Yii::$app->venteClass->getChargeMois($userPrimaryData["idEntreprise"]);

          $listeAppro = Yii::$app->venteClass->getApproMois($userPrimaryData["idEntreprise"]);

          //die(var_dump($charges));
          return $this->render('/site/_indexBaseUserLevel/cumulCharge.php', ['listeCharge' => $listeCharge, 'listeAppro' => $listeAppro]);
        break;

        case md5('stock_securite'):

          $listProduits = Yii::$app->venteClass->getArticleAlertstokCoreData($userPrimaryData['idEntreprise'], '');

          // RECUPERONS LES PRODUCTS CATEGORIES
          $productCategories = Yii::$app->inventaireClass->getCategories($userPrimaryData['idEntreprise']);

          $productUdm = Yii::$app->inventaireClass->getUdmBaseEntrepriseId($userPrimaryData['idEntreprise']);

          return $this->render('/site/_indexBaseUserLevel/detail/stockSecurite.php', ['listProduits' => $listProduits, 'produitUdm' => $productUdm, 'productCategories' => $productCategories]);

        break;

        case md5('recouvrement_paye'):

          $recouvrement_sur = 'Compte client';

          $client = (isset($request['action_on_this']) && $request['action_on_this'] != '') ? $request['action_on_this'] : '';

          //Facturier
          $bills = yii::$app->paiementClass->get_factures_autre_partie_soldees($idEntreprise);

          $each_bill_ttpaid = yii::$app->paiementClass->bill_ttpaid($bills);

          //Charger renseignements généraux du client
          $client_id = $client;
          $client = yii::$app->clientClass->getclientdata($client);

          return $this->render('/site/_indexBaseUserLevel/detail/recouvrementpaye.php', ['bills' => $bills, 'each_bill_ttpaid' => $each_bill_ttpaid, 'client' => $client, 'recouvrement_sur' => $recouvrement_sur]);
        break;

        case md5('stock_alerte'):

          $listProduits = Yii::$app->venteClass->getArticleAlertstokData($userPrimaryData['idEntreprise'], '',$userPrimaryData['idEntite']);

          // RECUPERONS LES PRODUCTS CATEGORIES
          $productCategories = Yii::$app->inventaireClass->getCategories($userPrimaryData['idEntreprise']);

          $productUdm = Yii::$app->inventaireClass->getUdmBaseEntrepriseId($userPrimaryData['idEntreprise']);

          return $this->render('/site/_indexBaseUserLevel/detail/stockAlerte.php', ['listProduits' => $listProduits, 'produitUdm' => $productUdm, 'productCategories' => $productCategories]);

        break;
      }
    }

    //Charger la liste des factures a recouvrir
    $facture_recouvrir = yii::$app->paiementClass->facture_recouvrir_mois_encours($idEntreprise);

    $this->pg = 'index';
    return $this->render($this->pg, ['monthlysaletargetpoint' => $objectifVente, 'montlysalegrowthbyadmin' => $montlysalegrowthbyadmin, 'montlychargeviewbyadmin' => $montlychargeviewbyadmin, 'montlysaleviewbyadmin' => $montlysaleviewbyadmin, 'montlyclientgrowthbysaler' => $montlyclientgrowthbysaler, 'montlyclientbysaler' => $montlyclientbysaler, 'monthlytargetpoint' => $monthlytargetpoint, 'montlysalegrowthbysaler' => $montlysalegrowthbysaler, 'montlysalebysaler' => $montlysalebysaler, 'currency' => $currency, 'userLevel' => $userLevel, 'idactor' => $idactor, 'msg' => $msg, 'dataPosted' => $dataPosted, 'facture_recouvrir' => $facture_recouvrir, 'solde' => $solde, 'cumulCharge' => $cumulCharge, 'topDixClient' => $topDixClient, 'topDixArticle' => $topDixArticle, 'cumulDette' => $cumulDette, 'cumulCreance' => $cumulCreance, 'valeurStock' => $valeurStock, 'artLowStok' => $artLowStok, 'cumulRecouvPaye' => $cumulRecouvPaye, 'stockAlerte' => $stockAlerte]);
  }

  # ACTION LOGIN
  public function actionLogin()
  {
    Yii::$app->layout = 'login_layout';
    if (Yii::$app->request->isPost) { // MAKE SURE THE USER HAS REALLY SUBMITTED
      switch (AuthController::authentifcation()) { // CKECK THE USER AUTHENTIFICATION
        case 'success': // IF RESPONSE IS SUCCESS
          return $this->redirect(md5('site_index')); // REDIRECT IT TO THE ACTION INDEX
          break;

        case '11':
          $msg = ['type' => 'rounded-md bg-red-200 p-4', 'strong' => Yii::t('app', 'erreur'), 'text' => Yii::t('app', 'champObligatoire')];
          return $this->render('login', [
            'msg' => $msg,
            'userName' => $_POST['userName'],
            'motPass' => $_POST['motPass'],
            'sugarpot' => $_POST['sugarpot'],
          ]);
          break;

        case '12':
          $msg = ['type' => 'rounded-md bg-red-200 p-4', 'strong' => Yii::t('app', 'erreur'), 'text' => Yii::t('app', 'invalidAction')];
          return $this->render('login', [
            'msg' => $msg,
            'userName' => $_POST['userName'],
            'motPass' => $_POST['motPass'],
            'sugarpot' => $_POST['sugarpot'],
          ]);
          break;

        case '22':
          $msg = ['type' => 'rounded-md bg-red-200 p-4', 'strong' => Yii::t('app', 'erreur'), 'text' => Yii::t('app', 'invalidUser')];
          return $this->render('login', [
            'msg' => $msg,
            'userName' => $_POST['userName'],
            'motPass' => $_POST['motPass'],
            'sugarpot' => $_POST['sugarpot'],
          ]);
          break;

        case '33':
          $msg = ['type' => 'rounded-md bg-red-200 p-4', 'strong' => Yii::t('app', 'erreur'), 'text' => Yii::t('app', 'accessNonAuthoriser')];
          return $this->render('login', [
            'msg' => $msg,
            'userName' => $_POST['userName'],
            'motPass' => $_POST['motPass'],
            'sugarpot' => $_POST['sugarpot'],
          ]);
          break;
      }
    }
    return $this->render('login');
  }

}
