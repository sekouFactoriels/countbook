<?php

namespace app\components;

use DateInterval;
use Yii;
use yii\base\component;
use yii\web\Controller;
use yii\base\InvalidConfigException;

class parametreClass extends Component
{
  public $connect = Null;

  /** Initialisons la variable de connexion **/
  public function __construct()
  {
    $this->connect = \Yii::$app->db;
  }

  /** Methode : Retourner les privileges d'un utilisateur **/
  public function getUserPrivilege($userId='')
  {
    $stmt = $this->connect->createCommand('SELECT `id`, `idUniq`, `idUserType`, `idBranch`, `menu` FROM `slim_userauthmenuaction` WHERE idUniq=:userId')
    ->bindValue(':userId', $userId)
    ->queryOne();
    if(isset($stmt) && sizeof($stmt)>0) return $stmt['menu'];
    return;
  }


  /** Methode : Retourner les privileges de ref en fonction du type de forfait de l'entreprise **/
  public function getprivilege_ref($forfait='')
  {
    $rslt = $this->connect->createCommand('select privilege from slim_ref_privilege where forfait=:forfait')
    ->bindValue(':forfait', $forfait)
    ->queryOne();
    if($rslt) return $rslt;
    return;
  }

  /** Methode : Creer un utilisateur **/
  public function addUserAccount($menu = '', $entreprise_id = '', $entite_id = '', $username = '', $pswrd = '', $typeusr = '', $entiteconnected = '', $nom = '', $pnom = '', $tel = '')
  {

    #Enregistrons les infos dans la table : slim_userauth
    $idInsertedUserAuth = Yii::$app->parametreClass->newUserInUserauth($entreprise_id, $entite_id, $username, $pswrd, $typeusr, $entreprise_id, $entiteconnected, date('Y-m-d'), date('Y-m-d'), '1');
    if (isset($idInsertedUserAuth)) {
      $reussite = Null;
      # Enregistrons les infos dans la table : slim_userauthmenuaction
      $stmt = Yii::$app->parametreClass->newRowUserauthmenuaction($idInsertedUserAuth, $typeusr, $menu);
      # Enregistrons les infos dans la table : slim_utulisateursysteme
      $reussite = Yii::$app->parametreClass->newRowUtulisateursysteme($idInsertedUserAuth, $nom, $pnom, $tel, $entreprise_id, $entite_id);
    }
  }

  /** Methode : Recuperrer les identifiants entreprise et entite via le pin **/
  public function getEntrepriseEntiteId($pin = '')
  {
    $entreprise_id = $entite_id = 0;
    $entreprise = $this->connect->createCommand('select id from slim_entreprise where pin=:pin')
      ->bindValue(':pin', $pin)
      ->queryOne();
    if (isset($entreprise['id'])) $entreprise_id = $entreprise['id'];

    $entite = $this->connect->createCommand('select id from slim_entite where idEntreprise=:idEntreprise')
      ->bindValue(':idEntreprise', $entreprise_id)
      ->queryOne();
    if (isset($entite['id'])) $entite_id = $entite['id'];

    //Alors formons le tableau de data
    return ['entreprise_id' => $entreprise_id, 'entite_id' => $entite_id];
  }

  /** Methode : Créer le compte du proprietaire de l_espace de gestion **/
  public function enrg_espace_gestion_owner($posted, $pin, $userconnected_id)
  {
    //Retrouver les id de l'entreprise et de l'entite via le pin de l'entreprise
    $id_array = parametreClass::getEntrepriseEntiteId();
  }


  /** Methode : Charger toutes les information d'un espace de gestion **/
  public function charger_espace_gestion($id_entreprise = '')
  {
    $espace_gestion = $this->connect->createCommand('select * from slim_entreprise where id=:id')
      ->bindValue(':id', $id_entreprise)
      ->queryOne();
    if ($espace_gestion) return $espace_gestion;
    return false;
  }

  //Methode de validation du duplicat
  public function valider_entreprise_duplicat($denomination = '', $tel = '')
  {
    $stmt = $this->connect->createCommand('select * from slim_entreprise where nom=:nom and tel=:tel')
      ->bindValues([':tel' => $tel, ':nom' => $denomination])
      ->queryAll();
    if (isset($stmt) && sizeof($stmt) > 0) return false;
    return true;
  }

  //Action pour valider les numeros de téléphone
  function validate_number($phone_number)
  {
    if (preg_match('/^[6-8][0-9]{8}$/', $phone_number)) {
      return true;
    } else {
      return false;
    }
    return;
  }

  /** Methode : Preparons la liste des services pour un rapport **/
  public function listeserviceforrepport($encodedidserviceposted, $idEntreprise, $typeUser, $idEntite)
  {
    $listservices = $idservices = Null;
    $idserviceposted = base64_decode($encodedidserviceposted);
    switch ($idserviceposted) {
      case '0': // Dans ce cas selection = tous les services
        // Recuperrons la liste de tous les services sous un utilisateur
        $listservices = parametreClass::listservices_entreprises($idEntreprise, $typeUser, $idEntite);
        for ($i = 0; $i < sizeof($listservices); $i++) {
          $idservices[] = $listservices[$i]['id'];
        }
        $idservices = implode(",", $idservices);
        break;

      default: // Dans ce cas selection = un services specifique
        $idservices = $idserviceposted;
        break;
    }
    return $idservices;
  }

