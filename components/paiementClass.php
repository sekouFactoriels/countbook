<?php

namespace app\components;

use Yii;
use yii\base\component;
use yii\web\Controller;
use yii\base\InvalidConfigException;

class paiementClass extends Component
{
  public $connect = Null;

  public function __construct()
  {
    $this->connect = \Yii::$app->db;
  }


  /** Methode : Charger la liste des factures a recourvrir pour le mois en cours **/
  public function facture_recouvrir_mois_encours($entreprise_id = '')
  {
    $thismonth = date('m');
    $rslt = $this->connect->createCommand('select * from slim_rappel where statut=:statut and idEntreprise=:idEntreprise and MONTH(date_rappel)=:thismonth  ORDER BY date_rappel DESC')
      ->bindValues([':thismonth' => $thismonth, ':idEntreprise' => $entreprise_id, ':statut' => 1])
      ->queryAll();
    if ($rslt) return $rslt;
    return;
  }

  /** Methode : Enregistrer le rappel pour prochain paiement **/
  public function preparer_next_paiement($categorie = '', $rappeler_sur = '', $rappeler_client_id = '', $date_rappel = '', $entreprise_id = '', $user_id = '')
  {
    $rslt = $this->connect->createCommand('INSERT INTO slim_rappel (categorie, rappeler_sur, rappeler_client_id, date_rappel, idEntreprise, idActeur) VALUES (:categorie, :rappeler_sur, :rappeler_client_id, :date_rappel, :idEntreprise, :idActeur)')
      ->bindValues([':idActeur' => $user_id, ':idEntreprise' => $entreprise_id, ':date_rappel' => $date_rappel, ':rappeler_client_id' => $rappeler_client_id, ':rappeler_sur' => $rappeler_sur, ':categorie' => $categorie])
      ->execute();
    if ($rslt) return $rslt;
    return;
  }


  /** Methode : Mettre à jour le reste a payer dans le detail de vente **/
  public function update_reste_payer_in_dtlvente($bill_number = '', $montantpaye = '', $dette = '', $entreprise_id = '', $user_id = '')
  {
    $rslt = $this->connect->createCommand('update slim_dtlsvente set montantpercu=:montantpercu, detteVente=:detteVente, idActeur=:idActeur where codeVente=:codeVente')
      ->bindValues([':idActeur' => $user_id, ':codeVente' => $bill_number, ':detteVente' => $dette, ':montantpercu' => $montantpaye])
      ->execute();
    return $rslt;
  }

  /** Methode : Charger l'id de la facture en fonction du numéro **/
  public function get_bill_id($bill_num = '')
  {
    $rslt = $this->connect->createCommand('select id from slim_bill where bill_number=:bill_number')
      ->bindValue(':bill_number', $bill_num)
      ->queryOne();
    if (isset($rslt)) return $rslt['id'];
    return;
  }

  /** Methode : Charger les datas de la facture **/
  public function get_bill_data($bill_id = '')
  {
    $rslt = $this->connect->createCommand('select * from slim_bill where id=:id')
      ->bindValue(':id', $bill_id)
      ->queryAll();
    if (isset($rslt)) return $rslt;
    return;
  }

  /** Methode : determiner tt. montant payé et dette par facture  **/
  public function bill_ttpaid($bill = '')
  {
    $bill_ttpaid = Null;
    if (isset($bill) && is_array($bill) && sizeof($bill) > 0) {
      foreach ($bill as $each_bill) {
        $rslt = $this->connect->createCommand('select SUM(montantpaye) as montantpaye from slim_bill_paiement_historique where  bill_id=:bill_id')
          ->bindValues([':bill_id' => $each_bill['id']])
          ->queryAll();

        if ($rslt) {
          $bill_ttpaid[] = ['bill_id' => $each_bill['id'], 'tt_paid' => $rslt[0]['montantpaye']];
        }
      }
    }
    return $bill_ttpaid;
  }

