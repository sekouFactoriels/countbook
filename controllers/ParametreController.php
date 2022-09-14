<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;

class ParametreController extends Controller
{
  private $pg = Null;
  private $msg = Null;
  private $listeBranche = Null;

  #******************************************
  #           NOMENCLATURE  DES ACTIONS
  # Nom de l'action : NomDOssier_Nomdufichier
  #***************************************

  /** Methode : Parametre des utilisateurs **/
  public function actionUsers()
  {
    $msg = $users = $postInStrg = $typeUser =  $user = $services = Null;
    $this->pg = '/parametre/monespacegestion/mainview.php';
    $userPrimaryData = Yii::$app->mainCLass->getUserAuthPrimaryInfo();
    $entreprises = Yii::$app->parametreClass->getentreprises($userPrimaryData['idEntreprise'], $userPrimaryData['iswiz']);
    $entreprises_aslist = Yii::$app->nonSqlClass->convertinttableautostrg($entreprises);
    $users = Yii::$app->parametreClass->getusers($entreprises_aslist);

    $espace_gestion = Yii::$app->parametreClass->charger_espace_gestion($userPrimaryData['idEntreprise']);

    // nombre de users
    $userCount = Yii::$app->parametreClass->countUser($userPrimaryData['idEntreprise']);

    // liste des branches
    $listeBranche =  Yii::$app->parametreClass->listeBrache($userPrimaryData['idEntreprise']);

    //liste des utilisateur
    $listeUtilisateur = Yii::$app->parametreClass->listeUtilisateur($userPrimaryData['idEntreprise']);

    // analyse action_key
    if (Yii::$app->request->isPost) {
      $request = $_POST;
      
      /** Analysons la valeur attribue a action_key **/
      switch ($request['action_key']) {
          
          // Do update user form
        case md5('users_updateuser'):
          // Evaluons si les champs obligatoires sont pas vides
          if (!empty($request['pnom']) && !empty($request['nom']) && (!empty($request['typeUsr']) && $request['typeUsr'] > 0)) {
            if (Yii::$app->session['token2'] != $_POST['token2']) {
              
              if (isset($request['action_on_this'])) {
                $idrow = base64_decode($request['action_on_this']);
                $updatestmt = Yii::$app->parametreClass->updateuser($idrow, serialize($request), $userPrimaryData['auhId']);

                /** Evitons la duplication du dernier enregistrement **/
                Yii::$app->session->set(Yii::$app->params['tok2'], $_POST[Yii::$app->params['tok2']]);
                if (isset($updatestmt) && $updatestmt == '2692') {
                  yii::$app->session->setFlash('flashmsg', '<div class="alert alert-success">' . yii::t("app", "msg_updateentreprise") . '</div>');
                }
              }

            }

          } else {
            $typeUser = Yii::$app->parametreClass->getAllUsertype();
            $this->pg = '/parametre/monespacegestion/editUserForm.php';
            $postInStrg = serialize($request);
            yii::$app->session->setFlash('flashmsg', '<div class="alert alert-danger">' . yii::t("app", "doneesForcesVides") . '</div>');
          }

          return $this->redirect(Yii::$app->request->baseUrl . '/' . md5('parametre_entreprises'));
          break;

          /** Enregistrement de compte **/
        case md5('users_saveuser'):
                  // Recuperrons les infos basic de l;utilisateur connected
          $userPrimaryData = Yii::$app->mainCLass->getUserAuthPrimaryInfo();
          if (Yii::$app->session['token2'] != $_POST['token2']) {
            // Evaluons si les champs obligatoires sont pas vides
            if (!empty($request['pnom']) && !empty($request['nom']) && (!empty($request['typeUsr']) && $request['typeUsr'] > 0) && !empty($request['nomuser']) && !empty($request['motpass']) && !empty($request['entreprise'])) {
              // recuperrons le nombre total d'utilisateur actifs en fonction de l'entreprise
              $allUser = Yii::$app->parametreClass->getAllUserInEntreprise($userPrimaryData['idEntreprise']);

              // Analysons si l'utilisateur peut bien cree encore autre user
              $analyseCreationUsr = Yii::$app->parametreClass->analyseCreationUsr($allUser['idcount'], $userPrimaryData['idEntreprise']);
              
              /*
                if($analyseCreationUsr !== '2692'){
                  $typeUser = Yii::$app->parametreClass->getAllUsertype();
                  $this->pg = '/parametre/user/newuser.php';
                  $postInStrg = serialize($request);
                  $msg = serialize(['type'=>'alert alert-danger','strong'=>Yii::t('app','erreur'),'text'=>Yii::t('app','usrcantbeadded')]);
                }
                */

              // prevenir duplication du nom d'utilisateur
              //if($analyseCreationUsr === '2692'){
              $usrnameDuplication = Yii::$app->parametreClass->preventDuplication($request['nomuser']);
              if ($usrnameDuplication !== '2692') {
                $typeUser = Yii::$app->parametreClass->getAllUsertype();
                $this->pg = '/parametre/monespacegestion/newUserForm.php';
                $postInStrg = serialize($request);
                yii::$app->session->setFlash('flashmsg', '<div class="alert alert-danger">' . yii::t("app", "usrduplication") . '</div>');
              }
              //}

              // Enregistrer le compte
              if ($usrnameDuplication === '2692') {
                $postInStrg = serialize($request);
                $chaineMenu = Yii::$app->parametreClass->buildMenuStrg($request);
                #preparation du mot de passe
                $pswrd = md5($request['motpass']);

                #Enregistrons les infos dans la table : slim_userauth
                $idInsertedUserAuth = Yii::$app->parametreClass->newUserInUserauth($request['entreprise'], $request['service'], $request['nomuser'], $pswrd, $request['typeUsr'], $request['entreprise'], $userPrimaryData['idEntite'], date('Y-m-d'), date('Y-m-d'), '1');
                if (isset($idInsertedUserAuth)) {
                  $reussite = Null;
                  # Enregistrons les infos dans la table : slim_userauthmenuaction
                  $stmt = Yii::$app->parametreClass->newRowUserauthmenuaction($idInsertedUserAuth, $request['typeUsr'], $chaineMenu);
                  # Enregistrons les infos dans la table : slim_utulisateursysteme
                  $reussite = Yii::$app->parametreClass->newRowUtulisateursysteme($idInsertedUserAuth, $request['nom'], $request['pnom'], $request['tel'], $request['entreprise'], $request['service']);
                }
                # Enregistrons l'evenement dans
                #### EVENEMENT : TYPE : augmentation de la quantite entrepose en stok ###
                $event_msg = Yii::t('app', 'msg_creationcomptUtilisateur');
                Yii::$app->mainCLass->creerEvent('015', $event_msg);
                /** Evitons la duplication du dernier enregistrement **/
                Yii::$app->session->set(Yii::$app->params['tok2'], $_POST[Yii::$app->params['tok2']]);
                yii::$app->session->setFlash('flashmsg', '<div class="alert alert-success">' . yii::t("app", "operatSucess") . '</div>');
              }

            } else {
              $typeUser = Yii::$app->parametreClass->getAllUsertype();
              $this->pg = '/parametre/monespacegestion/newUserForm.php';
              $postInStrg = serialize($request);
              yii::$app->session->setFlash('flashmsg', '<div class="alert alert-danger">' . yii::t("app", "doneesForcesVides") . '</div>');
            }
          }
          return $this->redirect(Yii::$app->request->baseUrl . '/' . md5('parametre_entreprises'));
          break;

          /** Charger le formulaire d'une creation d'un utilisateur **/
        case md5('users_newuser'):
          $privilege_ref = yii::$app->parametreClass->getprivilege_ref($espace_gestion['tl']);
          $services = Yii::$app->parametreClass->getentreprises_services($userPrimaryData['idEntreprise'], $userPrimaryData['iswiz']);
          $entreprises = Yii::$app->parametreClass->getentreprises($userPrimaryData['idEntreprise'], $userPrimaryData['iswiz']);
          $typeUser = Yii::$app->parametreClass->getAllUsertype();
          $this->pg = 'user/newuser.php';
          die('ddd');
          return $this->render($this->pg, ['msg' => $msg, 'typeUser' => $typeUser, 'services' => $services, 'entreprises' => $entreprises, 'privilege_ref'=>$privilege_ref]);
          break;

          /** Charger la liste des Utilisateurs **/
        case md5('users_editionuser'):
          if (isset($request['action_on_this'])) {
            $typeUser = Yii::$app->parametreClass->getAllUsertype();
            $action_on_this = base64_decode($request['action_on_this']);
            $user = Yii::$app->parametreClass->editionuser($action_on_this);
            $this->pg = 'user/editionuser.php';
          } else {
            // Chargeons la liste des entreprise
            $this->pg = 'user/users.php';
          }
          break;
      }
    }
    return $this->render($this->pg, ['msg' => $msg, 'typeUser' => $typeUser, 'services' => $services, 'entreprises' => $entreprises, 'users' => $users, 'iswiz' => $userPrimaryData['iswiz'], 'postInStrg' => $postInStrg, 'user' => $user, 'espace_gestion' => $espace_gestion, 'liste_branche' => $listeBranche, 'liste_utilisateur' => $listeUtilisateur, 'userCount' => $userCount]);
  }

