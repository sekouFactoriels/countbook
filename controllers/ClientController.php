<?php
namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;

class ClientController extends Controller {
  private $pg = Null;
  private $msg = Null;

  /** Espace Client **/
  public function actionThemain()
  {
    $UserAuthPrimaryInfo = Yii::$app->mainCLass->getUserAuthPrimaryInfo();
    $user_id = $UserAuthPrimaryInfo['auhId'];
    $entreprise_id = $UserAuthPrimaryInfo['idEntreprise'];
    $entite_id = $UserAuthPrimaryInfo['idEntite'];

    /** POST REQUEST **/
    if(Yii::$app->request->isPost)
    {
      $request = $_POST;
      /** Analysons la valeur attribue a action_key **/
      switch ($request['action_key']) 
      {

        /** Modifier les informations du client **/
        case md5('modifier_client'):
          if( (isset($request['appelation']) && $request['appelation'] != "") && (isset($request['tel']) && $request['tel'] != "")  && (isset($request['tel']) && $request['tel'] != "") )
            {
              // Enregistre les modifications effectuees
              $client = $request['action_on_this'];
              $rslt = yii::$app->clientClass->modifierClient($request, $client);
              Yii::$app->session->setFlash('flashmsg', '<div class="alert alert-success"> <strong> <i class="fa fa-exclamation-circle">&nbsp;</i> </strong>&nbsp;'.Yii::t("app","enrgSuccess").'</div>');
              return $this->redirect(Yii::$app->request->baseUrl.'/'.md5('client_themain'));
            }
        break;

        /** Charger le profil client **/
        case md5('charger_data_client'):
          $this->pg = '/client/profil_client.php';

          $client = (isset($request['action_on_this']) && $request['action_on_this'] != '') ? $request['action_on_this'] : '';
          
          $bills = Null;

          //Total montant déjà payé
          $total_montant_paid = yii::$app->paiementClass->calculer_tt_montant_paid_autre_partie($client, $entreprise_id);

          //Total montant non payé
          $total_montant_unpaid = yii::$app->paiementClass->calculer_tt_montant_unpaid_autre_partie($total_montant_paid, $client, $entreprise_id);

          //Facturier
          $bills = yii::$app->paiementClass->lister_factures($client, $entreprise_id);

          $each_bill_ttpaid = yii::$app->paiementClass->bill_ttpaid($bills);

          //Charger renseignements généraux du client
          $client_id = $client;
          $client = yii::$app->clientClass->getclientdata($client);

          //Render view
          return $this->render($this->pg, ['client'=>$client, 'total_montant_unpaid'=>$total_montant_unpaid, 'total_montant_paid'=>$total_montant_paid, 'bills'=>$bills, 'each_bill_ttpaid'=>$each_bill_ttpaid, 'client_id'=>$client_id]);
        break;

        /** Enregistrer un nouveau client **/
        case md5('enregistrer_client'):
            if(Yii::$app->session['token2']!=$_POST['token2']){
              if(isset($request['statutClient']) && $request['statutClient'] > 0 && isset($request['segmentClient']) && $request['segmentClient'] > 0 && !empty($request['appelation']))
              {
                $insertCnt = Yii::$app->clientClass->enrgclient($request['statutClient'], $request['segmentClient'], $request['appelation'], $request['dtebirth'], $request['tel'], $request['email'], $request['enrgmotif'], $request['dtlsmotif'], $entreprise_id, $user_id);
                switch ($insertCnt) { /** Analysons le resultat retourne **/
                  case '2626': /** Duplication **/
                    
                    $this->pg = '/client/newclient.php';
                    $clientEnrgMotifs = Yii::$app->parametreClass->clientEnrgMotifs($entreprise_id);                
                    //Afficher le msg de champs vides
                    Yii::$app->session->setFlash('flashmsg', '<div class="alert alert-danger"> <strong> <i class="fa fa-exclamation-circle">&nbsp;</i> </strong>&nbsp;'.Yii::t("app","duplication").'</div>');
                    return $this->render($this->pg, ['clientEnrgMotifs'=>$clientEnrgMotifs]);
                  break;

                  case '2692': /** Enregistrerment avec success **/
                    Yii::$app->session->set(Yii::$app->params['tok2'], $_POST[Yii::$app->params['tok2']]);
                    //Afficher le msg de champs vides
                    Yii::$app->session->setFlash('flashmsg', '<div class="alert alert-success"> <strong> <i class="fa fa-exclamation-circle">&nbsp;</i> </strong>&nbsp;'.Yii::t("app","enrgSuccess").'</div>');
                    return $this->redirect(Yii::$app->request->baseUrl.'/'.md5('client_themain'));
                  break;
                }
              }else{ // Champs obligatoires vides
                $this->pg = '/client/newclient.php';
                $clientEnrgMotifs = Yii::$app->parametreClass->clientEnrgMotifs($entreprise_id);                
                //Afficher le msg de champs vides
                Yii::$app->session->setFlash('flashmsg', '<div class="alert alert-warning"> <strong> <i class="fa fa-exclamation-circle">&nbsp;</i> </strong>&nbsp;'.Yii::t("app","doneesForcesVides").'</div>');
                return $this->render($this->pg, ['clientEnrgMotifs'=>$clientEnrgMotifs]);
              }
            }
          break;

        /** Charger la liste des clients enregistrés **/
        case md5('lister_clients'):
          $this->pg = '/client/clients.php';
          //$critere = (isset($request['selectCriteria'])) ? $_POST['selectCriteria'] : 10 ;
          $donneeRecherche = (isset($request[Yii::$app->params['donneeRecherche']])) ? $_POST[Yii::$app->params['donneeRecherche']] : '' ;
          $limit = (isset($request[Yii::$app->params['limit']])) ? $_POST[Yii::$app->params['limit']] : 1;
          $clients = yii::$app->clientClass->listeclients($entreprise_id, $donneeRecherche, $limit);
          return $this->render($this->pg, ['clients'=>$clients, 'donneeRecherche'=>$donneeRecherche, 'limit'=>$limit]);
        break;

        /** Charger formulaire enregistrement new client **/
        case md5('nouveau_client'):
          $this->pg = '/client/newclient.php';
          //chargement  all enregistrement  motifs
          $clientEnrgMotifs = Yii::$app->parametreClass->clientEnrgMotifs($entreprise_id);
          return $this->render($this->pg, ['clientEnrgMotifs'=>$clientEnrgMotifs]);
        break;
      }
    }
    $this->pg = '/client/themain.php';
    return $this->render($this->pg);
  }

}