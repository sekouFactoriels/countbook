<?php
    namespace app\components;
    use Yii;
    use yii\base\component;
    use yii\web\Controller;
    use yii\base\InvalidConfigException;

    Class clientClass extends Component {
      public $connect = Null;

      Public function __construct(){
        $this->connect = \Yii::$app->db;
      }

      //Methode : Enregistrer les modifications effectuees sur les infos de ce client
      public function modifierClient($data='', $client)
      {
        if(isset($client))
        {
          $rslt = $this->connect->createCommand('UPDATE slim_client set nom_appellation=:nom_appellation, tel1=:tel1, email=:email WHERE id=:client')
          ->bindValues([':client'=>$client, ':email'=>$data['email'], ':tel1'=>$data['tel'], ':nom_appellation'=>$data['appelation']])
          ->execute();
          return true;
        }
        return;
      }

      Public function getMontlyclientgrowthbysaler($idEntreprise, $clientgrowthmustcalculate, $monthlyclienttargetpoint){
         $clientgrouwth = Null;
         if(isset($clientgrowthmustcalculate) && $clientgrowthmustcalculate > 0){
          $clientgrouwth = $clientgrowthmustcalculate * 100 / $monthlyclienttargetpoint;
         }
         
         return $clientgrouwth;
      }

      /** Renvois la sommes des vente du mois en cours / saler **/
      public function getMontlyclientbysaler($idsaler){
         $monthlyclient = $thismonth = Null;
         $thismonth = date('m');
         if(isset($idsaler)){
            $stmt = $this->connect->createCommand('SELECT COUNT(nom_appellation) as sommeclient from slim_client where idActor=:idActeur and MONTH(insererle)=:thismonth and naturecontact=:naturecontact')
            ->bindValues([':idActeur'=>$idsaler, ':thismonth'=>$thismonth, ':naturecontact'=>'4'])
            ->queryOne();
            if(isset($stmt) && sizeof($stmt) > 0){
               $monthlyclient = $stmt['sommeclient'];
            }
         }
         return $monthlyclient;
      }


      /** Methode : mettre a jour les data du client **/
      public function updateClientData($statutcontact, $naturecontact, $nom_appellation, $tel1, $email, $dtebirth, $id_client_enrg_motif, $dtlsMotif, $statut, $idEntreprise, $idActor,$id){
        $dayBirth = $monthBirth = $yearBirth = $updated = Null;
        if(!empty($dtebirth)){
          //Explode de date into : day,month,year
          $date = explode('/',$dtebirth);
          $dayBirth = $date[1];
          $monthBirth = $date[0];
          $yearBirth = $date[2];
        }
        if(isset($statutcontact)){
          $stmt = $this->connect->createCommand('UPDATE slim_client SET statutcontact=:statutcontact, naturecontact=:naturecontact, nom_appellation=:nom_appellation, tel1=:tel1, email=:email, dayBirth=:dayBirth, monthBirth=:monthBirth, yearBirth=:yearBirth, id_client_enrg_motif=:id_client_enrg_motif, dtlsMotif=:dtlsMotif, statut=:statut, idActor=:idActor WHERE id=:id and idEntreprise=:idEntreprise')
          ->bindValues([':id'=>$id, ':idEntreprise'=>$idEntreprise, ':statutcontact'=>$statutcontact, ':naturecontact'=>$naturecontact, ':nom_appellation'=>$nom_appellation, ':tel1'=>$tel1, ':email'=>$email, ':dayBirth'=>$dayBirth, ':monthBirth'=>$monthBirth, ':yearBirth'=>$yearBirth, ':id_client_enrg_motif'=>$id_client_enrg_motif, ':dtlsMotif'=>$dtlsMotif, ':statut'=>$statut, ':idActor'=>$idActor])
          ->execute();
          $updated = '2692';
        }
        return $updated;
      }

      /** Methode retrouver les infos d'un contact / id ligne enregistrement **/
      public function getclientdata($id){
        $rslt  = Null;
        if(isset($id)){
          $stmt = $this->connect->createCommand('SELECT * FROM slim_client WHERE id=:id')
          ->bindValue(':id',$id)
          ->queryOne();
          if(sizeof($stmt) > 0){
            $rslt = $stmt;
          }
        }
        return $rslt;
      }

      /** Methode liste des contacts deja enregistres **/
      Public function listeclients($idEntreprise='', $donneeRecherche='', $limit='1'){
        $contacts = Null;
        if(isset($idEntreprise)){
          $limit = Yii::$app->inventaireClass->getRealLimit($limit);
          if(isset($limit) && $limit >0){
            $limit = 'LIMIT '.$limit;
          }

          $stmt = $this->connect->createCommand('SELECT * FROM slim_client
            WHERE idEntreprise=:idEntreprise  AND (nom_appellation LIKE :donneerecherche
                                                          OR adresse LIKE :donneerecherche
                                                          OR tel1 LIKE :donneerecherche
                                                          OR dtlsMotif LIKE :donneerecherche) '.$limit)
          ->bindValues([':idEntreprise'=>$idEntreprise, ':donneerecherche'=>'%'.$donneeRecherche.'%'])
          ->queryAll();
          if(sizeof($stmt) > 0){
            $contacts = $stmt;
          }
        }
        return $contacts;
      }

      /** Methode enregstre un nouveau contact  **/
      public function enrgclient($statutcontact, $naturecontact, $appelation, $dtebirth, $tel, $email, $enrgmotif, $dtlsmotif, $idEntreprise, $idactor){
        $rslt = $jr = $mois = $annee = Null;
        //Chech if data is not already saved
        $insererle = date('y-m-d');
        $stmt = $this->connect->createCommand('SELECT id FROM slim_client WHERE statutcontact=:statutcontact AND nom_appellation=:nom_appellation AND tel1=:tel1 AND email=:email')
        ->bindValues([':statutcontact'=>$statutcontact, ':nom_appellation'=>$appelation, ':tel1'=>$tel, ':email'=>$email])
        ->queryAll();
        if(sizeof($stmt) <= 0){
          //Explode de date into : day,month,year
          if(!empty($dtebirth)){
            $date = explode('/',$dtebirth);
            $jr = $date[1];
            $mois = $date[0];
            $annee = $date[2];
          }
          $insertstmt = $this->connect->createCommand('INSERT INTO slim_client (statutcontact, naturecontact, nom_appellation,tel1,email,dayBirth,monthBirth,yearBirth,id_client_enrg_motif,dtlsMotif,idEntreprise,idActor,insererle)
          VALUES (:statutcontact, :naturecontact, :nom_appellation,:tel1,:email,:dayBirth,:monthBirth,:yearBirth,:id_client_enrg_motif,:dtlsMotif,:idEntreprise,:idActor,:insererle)')
          ->bindValues([':statutcontact'=>$statutcontact, ':naturecontact'=>$naturecontact, ':nom_appellation'=>$appelation,':tel1'=>$tel,':email'=>$email,':dayBirth'=>$jr,':monthBirth'=>$mois,':yearBirth'=>$annee,
                        ':id_client_enrg_motif'=>$enrgmotif,':dtlsMotif'=>$dtlsmotif,':idEntreprise'=>$idEntreprise,':idActor'=>$idactor, ':insererle'=>$insererle])
          ->execute();
          if(isset($insertstmt)) $rslt = '2692';
        }else $rslt = '2626';
        return $rslt;
      }
    }