  /** Methode : Parametrage des entreprises **/
  public function actionEntreprises()
  {
    $msg = $entreprises = $postInStrg = $entreprisedata = $naturedata = $naturecampagnes = $entreprise = $users = $iswiz = Null;
    $this->pg = 'monespacegestion/mainview.php';
    $userPrimaryData = Yii::$app->mainCLass->getUserAuthPrimaryInfo();
    $espace_gestion = Yii::$app->parametreClass->charger_espace_gestion($userPrimaryData['idEntreprise']);
    
    // nombre de users
    $userCount = Yii::$app->parametreClass->countUser($userPrimaryData['idEntreprise']);

    // liste des branches
    $listeBranche =  Yii::$app->parametreClass->listeBrache($userPrimaryData['idEntreprise']);

    //liste des utilisateur
    $listeUtilisateur = Yii::$app->parametreClass->listeUtilisateur($userPrimaryData['idEntreprise']);

    //se rassurer que le resultat soit # de false
    if ($espace_gestion == false) {
      return $this->redirect(yii::$app->request->baseUrl . '/' . md5('deconnexion'));
    }

    // analyse action_key
    if (Yii::$app->request->isPost) {
      $request = $_POST;
      /** Analysons la valeur attribue a action_key **/
      switch ($request['action_key']) {
          
          // Telecharger et modifier le logo
        case md5('do_upload'):
          $pin = $espace_gestion['pin']; //Charger le pin de l'entreprise
          //Telecharger le logo s'il existe
          $uploaded_logo = '';
          if (isset($_FILES['logo']['name']) && !empty($_FILES['logo']['name'])) {
            $filesrc = '/' . Yii::$app->params['usermedia'] . '/';
            $uploaded_logo = Yii::$app->fileuploadClass->upload_logo($filesrc, $pin);
          }
          //Appliquer la mise à jour du logo
          $record_saved = Yii::$app->parametreClass->do_upload_logo($uploaded_logo, $espace_gestion['id']);
          //EVENEMENT : TYPE : augmentation de la quantite entrepose en stok //
          $event_msg = Yii::t('app', 'msg_liseajourlogo');
          Yii::$app->mainCLass->creerEvent('020', $event_msg);
          yii::$app->session->setFlash('flashmsg', '<div class="alert alert-success"><strong>Succès !</strong>' . yii::t("app", "operatSucess") . '</div>');
          return $this->redirect(Yii::$app->request->baseUrl . '/' . md5('parametre_entreprises'));
        break;

          // Charger le formulaire de modification/ajout du logo
        case md5('do_logouploader'):
          $this->pg = 'monespacegestion/logouploader.php';
          return $this->render($this->pg);
        break;

          // Update adresse
        case md5('do_update_adress'):
          if (isset($_POST['adresseEntite'])) {
            $stmt = Yii::$app->parametreClass->updateDescription($userPrimaryData['idEntreprise'], $_POST['adresseEntite']);
            
            yii::$app->session->setFlash('flashmsg', '<div class="alert alert-success"><strong>Succès !</strong>' . yii::t("app", "operatSucess") . '</div>');
          }
          return $this->redirect(Yii::$app->request->baseUrl . '/' . md5('parametre_entreprises'));
        break;

          // Update entreprise
        case md5('do_update_entreprise'):
          if (isset($_POST['denomination']) && !empty($_POST['denomination']) && !empty($_POST['activite']) && !empty($_POST['tel']) && !empty($_POST['email'])) {
            $idEntreprise = $userPrimaryData['idEntreprise'];
            $nom = $_POST['denomination'];
            $numComm = $_POST['numCommerce'];
            $activite = $_POST['activite'];
            $tel = $_POST['tel'];
            $email = $_POST['email'];
            $adresse = $_POST['addresse'];
            $objectifVente = $_POST['objectifVente'];
            $objectifClientele = $_POST['objectifClientele'];

            $stmt = Yii::$app->parametreClass->updateEntreprises($idEntreprise, $nom, $numComm, $activite, $tel, $email, $adresse, $objectifVente, $objectifClientele);

            if ($stmt) yii::$app->session->setFlash('flashmsg', '<div class="alert alert-success"><strong>Succès !</strong>' . yii::t("app", "operatSucess") . '</div>');
          } else {
            yii::$app->session->setFlash('flashmsg', '<div class="alert alert-danger"><strong>Erreur !</strong>' . yii::t("app", "champObligatoire") . '</div>');
          }
          return $this->redirect(Yii::$app->request->baseUrl . '/' . md5('parametre_entreprises'));
        break;

          // Update slogan
        case md5('update_slogan'):
          $userPrimaryData = Yii::$app->mainCLass->getUserAuthPrimaryInfo();

          if (isset($_POST['slogan'])) {
            $slogan = $_POST['slogan'];
            $idEntreprise = $userPrimaryData['idEntreprise'];

            $stmt = Yii::$app->parametreClass->updateSlogan($idEntreprise, $slogan);
            yii::$app->session->setFlash('flashmsg', '<div class="alert alert-success"><strong>Succès !</strong>' . yii::t("app", "operatSucess") . '</div>');
          }
          return $this->redirect(Yii::$app->request->baseUrl . '/' . md5('parametre_entreprises'));
        break;

          // New branche form
        case md5('branche_form'):
          $this->pg = 'monespacegestion/newBrancheForm.php';
          return $this->render($this->pg);
        break;

          // New user form
        case md5('new_user_form'):
          $privilege_ref = yii::$app->parametreClass->getprivilege_ref($espace_gestion['tl']);
          $msg = $users = $postInStrg = $typeUser =  $user = $services = Null;
          $userPrimaryData = Yii::$app->mainCLass->getUserAuthPrimaryInfo();
          $services = Yii::$app->parametreClass->getentreprises_services($userPrimaryData['idEntreprise'], $userPrimaryData['iswiz']);
          $entreprises = Yii::$app->parametreClass->getentreprises($userPrimaryData['idEntreprise'], $userPrimaryData['iswiz']);
          $typeUser = Yii::$app->parametreClass->getAllUsertype();
          $this->pg = 'monespacegestion/newUserForm.php';
          //yii::$app->session->setFlash('flashmsg', '<div class="alert alert-success"><strong>Succès !</strong>' . yii::t("app", "operatSucess") . '</div>');
          //return $this->redirect(yii::$app->request->baseUrl . '/' . md5('parametre_entreprises'));
          return $this->render($this->pg, ['msg' => $msg, 'typeUser' => $typeUser, 'services' => $services, 'entreprises' => $entreprises, 'users' => $users, 'privilege_ref'=>$privilege_ref]);
        break;

          // Editer user form
        case md5('users_editionuser'):
          $msg = $user = $postInStrg = $typeUser =  $user = $services = Null;
          if (isset($request['action_on_this'])) {
            $action_on_this = base64_decode($request['action_on_this']);
            
            $privilege_ref = yii::$app->parametreClass->getprivilege_ref($espace_gestion['tl']);
            $user_privilege = yii::$app->parametreClass->getUserPrivilege($action_on_this);
            $user_privilege_array = explode(yii::$app->params['menuSeperator'], $user_privilege);

            $typeUser = Yii::$app->parametreClass->getAllUsertype();
            
            $user = Yii::$app->parametreClass->editionuser($action_on_this);
            $this->pg = 'monespacegestion/editUserForm.php';
            
            return $this->render($this->pg, ['msg' => $msg, 'typeUser' => $typeUser, 'services' => $services, 'entreprises' => $entreprises, 'user' => $user, 'privilege_ref'=>$privilege_ref, 'user_privilege_array'=>$user_privilege_array]);

          } else {
            // Chargeons la liste des entreprise
            $this->pg = 'monespacegestion/mainview.php';
          }
        break;

          // New branche form
        case md5('new_branche'):
          if (isset($_POST['nomBranche']) && !empty($_POST['nomBranche']) && !empty($_POST['telBranche']) && !empty($_POST['emailBranche'])) {

            $idEntreprise = $userPrimaryData['idEntreprise'];
            $nom = $_POST['nomBranche'];
            $desc = $_POST['descriptionBranche'];
            $tel = $_POST['telBranche'];
            $email = $_POST['emailBranche'];
            $adresse = $_POST['adresseBranche'];

            $stmt = Yii::$app->parametreClass->newBranche($idEntreprise, $nom, $desc, $tel, $email, $adresse);
            $event_msg = Yii::t('app', 'msg_liseajourlogo');
            Yii::$app->mainCLass->creerEvent('020', $event_msg);
            yii::$app->session->setFlash('flashmsg', '<div class="alert alert-success"><strong>Succès !</strong>' . yii::t("app", "operatSucess") . '</div>');
          }
          return $this->redirect(Yii::$app->request->baseUrl . '/' . md5('parametre_entreprises'));
        break;

          // Edit Branche
        case md5('editBranche'):
          $userPrimaryData = Yii::$app->mainCLass->getUserAuthPrimaryInfo();
          $action_on_this = base64_decode($_POST['action_on_this']);
          $branche =  Yii::$app->parametreClass->editBrache($userPrimaryData['idEntreprise'], $action_on_this);
          $this->pg = 'monespacegestion/editBrancheForm.php';
          return $this->render($this->pg, ['branche' => $branche]);
        break;

          // Update branche
        case md5('update_branche'):
          if (isset($_POST['nomBranche']) && !empty($_POST['nomBranche']) && !empty($_POST['telBranche']) && !empty($_POST['emailBranche'])) {
            $nom = $_POST['nomBranche'];
            $desc = $_POST['descriptionBranche'];
            $tel = $_POST['telBranche'];
            $email = $_POST['emailBranche'];
            $adresse = $_POST['adresseBranche'];
            $action_on_this = $_POST['action_on_this'];

            $stmt = Yii::$app->parametreClass->updateBranche($nom, $desc, $tel, $email, $adresse, $action_on_this);
            yii::$app->session->setFlash('flashmsg', '<div class="alert alert-success"><strong>Succès !</strong>' . yii::t("app", "operatSucess") . '</div>');
          }
          return $this->redirect(Yii::$app->request->baseUrl . '/' . md5('parametre_entreprises'));
        break;

          // New entreprise forme
        case md5('new_entreprise'):
          $this->pg = 'monespacegestion/newentreprise.php';
          return $this->render($this->pg);
        break;
      }
    }

    return $this->render($this->pg, ['espace_gestion' => $espace_gestion, 'liste_branche' => $listeBranche, 'liste_utilisateur' => $listeUtilisateur, 'userCount' => $userCount]);
  }