  public function getuser_entite($auhid)
  {
    $stmt = $this->connect->createCommand('SELECT slim_entite.id as id, slim_entite.nom as nom FROM slim_entite
                                                JOIN slim_utulisateursysteme ON slim_utulisateursysteme.idEntite = slim_entite.id
                                                WHERE slim_utulisateursysteme.idUserAuth=:auhid')
      ->bindValue(':auhid', $auhid)
      ->queryAll();
    if (sizeof($stmt) > 0) {
      $entreprises = $stmt;
    }
    return $entreprises;
  }

  /** Methode : liste des services  **/
  public function listservices_entreprises($idEntreprise, $usertype = Null, $userservice = Null)
  {
    $services = Null;
    $useradmin = Yii::$app->params['usersToEditSales'];
    switch ($usertype) {
      case in_array($usertype, $useradmin):
        $stmt = $this->connect->createCommand('SELECT * FROM slim_entite WHERE idEntreprise=:idEntreprise')
          ->bindValue(':idEntreprise', $idEntreprise)
          ->queryAll();
        if (sizeof($stmt) > 0) {
          $services = $stmt;
        }
        break;

      default:
        $stmt = $this->connect->createCommand('SELECT * FROM slim_entite WHERE id=:id')
          ->bindValue(':id', $userservice)
          ->queryAll();
        if (sizeof($stmt) > 0) {
          $services = $stmt;
        }
        break;
    }
    return $services;
  }

  /** Methode : recuperation des infos de services d'une entreprise **/
  public function getentreprises_services($idEntreprise, $iswiz = Null)
  {
    $entreprises = Null;
    switch ($iswiz) {
      case '1':
        $stmt = $this->connect->createCommand('SELECT * FROM slim_entite')
          ->queryAll();
        if (sizeof($stmt) > 0) {
          $entreprises = $stmt;
        }
        break;

      default:
        $stmt = $this->connect->createCommand('SELECT * FROM slim_entite WHERE idEntreprise=:idEntreprise')
          ->bindValue(':idEntreprise', $idEntreprise)
          ->queryAll();
        if (sizeof($stmt) > 0) {
          $entreprises = $stmt;
        }
        break;
    }
    return $entreprises;
  }

  /** Methode : mise a jour les infos d'une enteprise **/
  public function updateuser($idrow, $serialized_post, $idactor)
  {
    $updated = $users = $usrs  =  Null;
    $updatepswrd = "";
    if (isset($idrow)) {
      $unserialized_post = unserialize($serialized_post);
      // Mettre a jour la table des utilisateurs de systeme


      

      $updatestmt = $this->connect->createCommand('UPDATE slim_utulisateursysteme SET nom=:nom, prenom=:prenom, adresse=:adresse, email=:email WHERE idUserAuth=:id')
        ->bindValues([':nom' => $unserialized_post['nom'], ':prenom' => $unserialized_post['pnom'], ':adresse' => $unserialized_post['tel'], ':email' => $unserialized_post['email'], ':id' => $idrow])
        ->execute();
      if (isset($unserialized_post['questionmotpass']) && $unserialized_post['questionmotpass'] === '1' && !empty($unserialized_post['motpass'])) {
        $username = Yii::$app->mainCLass->getuser_username($idrow);
        #preparation du mot de passe
        $pswrd = Yii::$app->accessClass->create_pass($username, $unserialized_post['motpass']);
        $updatepswrd = ' motPasse="' . $pswrd . '" , ';
      }
      // Mettre a jour la table de user authentification menu action
      $updatestmt = $this->connect->createCommand('UPDATE slim_userauth SET ' . $updatepswrd . ' typeUser=:typeUser, statCmpt=:statCmpt WHERE id=:id')
        ->bindValues([':typeUser' => $unserialized_post['typeUsr'], ':statCmpt' => $unserialized_post['statut'], ':id' => $idrow])
        ->execute();

      // Mettre a jour le champ de menuaction
      $chaineMenu = Yii::$app->parametreClass->buildMenuStrg($unserialized_post);

      $updatestmt = $this->connect->createCommand('UPDATE slim_userauthmenuaction SET idUserType=:idUserType, menu=:menu WHERE idUniq=:id')
        ->bindValues([':idUserType' => $unserialized_post['typeUsr'], ':menu' => $chaineMenu, ':id' => $idrow])
        ->execute();

      if (isset($updatestmt)) {
        $updated = '2692';
      }
    }
    return $updated;
  }

  /** Methode : edit les infos d'une entreprise **/
  public function editionuser($action_on_this)
  {
    $user = Null;
    if (isset($action_on_this) && !empty($action_on_this)) {
      $stmt = $this->connect->createCommand('SELECT slim_userauth.id, slim_userauth.typeUser, slim_userauth.userName, slim_userauth.statCmpt, slim_userauthmenuaction.menu, slim_utulisateursysteme.nom, slim_utulisateursysteme.email, slim_utulisateursysteme.prenom, slim_utulisateursysteme.adresse
            FROM slim_userauth
            JOIN slim_userauthmenuaction ON slim_userauthmenuaction.idUniq = slim_userauth.id
            JOIN slim_utulisateursysteme ON slim_utulisateursysteme.idUserAuth = slim_userauth.id
            WHERE slim_userauth.id=:id')
        ->bindValue(':id', $action_on_this)
        ->queryOne();
      if (isset($stmt)) {
        $user = $stmt;
      }
    }
    return $user;
  }

  /** Methode : renvois la liste des entreprises **/
  public function getusers($entreprises)
  {
    $users = Null;
    $stmt = $this->connect->createCommand('SELECT slim_utulisateursysteme.nom, prenom, idEntreprise, slim_entreprise.nom as nomentreprise, idUserAuth FROM slim_utulisateursysteme join slim_entreprise on slim_entreprise.id = slim_utulisateursysteme.idEntreprise WHERE idEntreprise IN (' . $entreprises . ')')
      ->queryAll();
    if (sizeof($stmt) > 0) {
      $users = $stmt;
    }
    return $users;
  }

  /** Methode : mise a jour les infos d'une enteprise **/
  public function updateentreprise($idrow, $serialized_post, $iswiz, $idactor)
  {
    $updated = $users = $usrs = Null;
    if (isset($idrow)) {
      $unserialized_post = unserialize($serialized_post);
      switch ($iswiz) {
        case '1':
          $updatestmt = $this->connect->createCommand('UPDATE slim_entreprise SET nom=:nom, tl=:tl, ddl=:ddl, dfl=:dfl, tel=:tel, statut=:statut, updatedbyactor=:updatedbyactor WHERE id=:id')
            ->bindValues([
              ':id' => $idrow, ':nom' => $unserialized_post['denomination'], ':tl' => $unserialized_post['forfait'], ':ddl' => isset($unserialized_post['dtedebutforfait']) ? Yii::$app->nonSqlClass->convert_date_to_sql_form($unserialized_post['dtedebutforfait'], "M/D/Y") : Null,
              ':dfl' => isset($unserialized_post['dtefinforfait']) ? Yii::$app->nonSqlClass->convert_date_to_sql_form($unserialized_post['dtefinforfait'], "M/D/Y") : Null, ':tel' => $unserialized_post['tel'], ':statut' => $unserialized_post['statut'], ':updatedbyactor' => $idactor
            ])
            ->execute();
          if (isset($updatestmt)) {
            $updated = '2692';
            //Selectionnons l'id de tous les utilisateurs ciferiome aux compte de cette entreprise en edition
            $users = $this->connect->createCommand('SELECT id FROM slim_userauth WHERE entrepriseId=:entrepriseId')
              ->bindValue(':entrepriseId', $idrow)
              ->queryAll();
            if (sizeof($users) > 0) {
              for ($i = 1; $i < sizeof($users); $i++) {
                $usrs[] = $users[$i]['id'];
              }
              $usrs = implode(',', $usrs);
              // Mettons a jour la date limit d'access a tous les utilisateur de systeme au compte de cette entreprise
              $dfl = isset($unserialized_post['dtefinforfait']) ? Yii::$app->nonSqlClass->convert_date_to_sql_form($unserialized_post['dtefinforfait'], "M/D/Y") : Null;
              $dateinlicenceformat = Yii::$app->nonSqlClass->convertdatetolimitforfait($dfl);
              //die($dateinlicenceformat);
              $updatestmt = $this->connect->createCommand('UPDATE slim_userauth SET dteLimit=:dteLimit, dteLimitUpdatedByActor=:dteLimitUpdatedByActor WHERE id IN (' . $usrs . ')')
                ->bindValues([':dteLimit' => $dateinlicenceformat, ':dteLimitUpdatedByActor' => $idactor])
                ->execute();
              if (isset($updatestmt)) {
                $updated = '2692';
              }
            }
          }
          break;

        default:
          $updatestmt = $this->connect->createCommand('UPDATE slim_entreprise SET nom=:nom, numerosCommerce=:numerosCommerce, slogan=:slogan, dteCreation=:dteCreation, description=:description, tel=:tel, email=:email, updatedbyactor=:updatedbyactor WHERE id=:id')
            ->bindValues([
              ':nom' => $unserialized_post['denomination'], ':numerosCommerce' => $unserialized_post['numCommerce'], ':slogan' => $unserialized_post['slogan'], ':dteCreation' => isset($unserialized_post['dteCreation']) ? Yii::$app->nonSqlClass->convert_date_to_sql_form($unserialized_post['dteCreation'], "M/D/Y") : Null, ':description' => $unserialized_post['description'], ':tel' => $unserialized_post['tel'],
              ':email' => $unserialized_post['email'], ':updatedbyactor' => $idactor, ':id' => $idrow
            ])
            ->execute();

          $updatebank = $this->connect->createCommand('update slim_bank set montant=:montant where idEntreprise=:idEntreprise')
            ->bindValues([':idEntreprise' => $idrow, ':montant' => $unserialized_post['bankmontant']])
            ->execute();

          if (isset($updatestmt)) {
            //Mettons a jour la table des target points
            $stmt = $this->connect->createCommand('UPDATE slim_entreprise_targetpoint SET monthlytargetsale=:monthlytargetsale, monthlytargetclient=:monthlytargetclient WHERE idEntreprise=:idEntreprise')
              ->bindValues([':monthlytargetsale' => $unserialized_post['monthlytargetsale'], ':monthlytargetclient' => $unserialized_post['monthlytargetclient'], ':idEntreprise' => $idrow])
              ->execute();
            $updated = '2692';
          }
          break;
      }
    }
  }

  /** Methode : edit les infos d'une entreprise **/
  public function editionentreprise($action_on_this)
  {
    $entreprise = Null;
    if (isset($action_on_this) && !empty($action_on_this)) {
      $stmt = $this->connect->createCommand('SELECT slim_entreprise.id, slim_bank.montant as bankmontant, nom, numerosCommerce, slogan, dteCreation, symbolemonaie, ddl,dfl, description, monthlytargetsale, monthlytargetclient FROM slim_entreprise 
            JOIN slim_entreprise_targetpoint on slim_entreprise.id = slim_entreprise_targetpoint.idEntreprise 
            JOIN slim_bank on slim_bank.idEntreprise = slim_entreprise.id
            WHERE slim_entreprise.id=:id 
            ')
        ->bindValue(':id', $action_on_this)
        ->queryOne();
      if (isset($stmt)) {
        $entreprise = $stmt;
      }
    }
    return $entreprise;
  }

  /** Methode : Appliquer la jour du logo **/
  public function do_upload_logo($logo = '', $id_commerce = '')
  {
    $do_upload = $this->connect->createCommand('update slim_entreprise set logo=:logo where id=:id')
      ->bindValues([':id' => $id_commerce, ':logo' => $logo])
      ->execute();
    if ($do_upload) return true;
    return;
  }

  /** Methode : Enregistrer une branche **/
  public function enrg_branche($commerce_enrg = '', $data = Null, $actor = '')
  {
    $id_commerce = 0;
    $commerce_data = $this->connect->createCommand('select id from slim_entreprise where pin=:pin')
      ->bindValue(':pin', $commerce_enrg)
      ->queryOne();
    if (isset($commerce_data) && sizeof($commerce_data) > 0) $id_commerce = $commerce_data['id'];

    switch ($data['forfait']) {
      case 'premium':
        $nom = $data['denominationEntite'];
        $Tel = $data['tel'];
        $email = $data['email'];
        $addresse = $data['adresseEntite'];
        break;

      default:
        $nom = $data['denomination'];
        $Tel = $data['telEntite'];
        $email = $data['emailEntite'];
        $addresse = $data['adresse'];
        break;
    }
    //Inserer la branche
    $this->connect->createCommand('INSERT INTO slim_entite (idEntreprise, nom, Tel, email, addresse, createdOn) VALUES (:idEntreprise, :nom, :Tel, :email, :addresse, :createdOn)')
      ->bindValues([':idEntreprise' => $id_commerce, ':nom' => $nom, ':Tel' => $Tel, ':email' => $email, ':addresse' => $addresse, ':createdOn' => date('Y-m-d')])
      ->execute();
    return true;
  }


  /** Methode : Enregistrer une société **/
  public function enrg_commerce($data, $logo, $code, $actor, $date_start_licence, $date_end_licence)
  {
    //Proceder à la création
    $insertstmt = $this->connect->createCommand('INSERT INTO slim_entreprise (nom, activite, tel, email, addresse, tl, ddl, dfl, logo, pin, createdbyactor, createdOn)
            VALUES (:nom, :activite, :tel, :email, :addresse, :tl, :ddl, :dfl, :logo, :pin, :createdbyactor, :createdOn)')
      ->bindValues([':nom' => $data["denomination"], ':activite' => $data["activite"], ':tel' => $data["tel"], ':email' => $data["email"], ':addresse' => $data["adresse"], ':tl' => $data['forfait'], ':ddl' => $date_start_licence, ':dfl' => $date_end_licence, ':logo' => $logo, ':pin' => $code, ':createdbyactor' => $actor, ':createdOn' => date('Y-m-d')])
      ->execute();
    return $code;
  }

  /** Methode : Save new entreprise **/
  public function enrg_espace_gestion($data = Null, $logo = Null, $code = '', $actor = 0)
  {
    $date_start_licence = !empty($data['dtedebutforfait']) ? Yii::$app->nonSqlClass->convert_date_to_sql_form($data['dtedebutforfait'], 'M/D/Y') : Null;
    $date_end_licence = yii::$app->nonSqlClass->topup_year($date_start_licence);

    $commerce_enrg = parametreClass::enrg_commerce($data, $logo, $code, $actor, $date_start_licence, $date_end_licence);
    $branche_enrg = parametreClass::enrg_branche($commerce_enrg, $data, $actor);

    return true;
  }

  public function _enrgnewentreprise($nom, $activite, $tel, $email, $adresse, $typelicence, $idactor)
  {
    $entreprise = Null;
    $diff10years = new DateInterval('P3650D');

    if (isset($serialized_post)) {
      $unserialized_post = unserialize($serialized_post);
      $stmt = $this->connect->createCommand('SELECT id FROM slim_entreprise WHERE nom=:nom AND statut=:statut')
        ->bindValues([':nom' => $unserialized_post['denomination'], ':statut' => '1'])
        ->queryAll();
      if (sizeof($stmt) <= 0) {
        $dteCreation = !empty($unserialized_post['dteCreation']) ? Yii::$app->nonSqlClass->convert_date_to_sql_form($unserialized_post['dteCreation'], 'M/D/Y') : Null;
        $ddl = !empty($unserialized_post['dtedebutforfait']) ?  Yii::$app->nonSqlClass->convert_date_to_sql_form($unserialized_post['dtedebutforfait'], 'M/D/Y') : Null;
        //$dfl =  !empty($unserialized_post['dtefinforfait']) ?  Yii::$app->nonSqlClass->convert_date_to_sql_form($unserialized_post['dtefinforfait'], 'M/D/Y') : Null;
        $dateDebut = !empty($unserialized_post['dtedebutforfait']);
        $dfl = $dateDebut->add($diff10years);
        $insertstmt = $this->connect->createCommand('INSERT INTO slim_entreprise (nom, activite, dteCreation, tl, ddl, dfl, adresse, tel, email, statut, createdbyactor, createdOn)
            VALUES (:nom, :activite, :dteCreation, :tl, :ddl, :dfl, :adresse, :tel, :email, :statut, :createdbyactor, :createdOn)')
          ->bindValues([':nom' => $nom, ':activite' => $activite, ':dteCreation' => $dteCreation, ':tl' => $typelicence, ':ddl' => $ddl, ':dfl' => $dfl, ':adresse' => $adresse, ':tel' => $tel, ':email' => $email, ':statut' => '1', ':createdbyactor' => $idactor, ':createdOn' => date("Y-m-d")])
          ->execute();
      }
      if (isset($insertstmt)) {
        $entreprise = '2692';
      }
    }
    return $entreprise;
  }

  //recuperation de idEntreprise
  public function idEtreprise($tel)
  {
    $entreprise = Null;
    $stmt = $this->connect->createCommand('SELECT id
            FROM slim_entreprise
            WHERE tel=:tel')
      ->bindValue(':tel', $tel)
      ->queryOne();
    return $stmt;
  }

  /** Methode : renvois la liste des entreprises **/
  public function getentreprises($idEntreprise, $iswiz = Null)
  {
    $entreprises = Null;
    switch ($iswiz) {
      case '1':
        $stmt = $this->connect->createCommand('SELECT slim_entreprise.*, COUNT(slim_entite.idEntreprise) AS nbEntite
            FROM slim_entreprise,slim_entite
            WHERE slim_entreprise.id=slim_entite.idEntreprise
            GROUP BY slim_entreprise.id')
          ->queryAll();

        // $nbFiliale=$this->connect->createCommand('SELECT slim_entreprise.*,COUNT(slim_entite.idEntreprise) AS nbEntite FROM slim_entreprise, slim_entite WHERE slim_entreprise.id=slim_entite.idEntreprise')
        // ->queryAll();

        if (sizeof($stmt) > 0) {
          $entreprises = $stmt;
        }
        break;

      default:
        $stmt = $this->connect->createCommand('SELECT * FROM slim_entreprise WHERE id=:idEntreprise')
          ->bindValue(':idEntreprise', $idEntreprise)
          ->queryAll();



        if (sizeof($stmt) > 0) {
          $entreprises = $stmt;
        }
        break;
    }
    return $entreprises;
  }

  //Nombre de Filiale
  public function nbFiliale($idEntreprise)
  {
    $nbFiliale = $this->connect->createCommand('SELECT slim_entreprise.*,COUNT(slim_entite.idEntreprise) AS nbEntite FROM slim_entreprise, slim_entite WHERE slim_entreprise.id=slim_entite.idEntreprise AND slim_entreprise.id=:idEntreprise')
      ->bindValue(':idEntreprise', $idEntreprise)
      ->queryAll();
  }

  /** Methode : Mise a jour, nature campagne  **/
  public function updatecampagne($idrow, $denomination, $msg, $statut, $idactor)
  {
    $updated = Null;
    if (isset($idrow)) {
      $stmt = $this->connect->createCommand('UPDATE slim_campagnenature SET denomination=:denomination, msg=:msg, statut=:statut, updatedpar=:updatedpar WHERE id=:id')
        ->bindValues([':denomination' => $denomination, ':msg' => $msg, ':statut' => $statut, ':updatedpar' => $idactor, ':id' => $idrow])
        ->execute();
      $updated = '2692';
    }
    return $updated;
  }

  /** Methode : edition camoagne **/
  public function editioncampagne($action_on_this)
  {
    $naturedata = Null;
    if (isset($action_on_this) && !empty($action_on_this)) {
      $stmt = $this->connect->createCommand('SELECT * FROM slim_campagnenature WHERE id=:id')
        ->bindValue(':id', $action_on_this)
        ->queryOne();
      if (isset($stmt)) {
        $naturedata = $stmt;
      }
    }
    return $naturedata;
  }

  /** Methode : Save new campagne **/
  public function enrgnewcampagne($denomination, $msg, $statut, $idactor, $idEntreprise)
  {
    $motif = Null;
    if (isset($denomination) && isset($idactor) && isset($idEntreprise)) {
      $stmt = $this->connect->createCommand('SELECT id FROM slim_campagnenature WHERE denomination=:denomination AND idEntreprise=:idEntreprise')
        ->bindValues([':denomination' => $denomination, ':idEntreprise' => $idEntreprise])
        ->queryAll();
      if (sizeof($stmt) <= 0) {
        $insertstmt = $this->connect->createCommand('INSERT INTO slim_campagnenature (denomination, msg, statut, idEntreprise, updatedpar) VALUES (:denomination, :msg, :statut, :idEntreprise, :updatedpar)')
          ->bindValues([':denomination' => $denomination, ':msg' => $msg, ':statut' => $statut, ':idEntreprise' => $idEntreprise, ':updatedpar' => $idactor])
          ->execute();
      }
      if (isset($insertstmt)) {
        $motif = '2692';
      }
    }
    return $motif;
  }

  /** Methode : renvois la liste des nature de campagnes **/
  public function getNatureCampagne($idEntreprise)
  {
    $natures = Null;
    if (isset($idEntreprise)) {
      $stmt = $this->connect->createCommand('SELECT * FROM slim_campagnenature WHERE idEntreprise=:idEntreprise')
        ->bindValue(':idEntreprise', $idEntreprise)
        ->queryAll();
      if (sizeof($stmt) > 0) {
        $natures = $stmt;
      }
    }
    return $natures;
  }

  /** Methode : update les infos d'un enregistrement **/
  public function updateMotifenrgClient($action_on_this, $statut, $libelle, $idactor)
  {
    $rslt = Null;
    if (isset($action_on_this)) {
      $stmt = $this->connect->createCommand('UPDATE slim_client_enrg_motif SET libelle=:libelle, insertBy=:insertBy, statut=:statut WHERE id=:id')
        ->bindValues([':libelle' => $libelle, ':insertBy' => $idactor, ':statut' => $statut, ':id' => $action_on_this])
        ->execute();
      if ($stmt) {
        $rslt = '2692';
      }
    }
    return $rslt;
  }

  /** Methode qui renvois le lable du motif d'enregistrement d'un contact **/
  public function labelContactMotifEnrg($idmotif)
  {
    $label = Null;
    if (isset($idmotif)) {
      $stmt = $this->connect->createCommand('SELECT libelle FROM slim_client_enrg_motif WHERE id=:id')
        ->bindValue(':id', $idmotif)
        ->queryOne();
      if (sizeof($stmt) > 0) {
        $label = $stmt['libelle'];
      }
    }
    if (is_null($label)) $label = Yii::t('app', 'tout');
    return $label;
  }

  /** Methode qui renvois le lable du statut d'un contact **/
  public function labelContactStatut($idContact)
  {
    $label = Yii::t('app', 'tout');
    if (isset($idContact)) {
      switch ($idContact) {
        case '1':
          $label = Yii::t('app', 'particulier');
          break;

        case '2':
          $label = Yii::t('app', 'entreprise');
          break;

        default:
          $label = Yii::t('app', 'tout');
          break;
      }
    }
    return $label;
  }

  /** Methode enregistrement d'un nouveau motifs pour l'enregistrement de client / prospect et autres **/
  public function enrgmotifenrgclient($libelle, $idactor, $idEntreprise)
  {
    $motif = Null;
    if (isset($libelle) && isset($idactor) && isset($idEntreprise)) {
      $stmt = $this->connect->createCommand('SELECT id FROM slim_client_enrg_motif WHERE libelle=:libelle AND idEntreprise=:idEntreprise')
        ->bindValues([':libelle' => $libelle, ':idEntreprise' => $idEntreprise])
        ->queryAll();
      if (sizeof($stmt) <= 0) {
        $insertstmt = $this->connect->createCommand('INSERT INTO slim_client_enrg_motif (idEntreprise, libelle, insertBy) VALUES (:idEntreprise, :libelle, :insertBy)')
          ->bindValues([':idEntreprise' => $idEntreprise, ':libelle' => $libelle, ':insertBy' => $idactor])
          ->execute();
      }
      if (isset($insertstmt)) {
        $motif = '2692';
      }
    }
    return $motif;
  }

  /** Methode renvois tous motifs in client enregistrement  **/
  public function clientEnrgMotifs($idEntreprise)
  {
    $motifs = Null;
    if (isset($idEntreprise)) {
      $stmt = $this->connect->createCommand('SELECT * FROM slim_client_enrg_motif WHERE idEntreprise=:idEntreprise AND statut=:statut')
        ->bindValues([':idEntreprise' => $idEntreprise, ':statut' => '1'])
        ->queryAll();
      if (sizeof($stmt) > 0) {
        $motifs = $stmt;
      }
    }
    return $motifs;
  }

  /** Methode qui enregistre une ligne d'info d'un utilisateur recement cree **/
  public function newRowUtulisateursysteme($idUserAuth, $nom, $prenom, $adresse, $idEntreprise, $idEntite)
  {
    $reussite = Null;
    if (isset($idUserAuth)) {
      $stmt = $this->connect->createCommand('INSERT INTO slim_utulisateursysteme (idUserAuth, nom, prenom, adresse, idEntreprise, idEntite) VALUES (:idUserAuth, :nom, :prenom, :adresse, :idEntreprise, :idEntite)')
        ->bindValues([':idUserAuth' => $idUserAuth, ':nom' => $nom, ':prenom' => $prenom, ':adresse' => $adresse, ':idEntreprise' => $idEntreprise, ':idEntite' => $idEntite])
        ->execute();
      if (isset($reussite)) {
        $reussite = '26';
      }
    }
    return $reussite;
  }

  /** Methode qui enregistre une ligne de role d'un utilisateur recement cree **/
  public function newRowUserauthmenuaction($idInsertedUserAuth, $typeUsr, $chaineMenu)
  {
    $stmt = Null;
    if (isset($idInsertedUserAuth)) {
      $stmt = $this->connect->createCommand('INSERT INTO slim_userauthmenuaction (idUniq, idUserType, menu) VALUES (:idUniq, :idUserType, :menu)')
        ->bindValues([':idUniq' => $idInsertedUserAuth, ':idUserType' => $typeUsr, ':menu' => $chaineMenu])
        ->execute();
    }
    return $stmt;
  }

  /** Methode qui cree un nouveau utilisateur dans la table userAuth **/
  public function newUserInUserauth($entreprise, $service, $userName, $motPasse, $typeUser, $entrepriseId, $listEntite, $dteCreation, $dteLimit, $statCmpt)
  {
    $idInsertedUserAuth = Null;
    if (isset($userName) && !empty($userName) && isset($motPasse) && !empty($motPasse)) {
      $stmt = $this->connect->createCommand('INSERT INTO slim_userauth (userName, motPasse, typeUser, entrepriseId, listEntite, dteCreation, dteLimit, statCmpt)
                                                  VALUES (:userName, :motPasse, :typeUser, :entrepriseId, :listEntite, :dteCreation, :dteLimit, :statCmpt)')
        ->bindValues([":userName" => $userName, ":motPasse" => $motPasse, ':typeUser' => $typeUser, ':entrepriseId' => $entreprise, ':listEntite' => $service, ':dteCreation' => $dteCreation, ':dteLimit' => $dteLimit, ':statCmpt' => $statCmpt])
        ->execute();
      if (isset($stmt)) {
        $idInsertedUserAuth = Yii::$app->mainCLass->getUserAuthId($userName);
      }
    }
    return $idInsertedUserAuth;
  }

  public function buildMenuStrg($request='')
  {
    $privilege = yii::$app->params['defaultmenu'];
    for ($i=0; $i < sizeof($request['privilege']); $i++) { 
      $privilege .= $request['privilege'][$i];
    }
    if(is_string($privilege)) return $privilege;
    return;
  }

  // /** Methode qui charge la chaine de menu **/
  // public function buildMenuStrg($postInStrg)
  // {
  //   if (isset($postInStrg)) {
  //     $postInArray = (!empty($postInStrg)) ? unserialize($postInStrg) : Null;
  //     if (is_array($postInArray) && sizeof($postInArray) > 0) {
  //       $menuStrg = Yii::$app->params['menuStrgInitial'];
  //       #Inventaire
  //       if (isset($postInArray['inventaire']) && $postInArray['inventaire'] == 1) {
  //         $menuStrg .= Yii::$app->params['subMenu_inventaire'];
  //       }
  //       #stock
  //       if (isset($postInArray['stok']) && $postInArray['stok'] == 1) {
  //         $menuStrg .= Yii::$app->params['subMenu_stok'];
  //       }
  //       #vente
  //       if (isset($postInArray['vente']) && $postInArray['vente'] == 1) {
  //         $menuStrg .= Yii::$app->params['subMenu_vente'];
  //       }

  //       #client
  //       if (isset($postInArray['client']) && $postInArray['client'] == 1) {
  //         $menuStrg .= Yii::$app->params['subMenu_client'];
  //       }

  //       #diver
  //       if (isset($postInArray['diver']) && $postInArray['diver'] == 1) {
  //         $menuStrg .= Yii::$app->params['subMenu_diver'];
  //       }

  //       #parametre
  //       if (isset($postInArray['parametre']) && $postInArray['parametre'] == 1) {
  //         $menuStrg .= Yii::$app->params['subMenu_parametre'];
  //       }

  //       #rapport
  //       if (isset($postInArray['rg_invent']) || isset($postInArray['rg_vente']) || isset($postInArray['rg_event'])) {
  //         $menuStrg .= Yii::$app->params['submenu_rapport'];
  //       }

  //       #rapport inventaire
  //       if (isset($postInArray['rg_invent']) && $postInArray['rg_invent'] == 1) {
  //         $menuStrg .= Yii::$app->params['submenu_rapport_inventaire'];
  //       }

  //       #rapport Vente
  //       if (isset($postInArray['rg_vente']) && $postInArray['rg_vente'] == 1) {
  //         $menuStrg .= Yii::$app->params['submenu_rapport_vente'];
  //       }

  //       #rapport diver
  //       if (isset($postInArray['rg_diver']) && $postInArray['rg_diver'] == 1) {
  //         $menuStrg .= Yii::$app->params['submenu_rapport_diver'];
  //       }
  //     }
  //   }
  //   return $menuStrg;
  // }

  /** Fonction qui previent la duplication du nom d'utilisateur **/
  public function preventDuplication($username)
  {
    $isprevent = Null;
    if (isset($username)) {
      $stmt = $this->connect->createCommand('SELECT userName FROM slim_userauth WHERE userName=:userName')
        ->bindValue(':userName', $username)
        ->queryOne();
      if (is_array($stmt) && sizeof($stmt) > 0) {
        $isprevent = '33';
      } else {
        $isprevent = '2692';
      }
    }
    return $isprevent;
  }

  /** Recuperrons les infos sur un type de licence en fonction de son id **/
  public function getLicenceInfos($id)
  {
    $infos = Null;
    if (isset($id)) {
      $infos = $this->connect->createCommand('SELECT * FROM slim_licence WHERE id=:id')
        ->bindValue(':id', $id)
        ->queryOne();
    }
    return $infos;
  }

  /** Renvois les infos generales d'une entreprise **/
  public function getEntrepriseInfos($idEntreprise)
  {
    $infos = Null;
    if (isset($idEntreprise)) {
      $infos = $this->connect->createCommand('SELECT * FROM slim_entreprise WHERE id=:id')
        ->bindValue(':id', $idEntreprise)
        ->queryOne();
    }
    return $infos;
  }

  /** Analysons si l'utilisateur peut encore creer de nouveau utilisateur avec le type de licence quil a subscrit **/
  public function analyseCreationUsr($actualUsrCount, $idEntreprise)
  {
    $rep = Null;
    if (isset($actualUsrCount) && isset($idEntreprise)) {
      $infosEntreprise = parametreClass::getEntrepriseInfos($idEntreprise);
      #hold entreprise licence type
      $entrepriseTypeLicence = (is_array($infosEntreprise) && sizeof($infosEntreprise) > 0) ? $infosEntreprise['tl'] : Null;
      $licenceInfo = parametreClass::getLicenceInfos($entrepriseTypeLicence);
      #hold licence type's nomber of user allowed
      $maxUsrBaseLicence = (is_array($licenceInfo) && sizeof($licenceInfo) > 0) ? $licenceInfo['nu'] : Null;
      # now ; we compare to see if new user can be added
      if ($actualUsrCount <= $maxUsrBaseLicence) {
        $rep = '2692';
      } else {
        $rep = '28';
      }
    }
    return $rep;
  }

  /** Recuperons la liste des types de l'utilisateur **/
  public function getAllUsertype()
  {
    $rslt = Null;
    $stmt = $this->connect->createCommand('SELECT id, usertypeid, nom FROM slim_usertype ORDER BY usertypeid DESC')
      ->queryAll();
    if (is_array($stmt) && sizeof($stmt)) {
      $rslt = $stmt;
    }
    return $rslt;
  }

  /** Recuperrons le nombre d'utilisateur actif par entreprise **/
  public function getAllUserInEntreprise($idEntreprise)
  {
    $allUser = Null;
    if (isset($idEntreprise)) {
      $stmt = $this->connect->createCommand('SELECT COUNT(entrepriseId) as idcount FROM slim_userauth
                                                WHERE entrepriseId =:idEntreprise
                                                 AND statCmpt IN ("1","2")
                                                 GROUP BY entrepriseId')
        ->bindValue(':idEntreprise', $idEntreprise)
        ->queryOne();
      if (is_array($stmt) && sizeof($stmt) > 0) {
        $allUser = $stmt;
      }
    }
    return $allUser;
  }

  public function deleteCampagne($idCamp)
  {
    $deleteCamp = $this->connect->createCommand('DELETE FROM slim_campagnenature WHERE id=:id')
      ->bindValue(':id', $idCamp)
      ->execute();
    return true;
  }

  // liste des branche par entreprise
  public function listeBrache($idEntreprise)
  {
    $stmt = $this->connect->createCommand('SELECT * FROM slim_entite WHERE idEntreprise=:idEntreprise AND statut=1')
      ->bindValue(':idEntreprise', $idEntreprise)
      ->queryAll();
    return $stmt;
  }

  // liste des utilisateur
  public function listeUtilisateur($idEntreprise)
  {
    $stmt = $this->connect->createCommand('SELECT slim_utulisateursysteme.idUserAuth AS idUserAuth, slim_utulisateursysteme.nom AS nom, slim_utulisateursysteme.prenom AS prenom, slim_utulisateursysteme.adresse AS adresse, slim_utulisateursysteme.email AS email, slim_usertype.nom AS privilege, slim_entite.nom AS entite
    FROM slim_utulisateursysteme, slim_usertype, slim_userauth,slim_entite

    WHERE slim_utulisateursysteme.idUserAuth=slim_userauth.id
    AND slim_userauth.listEntite=slim_entite.id 
    AND slim_userauth.typeUser=slim_usertype.usertypeid
    AND slim_utulisateursysteme.idEntreprise=:idEntreprise 
    ')
      ->bindValue(':idEntreprise', $idEntreprise)
      ->queryAll();
    return $stmt;
  }

  // mis a jour de la description de l entreprise
  public function updateDescription($idEntreprise, $idDesc)
  {
    $update = $this->connect->createCommand('UPDATE  slim_entreprise SET description=:description WHERE id=:id')
      ->bindValues([':id' => $idEntreprise, ':description' => $idDesc])
      ->execute();
    return true;
  }

  // update entreprise
  public function updateEntreprises($idEntreprise, $nom, $numComm, $activite, $tel, $email, $adresse, $objectifVente, $objectifClientele)
  {

    $update = $this->connect->createCommand('UPDATE  slim_entreprise SET nom=:nom, numerosCommerce=:numComm,activite=:activite, tel=:tel, email=:email, addresse=:adresse, objectifVente=:obVente, objectifClientele=:obClientele  WHERE id=:id')
      ->bindValues([':id' => $idEntreprise, ':nom' => $nom, ':numComm' => $numComm, ':activite' => $activite, ':tel' => $tel, ':email' => $email, ':adresse' => $adresse, ':obVente' => $objectifVente, ':obClientele' => $objectifClientele])
      ->execute();
    return true;
  }

  // new branche
  public function newBranche($idEntreprise, $nom, $desc, $tel, $email, $adresse)
  {
    $update = $this->connect->createCommand('INSERT INTO slim_entite (idEntreprise,statut,nom,description,Tel,email,addresse,createdOn) 
     VALUES(:idEntreprise,:statut,:nom,:description,:tel,:email,:adresse, :createdOn)')
      ->bindValues([':idEntreprise' => $idEntreprise, 'statut' => 1, ':nom' => $nom, ':description' => $desc, ':tel' => $tel, ':email' => $email, ':adresse' => $adresse, ':createdOn' => date('Y-m-d')])
      ->execute();
    return true;
  }

  // edit branche
  public function editBrache($idEntreprise, $id)
  {
    $stmt = $this->connect->createCommand('SELECT * FROM slim_entite WHERE idEntreprise=:idEntreprise AND statut=1 AND id=:idBranche')
      ->bindValues([':idEntreprise' => $idEntreprise, ':idBranche' => $id])
      ->queryOne();
    return $stmt;
  }

  // update branche
  public function updateBranche($nom, $desc, $tel, $email, $adresse, $idBranche)
  {
    $update = $this->connect->createCommand('UPDATE slim_entite SET nom=:nom,description=:description,Tel=:tel,email=:email,addresse=:adresse WHERE id=:idBranche ')
      ->bindValues([':nom' => $nom, ':description' => $desc, ':tel' => $tel, ':email' => $email, ':adresse' => $adresse, ':idBranche' => $idBranche])
      ->execute();
    return true;
  }

  // update slogan
  public function updateSlogan($idEntreprise, $slogan)
  {
    $update = $this->connect->createCommand('UPDATE slim_entreprise SET slogan=:slogan WHERE id=:idEntreprise ')
      ->bindValues([':idEntreprise' => $idEntreprise, ':slogan' => $slogan])
      ->execute();
    return true;
  }

  // liste des entreprise
  public function listentreprise()
  {
    $stmt = $this->connect->createCommand('SELECT * FROM slim_entreprise')->queryAll();
    return $stmt;
  }


  /** Methode enregistrement d'un nouveau compte bancaire**/
  public function enregbanque($numero, $adresse, $libelle, $idactor, $idEntreprise)
  {
    $banque = Null;
    if (isset($numero) && isset($libelle) && isset($idactor) && isset($idEntreprise)) {
      $stmt = $this->connect->createCommand('SELECT id FROM slim_entreprise_bank WHERE numero_compte=:numero_compte AND idEntreprise=:idEntreprise')
        ->bindValues([':numero_compte' => $numero, ':idEntreprise' => $idEntreprise])
        ->queryAll();
      if (sizeof($stmt) <= 0) {
        $insertstmt = $this->connect->createCommand('INSERT INTO slim_entreprise_bank (idEntreprise,numero_compte, adresse, banque, idActeur,dte_maj,statut) VALUES (:idEntreprise,:numCompte,:adresse, :banque,:idActeur, :dte_maj,:statut)')
          ->bindValues([':idEntreprise' => $idEntreprise, ':numCompte' => $numero, ':adresse' => $adresse, ':banque' => $libelle, ':idActeur' => $idactor, ':dte_maj' => date('Y-m-d'), ':statut' => '1'])
          ->execute();
      }
      if (isset($insertstmt)) {
        $banque = '2692';
      }
    }
    return $banque;
  }

  /** Methode renvois tous banque  **/
  public function listeBanque($idEntreprise)
  {
    $banques = Null;
    if (isset($idEntreprise)) {
      $stmt = $this->connect->createCommand('SELECT * FROM slim_entreprise_bank WHERE idEntreprise=:idEntreprise AND statut=:statut')
        ->bindValues([':idEntreprise' => $idEntreprise, ':statut' => '1'])
        ->queryAll();
      if (sizeof($stmt) > 0) {
        $banques = $stmt;
      }
    }
    return $banques;
  }

  /** Methode : update les infos d'un enregistrement **/
  public function updateEntepriseBank($action_on_this, $banque, $numero_compte, $adresse, $idactor)
  {
    $rslt = Null;
    if (isset($action_on_this)) {
      $stmt = $this->connect->createCommand('UPDATE slim_entreprise_bank SET banque=:banque, numero_compte=:numero_compte, adresse=:adresse,idActeur=:idActeur,dte_maj=:dte_maj WHERE id=:id')
        ->bindValues([':banque' => $banque, ':numero_compte' => $numero_compte, ':adresse' => $adresse, ':idActeur' => $idactor, ':dte_maj' => date('Y-m-d'), ':id' => $action_on_this])
        ->execute();
      if ($stmt) {
        $rslt = '2692';
      }
    }
    return $rslt;
  }

  public function countUser($idEntreprise)
  {
    $user=null;
    if (isset($idEntreprise)) {
      $stmt = $this->connect->createCommand('SELECT count(entrepriseId) as nombreuser FROM slim_userauth
                                             WHERE entrepriseId=:idEntreprise')
        ->bindValues([':idEntreprise' => $idEntreprise])
        ->queryOne();
      if (sizeof($stmt) > 0) {
        $user = $stmt['nombreuser'];
      }
    }
    return $user;
  }
}
