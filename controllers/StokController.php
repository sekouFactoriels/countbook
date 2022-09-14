<?php
namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;

class StokController extends Controller {
    private $pg = Null;
    private $msg = Null;

    //private $connect = \Yii::$app->db;
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }
    public function actions(){
        return [
            'connect'=>'\Yii::$app->db',
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }
    #******************************************
    #           NOMENCLATURE  DES ACTIONS
    # Nom de l'action : NomDOssier_Nomdufichier
    #******************************************

    /** Ajustement unitaire de produit : Formulaire d'ajustment **/
    public function actionAjustmentunitaire(){
        # GET USER AUTH PRIMARY INFOS
      $UserAuthPrimaryInfo = Yii::$app->mainCLass->getUserAuthPrimaryInfo();
        # SELECTIONNER LA LISTE DES ENTREPOTS D'UNE ENTREPRISE
      $listPointVente = Yii::$app->stokClass->getEntrepriseEntite($UserAuthPrimaryInfo['idEntreprise'], 1);
        # SELECTIONNER LA LISTE DES POINTS DE VENTES D'UNE ENTREPRISE
      $listEntrepot = Yii::$app->stokClass->getEntrepriseEntite($UserAuthPrimaryInfo['idEntreprise'], 2);
        # GET LIST OF PRODUCT
      $listProdcts = Yii::$app->inventaireClass->getProductsBaseidEntreprise($UserAuthPrimaryInfo['idEntreprise'], 2);
        # RENDER THE VIEW
      $this->pg = '/stok/ajustmentunitaire/ajustmentunitaire';
      return $this->render($this->pg, ['listEntrepot'=>$listEntrepot, 'listPointVente'=>$listPointVente, 'listProdcts'=>$listProdcts]);
    }


    /** action fournisseur **/
   public function actionFournisseur(){
     $request=$msg= $founisseurListe = Null;
    $this->pg ='/stok/fournisseur/fournisseur.php';
    //analyse de l'imput action_key
      if(Yii::$app->request->isPost){
        $request = $_POST;
        /* Recuperrons les infos basics de l'utilisateur connected */
        $UserAuthPrimaryInfo = Yii::$app->mainCLass->getUserAuthPrimaryInfo();

        /** Analysons la valeur attribue a action_key **/
        switch ($request['action_key']) {

            case md5('nfournisseur'):
                $this->pg = '/stok/fournisseur/nfournisseur.php';
            break;

            case md5('save_nfournisseur'):
            $connect= \yii::$app->db;
            $ins=$connect->createCommand('INSERT INTO slim_fournisseur(typeFourn,nom,societe,adresse,telephone, siteWeb, mobil, typeSociete,idEntreprise,idEntite) VALUES(:ty,:no,:socie,:adres,:tel,:site,:mobil,:type,:ent,:enti)')
            ->bindValues([':ty'=>$request['typeFourn'],
                ':no'=>$request['nom'],
                ':socie'=>$request['societe'],
                ':adres'=>$request['adresse'],
                ':tel'=>$request['tel'],
                ':site'=>$request['site'],
                ':mobil'=>$request['mobil'],
                ':type'=>$request['typeSoci'],
                 ':ent'=>$UserAuthPrimaryInfo['idEntreprise'],
                ':enti'=>$UserAuthPrimaryInfo['idEntite']
                ])
            ->execute();
            $this->pg ='/stok/fournisseur/nfournisseur.php';
            break;


            case md5('lfournisseurs'):
                $this->pg = '/stok/fournisseur/lfournisseurs.php';

                /* recuperons la liste de tous les fournisseurs */
                $founisseurListe = yii::$app->stokClass->getAllFounisseur($UserAuthPrimaryInfo['idEntreprise']);
            break;
        }
    }
    return $this->render($this->pg,['msg'=>$msg, 'founisseurListe'=>$founisseurListe]);
   }

    /** Ajustement de masse de produit : Upload de fichier excel **/
    public function actionAjustmentmasse(){

    }


}
