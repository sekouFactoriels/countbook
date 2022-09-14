<?php
    namespace app\components;
    use Yii;
    use yii\base\component;
    use yii\web\Controller;
    use yii\base\InvalidConfigException;

    Class mainCLass extends Component {
      public $connect = Null;

      Public function __construct(){
        $this->connect = \Yii::$app->db;
      }

      /** Methode : do agreement **/
      public function do_agreement($id_entreprise='', $stat_act_req='', $act_req='')
      {
        $do_agreement = $this->connect->createCommand('UPDATE slim_entreprise set stat_act_req=:stat_act_req and act_req=:act_req where id=:id')
        ->bindValues([':act_req'=>$act_req, ':stat_act_req'=>$stat_act_req, ':id'=>$id_entreprise])
        ->execute();
        if($do_agreement) return true;
        return;
      }

      /** Methode : Renvoyer le contrat de licence en cours **/
      public function load_agreement()
      {
        $agreement = $this->connect->createCommand('select * from slim_agreement where statut=:statut order by maj_le asc')
        ->bindValue(':statut', 1)
        ->queryOne();
        return $agreement;
      }

      /** Methode : Traiter une requete **/
      public function traiter_rappel($rappel_id='')
      {
        $rslt = $this->connect->createCommand('update slim_rappel set statut=:statut where id=:id')
        ->bindValues([':id'=> $rappel_id, ':statut'=>2])
        ->execute();
        if($rslt) return $rslt;
        return;
      }


      /** Methode : CHarger la liste des banques d'une entreprise **/
      public function charger_banques_entreprise($idEntreprise)
      {
        $rslt = $this->connect->createCommand('select * from slim_entreprise_bank where idEntreprise=:idEntreprise and statut=1')
        ->bindValue(':idEntreprise',$idEntreprise)
        ->queryAll();
        if($rslt) return $rslt;
        return;
      }

      /**********
      # FUNCTION : OPTION DU NIS : MODIFICATION DE L'ALGORITHME DE MAURICE KRAITCHIK (18882-1957)
      **********/
      public function get_orderer_number($indice='')
      {
        $last_rec = $prefixe = $newCode = $genCode = Null;
        switch($indice)
        {
          case strtolower('facture_fournisseur'):
            $prefixe = 'FF';
            $last_rec = $this->connect->createCommand('select bill_number from slim_bill where bill_number LIKE "FF%" order by bill_number desc')
            ->queryOne();
          break;

          case strtolower('facture_client'):
            $prefixe = 'FC';
             $last_rec = $this->connect->createCommand('select bill_number from slim_bill  where bill_number LIKE "FC%" order by bill_number desc')
            ->queryOne();

          break;

          case strtolower('devis_client'):
            $prefixe = 'DC';
             $last_rec = $this->connect->createCommand('select bill_number from slim_bill_devis  where bill_number LIKE "DC%" order by bill_number desc')
            ->queryOne();

          break;
        }

       

        if(isset($last_rec['bill_number']) && sizeof($last_rec['bill_number'])>0)
        {
          $last_rec = $last_rec['bill_number'];
        }

        if(isset($last_rec) && ($last_rec != Null))
        {
          $lastCode = substr($last_rec,2,7);
          $newCode = $lastCode + 1;
          if(strlen($newCode)==1){
            $genCode= $prefixe."00000".$newCode;
            }elseif (strlen($newCode)==2){
               $genCode= $prefixe."0000".$newCode;
            }elseif (strlen($newCode)==3){
                $genCode= $prefixe."000".$newCode;
            }elseif (strlen($newCode)==4){
                $genCode= $prefixe."00".$newCode;
            }elseif (strlen($newCode)==5){
                $genCode=$prefixe."0".$newCode;
            }else{
                $genCode= $prefixe.$newCode;
            }
        }else{
          $genCode = $prefixe.'000001';
        }
        return $genCode;
      } 

      #**********************************
      # FUNCTION : DATE DE FIN DE LICENCE
      #**********************************
      
       public static function create_pass($usr_name, $usr_password){
        if(isset($usr_password) && !empty($usr_password) && isset($usr_name) && !empty($usr_name)){
          $strg = $usr_name.Yii::$app->params['key_connector'].$usr_password;
          return $usr_password = Yii::$app->cryptoClass->create_password($strg);
        }

        return false;
      }


      /** Methode : CHarger les regions en fonction du pays **/
      public function get_regions($pays='')
      {
        switch ($pays) {
          case '*':
            $rslt = $this->connect->createCommand('select * from ste_sys_regions')
            ->queryAll();
          break;
          
          default:
            $rslt = $this->connect->createCommand('select * from ste_sys_regions where pays=:pays')
            ->bindValue(':pays', $pays)
            ->queryAll();
          break;
        }
        
        return $rslt;
      }


      /** Methode : Charger les prefectures en fonction de la region selectionné **/
      public function load_prefectures($region='')
      {
        switch ($region) {
          case '*':
            $rslt = $this->connect->createCommand('select * from ste_sys_prefecture')
            ->queryAll();
          break;

          default:
            $rslt = $this->connect->createCommand('select * from ste_sys_prefecture where region=:region')
            ->bindValue(':region', $region)
            ->queryAll();
          break;
        }
        return $rslt;
      }


      /** Methode : Get all pays data **/
      public function getpays(){
        $rslt =Null;
        $rslt = $this->connect->createCommand('SELECT * FROM ste_sys_pays order by nom asc')
        ->queryAll();
        return $rslt;
      }

      /** Methode analyse si un compte est toujours actif **/
      public function checkifaccountactive(){
         $userSessionDtls = unserialize(Yii::$app->session[Yii::$app->params['userSessionDtls']]);
         $idUniqUser = $userSessionDtls['idUniqUser'];
      }

      /** Methode qui renvois les points objectifs d'une entreprise **/
      public function gettargetpoints($idEntreprise){
         $targetspoint = Null;
         if(isset($idEntreprise)){
            $stmt = $this->connect->createCommand('SELECT objectifVente, objectifClientele FROM slim_entreprise where id=:idEntreprise')
            ->bindValue(':idEntreprise',$idEntreprise)
            ->queryOne();
            if(sizeof($stmt) > 0){
               $targetspoint = $stmt;
            }
         }
         return $targetspoint;
      }
      

      /** Methode : validate & update user passeword **/
      public function valideupdateuserpassword($postserialise){
        $rslt = Null;
        if(isset($postserialise)){
          $request = unserialize($postserialise);
          switch ($request['action_key']){
            // Update mot de passe de l'utilisateur connected
            case md5('updatepswrd'):
            //Check if password1 & 2 are same
            if($request['motpass1'] !== $request['motpass2']){
              $rslt = '1212';
            }
            break;
          }
        }
        return $rslt;
      }

      /** Methode : Check if user must change his passsword **/
      public function usermustechangepswrd($usr){
        $mustchange = Null;
        if(isset($usr)){
          $stmt = $this->connect->createCommand('SELECT mustchangepswrd FROM slim_userauth WHERE userName=:userName')
          ->bindValue(':userName', $usr)
          ->queryOne();
          if(isset($stmt)){
            $mustchange = $stmt['mustchangepswrd'];
          }
        }
        return $mustchange;
      }

      /** Methode qui renvois l'id d'un utlisateur base : username **/
      Public function getUserAuthId($username){
        $id = Null;
        if(isset($username)){
          $stmt = $this->connect->createCommand('SELECT id FROM slim_userauth WHERE  userName=:userName')
          ->bindValue(':userName', $username)
          ->queryOne();
          if(sizeof($stmt) > 0){
            $id = $stmt['id'];
          }
        }
        return $id;
      }
      /***************************************************************/
      //  Recuperrer le nom de l'utilisateur en fonction de son id   //
      /***************************************************************/
      public function getUserFullnameBaseId($userId){
        $fullname = Null;
        if(isset($userId)){
          $stmt = $this->connect->createCommand('SELECT * FROM slim_utulisateursysteme WHERE idUserAuth = :idUserAuth')
                                ->bindValue(':idUserAuth', $userId)
                                ->queryOne();
          if(is_array($stmt)){
            $fullname = $stmt['prenom'].'&nbsp;'.$stmt['nom'];
          }
        }
        return $fullname;
      }
      /****************************************/
      //  Recuperrer le nom de l'entreprise   //
      /****************************************/
      Public function getEntrepriseData($idEntreprise){
        $entrepriseData = Null;
        if(isset($idEntreprise)){
          $stmt = $this->connect->createCommand('SELECT * FROM slim_entreprise WHERE slim_entreprise.id = :id')
                                ->bindValue(':id', $idEntreprise)
                                ->queryOne();
          if(is_array($stmt)){
            $entrepriseData = $stmt;
          }
        }
        return $entrepriseData;
      }

      public function getuser_username($id){
        $username = Null;
        if(isset($id)){
          $stmt = $this->connect->createCommand('SELECT userName FROM slim_userauth WHERE id=:id')
          ->bindValue(':id',$id)
          ->queryOne();
          if(sizeof($stmt)>0){
            $username = $stmt['userName'];
          }
        }
        return $username;
      }


      #*******************************************
      # GET USER AUTHENTIFIED PRIMARY INFORMATIONS
      #*******************************************
      public function getUserAuthPrimaryInfo(){
        $userData = Null;
        $userSessionDtls = Yii::$app->session[Yii::$app->params['userSessionDtls']];
        $userAuthDtlsArray = unserialize($userSessionDtls);
        if($userAuthDtlsArray){

          # GET THE ENTREPRISE OF USER AUTH
          $user_id = (isset($userAuthDtlsArray['idUniqUser'])) ? $userAuthDtlsArray['idUniqUser'] : 0;
          $entrepriseDtls = mainCLass::getEntrepriseOfUser($user_id);
          $entrepriseData = mainCLass::getEntrepriseData($entrepriseDtls['idEntreprise']);
          $userData = ['typeUser'=>$entrepriseDtls['typeUser'],
                        'auhId'=>$userAuthDtlsArray['idUniqUser'],
                        'idEntreprise'=>$entrepriseDtls['idEntreprise'],
                        'idEntite'=>$entrepriseDtls['idEntite'],
                        'currency'=>$entrepriseData['symbolemonaie'],
                        'iswiz'=>$entrepriseDtls['iswiz'],
                        'objectifVente'=>$entrepriseData['objectifVente'],
                        'objectifClientele'=>$entrepriseData['objectifClientele']
                      ];
          return $userData;
        }else{
          Yii::$app->layout = 'login_layout';
          return;
        }
      }

      /*******************************************************/
      /*/       DEBUT FUNCTION BASIQUES A ADAPTER           /*/
      /*******************************************************/

      #*******************************************
      # RENVOIS ID DE LENTREPRISE DE L'UTILISATEUR
      #*******************************************
      public function getEntrepriseOfUser($userAuthId){
        $entrepriseId = $entrepriseDtls = Null;
        if(!empty($userAuthId)){
          $connect = \Yii::$app->db;
          $rslt = $connect->createCommand('SELECT entrepriseId, listEntite, typeUser, iswiz FROM slim_userauth WHERE id=:id')
                          ->bindValue(':id',$userAuthId)
                          ->queryOne();
          if(isset($rslt)){
            $idEntite = $rslt['listEntite'];
            $entrepriseId = $rslt['entrepriseId'];
            $typeUser = $rslt['typeUser'];
            $iswiz = $rslt['iswiz'];
            $entrepriseDtls = ['idEntreprise'=>$entrepriseId, 'idEntite'=>$idEntite, 'typeUser'=>$typeUser, 'iswiz'=>$iswiz];
          }
        }
        return $entrepriseDtls;
      }

      #**********************************
    	# RENVOIS ID DU TYPE D'UTILISATEUR
    	#**********************************
      Public function getTypeIdUniqUser($idUniqUser){
        $connect = \Yii::$app->db;
        if(!empty($idUniqUser)){
          $stmt = $connect
                  ->createCommand('SELECT typeUser FROM slim_userauth
                                   WHERE id=:idUniqUser AND statCmpt="1"')
                  ->bindValue(':idUniqUser',$idUniqUser);
          $rslt = $stmt->queryOne();
          if(is_array($rslt)){
            return $rslt['typeUser'];
          }else return false;
        }else return false;
      }

      public function listEntiteId($idUniqUser){
        $listEntite = Null;
        if(!empty($idUniqUser)){
          $connect = \Yii::$app->db;
          $rslt = $connect->createCommand('SELECT listEntite FROM slim_userauth WHERE id=:id')
                          ->bindValue(':id',$idUniqUser)
                          ->queryOne();
          if(sizeof($rslt) > 0){
            $listEntite = $rslt['listEntite'];
          }
        }
        return $listEntite;
      }

      #***************************************************
    	# RENVOIS TRUE SI TOKEN DU USER AUTHETIFIE EST VALID
    	#***************************************************
      Public function validiteToken($token){
        if(!empty($token)){
          $userSessionDtls = Yii::$app->session[Yii::$app->params['userSessionDtls']];
          $userAuthDtlsArray = unserialize($userSessionDtls);
          $userAuthToken = base64_encode(md5($userAuthDtlsArray['userName']));
          if($token == $userAuthToken){
            return true;
          }else return false;
        }
      }

      #************************************
    	# RENVOIS LES DETAILS SUR UN SERVEUR
    	#************************************
      public function renvoisDtlsServeur(){
        $server = $_SERVER;
        $str_array = array('PATH','PATHEXT','SystemRoot','SERVER_ADMIN','DOCUMENT_ROOT','SERVER_SOFTWARE','SERVER_SIGNATURE','CONTEXT_DOCUMENT_ROOT','COMSPEC','WINDIR','SCRIPT_FILENAME');
        foreach ($str_array as $key) {
          unset($server[$key]);
        }
        $server = serialize($server);
        return $server;
      }

      #************************************
    	# ENREGISTRER L'EVENEMENT DANS LA DB
    	#************************************
    	public function creerEvent($eventCode, $eventDesc, $idEntite=Null){
    		$server = $_SERVER;
        $str_array = array('PATH','PATHEXT','SystemRoot','SERVER_ADMIN','DOCUMENT_ROOT','SERVER_SOFTWARE','SERVER_SIGNATURE','CONTEXT_DOCUMENT_ROOT','COMSPEC','WINDIR','SCRIPT_FILENAME');
        foreach ($str_array as $key) {
          unset($server[$key]);
        }
    		$server = serialize($server);
        $userSessionDtls = Yii::$app->session[Yii::$app->params['userSessionDtls']];
        $userAuthDtlsArray = unserialize($userSessionDtls);
        #RECUPERONS LES INFOS PRIMAIRE D'UN UTILISATEUR
        $userPrimaryData = Yii::$app->mainCLass->getUserAuthPrimaryInfo();
        $typeUser = $userPrimaryData['typeUser'];
        $idUserAuth = $userPrimaryData['auhId'];
        $idEntite = $userPrimaryData['idEntite'];
        $idEntreprise = $userPrimaryData['idEntreprise'];
        # INSERT CES VALEUR DANS LA DB : ste_eventdetails
        $connect = \Yii::$app->db;
        #$corpMedicalHosto = (!empty(Yii::$app->session[Yii::$app->params['medHost']])) ? Yii::$app->session[Yii::$app->params['medHost']] : Null;
        $rslt = $connect
                ->createCommand()->insert('slim_eventdetails', ['idUserAuthType'=>$typeUser,
                                                            'idUserAuth'=>$idUserAuth,
                                                            'eventTypeCode'=>$eventCode,
                                                            'eventDesc'=>$eventDesc,
                                                            'eventDte'=>date('Y-m-d h:i:s'),
                                                            'serverDtls'=>$server,
                                                            'idEntite'=>$idEntite,
                                                            'idEntreprise'=>$idEntreprise
                                                            ])
                                ->execute();
        return true;

    	}

      #***********************
      # RENVOIS TOUS LES PAYS
      #***********************
      public function renvTtPays(){
          $connect = \Yii::$app->db;
          $stmt = $connect
                  ->createCommand('SELECT id, nom, monaie, telCode FROM syspays');
          $rslt = $stmt->queryAll();
          if(is_array($rslt) && count($rslt)){
            return $rslt;
          }
      }

      /** Renvois le nom de l'evenement **/
      public function geteventtypename($id){
        $nom = Null;
        if(isset($id)){
          $stmt = $this->connect->createCommand('SELECT titre FROM slim_eventtype WHERE code=:code')
          ->bindValue(':code',$id)
          ->queryOne();
          if(sizeof($stmt) > 0){
            $nom = $stmt['titre'];
          }
        }
        return $nom;
      }

      #************************************
      # RENVOIS TOUS LES TYPES D'evenements

      #************************************
      public function renvEventType(){
         $allEventType = null;
          $connect = \Yii::$app->db;
          $stmt = $connect
                  ->createCommand('SELECT * FROM slim_eventtype');
          $rslt = $stmt->queryAll();
          if(is_array($rslt) && count($rslt)){
            $allEventType= $rslt;
          }
          return $allEventType;
      }

      #**********************************************#
      # fonction qui renvoie les infos d'utulisateur #
      #********** -- aliou/13/07/2018 --*************#
      public function renvUserType(){
        $userType = null;
        $connect = \Yii::$app->db;
        $stmt = $connect
                 ->createCommand('SELECT * FROM slim_usertype');
        $rslt = $stmt->queryAll();
        if(is_array($rslt) && count($rslt)){
            $userType=$rslt;
        }
        return $userType;
      }
      #**********************************************#
      # fonction qui renvoie la liste des depenses #
      #********** -- aliou/13/07/2018 --*************#
      public function renvCharge($idEntreprise){
        $depens = null;
        $stmt = $this->connect->createCommand('SELECT * FROM slim_charges_motif where idEntreprise=:idEntreprise')
                        ->bindValue(':idEntreprise',$idEntreprise)
                        ->queryAll();
                        if(is_array($stmt) && sizeof($stmt) > 0){
                        $depens = $stmt;
                        }
                        return $depens;
      }

      #************************************************************************************
      # RENVOIS INFOS UTILISATEUR EN FONCTION : TYPE UTILISATEUR, ID UNIQUE D L'UTILISATEUR   -- aliou/13/07/2018 --
      #************************************************************************************
      Public function usersAuthInfos($typeUser, $userAuthId){
         $typeUser = (isset($typeUser)) ? $typeUser  : Null;
         $userAuthId = (isset($userAuthId)) ? $userAuthId : Null;
         $rslt = (isset($rslt)) ? $rslt : Null;
         $nomTle = $nomColum = Null;

           switch ($typeUser) {
             case 1: # Cas des boss
               $nomTle = 'utulisateursysteme';
               $nomColum = 'idUserAuth';
             break;

             default:
               return false;
             break;
           }
           # REQUETTE INFOS DES UTILISATEURS
           $connect = \Yii::$app->db;

           $stmt = $connect
                   ->createCommand('SELECT * FROM slim_'.$nomTle.' WHERE '.$nomColum.' =:userAuthId')
                   ->bindValue(':userAuthId',$userAuthId);
           $rslt = $stmt->queryOne();
           if(is_array($rslt) && count($rslt)){
             return $rslt;
           }
           return false;
         }
      # faire cette function : renvois_nivo_acc
    }
