<?php
namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;

class PaiementController extends Controller 
{
  private $pg = Null;
  private $msg = Null;

  /** Methode : Faire un paiement **/
  public function actionThemain()
  {
    $this->pg = '/recouvrement/themain.php';
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
        //CHARGER LA FACTURE
        case md5('charger_facture_data'):
          $dataposted = serialize($_POST);
          $asbsoluteUrlData='repport/facture';
          yii::$app->session->set('formdataposted',$dataposted);
          $this->pg = '/recouvrement/facture_mainreportframe.php';
          return $this->render($this->pg, ['asbsoluteUrlData'=>$asbsoluteUrlData]);
        break;

        //CHARGER LA LISTE DES FACTURES CLIENTS NON RECOUVERTS
        case md5('lister_factures_clients_non_soldees'):
        case md5('mettre_a_jour_le_statut_de_la_facture'):
          $this->pg = '/recouvrement/themain_unpaid_bill.php';
          if($request['action_key'] == md5('mettre_a_jour_le_statut_de_la_facture'))
          {
            $bill_id = $request['action_on_this_val'];
            yii::$app->paiementClass->solder_dette($bill_id, 1);
            Yii::$app->session->setFlash('flashmsg', '<div class="alert alert-success"> <strong> <i class="fa fa-exclamation-circle">&nbsp;</i> </strong>&nbsp;'.Yii::t("app","operatSucess").'</div>');
          }
          $bills = yii::$app->paiementClass->get_factures_autre_partie_non_soldees($entreprise_id, 1);

          $each_bill_ttpaid = yii::$app->paiementClass->bill_ttpaid($bills);

          $recouvrement_sur = 'Compte client';

          return $this->render($this->pg, ['bills'=>$bills, 'each_bill_ttpaid'=>$each_bill_ttpaid, $recouvrement_sur]);
        break;


        //CHARGER LA LISTE DES FACTURES FOURNISSEUR NON RECOUVERTS
        case md5('lister_factures_fournisseurs_non_soldees'):
        case md5('mettre_a_jour_le_statut_de_la_facture'):
          $this->pg = '/recouvrement/themain_unpaid_bill.php';
          if($request['action_key'] == md5('mettre_a_jour_le_statut_de_la_facture'))
          {
            $bill_id = $request['action_on_this_val'];
            yii::$app->paiementClass->solder_dette($bill_id, 1);
            Yii::$app->session->setFlash('flashmsg', '<div class="alert alert-success"> <strong> <i class="fa fa-exclamation-circle">&nbsp;</i> </strong>&nbsp;'.Yii::t("app","operatSucess").'</div>');
          }
          $bills = yii::$app->paiementClass->get_factures_autre_partie_non_soldees($entreprise_id, 2);

          $each_bill_ttpaid = yii::$app->paiementClass->bill_ttpaid($bills);

          $recouvrement_sur = "Compte fournisseur";

          return $this->render($this->pg, ['bills'=>$bills, 'each_bill_ttpaid'=>$each_bill_ttpaid, 'recouvrement_sur'=>$recouvrement_sur]);

        break;


        //ENREGISTRER LES MODIFICATIONS DE RENSEIGNEMENT GENERAUX
        case md5('enregistrer_rembourssement_client'):
        case md5('preparer_rembourssement_client'): 
          $client = Null;
          $this->pg = '/recouvrement/recouvrement_form.php';
          
          //Rassurer que le numeros de la facture et l'id envoyé correspondent
          $facture_num = isset($request['action_on_this']) ? $request['action_on_this'] : Null;
          
          $facture_id = isset($request['action_on_this_val']) ? $request['action_on_this_val'] : Null;
         
          $validated_bill = yii::$app->paiementClass->validate_bill($facture_num, $facture_id);

          if($validated_bill)
          {            
            //Determiner le montant déjà payé
            $bill_paid = yii::$app->paiementClass->bill_ttpaid($validated_bill);
            if(isset($bill_paid) && is_array($bill_paid))
            {
              foreach($validated_bill as $key => $each_validated_bill)
              {
                //Preparer des variantes necessaires aux 2 cas
                $banques = yii::$app->mainCLass->charger_banques_entreprise($entreprise_id);
                $client = yii::$app->clientClass->getclientdata($each_validated_bill['autre_partie_id']);
                $historique_paiement = yii::$app->paiementClass->get_paiement_historique($each_validated_bill['id']);
                /***********************************************/
                /** EXCLUSIVEMENT RESERVE A UN ENREGISTREMENT **/
                /***********************************************/
                if($request['action_key'] === md5(strtolower('enregistrer_rembourssement_client')))
                {
                  if(Yii::$app->session['token2']!=$_POST['token2'])
                  {
                    //Preparer les variables
                    $mont_payer = (isset($request['mont_payer'])) ? $request['mont_payer'] : Null;
                    $tt_paid = $bill_paid[$key]['tt_paid'] + $mont_payer;
                    $dette = $each_validated_bill['montantTotalPayer'] - $tt_paid;


                    //Determiner la valeur du mode de paiement
                    switch($request['mode_paiement'])
                    {
                      case '2':
                        $val_mode_paiement = $request['numero_cheque'];
                      break;

                      case '3':
                        $val_mode_paiement = $request['banque_denomination'];
                      break;

                      default:
                        $val_mode_paiement = Null;
                      break;
                    }

                    //procèder à l'enregistrement
                    $rslt = yii::$app->paiementClass->enrg_paiement($each_validated_bill['autre_partie_id'], $facture_id, $mont_payer, $dette, $request['mode_paiement'], $val_mode_paiement, $entreprise_id, $entite_id, $user_id);

                    //Mettre à jour le paiement le reste a payer dans la la table de dtls de vente
                    $bill_number = isset($each_validated_bill['bill_number']) ? $each_validated_bill['bill_number'] : '';
                    $rslt = Yii::$app->paiementClass->update_reste_payer_in_dtlvente($bill_number, $tt_paid, $dette, $entreprise_id, $user_id);

                    if($rslt)
                    {
                      //Preparer le rappel du prochain paiement si montant restant / dette est > 0
                      if($dette > 0)
                      {
                        $categorie = 1; // Facture a payer
                        $rappeler_sur = $each_validated_bill['bill_number'];
                        $rappeler_client_id = $each_validated_bill['autre_partie_id'];
                        $date_rappel = Yii::$app->nonSqlClass->convert_date_to_sql_form($request['nextpaiementdate'], "M/D/Y");
                        yii::$app->paiementClass->preparer_next_paiement($categorie, $rappeler_sur, $rappeler_client_id, $date_rappel, $entreprise_id, $user_id);
                      }

                      if($dette <= 0)
                      {
                        yii::$app->paiementClass->solder_dette($facture_id, 1);
                      }

                      Yii::$app->session->setFlash('flashmsg', '<div class="alert alert-success"> <strong> <i class="fa fa-exclamation-circle">&nbsp;</i> </strong>&nbsp;'.Yii::t("app","operatSucess").'</div>');

                      yii::$app->session->set('token2',$_POST['token2']);
                    }
                  }
                }
                /************************************************/
                /** .EXCLUSIVEMENT RESERVE A UN ENREGISTREMENT **/
                /************************************************/

                $tt_paid = $bill_paid[$key]['tt_paid'];
                $dette = $each_validated_bill['montantTotalPayer'] - $tt_paid;
              }
            }else{
              Yii::$app->session->setFlash('flashmsg', '<div class="alert alert-danger"> <strong> <i class="fa fa-exclamation-circle">&nbsp;</i> </strong>&nbsp;'.Yii::t("app","invalide_identifiant_bill").'</div>');
              $this->redirect(Yii::$app->request->baseUrl.'/'.md5('paiement_themain'));
            }

            //AFFICHER LA VUE
            return $this->render($this->pg, ['validated_bill'=>$validated_bill, 'dette'=>$dette, 'tt_paid'=>$tt_paid, 'banques'=>$banques, 'client'=>$client, 'historique_paiement'=>$historique_paiement]);
          }else{
            Yii::$app->session->setFlash('flashmsg', '<div class="alert alert-danger"> <strong> <i class="fa fa-exclamation-circle">&nbsp;</i> </strong>&nbsp;'.Yii::t("app","invalide_identifiant_bill").'</div>');
            return $this->redirect(Yii::$app->request->baseUrl.'/'.md5('paiement_themain'));}
        break;


        //ENREGISTRER LES MODIFICATIONS DE RENSEIGNEMENT GENERAUX
        case md5('enregistrer_rembourssement_fournisseur'):
        case md5('preparer_rembourssement_fournisseur'): 
          $this->pg = '/recouvrement/recouvrement_form.php';

          //Rassurer que le numeros de la facture et l'id envoyé correspondent
          $facture_num = isset($request['action_on_this']) ? $request['action_on_this'] : Null;
          $facture_id = isset($request['action_on_this_val']) ? $request['action_on_this_val'] : Null;
          $validated_bill = yii::$app->paiementClass->validate_bill($facture_num, $facture_id);

          if($validated_bill){            
            //Determiner le montant déjà payé
            $bill_paid = yii::$app->paiementClass->bill_ttpaid($validated_bill);
            if(isset($bill_paid) && is_array($bill_paid))
            {
              foreach($validated_bill as $key => $each_validated_bill)
              {
                //Preparer des variantes necessaires aux 2 cas
                $banques = yii::$app->mainCLass->charger_banques_entreprise($entreprise_id);
                $fournisseur = yii::$app->fournisseurClass->get_fournisseur($each_validated_bill['id']);
                $historique_paiement = yii::$app->paiementClass->get_paiement_historique($each_validated_bill['id']);
                /***********************************************/
                /** EXCLUSIVEMENT RESERVE A UN ENREGISTREMENT **/
                /***********************************************/
                if($request['action_key'] === md5(strtolower('enregistrer_rembourssement_fournisseur')))
                {
                  if(Yii::$app->session['token2']!=$_POST['token2'])
                  {
                    //Preparer les variables
                    $mont_payer = (isset($request['mont_payer'])) ? $request['mont_payer'] : Null;
                    $tt_paid = $bill_paid[$key]['tt_paid'] + $mont_payer;
                    $dette = $each_validated_bill['montantTotalPayer'] - $tt_paid;


                    //Determiner la valeur du mode de paiement
                    switch($request['mode_paiement'])
                    {
                      case '2':
                        $val_mode_paiement = $request['numero_cheque'];
                      break;

                      case '3':
                        $val_mode_paiement = $request['banque_denomination'];
                      break;

                      default:
                        $val_mode_paiement = Null;
                      break;
                    }

                    //procèder à l'enregistrement
                    $rslt = yii::$app->paiementClass->enrg_paiement($each_validated_bill['autre_partie_id'], $facture_id, $mont_payer, $dette, $request['mode_paiement'], $val_mode_paiement, $entreprise_id, $entite_id, $user_id);
                    if($rslt)
                    {
                      if($dette <= 0)
                      {
                        yii::$app->paiementClass->solder_dette($facture_id, 1);
                      }

                      Yii::$app->session->setFlash('flashmsg', '<div class="alert alert-success"> <strong> <i class="fa fa-exclamation-circle">&nbsp;</i> </strong>&nbsp;'.Yii::t("app","operatSucess").'</div>');

                      yii::$app->session->set('token2',$_POST['token2']);
                    }
                  }
                }
                /************************************************/
                /** .EXCLUSIVEMENT RESERVE A UN ENREGISTREMENT **/
                /************************************************/

                $tt_paid = $bill_paid[$key]['tt_paid'];
                $dette = $each_validated_bill['montantTotalPayer'] - $tt_paid;
              }
            }else{
              Yii::$app->session->setFlash('flashmsg', '<div class="alert alert-danger"> <strong> <i class="fa fa-exclamation-circle">&nbsp;</i> </strong>&nbsp;'.Yii::t("app","invalide_identifiant_bill").'</div>');
              $this->redirect(Yii::$app->request->baseUrl.'/'.md5('paiement_themain'));
            }

            //AFFICHER LA VUE
            return $this->render($this->pg, ['validated_bill'=>$validated_bill, 'dette'=>$dette, 'tt_paid'=>$tt_paid, 'banques'=>$banques, 'fournisseur'=>$fournisseur, 'historique_paiement'=>$historique_paiement]);
          }else{
            Yii::$app->session->setFlash('flashmsg', '<div class="alert alert-danger"> <strong> <i class="fa fa-exclamation-circle">&nbsp;</i> </strong>&nbsp;'.Yii::t("app","invalide_identifiant_bill").'</div>');
            return $this->redirect(Yii::$app->request->baseUrl.'/'.md5('paiement_themain'));}
        break;
      }
    }

    return $this->render($this->pg);
  }
}