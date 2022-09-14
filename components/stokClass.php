<?php
    namespace app\components;
    use Yii;
    use yii\base\component;
    use yii\web\Controller;
    use yii\base\InvalidConfigException;

    Class stokClass extends Component {
      public $connect = Null;

      Public function __construct(){
        $this->connect = \Yii::$app->db;
      }

      # FUNCTION RENVOIS LA LISTE DES ENTREPOT D'UNE ENTREPRISE BASE ON USERRIGHT
      public function getEntrepriseEntite($idEntreprise, $typeEntite){
        $listEntrepot = Null;
        if(isset($idEntreprise)){
          $rslt = $this->connect->createCommand('SELECT * FROM slim_entite WHERE statut=:statut AND  idEntreprise=:idEntreprise')
                        ->bindValues([':statut'=>$typeEntite,':idEntreprise'=>$idEntreprise])
                        ->queryAll();
          if(isset($rslt) && sizeof($rslt) > 0){
            $listEntrepot = $rslt;
          }
        }
        return $listEntrepot;
      }

      # FONCION QUI RENVOIS LA LISTE DES FOURNISSEURS
      public function getAllFounisseur($idEntreprise){
        $sel=null;
        if(isset($idEntreprise)){
          $rslt=$this->connect->createCommand('SELECT * FROM slim_fournisseur WHERE idEntreprise=:idEn')
          ->bindValue(':idEn',$idEntreprise)
          ->queryAll();
          if(isset($rslt)){
            $sel=$rslt;
          }
        }
        return $sel;
      }

    }
