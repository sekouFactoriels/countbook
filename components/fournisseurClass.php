<?php

  namespace app\components;
  use Yii;
  use yii\base\component;
  use yii\web\Controller;
  use yii\base\InvalidConfigException;
  Class fournisseurClass extends Component 
  {
    public $connect = Null;

    Public function __construct(){
        $this->connect = \Yii::$app->db;
    }

    /** Methode : charger les informations concernant un fournisseur **/
    public function get_fournisseur($fournisseur_id='')
    {
      $rslt = $this->connect->createCommand('select * from slim_fournisseur where id=:id')
      ->bindValue(':id', $fournisseur_id)
      ->queryAll();
      if($rslt) return $rslt;
      return;
    }

    /** Methode : enregistrer les modifications renseignements généraux **/
    public function update_renseignement_gnr($data='', $fournisseur='', $idactor='')
    {
      $rslt = Null;
      if(isset($data) && is_array($data))
      {
        $rslt = $this->connect->createCommand('update slim_fournisseur set raison_sociale=:raison_sociale, denomination=:denomination, telephone=:telephone, adresse_physique=:adresse_physique, site_web=:site_web, idactor=:idactor where id=:id')
        ->bindValues([':id'=>$fournisseur,':idactor'=>$idactor, ':site_web'=>$data['siteweb'], ':adresse_physique'=>$data['adresse_physique'], ':telephone'=>$data['telephone'], ':denomination'=>$data['denomination'], ':raison_sociale'=>$data['raison_sociale']])
        ->execute();
      }
      if($rslt) return $rslt;
      return;
    }


    /** Methode : Charger les renseignements sur un fournisseur **/
    public function charger_fournisseur($fournisseur='')
    {
      $rslt =  $this->connect->createCommand('select * from slim_fournisseur where id=:id')
      ->bindValue(':id', $fournisseur)
      ->queryOne();
      return $rslt;
    }

    /** Methode : Charger la liste des fournisseurs **/
    public function lister_fournisseurs($entite_id='', $donneeRecherche='', $limit='')
    {
      $limit = Yii::$app->inventaireClass->getRealLimit($limit);
      if(isset($limit) && $limit >0){
        $limit = 'LIMIT '.$limit;
      }

      $rslt = $this->connect->createCommand('select * from slim_fournisseur where statut=:statut and entite_id=:entite_id AND (denomination LIKE :donneerecherche
                                                          OR telephone LIKE :donneerecherche
                                                          OR adresse_courriel LIKE :donneerecherche
                                                          OR site_web LIKE :donneerecherche) '.$limit)
      ->bindValues([':entite_id'=>$entite_id, ':statut'=>1, ':donneerecherche'=>'%'.$donneeRecherche.'%'])
      ->queryAll();
      return $rslt;
    }

    /** Methode : Enregistrer un nouveau fournisseur **/
    public function enregisrer_fourn($data='', $user_id='', $entreprise_id='', $entite_id='')
    {
      $rslt = $this->connect->createCommand('insert into slim_fournisseur (raison_sociale, denomination, telephone, adresse_physique, adresse_courriel, site_web, entreprise_id, entite_id, idactor, ajouter_le) values (:raison_sociale, :denomination, :telephone, :adresse_physique, :adresse_courriel, :site_web, :entreprise_id, :entite_id, :idactor, :ajouter_le)')

      ->bindValues([':ajouter_le'=>date('Y-m-d'), ':idactor'=>$user_id, ':entite_id'=>$entite_id, ':entreprise_id'=>$entreprise_id, ':site_web'=>$data['siteweb'], ':adresse_courriel'=>$data['email'], ':adresse_physique'=>$data['adresse_physique'], ':telephone'=>$data['telephone'], ':denomination'=>$data['denomination'], ':raison_sociale'=>$data['raison_sociale']])
      ->execute();
      
      return true;
    }

    /** Methode : Check si les datas envoyees sont news **/
    public function fournisseur_est_nouveau($request='', $entreprise_id='')
    {
      $rslt = $this->connect->createCommand('select * from slim_fournisseur where entreprise_id=:entreprise_id and telephone=:telephone and denomination=:denomination')
      ->bindValues([':denomination'=>$request['denomination'], ':entreprise_id'=>$entreprise_id, ':telephone'=>$request['telephone']])
      ->queryOne();
      if(isset($rslt)) return '2604';
      return;
    }
  }
