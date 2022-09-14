<?php
    namespace app\components;
    use Yii;
    use yii\base\component;
    use yii\web\Controller;
    use yii\base\InvalidConfigException;

    Class accessClass extends Component {


      #*********************************************************************
      # THIS FUNCTION ENCRYPT A PASSSWORD AND RETURN THE DEFINITIF PASSWORD
      #*********************************************************************
      public static function create_pass($usr_name, $usr_password){
        if(isset($usr_password) && !empty($usr_password) && isset($usr_name) && !empty($usr_name)){
          $strg = md5($usr_password);
          return $strg;
        }
        return false;
      }

    }
