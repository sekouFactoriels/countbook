<?php
namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;

class FournisseurController extends Controller 
{
  private $pg = Null;
  private $msg = Null;

  /** Espace fournisseur **/
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

        //ENREGITRER LES MODIFICATIONS DE RENSEIGNEMENT GENERAUX
        case md5('enrg_modifis_renseignement_fournisseur'):
          $this->pg = '/fournisseur/profil_fournisseur.php';

          $fournisseur = (isset($request['action_on_this']) && $request['action_on_this'] != '') ? $request['action_on_this'] : '';

          if($fournisseur)
          {
            yii::$app->fournisseurClass->update_renseignement_gnr($request, $fournisseur, $user_id);
            //AFFICHER LE MSG D'ENREGISTREMENT 
            Yii::$app->session->setFlash('flashmsg', '<div class="alert alert-success"> <strong> <i class="fa fa-exclamation-circle">&nbsp;</i> </strong>&nbsp;'.Yii::t("app","operatSucess").'</div>');
          }
          return $this->redirect(Yii::$app->request->baseUrl.'/'.md5('fournisseur_themain'));

        break;


        //CHARGER LES RENSEIGNEMENT SUR UN FOURNISSEUR ET L'HISTORIQUE DES TRANSACTIONS
        case md5('charger_data_fournisseur'):
          $this->pg = '/fournisseur/profil_fournisseur.php';

          $fournisseur = (isset($request['action_on_this']) && $request['action_on_this'] != '') ? $request['action_on_this'] : '';
          
          $bills = Null;

          //Total montant déjà payé
          $total_montant_paid = yii::$app->paiementClass->calculer_tt_montant_paid_autre_partie($fournisseur, $entreprise_id);

          //Total montant non payé
          $total_montant_unpaid = yii::$app->paiementClass->calculer_tt_montant_unpaid_autre_partie($total_montant_paid, $fournisseur, $entreprise_id);

          //Facturier
          $bills = yii::$app->paiementClass->lister_factures($fournisseur, $entreprise_id);

          $each_bill_ttpaid = yii::$app->paiementClass->bill_ttpaid($bills);

          //Charger renseignements généraux du fournisseur
          $fournisseur_id = $fournisseur;
          $fournisseur = yii::$app->fournisseurClass->charger_fournisseur($fournisseur);

          //Render view
          return $this->render($this->pg, ['fournisseur'=>$fournisseur, 'total_montant_unpaid'=>$total_montant_unpaid, 'total_montant_paid'=>$total_montant_paid, 'bills'=>$bills, 'each_bill_ttpaid'=>$each_bill_ttpaid, 'fournisseur_id'=>$fournisseur_id]);
        break;


        //CHARGER LA LISTE DES FOURNISSEURS
        case md5('lister_fournisseurs'):
          $donneeRecherche = (isset($request[Yii::$app->params['donneeRecherche']])) ? $_POST[Yii::$app->params['donneeRecherche']] : '' ;
          $limit = (isset($request[Yii::$app->params['limit']])) ? $_POST[Yii::$app->params['limit']] : 1;

          $fournisseurs = yii::$app->fournisseurClass->lister_fournisseurs($entite_id, $donneeRecherche, $limit);
          $this->pg = '/fournisseur/fournisseurs.php';
          return $this->render($this->pg, ['fournisseurs'=>$fournisseurs, 'donneeRecherche'=>$donneeRecherche, 'limit'=>$limit]);
        break;

        //ENREGISTRER FOURNISSEUR
        case md5('enregistrer_fournisseur'):
          if(isset($request['raison_sociale']) && $request['raison_sociale'] != '' && isset($request['denomination']) && $request['denomination'] != '' && isset($request['telephone']) && $request['telephone'] !='')
          {
            /** Verifier si le fournisseur n'est pas deja enregistré **/
            $nouveau_fourn = yii::$app->fournisseurClass->fournisseur_est_nouveau($request);

            //Afficher le msg de duplication
            if(!isset($nouveau_fourn))
            {
              Yii::$app->session->setFlash('flashmsg', '<div class="alert alert-danger"> <strong> <i class="fa fa-exclamation-circle">&nbsp;</i> </strong>&nbsp;'.Yii::t("app","operatSucess").'</div>');
              $this->pg = '/fournisseur/fournisseur.php';
              return $this->render($this->pg, ['request'=>$request]);
            }

            /** Enregistrer le fournisseur **/
            $saved_fourn = yii::$app->fournisseurClass->enregisrer_fourn($request, $user_id, $entreprise_id, $entite_id);

            //AFFICHER LE MSG D'ENREGISTREMENT 
            Yii::$app->session->setFlash('flashmsg', '<div class="alert alert-success"> <strong> <i class="fa fa-exclamation-circle">&nbsp;</i> </strong>&nbsp;'.Yii::t("app","operatSucess").'</div>');

          }else{
            //Afficher le msg de champs vides
            Yii::$app->session->setFlash('flashmsg', '<div class="alert alert-warning"> <strong> <i class="fa fa-exclamation-circle">&nbsp;</i> </strong>&nbsp;'.Yii::t("app","doneesForcesVides").'</div>');
            $this->pg = '/fournisseur/fournisseur.php';
            return $this->render($this->pg, ['request'=>$request]);
          }
        break;


        // NOUVEAU FOURNISSEUR
        case md5('nouveau_fournisseur'):
          $this->pg = '/fournisseur/fournisseur.php';
          return $this->render($this->pg);
        break;
      }
    }
    $this->pg = '/fournisseur/themain.php';
    return $this->render($this->pg);
  }
}