  /** Methode : Lister les facture par fournisseur **/
  public function lister_factures($autre_partie_id = '', $entreprise = '')
  {
    $rslt = $this->connect->createCommand('select * from slim_bill where autre_partie_id=:autre_partie_id and idEntreprise=:idEntreprise ORDER BY date_topup DESC')
      ->bindValues([':idEntreprise' => $entreprise, ':autre_partie_id' => $autre_partie_id])
      ->queryAll();
    if ($rslt) return $rslt;
    return;
  }

  /** Methode : Lister les facture dun client **/
  public function lister_factures_du_mois($autre_partie_id = '', $entreprise = '')
  {
    $thismonth = date('m');
    $rslt = $this->connect->createCommand('select * from slim_bill 
      where autre_partie_id=:autre_partie_id 
      and idEntreprise=:idEntreprise 
      AND MONTH(date_topup)=:thismonth
      ORDER BY date_topup DESC')
      ->bindValues([':idEntreprise' => $entreprise, ':autre_partie_id' => $autre_partie_id, ':thismonth' => $thismonth])
      ->queryAll();
    if ($rslt) return $rslt;
    return;
  }

  /** Methode : Lister des creances du mois **/
  public function lister_creance_du_mois($entreprise = '')
  {
    $thismonth = date('m');
    $rslt = $this->connect->createCommand('select slim_bill.* from slim_bill,slim_dtlsvente 
              where slim_bill.bill_number=slim_dtlsvente.codeVente
              AND slim_bill.idEntreprise=:idEntreprise
              AND slim_bill.categorie_autre_partie=1
              AND MONTH(slim_bill.date_topup)=:thismonth
              ORDER BY slim_bill.date_topup DESC')
      ->bindValues([':idEntreprise' => $entreprise, ':thismonth' => $thismonth])
      ->queryAll();
    if ($rslt) return $rslt;
  }

  /** Methode : Lister des dettes du mois **/
  public function lister_dette_du_mois($entreprise = '')
  {
    $thismonth = date('m');
    $rslt = $this->connect->createCommand('select * from slim_bill 
              where idEntreprise=:idEntreprise 
              AND categorie_autre_partie=2
              AND MONTH(date_topup)=:thismonth
              ORDER BY date_topup DESC')
      ->bindValues([':idEntreprise' => $entreprise, ':thismonth' => $thismonth])
      ->queryAll();
    if ($rslt) return $rslt;
    return;
  }

  /** Methode : Calculer le montant total nonpaye **/
  public function calculer_tt_montant_unpaid_autre_partie($montantpaye = '', $autre_partie_id = '', $entreprise = '')
  {
    $montant_nonpaye = Null;
    $rslt = $this->connect->createCommand('select SUM(montantTotalPayer) as montantTotalPayer, bill_number from slim_bill where autre_partie_id=:autre_partie_id and idEntreprise=:idEntreprise')
      ->bindValues([':idEntreprise' => $entreprise, ':autre_partie_id' => $autre_partie_id])
      ->queryAll();
    if ($rslt) {
      foreach ($rslt as $key => $each_rslt) {
        if (isset($each_rslt['montantTotalPayer'])) {
          $montantTotalPayer = isset($each_rslt['montantTotalPayer']) ? $each_rslt['montantTotalPayer'] : Null;

          $montant_nonpaye = $montantTotalPayer - $montantpaye[$key]['montantpaye'];
        }
      }
    }
    if ($montant_nonpaye) return floatval($montant_nonpaye);
    return Null;
  }

  /** Methode : Calculer le montant total des montants payes a un fournisseur **/
  public function calculer_tt_montant_paid_autre_partie($bill_autre_partie_id = '', $entreprise = '')
  {
    $rslt = $this->connect->createCommand('select SUM(montantpaye) as montantpaye from slim_bill_paiement_historique where bill_autre_partie_id=:bill_autre_partie_id and idEntreprise=:idEntreprise')
      ->bindValues([':idEntreprise' => $entreprise, ':bill_autre_partie_id' => $bill_autre_partie_id])
      ->queryAll();
    if ($rslt) return $rslt;
    return;
  }

  /** Methode : Charger la liste des factures fournisseurs non payees **/
  public function get_factures_autre_partie_non_soldees($entreprise_id = '', $categorie_autre_partie = '')
  {
    $rslt = $this->connect->createCommand('select * from slim_bill where idEntreprise=:idEntreprise and statut=:statut and categorie_autre_partie=:categorie_autre_partie order by date_topup DESC')
      ->bindValues([':statut' => 2, ':idEntreprise' => $entreprise_id, ':categorie_autre_partie' => $categorie_autre_partie])
      ->queryAll();
    return $rslt;
  }

  /** Methode : Charger la liste des factures client payees **/
  public function get_factures_autre_partie_soldees($entreprise)
  {
    $thismonth = date('m');
    $rslt = $this->connect->createCommand('select slim_dtlsvente.*,slim_bill.id as idbill from slim_dtlsvente,slim_bill 
              where slim_dtlsvente.codeVente=slim_bill.bill_number
              and slim_dtlsvente.idEntreprise=:idEntreprise
              AND slim_dtlsvente.montantpercu > 0
              AND MONTH(slim_dtlsvente.lastUpdate)=:thismonth
              ORDER BY slim_dtlsvente.lastUpdate DESC')
      ->bindValues([':idEntreprise' => $entreprise, ':thismonth' => $thismonth])
      ->queryAll();
    if ($rslt) return $rslt;
  }

  /** Methode : Solder une dette **/
  public function solder_dette($bill_id = '', $statut = '')
  {
    $rslt = $this->connect->createCommand('update slim_bill set statut=:statut where id=:id')
      ->bindValues([':id' => $bill_id, ':statut' => $statut])
      ->execute();
    if ($rslt) return true;
    return;
  }

  /** Methode : enregistrer les  **/
  public function enrg_paiement($bill_autre_partie_id = '', $bill_id = '', $montantpaye = '', $montantrestant = '', $mode_paiement = '', $pj_mode_paiement = '', $idEntreprise = '', $idEntite = '', $idActeur = '')
  {
    $rslt = $this->connect->createCommand('INSERT INTO slim_bill_paiement_historique (bill_autre_partie_id, bill_id, montantpaye, montantrestant, mode_paiement, pj_mode_paiement, idEntreprise, idEntite, idActeur) VALUES (:bill_autre_partie_id, :bill_id, :montantpaye, :montantrestant, :mode_paiement, :pj_mode_paiement, :idEntreprise, :idEntite, :idActeur)')
      ->bindValues([':idActeur' => $idActeur, ':idEntite' => $idEntite, ':idEntreprise' => $idEntreprise, ':pj_mode_paiement' => $pj_mode_paiement, ':mode_paiement' => $mode_paiement, ':montantrestant' => $montantrestant, ':montantpaye' => $montantpaye, ':bill_id' => $bill_id, ':bill_autre_partie_id' => $bill_autre_partie_id])
      ->execute();
    if ($rslt) return $rslt;
    return;
  }

  /** Methode : Charger l'historique des paiement **/
  public function get_paiement_historique($bill_id = '')
  {
    $rslt = $this->connect->createCommand('select * from slim_bill_paiement_historique where bill_id=:bill_id order by date_maj desc')
      ->bindValue(':bill_id', $bill_id)
      ->queryAll();
    if ($rslt) return $rslt;
    return;
  }

  /** Methode : Valider un paiement **/
  public function validate_bill($facture_num = '', $facture_id = '')
  {
    if (isset($facture_num) && isset($facture_id) && ($facture_id > 0)) {
      $rslt = $this->connect->createCommand('select * from slim_bill where id=:id and bill_number=:bill_number')
        ->bindValues([':id' => $facture_id, ':bill_number' => $facture_num])
        ->queryAll();
      if ($rslt) return $rslt;
    }
    return;
  }
}
