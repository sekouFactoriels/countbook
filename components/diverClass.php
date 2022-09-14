<?php
    namespace app\components;
    use Yii;
    use yii\base\component;
    use yii\web\Controller;
    use yii\base\InvalidConfigException;

    Class diverClass extends Component {
      public $connect = Null;

      /** Initialisons la variable de connexion **/
      public function __construct(){
        $this->connect = \Yii::$app->db;
      }

      /** Renvois le total de charges mensuelles view / admin **/
      Public function getTotalchargesmensuelbyadmin($idEntreprise){
        $monthlycharge = Null;
        $thismonth = date('m');
        if(isset($idEntreprise)){
          $stmt= $this->connect->createCommand('SELECT SUM(montant) as charges FROM slim_charges where idEntreprise=:idEntreprise AND MONTH(LastUpdatedOn) =:thismonth')
                                ->bindValues(['idEntreprise'=>$idEntreprise,
                                            ':thismonth'=>$thismonth])
                                ->queryOne();
          if(isset($stmt) && sizeof($stmt) > 0){
            $monthlycharge = $stmt['charges'];
          }
        }
        return $monthlycharge;
      }

      /** Renvois le total de reappro mensuelles view / admin **/
      Public function getTotalreappromensuelbyadmin($idEntreprise){
        $monthlycharge = $monthlyReappro = Null;
        $thismonth = date('m');
        $factureFour='FF';
        if(isset($idEntreprise)){
          $stmt = $this->connect->createCommand('SELECT SUM(billAmount) as sommeReappro from slim_bill where idEntreprise=:idEntreprise and MONTH(date_topup)=:thismonth and statut in (1,2)
          and bill_number LIKE :fact')
        ->bindValues([':idEntreprise' => $idEntreprise, ':thismonth' => $thismonth,'fact'=>$factureFour.'%'])
        ->queryOne();
          if(isset($stmt) && sizeof($stmt) > 0){
            $monthlyReappro = $stmt['sommeReappro'];
          }
        }
        return $monthlyReappro;
      }



      /** Methode qui renvois la denomination  **/
         #* 2sp : 24/10/18 *#
      public function nommotifcharge($idmotif){
         $nom = Null;
         if(isset($idmotif)){
           $stmt = $this->connect->createCommand('SELECT nom FROM slim_charges_motif WHERE id=:id')
           ->bindValue(':id',$idmotif)
           ->queryOne();
           if(sizeof($stmt) > 0){
             $nom = $stmt['nom'];
           }
         }
         return $nom;
      }

      /** Methode qui renvois les utulisateurs d'un systeme **/
         #* 2sp : 24/10/18 *#
      public function showDepenseCoreData($idEntreprise,$datefrom,$dateto,$depensecode){
        $rslt = Null;
        $depensesstrg = (!empty($depensecode)) ? ' and idMotif = '.$depensecode :'' ;
        $stmt= $this->connect->createCommand('SELECT * FROM slim_charges where idEntreprise=:idEntreprise AND LastUpdatedOn >=:dtfr AND LastUpdatedOn <=:dtto '.$depensesstrg)
                              ->bindValues(['idEntreprise'=>$idEntreprise,
                                          'dtfr'=>$datefrom,
                                          'dtto'=>$dateto])
                              ->queryAll();
         if (is_array($stmt) && sizeof($stmt)>0) {
            $rslt=$stmt;
         }
         return $rslt;
      }

      /** Methode qui renvois les utulisateurs d'un systeme **/
         #* 2sp : 09/06/2022 *#
         public function showDepenseCoreDataSecond($idEntreprise,$datefrom,$dateto,$depensecode,$listeservice){
          $rslt = Null;
          $depensesstrg = (!empty($depensecode)) ? ' and idMotif = '.$depensecode :'' ;
          $stmt= $this->connect->createCommand('SELECT * FROM slim_charges where idEntreprise=:idEntreprise AND LastUpdatedOn >=:dtfr AND LastUpdatedOn <=:dtto AND idEntite IN ('.$listeservice.')'.$depensesstrg)
                                ->bindValues(['idEntreprise'=>$idEntreprise,
                                            'dtfr'=>$datefrom,
                                            'dtto'=>$dateto])
                                ->queryAll();
           if (is_array($stmt) && sizeof($stmt)>0) {
              $rslt=$stmt;
           }
           return $rslt;
        }

          /** Methode qui renvois les utulisateurs d'un systeme **/
         #* 2sp : 24/10/18 *#
         public function showDepenseCoreDataForFondRoul($idEntreprise,$datefrom,$dateto,$depensecode,$listeservice){
          $rslt = Null;
          $depensesstrg = (!empty($depensecode)) ? ' and idMotif = '.$depensecode :'' ;
          $stmt= $this->connect->createCommand('SELECT * FROM slim_charges where idEntreprise=:idEntreprise AND LastUpdatedOn >=:dtfr AND LastUpdatedOn <=:dtto AND idEntite IN ('.$listeservice.')'.$depensesstrg)
                                ->bindValues(['idEntreprise'=>$idEntreprise,
                                            'dtfr'=>$datefrom,
                                            'dtto'=>$dateto])
                                ->queryAll();
           if (is_array($stmt) && sizeof($stmt)>0) {
              $rslt=$stmt;
           }
           return $rslt;
        }

      /** Methode qui renvois les utulisateurs d'un systeme **/
         #* 2sp : 24/10/18 *#
      public function showUserCoreData($idEntreprise, $usertypeid){
         $rslt = Null;
         $userstrg = (!empty($usertypeid)) ? ' and idUserType = '.$usertypeid :'' ;
         $stmt = $this->connect->createCommand('SELECT nom, prenom, adresse from slim_utulisateursysteme
                  join slim_userauthmenuaction on idUniq=slim_utulisateursysteme.idUserAuth
                  where slim_utulisateursysteme.idEntreprise=:idEntreprise '.$userstrg)
                  ->bindValue(':idEntreprise', $idEntreprise)
                  ->queryAll();
         if (is_array($stmt) && sizeof($stmt)>0) {
           $rslt=$stmt;
         }
         return $rslt;
      }

      /** Methode qui renvoie les evenements **/
       #--        aliou --14/07/2018         --#

      public function showEventCoreData($idEntreprise,$datefrom,$dateto,$eventtypecode,$listeservice){
        $rslt = Null;
        $eventstrg = (!empty($eventtypecode)) ? ' and eventTypeCode = '.$eventtypecode :'' ;
        $stmt= $this->connect->createCommand('SELECT * FROM slim_eventdetails where idEntreprise=:idEntreprise AND eventDte >=:dtfr AND eventDte <=:dtto AND idEntite IN ('.$listeservice.')'.$eventstrg)
         ->bindValues([ 'idEntreprise'=>$idEntreprise,
                        'dtfr'=>$datefrom,
                        'dtto'=>$dateto])
         ->queryAll();
         if (is_array($stmt) && sizeof($stmt)>0) {
           $rslt=$stmt;
         }
         return $rslt;
      }

      /** Methode : Renvois les chrages actives **/
      public function listeCharges($idEntreprise, $typeUser, $idActor){
        $and = $rslt = Null;

        if(isset($idEntreprise) && isset($typeUser)){
          switch ($typeUser) {
            case '9':
            case '8':
            case '7':
              $and = '';
            break;

            case '6':
              $and = ' and idActor='.$idActor;
            break;
          }
          $stmt = $this->connect->createCommand('SELECT slim_charges.id as chargeid, idMotif,dateOperation, montant, description, slim_charges_motif.nom as motifname, description from slim_charges join slim_charges_motif on slim_charges_motif.id = slim_charges.idMotif where slim_charges.idEntreprise ='.$idEntreprise.$and )
          ->queryAll();
          if(is_array($stmt) && sizeof($stmt) > 0){
            $rslt = $stmt;
          }
        }
        return $rslt;
      }

      /** Methode : Insertion new charge **/
      public function newCharge($motif,$dateOperation, $montant, $desc, $idActor, $idEntreprise){
        $reussite = Null;
        $stmt = $this->connect->createCommand('INSERT INTO slim_charges (idMotif,dateOperation, montant, description, idActor, idEntreprise) VALUES (:idMotif, :dateOperation, :montant, :description, :idActor, :idEntreprise)')
        ->bindValues([':idMotif'=>$motif,':dateOperation'=>$dateOperation, ':montant'=>$montant, ':description'=>$desc, ':idActor'=>$idActor, ':idEntreprise'=>$idEntreprise])
        ->execute();
        if($stmt){
          $reussite = '2604';
        }
        return $reussite;
      }

      /** Methode : Insert un nouveau label de motif **/
      public function insertMotifLabel($motifLabel, $idEntreprise, $idActor){
        $result = Null;
        if(isset($motifLabel)){
          $stmt = $this->connect->createCommand('INSERT INTO slim_charges_motif (nom, idEntreprise, idActor) VALUES (:nom, :idEntreprise, :idActor)')
          ->bindValues([':nom'=>$motifLabel, ':idEntreprise'=>$idEntreprise, ':idActor'=>$idActor])
          ->execute();
          if($stmt){
            $list = $this->connect->createCommand('SELECT id, nom FROM slim_charges_motif WHERE idEntreprise=:idEntreprise AND statut=:statut')
            ->bindValues([':idEntreprise'=>$idEntreprise, ':statut'=>'1'])
            ->queryAll();
            if(is_array($list) && sizeof($list) > 0){
              foreach ($list as $key => $value) {
                $result .='<option value="'.$value['id'].'">'.$value['nom'].'</option>';
              }
            }
          }
        }
        return $result;
      }

      /** Methode : renvois la liste des motifs **/
      public function listMotif($idEntreprise){
        $list = Null;
        if(isset($idEntreprise)){
          $stmt = $this->connect->createCommand('SELECT id, nom from slim_charges_motif where statut=:statut and idEntreprise=:idEntreprise')
          ->bindValues([':statut'=>'1', ':idEntreprise'=>$idEntreprise])
          ->queryAll();
          if(is_array($stmt) && sizeof($stmt) > 0){
            $list = $stmt;
          }
        }
        return $list;
      }

      public function saveRequest($nom,$tel,$email,$nomEntreprise,$poste){
        
        $stmt=$this->connect->createCommand('INSERT INTO slim_requete (nom,tel,email,nomEntreprise,postOccupe)
                                            VALUES(:nom,:tel,:email,:nomEntreprise,:poste)')
                                            ->bindValues([':nom'=>$nom,':tel'=>$tel,':email'=>$email,':nomEntreprise'=>$nomEntreprise,':poste'=>$poste])
                                            ->execute();
      }
      
  }
