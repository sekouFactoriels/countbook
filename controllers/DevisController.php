<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\RgUsrInfo;
use yii\db\Query;

class DevisController extends Controller
{

    private $pg = Null;

    public function actionThemain()
    {


        $userPrimaryData = $request = $productVenduDtls = $devis = $msg = $ventesDtls = $listeProduit = $ProductdtlsForSale = $listeClient = $dataPosted = $banques = Null;
        $this->pg = '/devis/themain/mainpage.php';
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
        $ProductdtlsForSale = Yii::$app->inventaireClass->getProductdtlsForSaleBaseEntreprise($idEntite);

        // Ajax : ajouter le produit au panier de vente
        if (isset($_GET['selectedDiv']) && isset($_GET['addToSaleBaquet']) && $_GET['addToSaleBaquet'] == md5('1')) {
            // Recuperrons la valeur de l'element selectionner
            $selectectedELement = json_decode(base64_decode($_GET['selectedDiv']));
            $rslt = '<tr id="row_' . $selectectedELement->slimproductid . '" class="eachrow">
                        <td><input type="hidden" Class="totalBought" name="sousTotalAchat[]" id="sousTotalAchat_' . $selectectedELement->slimproductid . '" value="">&#8226;<input autocomplete="off" type="hidden" name="selectectedELement[]" id="selectectedELement" value="' . $_GET['selectedDiv'] . '"></td>
                        <td>' . $selectectedELement->productCode . ' : ' . $selectectedELement->libelle . '</td>
                        <td>' . Yii::$app->inventaireClass->getUdmLabel($selectectedELement->udm) . '</td>
                        <td>' . $selectectedELement->qteDispo . '</td>
                        <td class="text-center">
                        <div class="form-group text-center" style="margin-left: 3%;">
                                              <div class="input-group">
                       
                        <input autocomplete="off" class="form-control qtes text-center" style="width: 50%;" onKeyup="calculSubTotal(this.id,' . $selectectedELement->qteDispo . ',  ' . $selectectedELement->prixUnitaireVente . ', ' . $selectectedELement->slimproductid . ',' . $selectectedELement->prixUnitaireAchat . ')" name="qteToBeSale[]" id="qteToBeSale_' . $selectectedELement->slimproductid . '" value="0">
                        
                        <a href="javascript:;" onclick="decQte(this.id,' . $selectectedELement->qteDispo . ',  ' . $selectectedELement->prixUnitaireVente . ', ' . $selectectedELement->slimproductid . ',' . $selectectedELement->prixUnitaireAchat . ',' . $selectectedELement->slimproductid . ')" name="qteToBeSale[]" id="qteToBeSale_' . $selectectedELement->slimproductid . '" class="input-group-text btn btn-warning qtebtn dec" id="btn1">-</a>
                        &nbsp;
                        <a href="javascript:;" onclick="incQte(this.id,' . $selectectedELement->qteDispo . ',  ' . $selectectedELement->prixUnitaireVente . ', ' . $selectectedELement->slimproductid . ',' . $selectectedELement->prixUnitaireAchat . ',' . $selectectedELement->slimproductid . ')" name="qteToBeSale[]" id="qteToBeSale_' . $selectectedELement->slimproductid . '" class="input-group-text btn btn-success qtebtn inc" id="btn2">+</a>
                        </div>
                        </div>
                        </td>
                        <td><input autocomplete="off" class="form-control totalmustpay" type="number"  name="sousTotalLabel[]" id="sousTotalLabel_' . $selectectedELement->slimproductid . '"></td>
                        <td><a id="' . $selectectedELement->slimproductid . '" class="btn btn-danger" onclick="deletThisRow(this.id)"><i Class="fa fa-times">&nbsp;</i></a></td>
                      </tr>
                    ';
            return $rslt;
        }
        //analyse de l'imput action_key
        if (Yii::$app->request->isPost) {
            $request = $_POST;
            /** Analysons la valeur attribue a action_key **/
            switch ($request['action_key']) {

                    //---------- Nouveau Bond de Commande --------------------------//
                case md5('new_devis'):
                    $this->pg = '/devis/themain/newdevis.php';
                    $UserAuthPrimaryInfo = Yii::$app->mainCLass->getUserAuthPrimaryInfo();
                    $adminarray = Yii::$app->params['usersToEditSales'];
                    $idservicesuser = base64_encode(in_array($UserAuthPrimaryInfo['typeUser'], $adminarray) ? '0' : $UserAuthPrimaryInfo['idEntite']);
                    $idEntite = Yii::$app->parametreClass->listeserviceforrepport($idservicesuser, $UserAuthPrimaryInfo['idEntreprise'], $UserAuthPrimaryInfo['typeUser'], $UserAuthPrimaryInfo['idEntite']);
                    $listeProduit = Yii::$app->venteClass->getProductBaseEntreprise($idEntite);
                    $listeClient = Yii::$app->venteClass->getAllClient($UserAuthPrimaryInfo['idEntreprise']);
                    $banques = yii::$app->mainCLass->charger_banques_entreprise($UserAuthPrimaryInfo['idEntreprise']);
                    return $this->render($this->pg, ['userPrimaryData' => serialize($userPrimaryData), 'productVenduDtls' => $productVenduDtls, 'ventesDtls' => $ventesDtls, 'dataPosted' => $dataPosted, 'msg' => $msg, 'listeClient' => $listeClient, 'listeProduit' => $listeProduit, 'ProductdtlsForSale' => $ProductdtlsForSale, 'banques' => $banques]);
                    break;

                case md5('liste_devis'):

                    $devis = Yii::$app->devisClass->getAllDevis($actor_idEntreprise, $actor_idEntite);
                    $this->pg = '/devis/themain/listedevis.php';

                    break;

                case md5('enrgdevis'):
                    $requirement = $bill_saved = $numero_facture = $pj_mode_paiement = $stmt = Null;

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
                        $numero_facture = yii::$app->mainCLass->get_orderer_number(strtolower('devis_client'));

                        // die(print_r($numero_facture));

                        $bill_saved = yii::$app->devisClass->enrgBillDevis($numero_facture, $actor_idEntreprise, $actor_idEntite);
                    }

                    //Enregistrer le devis
                    if ($bill_saved) {
                        if (isset($request['totalMonetaire']) && $request['totalMonetaire'] != 0) {
                            // die(print_r('bingoo'));
                            $ventedate = Yii::$app->nonSqlClass->convert_date_to_sql_form($request['ventedate'], "M/D/Y");
                            $stmt = Yii::$app->devisClass->addDevis($numero_facture, $request['acheteur'], $ventedate, $request['totalMonetaire'], $request['remiseMonetaire'], $request['montantFinal'], $actor_idEntreprise, $actor_idEntite, $idactor);
                        }
                    }

                    switch ($stmt) {
                        case '92':
                            $RecentDevisid = Yii::$app->devisClass->getDevisId($numero_facture);
                            foreach ($request['selectectedELement'] as $key => $value) {
                                $qteSold = $request['qteToBeSale'][$key];
                                $sub_selectectedELement = json_decode(base64_decode($value));

                                // $idProd, $idDevis, $quatite, $prixUnitaire, $prixTotal, $idEntreprise, $idEntite)

                                $stmt = Yii::$app->devisClass->addDetailDevis($sub_selectectedELement->slimproductid, $RecentDevisid, $qteSold, $sub_selectectedELement->prixUnitaireVente, $request['sousTotalLabel'][$key], $actor_idEntreprise, $actor_idEntite);
                            }
                            break;
                    }
                    break;
                    //CHARGER LA FACTURE
                case md5('charger_facture_data'):
                    $dataposted = serialize($_POST);
                    $asbsoluteUrlData = 'repport/devis';
                    yii::$app->session->set('formdataposted', $dataposted);
                    $this->pg = 'themain/devis_mainreportframe.php';
                    return $this->render($this->pg, ['asbsoluteUrlData' => $asbsoluteUrlData]);
                    break;
            }
        }
        return $this->render($this->pg, ['devis' => $devis]);
    }
}
