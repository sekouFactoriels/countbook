<?php
    namespace app\components;
    use Yii;
    use yii\helpers\Html;
    use yii\base\component;
    use yii\web\Controller;
    use yii\base\InvalidConfigException;

    Class nonSqlClass extends Component {



      /** Methode : Ajouter des années sur une date  **/
      public function topup_year($date_given=Null, $incrementeur=5)
      {
        $date_gotten = Null;
        if(isset($incrementeur))
        {
          $date_given = explode('-', $date_given);
          $date_gotten = mktime(0, 0, 0, $date_given['1'],   $date_given['2'],   $date_given['0'] + $incrementeur);
          $date_gotten = date('Y-m-d', $date_gotten);
        }
        return $date_gotten;
      }


      /** Methode : Libeller les categories de client **/
      public function libeller_categorie_client($categorie_id='')
      {
        $rslt = '';
        switch($categorie_id)
        {
          case 1:
            $rslt = 'Prospect Froid';
          break;

          case 2:
            $rslt = 'Prospect Tiede';
          break;

          case 3:
            $rslt = 'Prospect Chaud';
          break;

          case 4:
            $rslt = 'Client';
          break;

          default:
            $rslt = 'N/S';
          break;
        }
        return $rslt;
      }

      /** Methode : Libeller du mode de paiement **/
      public function libeller_mode_paiement($id='')
      {
        $rslt = '';
        switch($id)
        {
          case 1:
            $rslt = 'Cash';
          break;

          case 2:
            $rslt = 'Chèque';
          break;

           case 3:
            $rslt = 'Virement Bancaire';
          break;

          default:
            $rslt = 'N/S';
          break;
        }
        return $rslt;
      }

      /** Methode : Libeller la raison sociale des fournisseurs **/
      public function libeller_raison_sociale($id='')
      {
        $rslt = '';
        switch($id)
        {
          case 1:
            $rslt = yii::t('app','particulier');
          break;

          case 2:
            $rslt = yii::t('app','entreprise');
          break;

          default:
            $rslt = 'N/S';
          break;
        }
        return $rslt;
      }

      public function convertinttableautostrg($tableau){
        $strg = Null;
        if(sizeof($tableau)>0){
          for ($i=0; $i < sizeof($tableau); $i++) {
            $minitableau[]=$tableau[$i]['id'];
          }
          $strg = implode(',',$minitableau);
        }
        return $strg;
      }

      public function convertdatetolimitforfait($dateinsqlformat){
        $dateinlicenceformat = Null;
        if(isset($dateinsqlformat)){
          $dfl_array = explode('-', $dateinsqlformat);
          $dfl_strg = $dfl_array['0'].$dfl_array['1'].$dfl_array['2'];
          $dateinlicenceformat = base64_encode($dfl_strg);
        }
        return $dateinlicenceformat;
      }

      public function convertArraytoString($monarray, $indexaselect){
        $data = Null;
        $cmpt = 0;
        if(sizeof($monarray) > 0){
          foreach ($monarray as $key => $value) {
            $space = ($key == 0) ? ''  : ',' ;
            $data .= $space.$value[$indexaselect];
          }
          //$data = str_replace('','","',$data);
        }
        return $data;
      }
      public function convert_to_moneyformat($number){
        $number = 1000000000;
        $output = Null;
        if(isset($number)){
          $numberlen = strlen($number);
          $divider = $numberlen / 3;
          for ($i=1; $i < $divider ; $i++) {
            $output.= $number[$i].'_';
          }
        }
        return $number[1];
      }
      /*********************************************/
      // Cette fonction compare deux date entre elle
      /*********************************************/
      public function date_compare($startDate, $endDate){
        $cmptaror = false;
        $startDate = nonSqlClass::convert_date_to_sql_form($startDate, "M/D/Y");
        $endDate = nonSqlClass::convert_date_to_sql_form($endDate, "M/D/Y");

        if (strtotime($startDate) < strtotime($endDate)){
            $cmptaror = true;
        }else{
              $cmptaror = false;
        }
        return $cmptaror;
      }

      /***************************************/
      /** Get le label du type de l'article **/
      /***************************************/
      public function articleTypeLabel($typeId){
        $typeLabel = Null;
        if(isset($typeId)){
          switch ($typeId) {
            case '2':
              $typeLabel = 'Service';
            break;

            case '1':
            default:
              $typeLabel = 'Stock';
            break;

          }
          return $typeLabel;
        }

      }

      /***************************/
      /** Get le label du genre **/
      /***************************/
      public function genderLabel($genderId){
        $genderLabel = Null;
        if(isset($genderId)){
          switch ($genderId) {
            case '2':
              $genderLabel = Yii::t('app','mculin');
            break;

            case '1':
              $genderLabel = Yii::t('app','fmin');
            break;

            case '3':
              $genderLabel = Yii::t('app','autre');
            break;
          }
          return $genderLabel;
        }

      }

      #************************************************************************************************
      # FUNCTION QUI RENVOIS LE CODE QUI INITIE LA CREATION DE COMPTE D'UN SANTEYAKAH ET OPTIENT LE NIS
      #************************************************************************************************
      public function genNouveauCode(){
        $j = $code = $holder = Null;
        $code = array();
        $j = (strlen(date('j')) < 2) ? date('jj') : date('j');
        /*
        for ($i=0; $i < 100; $i++) {
          $holder = rand(10,99).$j.rand(10,99);
          $code[$i] = $holder;
        }*/
        return $holder = $j.rand(100,999);
      }

      #********************************************
      # FUNCTION QUI RENVOIS LE NIS D'UN SANTEYAKAH
      #********************************************
      public function codeNis($dteNaissance){
        if(isset($dteNaissance)){
          # DETERMINATION DES VARIABLES
          $j = $q = $m = $a = Null;
        }else return false;
      }

      #***************************************************************************
      # CETTE FUNCTION RENVOIS UN HIDDEN IMPUT CONTRE LA DUPLICATION DE VALIDATION
      #***************************************************************************
      public function getHiddenFormTokenField(){
        $token = Yii::$app->getSecurity()->generateRandomString();
        $token = str_replace('+','.',base64_encode($token));
        Yii::$app->session->set(Yii::$app->params['postToken'], $token);
        return Html::hiddenInput(Yii::$app->params['postToken'],$token);
      }

      #****************************************************************************************
      # FUNCTION : RENVOIS LE NUMEROS DU JOUR DE LA SEMAINE EN FUNCTION DU JOUR DE LA SEMAINE
      #****************************************************************************************
      public function findDayNum($DayOfWeek){
        $dayNum = Null;
        switch (strtolower($DayOfWeek)) {
          case 'sat':
            $dayNum = 0;
          break;

          case 'sun':
            $dayNum = 1;
          break;

          case 'mon':
            $dayNum = 2;
          break;

          case 'sat':
            $dayNum = 3;
          break;

          case 'wed':
            $dayNum = 4;
          break;

          case 'thu':
            $dayNum = 5;
          break;

          default:
            $dayNum = Null;
          break;
        }
        return $dayNum;
      }

      #**********************************************************
      # THIS FUNCTION CONVERT A FRIENDLY DATE TO SQL DATE FORMAT
      #**********************************************************
      public static function convert_date_to_sql_form($indate,$inputformat,$format='Y-M-D'){
        $output = Null;
        $output = $indate;
        $year='';
        $month='';
        $day='';
        if(isset($inputformat) && !empty($inputformat) && isset($indate)  && !empty($indate)){
          switch(strtoupper($inputformat)) {
            case "M/D/Y":
              $split = explode('/',$indate);
              $year=$split[2];$month=$split[0];$day=$split[1];
            break;

            case "Y-D-M":
              $split = explode('-',$indate);
              $year=$split[0];$month=$split[2];$day=$split[1];
            break;

            case "D/M/Y":
              $split = explode('/',$indate);
              $year=$split[2]; $month=$split[0]; $day=$split[1];
            break;

            case "Y-M-D":
              $split = explode('-',$indate);
              $year=$split[0];$month=$split[1];$day=$split[2];
            break;
          }

          switch(strtoupper($format)) {
            case "Y-M-D":
                 $output = $year.'-'.$month.'-'.$day;
            break;

            case "D/M/Y":
                $output = $day.'/'.$month.'/'.$year;
            break;

            case "M/D/Y":
                $output = $month.'/'.$day.'/'.$year;
            break;
        }
        }
        return $output;
      }

      #*********************************
      # THIS FUNCTION GET SERVER DETAILS
      #*********************************
      public static function get_server_dtls(){
        $server = $_SERVER;
        $str_array = array('PATH','PATHEXT','SystemRoot','SERVER_ADMIN','DOCUMENT_ROOT','SERVER_SOFTWARE','SERVER_SIGNATURE','CONTEXT_DOCUMENT_ROOT','COMSPEC','WINDIR','SCRIPT_FILENAME');
        foreach ($str_array as $key) {
          unset($server[$key]);
        }
        $server = serialize($server);
        return $server;
      }

      #**********************************************#
      # FUNCTION : GENERATE UNIQUE STAFF ID FOR USER #
      #**********************************************#
      Public static function generate_unique_id(){
        $staffid = Yii::$app->params['staff_id_prefix'].
                      substr(rand(), 0, Yii::$app->params['gen_key1_length']).
                      substr(date("i"), 1, 2).substr(date("sa"), 1, 1).
                      substr(date("D"), 0, 1);
        $is_unique = Yii::$app->mainCLass->check_staffid_if_unique($staffid);
        if($is_unique == true OR $is_unique == 1){
          return $staffid;
        }

        $staffid = Yii::$app->params['staff_id_prefix'].
                      substr(rand(), 0, Yii::$app->params['gen_key1_length']).
                      substr(date("i"), 1, 2).substr(date("sa"), 1, 1).
                      substr(date("D"), 0, 1);
        $is_unique = Yii::$app->mainCLass->check_staffid_if_unique($staffid);
        if($is_unique == true OR $is_unique == 1){
          return $staffid;
        }


        $staffid = Yii::$app->params['staff_id_prefix'].
                      substr(rand(), 0, Yii::$app->params['gen_key1_length']).
                      substr(date("i"), 1, 2).substr(date("sa"), 1, 1).
                      substr(date("D"), 0, 1);
        $is_unique = Yii::$app->mainCLass->check_staffid_if_unique($staffid);
        if($is_unique == true OR $is_unique == 1){
          return $staffid;
        }

        return false;

      }

    }