  /** Methode : Parametrage des campagnes **/
  public function actionCampagnes()
  {
    $msg = $naturecampagnes = $postInStrg = $naturedata = Null;
    $this->pg = 'campagne/campagnes.php';
    $userPrimaryData = Yii::$app->mainCLass->getUserAuthPrimaryInfo();
    $naturecampagnes = Yii::$app->parametreClass->getNatureCampagne($userPrimaryData['idEntreprise']);
    // analyse action_key
    if (Yii::$app->request->isPost) {
      $request = $_POST;
      /** Analysons la valeur attribue a action_key **/
      switch ($request['action_key']) {
        case md5('campagnes_updatecampagne'): // Update nature campagne data
          if (!empty($request['denomination']) && !empty($request['msg']) && $request['statut'] > 0) {
            if (Yii::$app->session['token2'] != $_POST['token2']) {
              if (isset($request['action_on_this'])) {
                $idrow = base64_decode($request['action_on_this']);
                $updatestmt = Yii::$app->parametreClass->updatecampagne($idrow, $request['denomination'], $request['msg'], $request['statut'], $userPrimaryData['auhId']);
                /** Evitons la duplication du dernier enregistrement **/
                Yii::$app->session->set(Yii::$app->params['tok2'], $_POST[Yii::$app->params['tok2']]);
                if (isset($updatestmt) && $updatestmt == '2692') {
                  // View : liste des natures de campagnes
                  $this->pg = 'campagne/campagnes.php';
                  $naturecampagnes = Yii::$app->parametreClass->getNatureCampagne($userPrimaryData['idEntreprise']);
                  $msg = serialize(['type' => 'alert alert-success', 'strong' => Yii::t('app', 'success'), 'text' => Yii::t('app', 'msg_updatecampagne')]); ##### PREPARATION DU MESSAGE
                }
              }
            }
          } else {
            $this->pg = 'campagne/editioncampagne.php';
            $msg = serialize(['type' => 'alert alert-danger', 'strong' => Yii::t('app', 'erreur'), 'text' => Yii::t('app', 'doneesForcesVides')]);
          }
          break;

        case md5('campagnes_editioncampagne'): // get data une nature distinctive
          if (isset($request['action_on_this'])) {
            $action_on_this = base64_decode($request['action_on_this']);
            $naturedata = Yii::$app->parametreClass->editioncampagne($action_on_this);
            $this->pg = 'campagne/editioncampagne.php';
          } else {
            // View : liste des natures de campagnes
            $this->pg = 'campagne/campagnes.php';
            $naturecampagnes = Yii::$app->parametreClass->getNatureCampagne($userPrimaryData['idEntreprise']);
          }
          break;

        case md5('campagnes_savecampagne'): // enregistrement d'une bew campagne
          if (Yii::$app->session['token2'] != $_POST['token2']) {
            if (!empty($request['denomination']) && !empty($request['msg']) && $request['statut'] > 0) {
              $newCampagneNature = Yii::$app->parametreClass->enrgnewcampagne($request['denomination'], $request['msg'], $request['statut'], $userPrimaryData['auhId'], $userPrimaryData['idEntreprise']);
              #### EVENEMENT : TYPE : augmentation de la quantite entrepose en stok ###
              $event_msg = Yii::t('app', 'msg_creationcampagne');
              Yii::$app->mainCLass->creerEvent('015', $event_msg);
              /** Evitons la duplication du dernier enregistrement **/
              Yii::$app->session->set(Yii::$app->params['tok2'], $_POST[Yii::$app->params['tok2']]);
              $msg = serialize(['type' => 'alert alert-success', 'strong' => Yii::t('app', 'success'), 'text' => Yii::t('app', 'msg_creationcomptUtilisateur')]); ##### PREPARATION DU MESSAGE
              // View : liste des natures de campagnes
              $this->pg = 'campagne/campagnes.php';
              $naturecampagnes = Yii::$app->parametreClass->getNatureCampagne($userPrimaryData['idEntreprise']);
            } else {
              $this->pg = 'campagne/newcampagne.php';
              $msg = serialize(['type' => 'alert alert-danger', 'strong' => Yii::t('app', 'erreur'), 'text' => Yii::t('app', 'doneesForcesVides')]);
            }
          }
          break;

        case md5('campagnes_newcampagne'): // Formulaire novelle nature campagne
          $this->pg = 'campagne/newcampagne.php';
          break;
          //Suppression des Campagnes
        case md5('suppNature'):
          $data = Null;
          $id = base64_decode($request['action_on_this']);
          $stmt = yii::$app->parametreClass->deleteCampagne($id);
          $this->pg = 'campagne/campagnes.php';
          $userPrimaryData = Yii::$app->mainCLass->getUserAuthPrimaryInfo();
          $naturecampagnes = Yii::$app->parametreClass->getNatureCampagne($userPrimaryData['idEntreprise']);
          break;
      }
    }
    return $this->render($this->pg, ['msg' => $msg, 'naturedata' => $naturedata, 'naturecampagnes' => $naturecampagnes]);
  }

