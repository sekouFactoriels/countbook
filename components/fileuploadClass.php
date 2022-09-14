<?php
    namespace app\components;
    use Yii;
    use yii\base\component;
    use yii\web\Controller;
    use yii\base\InvalidConfigException;

    Class fileuploadClass extends Component{

      public $connect = Null;

      Public function __construct(){
        $this->connect = \Yii::$app->db;
      }

      /** Methode : telecharger le logo et retourner le nom unique de l'image **/
      public function upload_logo($link_to_upload, $rand_numb)
      {
        $file = $_FILES['logo'];
        if(isset($file) && sizeof($file)>0)
        {
          $file_name = $file['name'];
          $file_size = $file['size'];
          $file_tmp = $file['tmp_name'];
          $file_type= $file['type'];
          $extCounter = explode('.', $file_name);
          $file_ext=end($extCounter);

          $file_uni_name = $rand_numb.$extCounter[0].'.'.$file_ext;
          $expensions= array("jpeg","jpg","png");

          if(in_array($file_ext,$expensions) === false){
            $rslt = 'error';
          }
          if($file_size > Yii::$app->params['maxFileSize']){
            $rslt = 'error';
          }

          if(empty($errors)==true)
          {
            $targetfolder = \Yii::getAlias(Yii::$app->basePath.$link_to_upload);      
            if(move_uploaded_file($file_tmp, $targetfolder.$file_uni_name))
            {
              return $file_uni_name;
            }
          }
          return;
        }
      }

        /** Methode : telecharger l image de l artice **/
        public function upload_image($link_to_upload, $rand_numb)
        {
          $file = $_FILES['logo'];
          if(isset($file) && sizeof($file)>0)
          {
            $file_name = $file['name'];
            $file_size = $file['size'];
            $file_tmp = $file['tmp_name'];
            $file_type= $file['type'];
            $extCounter = explode('.', $file_name);
            $file_ext=end($extCounter);
  
            $file_uni_name = $rand_numb.$extCounter[0].'.'.$file_ext;
            $expensions= array("jpeg","jpg","png");
  
            if(in_array($file_ext,$expensions) === false){
              $rslt = 'error';
            }
            if($file_size > Yii::$app->params['maxFileSize']){
              $rslt = 'error';
            }
  
            if(empty($errors)==true)
            {
              $targetfolder = \Yii::getAlias(Yii::$app->basePath.$link_to_upload);      
              if(move_uploaded_file($file_tmp, $targetfolder.$file_uni_name))
              {
                return $file_uni_name;
              }
            }
            return;
          }
        }

      /** Methode : Upload logo de l'entreprise **/
      public function upload_entreprise_logo($link_to_upload, $serialisedfiles, $serializedPosts, $id_entreprise, $table_suffixe=Null)
      {
        $rslt = $fileuploadeduniname = Null;
        if(isset($serialisedfiles))
        {
          $posts = unserialize($serializedPosts);
          $files = unserialize($serialisedfiles);
          // Upload and saving process
          foreach ($files as $value) {
            if(sizeof($value['tmp_name']) > 0){
              for ($i=0; $i <= sizeof($value['tmp_name']); $i++) {
                if(isset($value['name'][$i])){
                  $rand_numb = md5(uniqid(microtime()));
                  $file_name = $value['name'][$i];
                  $file_size =$value['size'][$i];
                  $file_tmp =$value['tmp_name'][$i];
                  $file_type=$value['type'][$i];
                  $extCounter = explode('.', $file_name);
                  $file_ext=end($extCounter);

                  $file_uni_name = $extCounter[0].$rand_numb.'.'.$file_ext;
                  $expensions= array("jpeg","jpg","png");
                  $fileuploadeduniname[$i] = [$file_uni_name];
                }
                if(in_array($file_ext,$expensions) === false){
                  $rslt = '11';
                }
                if($file_size > Yii::$app->params['maxFileSize']){
                 $rslt = '22';
                }

                if(empty($errors)==true){
                  $targetfolder = \Yii::getAlias(Yii::$app->basePath.$link_to_upload);
                  
                  if(move_uploaded_file($file_tmp, $targetfolder.$file_uni_name)){
                    //Renvois l'id uniq de l'utilisateur connected
                    $userPrimaryData = Yii::$app->mainCLass->getUserAuthPrimaryInfo();
                    $typeUser = $userPrimaryData['typeUser'];
                    $user_id = $userPrimaryData['auhId'];
                    $idEntreprise = $userPrimaryData['idEntreprise'];

                    switch ($_POST['action_key']) 
                    {
                      case md5('entreprises_updateentreprise'):
                        $rslt = fileuploadClass::save_entreprise_logo($table_suffixe, $posts['fileToUploadName'][$i], $file_name, $file_uni_name, $file_ext, $file_size, $idEntreprise, $user_id);
                      break;
                    }
                    
                    if($rslt != '2604'){
                      return '9966';
                    }
                  }
                }else{
                  return '9966'; // On retourne une valeur pour stopper le processus
                }
              }
            }
          }
        }
        return $fileuploadeduniname;
      }

      
      /** Function : Enregistrer les infos d'un ticket sante **/
      public function saveUploadedticketsanteFile($tablsuffixe, $file_name, $file_uni_name, $file_ext, $file_size, $user, $ticketsante)
      {
        $rslt = Null;
        if(isset($tablsuffixe)){
          $stmt = $this->connect->createCommand('INSERT INTO ste_'.$tablsuffixe.' (file_name, file_uni_name, file_ext, file_size, code_ticketsante, id_actor)
          VALUES ( :file_name, :file_uni_name, :file_ext, :file_size, :code_ticketsante, :id_actor)')
          ->bindValues([':file_name'=>$file_name, ':file_uni_name'=>$file_uni_name, ':file_ext'=>$file_ext, ':file_size'=>$file_size, ':code_ticketsante'=>$ticketsante, ':id_actor'=>$user])
          ->execute();
          $rslt = '2604';
        }
        return $rslt;
      }


      /** Methode : Get user profil picture **/
      public function getuserprofilpic($user)
      {
        $pic = Null;
        if(isset($user) && !empty($user)){
          $stmt = $this->connect->createCommand('SELECT file_uni_name FROM ste_userprofilpic_uploaded WHERE user=:user')
          ->bindValue(':user',$user)
          ->queryOne();
          if(isset($stmt) && sizeof($stmt)>0)$pic = $stmt['file_uni_name'];
        }
        return $pic;
      }

      /** Methode : Get files recently saved **/
      public function getfilesuploadedid($idsanteyakah, $identite, $statut, $limit)
      {
        $rslt = Null;
        if(isset($idsanteyakah)){
          $stmt = $this->connect->createCommand('SELECT id FROM ste_carnconsult_uploaded WHERE id_santeyakah=:id_santeyakah and id_entite=:id_entite and statut =:statut ORDER BY uploaed_on DESC LIMIT '.$limit)
          ->bindValues([':id_entite'=>$identite, ':id_santeyakah'=>$idsanteyakah, ':statut'=>$statut])
          ->queryAll();
          $rslt = $stmt;
        }
        return $rslt;
      }

      /** Methode : mettre a 2 (suprimer) le statut d'une image  **/
      public function dlttempimgbaseonrowid($imgid){
        $rslt = Null;
        if($imgid){
          $stmt = $this->connect->createCommand('UPDATE `ste_uploaded_temp` SET statut=:statut WHERE id=:id')
          ->bindValues([':statut'=>2, ':id'=>$imgid])
          ->execute();
          if($stmt){
            $rslt = true;
          }
        }
        return $rslt;
      }

      /** Methode : renvois les lignes de data des fichiers telecharges **/
      public function getUploadedandsavedfilesfortemp($code_telechargement, $idsetted=Null){
        $thefiles = Null;
        (!empty($idsetted)) ?  $id = 'id,' : $id = '';

        $stmt = $this->connect->createCommand('SELECT '.$id.' file_label, file_name, file_uni_name, file_ext, file_size, id_santeyakah, id_actor, statut, id_entite FROM ste_uploaded_temp WHERE code_telechargement=:code_telechargement AND statut=:statut')
        ->bindValues([':code_telechargement'=>$code_telechargement, ':statut'=>1])
        ->queryAll();
        if(isset($stmt) && sizeof($stmt)>0){
          $thefiles = $stmt;
        }
        return $thefiles;
      }

      
      /** Function : Met a jour les infos de la photo de profil telecharge **/
      public function updateUploadedprofilpicFile($tablsuffixe, $file_name, $file_uni_name, $file_ext, $file_size, $user){
        $rslt = Null;
        if(isset($tablsuffixe)){
          $stmt = $this->connect->createCommand('UPDATE ste_'.$tablsuffixe.' SET file_name=:file_name, file_uni_name=:file_uni_name, file_ext=:file_ext, file_size=:file_size WHERE user=:user')
          ->bindValues([':file_name'=>$file_name, ':file_uni_name'=>$file_uni_name, ':file_ext'=>$file_ext, ':file_size'=>$file_size, ':user'=>$user])
          ->execute();
          $rslt = '2604';
        }
        return $rslt;
      }

      /** Function : Enregistrer les infos de la photo de profil telecharge **/
      public function saveUploadedprofilpicFile($tablsuffixe, $file_name, $file_uni_name, $file_ext, $file_size, $user){
        $rslt = Null;
        if(isset($tablsuffixe)){
          $stmt = $this->connect->createCommand('INSERT INTO ste_'.$tablsuffixe.' (file_name, file_uni_name, file_ext, file_size, user)
          VALUES ( :file_name, :file_uni_name, :file_ext, :file_size, :user)')
          ->bindValues([':file_name'=>$file_name, ':file_uni_name'=>$file_uni_name, ':file_ext'=>$file_ext, ':file_size'=>$file_size, ':user'=>$user])
          ->execute();
          $rslt = '2604';
        }
        return $rslt;
      }

      /** Function : Enregistrer les infos du fichier telecharge **/
      public function saveUploadedFile($tablsuffixe, $file_label, $file_name, $file_uni_name, $file_ext, $file_size, $id_actor, $id_entite, $idsanteyakah, $code_telechargement=''){
        $rslt = Null;
        //var_dump($tablsuffixe);die();
        if(isset($tablsuffixe)){
          switch ($tablsuffixe) {
            case 'uploaded_temp':
              $stmt = $this->connect->createCommand('INSERT INTO ste_uploaded_temp (file_label, file_name, file_uni_name, file_ext, file_size, id_actor, id_entite, id_santeyakah, code_telechargement)
              VALUES (:file_label, :file_name, :file_uni_name, :file_ext, :file_size, :id_actor, :id_entite, :id_santeyakah, :code_telechargement)')
              ->bindValues([':file_label'=>$file_label, ':file_name'=>$file_name, ':file_uni_name'=>$file_uni_name, ':file_ext'=>$file_ext, ':file_size'=>$file_size, ':id_actor'=>$id_actor, ':id_entite'=>$id_entite, ':id_santeyakah'=>$idsanteyakah, ':code_telechargement'=>$code_telechargement])
              ->execute();
              $rslt = '2604';
            break;
            
            default:
              $stmt = $this->connect->createCommand('INSERT INTO ste_'.$tablsuffixe.' (file_label, file_name, file_uni_name, file_ext, file_size, id_actor, id_entite)
              VALUES (:file_label, :file_name, :file_uni_name, :file_ext, :file_size, :id_actor, :id_entite)')
              ->bindValues([':file_label'=>$file_label, ':file_name'=>$file_name, ':file_uni_name'=>$file_uni_name, ':file_ext'=>$file_ext, ':file_size'=>$file_size, ':id_actor'=>$id_actor, ':id_entite'=>$id_entite])
              ->execute();
              $rslt = '2604';
            break;
          }
          
        }
        return $rslt;
      }

      public function uploadProfilPic($link_to_upload, $serialisedfiles, $serializedPosts, $idsanteyakah, $tablsuffixe=Null){
        $rslt = $fileuploadeduniname = Null;
        if(isset($serialisedfiles)){
          $posts = unserialize($serializedPosts);
          $files = unserialize($serialisedfiles);
          // Upload and saving process
          foreach ($files as $value) {
            if(sizeof($value['tmp_name']) > 0){
              for ($i=0; $i <= sizeof($value['tmp_name']); $i++) {
                if(isset($value['name'][$i])){
                  $rand_numb = md5(uniqid(microtime()));
                  $file_name = $value['name'][$i];
                  $file_size =$value['size'][$i];
                  $file_tmp =$value['tmp_name'][$i];
                  $file_type=$value['type'][$i];
                  $extCounter = explode('.', $file_name);
                  $file_ext=end($extCounter);

                  $file_uni_name = $extCounter[0].$rand_numb.'.'.$file_ext;
                  $expensions= array("jpeg","jpg","png");
                  $fileuploadeduniname[$i] = [$file_uni_name];
                }
                if(in_array($file_ext,$expensions) === false){
                  $rslt = '11';
                }
                if($file_size > Yii::$app->params['maxFileSize']){
                 $rslt = '22';
                }
                if(empty($errors)==true){
                  $targetfolder = \Yii::getAlias(Yii::$app->basePath.$link_to_upload);

                  if(move_uploaded_file($file_tmp, $targetfolder.$file_uni_name)){

                    //Redimensionner les images 
                    $image = Yii::$app->zebraimageClass;
                    // indicate a source image
                    $image->source_path = $targetfolder.$file_uni_name;
                    // and if there is an error, show the error message
                    if (!$image->resize(41, 41, ZEBRA_IMAGE_BOXED, -1)) show_error($image->error, $image->source_path, $image->target_path);
                    // from this moment on, work on the resized image
                    $image->source_path = $targetfolder.$file_uni_name;
                    // indicate a target image
                    $image->target_path = $targetfolder.'small_'.$file_uni_name;

                    #Renvois l'id uniq de l'utilisateur connected
                    $userAuthDtlsArray = unserialize(Yii::$app->session[Yii::$app->params['userSessionDtls']]);
                    #Renvoit l'id du type d'utilisateur connected
                    $typeUser = Yii::$app->mainCLass->getTypeIdUniqUser($userAuthDtlsArray['idUniqUser']);
                    #Enregistre le fichier telecharged dans le temporary uploaded file data's table
                    $tablsuffixe = isset($tablsuffixe) && !empty($tablsuffixe) ? $tablsuffixe : 'uploaded_temp';
                    $activeEntite = Yii::$app->session[Yii::$app->params['activeEntiveUserEnFnct']];
                    switch ($_POST['action_key']) {
                      case md5("selfprofil_updateprofilpicteure"): // Cas : Telecharger une photo de profil
                        $user = $idsanteyakah; //Dans le cas du profil pic, l'id envoye est de l'utilisateur connected
                        $userprofilpic = Yii::$app->fileuploadClass->getuserprofilpic($userAuthDtlsArray['idUniqUser']);
                        if(isset($userprofilpic) && !empty($userprofilpic)){
                        $rslt = fileuploadClass::updateUploadedprofilpicFile($tablsuffixe, $file_name, $file_uni_name, $file_ext, $file_size, $user);
                        }else{
                          $rslt = fileuploadClass::saveUploadedprofilpicFile($tablsuffixe, $file_name, $file_uni_name, $file_ext, $file_size, $user);
                        }
                      break;
                      
                      default: // Cas : Telecharger le reste des images
                        $rslt = fileuploadClass::saveUploadedFile($tablsuffixe, $posts['fileToUploadName'][$i], $file_name, $file_uni_name, $file_ext, $file_size, $userAuthDtlsArray['idUniqUser'], $activeEntite, $idsanteyakah);
                      break;
                    }
                    
                    if($rslt != '2604'){
                      return '9966';
                    }
                  }
                }else{
                  return '9966'; // On retourne une valeur pour stopper le processus
                }
              }
            }
          }
        }
        return $fileuploadeduniname;
      }

      /** Function : upload file from  **/
      public function uploadFile($link_to_upload, $serialisedfiles, $serializedPosts, $idsanteyakah, $tablsuffixe=Null, $autreparam=Null, $code_telechargement='')
      {
        $rslt = $fileuploadeduniname = Null;
        if(isset($serialisedfiles))
        {
          $posts = unserialize($serializedPosts);
          $files = unserialize($serialisedfiles);
          // Upload and saving process
          foreach ($files as $value) {
            if(sizeof($value['tmp_name']) > 0){
              for ($i=0; $i <= sizeof($value['tmp_name']); $i++) {
                if(isset($value['name'][$i])){
                  $rand_numb = md5(uniqid(microtime()));
                  $file_name = $value['name'][$i];
                  $file_size =$value['size'][$i];
                  $file_tmp =$value['tmp_name'][$i];
                  $file_type=$value['type'][$i];
                  $extCounter = explode('.', $file_name);
                  $file_ext=end($extCounter);

                  $file_uni_name = $extCounter[0].$rand_numb.'.'.$file_ext;
                  $expensions= array("jpeg","jpg","png");
                  $fileuploadeduniname[$i] = [$file_uni_name];
                }
                if(in_array($file_ext,$expensions) === false){
                  $rslt = '11';
                }
                if($file_size > Yii::$app->params['maxFileSize']){
                 $rslt = '22';
                }

                if(empty($errors)==true){
                  $targetfolder = \Yii::getAlias(Yii::$app->basePath.$link_to_upload);
                  
                  if(move_uploaded_file($file_tmp, $targetfolder.$file_uni_name)){
                    //Renvois l'id uniq de l'utilisateur connected
                    $userAuthDtlsArray = unserialize(Yii::$app->session[Yii::$app->params['userSessionDtls']]);
                    //Renvoit l'id du type d'utilisateur connected
                    $typeUser = Yii::$app->mainCLass->getTypeIdUniqUser($userAuthDtlsArray['idUniqUser']);
                    //Enregistre le fichier telechargé dans le temporary uploaded file data's table
                    $tablsuffixe = isset($tablsuffixe) && !empty($tablsuffixe) ? $tablsuffixe : 'uploaded_temp';
                    $activeEntite = Yii::$app->session[Yii::$app->params['activeEntiveUserEnFnct']];
                    switch ($_POST['action_key']) 
                    {
                      // Cas : Enregistrer les pieces jointes du reply au sante
                      case md5("ticketsante_addnewreply"):
                        $actor = $idsanteyakah;
                        $reply = $autreparam;
                        $rslt = fileuploadClass::saveUploadedticketsantereplyFile($tablsuffixe, $file_name, $file_uni_name, $file_ext, $file_size, $actor, $reply);
                      break;

                      // Cas : Enregistrer les pieces jointes du ticket sante
                      case md5("ticketsante_newticket"):
                        $santeyakah = $idsanteyakah;
                        $ticketsante = $autreparam;
                        $rslt = fileuploadClass::saveUploadedticketsanteFile($tablsuffixe, $file_name, $file_uni_name, $file_ext, $file_size, $santeyakah, $ticketsante);
                      break;

                      // Cas : Telecharger une photo de profil
                      case md5("selfprofil_updateprofilpicteure"): 
                        $user = $idsanteyakah; //Dans le cas du profil pic, l'id envoye est de l'utilisateur connected
                        $userprofilpic = Yii::$app->fileuploadClass->getuserprofilpic($userAuthDtlsArray['idUniqUser']);
                        if(isset($userprofilpic) && !empty($userprofilpic)){
                        $rslt = fileuploadClass::updateUploadedprofilpicFile($tablsuffixe, $file_name, $file_uni_name, $file_ext, $file_size, $user);
                        }else{
                          $rslt = fileuploadClass::saveUploadedprofilpicFile($tablsuffixe, $file_name, $file_uni_name, $file_ext, $file_size, $user);
                        }
                      break;
                      
                      default: // Cas : Telecharger le reste des images
                        //var_dump($tablsuffixe);
                        $rslt = fileuploadClass::saveUploadedFile($tablsuffixe, $posts['fileToUploadName'][$i], $file_name, $file_uni_name, $file_ext, $file_size, $userAuthDtlsArray['idUniqUser'], $activeEntite, $idsanteyakah, $code_telechargement);
                      break;
                    }
                    
                    if($rslt != '2604'){
                      return '9966';
                    }
                  }
                }else{
                  return '9966'; // On retourne une valeur pour stopper le processus
                }
              }
            }
          }
        }
        return $fileuploadeduniname;
      }
    }
?>
