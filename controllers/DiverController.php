<?php
namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;

class DiverController extends Controller {
    private $pg = Null;
    private $msg = Null;


    #Charge de l'entreprise
    public function actionCharge(){
      $msg = $listMotif = $idActor = $listeCharges = Null;
      $this->pg = '/diver/charge/filtre.php';
      $UserAuthPrimaryInfo = Yii::$app->mainCLass->getUserAuthPrimaryInfo();
      //Ajax request
      if(isset($_GET['action_key']) && isset($_GET['ajax_action_key']) && $_GET['ajax_action_key']== md5('newMotif') && isset($_GET['motifLabel'])){
        $stmt = Yii::$app->diverClass->insertMotifLabel($_GET['motifLabel'], $UserAuthPrimaryInfo['idEntreprise'], $UserAuthPrimaryInfo['auhId']);
        return $stmt;
      }
      //analyse de l'imput action_key
      if(Yii::$app->request->isPost){
        $request = $_POST;
        /** Analysons la valeur attribue a action_key **/
        switch ($request['action_key']) {
          //--------------------------- Liste des charges ---------------------------//
          case md5('charges'):
            $listMotif = Yii::$app->diverClass->listMotif($UserAuthPrimaryInfo['idEntreprise']);
            $listeCharges = Yii::$app->diverClass->listeCharges($UserAuthPrimaryInfo['idEntreprise'], $UserAuthPrimaryInfo['typeUser'], $UserAuthPrimaryInfo['auhId']);
            $this->pg = '/diver/charge/charges.php';
          break;
          //--------------------------- Ouvrir un formulaire de creation de charge ---------------------------//
          case md5('ncharge'):
            $listMotif = Yii::$app->diverClass->listMotif($UserAuthPrimaryInfo['idEntreprise']);
            $this->pg = '/diver/charge/ncharge.php';
          break;
          //--------------------------- Enregistrer la charge ---------------------------//
          case md5('enrgCharge'):
            $date = Yii::$app->nonSqlClass->convert_date_to_sql_form($request['chargedate'], 'M/D/Y','Y-M-D');
            if(isset($request['motif']) && isset($request['chargedate']) && isset($request['montant']) && !empty($request['montant'])){
              //Avoid many insertion on many submit
              if(Yii::$app->session['token2']!=$_POST['token2']){
                $stmt = Yii::$app->diverClass->newCharge($request['motif'],$date, $request['montant'], $request['desc'], $UserAuthPrimaryInfo['auhId'], $UserAuthPrimaryInfo['idEntreprise']);
                if($stmt == '2604'){
                  $this->pg = '/diver/charge/filtre.php';
                  #### EVENEMENT : TYPE : augmentation de la quantite entrepose en stok ###
                  $event_msg = Yii::t('app','msg_nouvellechargeajouteravecsuccess');
                  Yii::$app->mainCLass->creerEvent('016', $event_msg);
                  /** Evitons la duplication du dernier enregistrement **/
                  Yii::$app->session->set(Yii::$app->params['tok2'], $_POST[Yii::$app->params['tok2']]);
                  $msg = serialize(['type'=>'alert alert-success','strong'=>Yii::t('app','success'),'text'=>Yii::t('app','msg_nouvellechargeajouteravecsuccess')]); ##### PREPARATION DU MESSAGE
                }
              }
            } else {
              $this->pg = '/diver/charge/ncharge.php';
              $listMotif = Yii::$app->diverClass->listMotif($UserAuthPrimaryInfo['idEntreprise']);
              $msg = serialize(['type'=>'alert alert-danger','strong'=>Yii::t('app','erreur'),'text'=>Yii::t('app','champObligatoire')]);
            }
          break;
        }
      }
      return $this->render($this->pg, ['msg'=>$msg, 'listMotif'=>$listMotif, 'listeCharges'=>$listeCharges]);
    }

}