  //Methode : motif enregistrement client
  public function actionMotifsenrgclient()
  {
    $msg = $motifs = Null;
    $this->pg = 'motifenrgclient/motifs.php';
    $userPrimaryData = Yii::$app->mainCLass->getUserAuthPrimaryInfo();

    //Ajax request // enrg motif enrg client
    if (Yii::$app->request->isGet && isset($_GET['action_key']) && $_GET['action_key'] == md5('enrgmotifenrgclient') && !empty($_GET['libelle'])) {
      $motif = Yii::$app->parametreClass->enrgmotifenrgclient($_GET['libelle'], $userPrimaryData['auhId'], $userPrimaryData['idEntreprise']);
      return $motif;
    }

    if (Yii::$app->request->isPost) {
      $request = $_POST;
      $userPrimaryData = Yii::$app->mainCLass->getUserAuthPrimaryInfo();
      //Analyse de l'action_key
      switch ($request['action_key']) {
        case md5('parametre_updatemotif'):
          $action_on_this = base64_decode($request['action_on_this']);
          $libelle = $request['libelleeditedmotif'];
          $statut = $request['statutmotif'];
          $idactor = $userPrimaryData['auhId'];
          $updateStmt = Yii::$app->parametreClass->updateMotifenrgClient($action_on_this, $statut, $libelle, $idactor);
          break;
      }
    }

    $motifs = Yii::$app->parametreClass->clientEnrgMotifs($userPrimaryData['idEntreprise']);
    return $this->render($this->pg, ['msg' => $msg, 'motifs' => $motifs]);
  }

