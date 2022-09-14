<?php
    namespace app\components;
    use Yii;
    use yii\base\component;
    use yii\web\Controller;
    use yii\base\InvalidConfigException;

    Class leftMenuCLass extends Component{

      // RENVOIS SOUS TITRE DE PAGE EN FONCTION DE CONTROLLER ID
      public function getPageStitle($actionId){
        $pageStitle = Null;
        if(!empty($actionId)){
          switch ($actionId) {
            case 'index':
              $pageStitle = Yii::t('app','tableauBord');
            break;
          }
        }
        return $pageStitle;
      }

      // RENVOIS TITRE DE PAGE EN FONCTION DE CONTROLLER ID
      public function getPageTitle($controllerId){
        $pTitle = Null;
        if(!empty($controllerId)){
          switch ($controllerId) {

            case 'bondcommand':
            case 'bondcommand_themain':
              $pTitle = Yii::t('app','bondcommand_themain');
            break;

            case 'paiement':
              $pTitle = Yii::t('app','paiement_themain');
            break;

            case 'fournisseur':
              $pTitle = Yii::t('app','fournisseur');
            break;


            case 'site':
              $pTitle = Yii::t('app','acceuil');
            break;

            case 'inventaire': // INVENTAIRE
              $pTitle = Yii::t('app','inventaire');
            break;

            case 'vente_simple': // VENTE
            case 'vente':
              $pTitle = Yii::t('app','vente');
            break;

            case 'client_themain': //CLient
            case 'client':
              $pTitle = Yii::t('app','client_themain');
            break;

            case 'command': // COMMANDE
              $pTitle = Yii::t('app','command');
            break;

            case 'parametre': // parametre
              $pTitle = Yii::t('app','parametre');
            break;

            case 'approvision': // APPROVISIONNEMENT
              $pTitle = Yii::t('app','approvision');
            break;

            case 'stok': // STOCK
              $pTitle = Yii::t('app','stok');
            break;

            case 'diver': // DIVER
              $pTitle = Yii::t('app','diver');
            break;

            case 'rg': // RAPPORT GENERAL
              $pTitle = Yii::t('app','rg');
            break;

            case 'reajustement': // RAPPORT GENERAL
              $pTitle = Yii::t('app','reajustement_themain');
            break;

          }
        }
        return $pTitle;
      }

      // RENVOIS LES DIFFERENTS ICONS DU MENU
      public function getIcon($actions){
        $icon = Null;
        switch ($actions) {

          case 'bondcommand':
          case 'bondcommand_themain':
            $icon = 'bold';
          break;


          case 'paiement':
          case 'paiement_themain': //PAIEMENT
            $icon = 'usd';
          break;

          case 'fournisseur':
          case 'fournisseur_themain': //FOURNISSEUR
            $icon = 'link';
          break;

          case 'site_index': // INDEX
          case 'site':
            $icon = 'home';
          break;

          case 'inventaire': // INVENTAIRE
            $icon = 'exchange';
          break;

          case 'vente_simple': // VENTE
          case 'vente':
            $icon = 'money';
          break;

          case 'client_themain': //CLient
          case 'client':
            $icon = 'users';
          break;

          case 'command': // COMMANDE
            $icon = 'magnet';
          break;

          case 'parametre': // parametre
            $icon = 'building-o';
          break;

          case 'approvision': // APPROVISIONNEMENT
            $icon = 'arrows-alt';
          break;

          case 'stok': // STOCK
            $icon = 'briefcase';
          break;

          case 'diver': // DIVER
            $icon = 'fire';
          break;

          case 'rg': // REPPORT GENERAL
            $icon = 'globe';
          break;

          case 'reajustement':
          case 'reajustement_themain': //  reajustement
            $icon = 'refresh';
          break;
        }
        return $icon;
      }

      // RENVOIS ACTIVE SI MENU CONTIENT ACTIVE ACTION
      public function colorActiveMenu($menuAction, $identifier)
      {

        switch ($identifier) 
        {
          #****************************************************************
          # IDENTIFICATION DU TYPE DE MENU : SIMPLE MENU OU AVEC SOUS MENU
          #****************************************************************
          case 1: # CASE DE SIMPLE MENU
          if(isset($menuAction)){
            if(Yii::$app->controller->id.'_'.Yii::$app->controller->action->id == $menuAction)
            {
              return "active";
            }
          }
          break;

          case 2: # CAS DE SOUS MENU
              for ($i=1; $i < sizeof($menuAction); $i++) {
                #*************************************************************
                # ANALYSE SI LE SUB_MENU CLIQUER EST AUTORISER A L'UTILISATEUR
                #*************************************************************
                if($menuAction[$i] == Yii::$app->controller->id.'_'.Yii::$app->controller->action->id){
                  return "active";
                }
            }
          break;

          default:
            return Null;
          break;
        }
        return Null;
      }

      // RENVOIS LA CHAINE DE CHARACTERE DE MENU
      public function menuString($typeUser, $idUserAuth){
        $menuArray = Null;
        if(isset($typeUser) && isset($idUserAuth)){
          $connect = \Yii::$app->db;
          $rslt = $connect->createCommand('SELECT menu FROM slim_userauthmenuaction
                                            WHERE idUniq=:idUniq
                                            AND idUserType=:idUserType')
                          ->bindValues([':idUniq'=>$idUserAuth, ':idUserType'=>$typeUser])
                          ->queryOne();
          if(is_array($rslt)){
            return $rslt['menu'];
          }
        } else $menuArray = Null;

        return $menuArray;
      }

      #**********************
      # CONSTRUCTEUR DE MENU
      #**********************
      public function menuConstructeur($typeUser, $idUserAuth){
        $subMenu = $menuString = Null;
        $menuString = leftMenuCLass::menuString($typeUser, $idUserAuth);
        if(isset($menuString)){
          $hiddenaction = Yii::$app->params['hiddenaction'];
          $menuArray = explode(Yii::$app->params['menuSeperator'], $menuString); # ON FORME LA LIGNE PRICI[ALE DES MENUS
          foreach ($menuArray as $value) {
            /* Empechons la vue des actions ajax */
            $subMenuArray = explode(Yii::$app->params['subMenuSeperator'], $value);
            if(!(in_array($subMenuArray[0], $hiddenaction)))
            {
              if(is_array($subMenuArray) AND sizeof($subMenuArray)>1)
              {
                # DANS CE CAS , CEST UN MENU AVEC SOUS MENU
                echo '<li class="nav-parent '.leftMenuCLass::colorActiveMenu($subMenuArray, 2).'">
                        <a data-toggle="dropdown" href="javascript:;">
                          <i class="fa fa-'.leftMenuCLass::getIcon($subMenuArray[0]).'"></i>
                          '.Yii::t("app",$subMenuArray[0]).'<span class="selected"> </span>
                        </a>
                        <ul class="children">';
                    for ($i=1; $i < sizeof($subMenuArray); $i++) 
                    {
                      if(!(in_array($subMenuArray[$i], $hiddenaction)))
                      {
                      echo '<li>
                        <a href="'.Yii::$app->request->baseUrl.'/'.md5($subMenuArray[$i]).'"><i Class="fa fa-caret-right">&nbsp;</i>'.Yii::t("app",$subMenuArray[$i]).'</a>
                      </li>';
                      }
                    }

                echo '</ul></li>';
              }else {
                if(isset($value) && !empty($value)){
                  echo '<li class="'.leftMenuCLass::colorActiveMenu($value, 1).'">
                  <a href="'.Yii::$app->request->baseUrl.'/'.md5($value).'"> <i class="fa fa-'.leftMenuCLass::getIcon($value).'"></i>'.Yii::t("app",$value).'</a>
                  </li>';
                }
              }
            }
          }
        }
      }

    }
