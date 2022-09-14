<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;

class ReajustementController extends Controller
{
    private $pg = Null;
    private $msg = Null;


    #Reagistement du stock
    public function actionThemain()
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

        $listProduits = Yii::$app->inventaireClass->getReajustementProd($critere, $donneeRecherche, $limit, $idEntite);

        // RECUPERONS LES PRODUCTS CATEGORIES
        $productCategories = Yii::$app->inventaireClass->getCategories($userPrimaryData['idEntreprise']);

        // RECUPERONS LES PRODUCTS MARQUES
        $productMarque = Yii::$app->inventaireClass->getProductMarque($userPrimaryData['idEntreprise']);

        // RECUPERONS LA LISTE DE TOUS LES PRODUITS DANS UN ENTREPOT
        $entrepotProductQte = Yii::$app->inventaireClass->getAllProductQteInEntrepot($userPrimaryData['idEntreprise']);
        // Recuperons l'udm du produit
        $productUdm = Yii::$app->inventaireClass->getUdmBaseEntrepriseId($userPrimaryData['idEntreprise']);

        $this->pg = '/reagistement/themain/reagistement';

        /** Accedons a la vue now **/



        if (isset($_POST['action_key']) && $_POST['action_key'] == md5('reajuster')) {
            $request = $_POST;
            $i = 0;
            foreach ($request['selectectedELement'] as $key => $value) {
                $qteSold = $request['qteExistante'][$key];
                $id = $request['selectectedELement'][$key];
                
                if ($qteSold != '' || $qteSold != 0) {

                    $stmt = Yii::$app->inventaireClass->reajustementeQte($id, $qteSold);
                }
            }

            // $this->pg = '/reagistement/themain/reagistement';
        }


        return $this->render($this->pg, ['msg' => $msg, 'productFabricant' => $productFabricant, 'productGenericName' => $productGenericName, 'productGroupes' => $productGroupes, 'produitDtls' => $produitDtls, 'produitUdm' => $productUdm, 'listProduits' => $listProduits, 'productCategories' => $productCategories, 'productMarque' => $productMarque, 'entrepotProductQte' => $entrepotProductQte, 'historReapp' => $historReapp, 'Article' => $Article]);
    }
}