  /**  action nouveau entrepot **/
  public function actionEntite()
  {
    $request = $msg = $entreprises = $listentite = Null;
    $this->pg = '/parametre/nentite/filtre.php';
    /* Recuperrons les infos basics de l'utilisateur connected */
    $userPrimaryData = Yii::$app->mainCLass->getUserAuthPrimaryInfo();
    $entreprises = Yii::$app->parametreClass->getentreprises($userPrimaryData['idEntreprise'], $userPrimaryData['iswiz']);
    //analyse de l'imput action_key
    if (Yii::$app->request->isPost) {
      if (Yii::$app->session['token2'] != $_POST['token2']) {
        $request = $_POST;
        /** Analysons la valeur attribue a action_key **/
        switch ($request['action_key']) {
          case md5('new_entite'):
            $this->pg = 'nentite/nEntite.php';
            break;

          case md5('save_entite'):
            # GET USER AUTH PRIMARY INFOS
            $UserAuthPrimaryInfo = Yii::$app->mainCLass->getUserAuthPrimaryInfo();
            $connect = \Yii::$app->db;
            $stmt = $connect->createCommand('INSERT INTO slim_entite(nom,description,Tel,email,addresse,createdOn,idEntreprise) VALUES(:nom,:des,:tel,:email,:adre,:crea,:ent)')
              ->bindValues([
                ':nom' => $request['nom'],
                ':des' => $request['description'],
                ':tel' => $request['tel'],
                ':email' => $request['email'],
                ':adre' => $request['adresse'],
                ':crea' => $request['dte'],
                ':ent' => $request['entreprise']
              ])
              ->execute();
            $this->pg = '/parametre/nentite/nEntite.php';
            break;

          case md5('lEntite'):
            $this->pg = '/parametre/nentite/lEntite.php';
            break;

          case md5('liste_entite'):
            $listentite = Yii::$app->parametreClass->getentreprises_services($userPrimaryData['idEntreprise'], $userPrimaryData['iswiz']);
            break;
        }
      }
    }
    return $this->render($this->pg, ['msg' => $msg, 'entreprises' => $entreprises, 'listentite' => $listentite]);
  }

  public function actionNewentreprise()
  {
    $msg = $entreprises = $postInStrg = $entreprisedata = $naturedata = $naturecampagnes = $entreprise = $users = $iswiz = Null;
    $this->pg = 'monespacegestion/newentreprise.php';
    $userPrimaryData = Yii::$app->mainCLass->getUserAuthPrimaryInfo();
    $services = Yii::$app->parametreClass->getentreprises_services($userPrimaryData['idEntreprise'], $userPrimaryData['iswiz']);
    $entreprises = Yii::$app->parametreClass->getentreprises($userPrimaryData['idEntreprise'], $userPrimaryData['iswiz']);
    $typeUser = Yii::$app->parametreClass->getAllUsertype();
    // analyse action_key
    if (Yii::$app->request->isPost) {
      $request = $_POST;
      /** Analysons la valeur attribue a action_key **/
      switch ($request['action_key']) {

        case md5('newentreprise_saveentreprise'):

          if (Yii::$app->session['token2'] != $_POST['token2']) {
            if (!empty($request['denomination']) && !empty($request['activite']) && !empty($request['forfait']) && !empty($request['dtedebutforfait']) && !empty($request['tel']) && !empty($request['email']) && !empty($request['adresse']) && !empty($request['pnom']) && !empty($request['nom']) && !empty($request['tel_owner']) && !empty($request['nomuser']) && !empty($request['motpass'])) {

              $telValidation = true;
              $nom = $request['denomination'];
              $activite = $request['activite'];
              $typelicence = $request['forfait'];
              $tel = $request['tel'];
              $email = $request['email'];
              $adresse = $request['adresse'];
              $owner_created = '';
              $usrnameDuplication = Yii::$app->parametreClass->preventDuplication($request['nomuser']);

              if ($usrnameDuplication !== '2692') {
                $this->pg = 'monespacegestion/newentreprise.php';
                yii::$app->session->setFlash('flashmsg', '<div class="alert alert-danger"><strong>Erreur !</strong>' . yii::t("app", "usrduplication") . '</div>');
                return $this->render($this->pg);
              }

              //Validate the phone number
              $telValidation = yii::$app->parametreClass->validate_number($tel);
              if ($telValidation == false) {
                $this->pg = 'monespacegestion/newentreprise.php';
                yii::$app->session->setFlash('flashmsg', '<div class="alert alert-danger"><strong>Erreur !</strong>' . yii::t("app", "invalidetelephoneformat") . '</div>');
                return $this->render($this->pg, ['iswiz' => $userPrimaryData['iswiz'], 'request' => $request]);
              }

              //Validation email
              if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->pg = 'monespacegestion/newentreprise.php';
                yii::$app->session->setFlash('flashmsg', '<div class="alert alert-danger"><strong>Erreur !</strong>' . yii::t("app", "invalideemailformat") . '</div>');
                return $this->render($this->pg, ['iswiz' => $userPrimaryData['iswiz'], 'request' => $request]);
              }

              //Eviter le duplicat d'espace de gestion
              if (yii::$app->parametreClass->valider_entreprise_duplicat($nom, $tel, $country_code = '') == false) {
                $this->pg = 'monespacegestion/newentreprise.php';
                yii::$app->session->setFlash('flashmsg', '<div class="alert alert-danger"><strong>Erreur !</strong>' . yii::t("app", "espace_gestion_existant") . '</div>');
                return $this->render($this->pg, ['iswiz' => $userPrimaryData['iswiz'], 'request' => $request]);
              }

              // prevenir duplication du nom d'utilisateur
              $usrnameDuplication = Yii::$app->parametreClass->preventDuplication($request['nomuser']);
              if ($usrnameDuplication !== '2692') {
                $typeUser = Yii::$app->parametreClass->getAllUsertype();
                $this->pg = 'monespacegestion/newentreprise.php';
                yii::$app->session->setFlash('flashmsg', '<div class="alert alert-danger"><strong>Erreur !</strong>' . yii::t("app", "espace_gestion_existant") . '</div>');
                return $this->render($this->pg);
              }

              //generer le pin de l'espace de gestion
              $pin = date('d').rand(100, 999) . uniqid(microtime());

              //Telecharger le logo s'il existe
              $uploaded_logo = '';
              if (isset($_FILES['logo']['name']) && !empty($_FILES['logo']['name'])) {
                $filesrc = '/' . Yii::$app->params['usermedia'] . '/';
                $uploaded_logo = Yii::$app->fileuploadClass->upload_logo($filesrc, $pin);
              }

              /** Procéder à la création de l'espace de gestion **/
              $record_saved = Yii::$app->parametreClass->enrg_espace_gestion($request, $uploaded_logo, $pin, $userPrimaryData['auhId']);
              //Preparer les variables qui doivent intervenir
              $entreprise_id = '';
              $entite_id = '';
              $entreprise_entite_id = yii::$app->parametreClass->getEntrepriseEntiteId($pin);
              if ($entreprise_entite_id) {
                $entreprise_id = $entreprise_entite_id['entreprise_id'];
                $entite_id = $entreprise_entite_id['entite_id'];
              }
              $menu = Yii::$app->params['starterMenu'];
              $username = $request['nomuser'];
              $pswrd = md5($request['motpass']);
              $typeusr = 9;
              $nom = $request['nom'];
              $pnom = $request['pnom'];
              $tel = $request['country_tel_code'] . $request['tel_owner'];
              $entiteconnected = $userPrimaryData['idEntite'];
              yii::$app->parametreClass->addUserAccount($menu, $entreprise_id, $entite_id, $username, $pswrd, $typeusr, $entiteconnected, $nom, $pnom, $tel);

              //EVENEMENT : TYPE : augmentation de la quantite entrepose en stok //
              $event_msg = Yii::t('app', 'msg_creationentreprise');
              Yii::$app->mainCLass->creerEvent('015', $event_msg);
              /** Evitons la duplication du dernier enregistrement **/
              Yii::$app->session->set(Yii::$app->params['tok2'], $_POST[Yii::$app->params['tok2']]);

              // Chargeons la liste des entreprise
              $this->pg = 'monespacegestion/mainview.php';
              $entreprises = Yii::$app->parametreClass->getentreprises($userPrimaryData['idEntreprise'], $userPrimaryData['iswiz']);
              yii::$app->session->setFlash('flashmsg', '<div class="alert alert-success"><strong>Erreur ! </strong>' . yii::t("app", "msg_creationentreprise") . '</div>');

              //Rediriger a la page de la liste des entreprises
              return $this->redirect(Yii::$app->request->baseUrl . '/' . md5('parametre_entreprises'));
            } else {
              $this->pg = 'monespacegestion/newentreprise.php';
              yii::$app->session->setFlash('flashmsg', '<div class="alert alert-danger"><strong>Erreur : </strong>' . yii::t("app", "doneesForcesVides") . '</div>');
              return $this->render($this->pg, ['iswiz' => $userPrimaryData['iswiz']]);
            }
          }
          break;
      }
    }
    return $this->render($this->pg, ['iswiz' => $userPrimaryData['iswiz'], 'naturedata' => $naturedata, 'naturecampagnes' => $naturecampagnes, 'entreprises' => $entreprises, 'entreprise' => $entreprise, 'iswiz' => $userPrimaryData['iswiz']]);
  }

  public function actionListentreprise()
  {
    $this->pg = 'monespacegestion/listentreprise.php';
    $listentreprise = Yii::$app->parametreClass->listentreprise();
    return $this->render($this->pg, ['listentreprise' => $listentreprise]);
  }

  public function actionBanque()
  {
    $msg = $banques = Null;
    $this->pg = 'banque/banques.php';
    $userPrimaryData = Yii::$app->mainCLass->getUserAuthPrimaryInfo();

    //Ajax request // enrg motif enrg client
    if (Yii::$app->request->isGet && isset($_GET['action_key']) && $_GET['action_key'] == md5('enrg_banque') && !empty($_GET['numero'])) {
      $banque = Yii::$app->parametreClass->enregbanque($_GET['numero'], $_GET['adresse'], $_GET['libelle'], $userPrimaryData['auhId'], $userPrimaryData['idEntreprise']);
      return $banque;
    }

    if (Yii::$app->request->isPost) {
      $request = $_POST;
      $userPrimaryData = Yii::$app->mainCLass->getUserAuthPrimaryInfo();
      //Analyse de l'action_key
      switch ($request['action_key']) {
        case md5('parametre_update_bank'):

          $action_on_this = base64_decode($request['action_on_this']);
          $numero_compte = $request['editenumero'];
          $banque = $request['editelibelle'];
          $adresse = $request['editeadresse'];
          $idactor = $userPrimaryData['auhId'];

          $updateStmt = Yii::$app->parametreClass->updateEntepriseBank($action_on_this, $banque, $numero_compte, $adresse, $idactor);
          break;
      }
    }

    $banques = Yii::$app->parametreClass->listeBanque($userPrimaryData['idEntreprise']);
    return $this->render($this->pg, ['msg' => $msg, 'banques' => $banques]);
  }
}
