<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\MngUserEmployeeCat;
use app\models\MngUserEmployeePos;
use yii\db\Query;

class UsersController extends Controller {

    public function actions()
    {
      //Yii::$app->mycomponent->behaviors();
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    //************************************************//
    //************************************************//
    // START CATEGORY
    // CREATION
    // MODIFYCATION
    // LIST
    //************************************************//
    //************************************************//

    public function actionCat(){ // START ACTION : CATEGORY
      if(Yii::$app->request->isPost){
        #*******************
        # SETTING VARIABLES
        #*******************
        $model = $inst_cat = $modify_this = null;
        $title = Yii::$app->request->post()['title'];
        $prefix = Yii::$app->request->post()['prefix'];
        $description = Yii::$app->request->post()['description'];
        $status = Yii::$app->request->post()['status'];
        $submit_action = Yii::$app->request->post()['submit_action'];

        switch (strtolower($submit_action)) {
          #***************************************************
          # CHECK ON THE ACTION TAKEN : SAVE OR UPDATE ACTION
          #***************************************************
          case 'save':
            if(empty($title) || empty($prefix) || empty($description) || $status<=0){
              $msg = ['type'=>'alert alert-danger','strong'=>Yii::t('app','warning'),'text'=>Yii::t('app','empty_warning_msg')];
              return $this->render('/site/users_category/category', ['model'=>$model, 'msg'=>$msg]);
            }else{
              #*****************************
              # CHECK AND AVOID DUPLICATION
              #*****************************
              $val = Yii::$app->mainCLass->avoid_cat_duplicate($title, $prefix, 'category',$submit_action); // AVOID DUPLICATION
              if($val > 0){
                $msg = ['type'=>'alert alert-danger','strong'=>Yii::t('app','warning'),'text'=>Yii::t('app','duplication_msg')];
                return $this->render('/site/users_category/category', ['model'=>$model,'inst_cat'=>$inst_cat,'msg'=>$msg]);
              }
              #****************************************************
              # SAVE NEW RECORD INFORMATION & REDIRECT TO THE LIST
              #****************************************************
              $model = new MngUserEmployeeCat;
              $model->attributes = Yii::$app->request->post();
              $model['inst_id'] = Yii::$app->session['emisaf_inst_id'];
              if($model->validate()){
                $model->save();
                return Yii::$app->response->redirect(['/users/categories']);
              }
            }
          break;

          case'update':
            $modify_this = Yii::$app->request->post()['modify_this']; // id of the component about to update record
            if(empty($title) || empty($prefix) || empty($description) || empty($modify_this) || $status<=0){
              $msg = ['type'=>'alert alert-danger','strong'=>Yii::t('app','warning'),'text'=>Yii::t('app','empty_warning_msg')];
              return $this->render('/site/users_category/category', ['model'=>$model,'inst_cat'=>$inst_cat,'msg'=>$msg]);
            }else{
              #*****************************
              # CHECK AND AVOID DUPLICATION
              #*****************************
              $val = Yii::$app->mainCLass->avoid_cat_duplicate($title, $prefix, 'category',$submit_action, $modify_this); // AVOID DUPLICATION
              if($val > 0){
                $msg = ['type'=>'alert alert-danger','strong'=>Yii::t('app','warning').'&nbsp;'.Yii::t('app','duplication_word'),'text'=>Yii::t('app','duplication_msg')]; // SHOW WARNING DUPLICATION MESSAGE
                return $this->render('/site/users_category/category', ['model'=>$model,'inst_cat'=>$inst_cat,'msg'=>$msg]);
              }
              #****************************************************
              # UPDATE RECORD INFORMATION & REDIRECT TO THE LIST
              #****************************************************
              $model = new MngUserEmployeePos; // INIATE A NEW INSTANCE
              $model->attributes = Yii::$app->request->post();
              $model['inst_id'] = Yii::$app->session['emisaf_inst_id'];
              if($model->validate()){
                $connection = \Yii::$app->db;
                $resutl = $connection
                            ->createCommand('UPDATE employee_cat SET title=:title, description=:description, prefix=:prefix, status=:status WHERE id=:id')
                            ->bindValues([':title'=>$title, ':description'=>$description, ':prefix'=>$prefix, ':status'=>$status, ':id'=>$modify_this])
                            ->execute();
                return $this->redirect( Yii::$app->request->baseUrl.'/users/categories');
              }
            }
          break;

          default: // RETURN TO THE GENERAL LIST OF CATEGORIES
            return $this->redirect( Yii::$app->request->baseUrl.'/users/categories');
          break;
        }

      }else{
          $model = new MngUserEmployeeCat;
          return $this->render('/site/users_category/category', ['model'=>$model]);
      }
    }

    Public function actionCats(){
      $connection = \Yii::$app->db;
      $model = new MngUserEmployeeCat;

      if(Yii::$app->request->isPost){
        $inst_id = Yii::$app->session['emisaf_inst_id'];
        switch (strtolower(Yii::$app->request->post()['submit_action'])) {
          case 'edit':
            $val = Yii::$app->request->post();
            $resutl = $connection
                        ->createCommand('SELECT * FROM employee_cat WHERE id=:id')
                        ->bindValue(':id',$val['modify_this']);
            $resutl = $resutl->queryOne();
            return $this->render('/site/users_category/category', ['model'=>$model,'resutl'=>$resutl]);
          break;
        }
      }else{
        $inst_cat = Yii::$app->mainCLass->get_inst_cat();
        return $this->render('/site/users_category/categories', ['model'=>$model,'inst_cat'=>$inst_cat]);
      }
    }

    #*****************************************
    #*****************************************
    # START POSITION
    # CREATION
    # MODIFYCATION
    # LIST
    #*****************************************
    #*****************************************

    Public function actionPoss(){ // START ACTION : POSITIONS
      $model = (!empty($model))?$model:null;
      $cat_count = Yii::$app->mainCLass->get_inst_cat_count(); // GET INSTITUION CATEGORY COUNT
      if($cat_count>0){
        $connection = \Yii::$app->db;
        if(Yii::$app->request->isPost){
          $inst_id = Yii::$app->session['emisaf_inst_id'];
          switch (strtolower(Yii::$app->request->post()['submit_action'])) {
            case 'edit':
              $inst_cat_array = Yii::$app->mainCLass->get_cat_in_array();
              $data = Yii::$app->request->post();
              $resutl = $connection
                      ->createCommand('SELECT * FROM employee_pos WHERE id=:id')
                      ->bindValue(':id',$data['modify_this']);
              $resutl = $resutl->queryOne();
              return $this->render('/site/users_position/position', ['model'=>$model,'resutl'=>$resutl, 'inst_cat_array'=>$inst_cat_array]);
            break;
          }
        }else{
          $inst_pos = Yii::$app->mainCLass->get_inst_pos();
          return $this->render('/site/users_position/positions', ['inst_pos'=>$inst_pos, 'model'=>$model]);
        }
      }else{
        $msg = ['type'=>'note note-info','strong'=>Yii::t('app','warning'),'text'=>Yii::t('app','set_users_pos_config')];
        return $this->render('/site/all_msg_file/setup_required_msg', ['msg'=>$msg]);
      }

    }

    public function actionPos(){
      $inst_cat_array = Yii::$app->mainCLass->get_cat_in_array();
      $model = new MngUserEmployeePos;
      if(Yii::$app->request->isPost){
        #*******************
        # SETTING VARIABLES
        #*******************
        $model = !empty($model) ? $model : Null;
        $cat_id = Yii::$app->request->post()['cat_id'];
        $title = Yii::$app->request->post()['title'];
        $description = Yii::$app->request->post()['description'];
        $status = Yii::$app->request->post()['status'];
        $submit_action = Yii::$app->request->post()['submit_action'];
        #***************************************************
        # CHECK ON THE ACTION TAKEN : SAVE OR UPDATE ACTION
        #***************************************************
        switch (strtolower($submit_action)){
          case 'save':
            if( (!isset($cat_id)  && $cat_id<=0) || empty($title) || empty($description) || $status<=0){
              $msg = ['type'=>'alert alert-danger','strong'=>Yii::t('app','warning'),'text'=>Yii::t('app','empty_warning_msg')];
              return $this->render('/site/users_position/position', ['model'=>$model, 'msg'=>$msg, 'inst_cat_array'=>$inst_cat_array]);
            }else{
              #*****************************
              # CHECK AND AVOID DUPLICATION
              #*****************************
              $val = Yii::$app->mainCLass->avoid_pos_duplicate($title, Null, 'position',$submit_action); // AVOID DUPLICATION
              if($val > 0){
                $msg = ['type'=>'alert alert-danger','strong'=>Yii::t('app','warning').'&nbsp;'.Yii::t('app','duplication_word'),'text'=>Yii::t('app','duplication_msg')]; // SHOW WARNING DUPLICATION MESSAGE
                return $this->render('/site/users_position/position', ['model'=>$model,'inst_cat_array'=>$inst_cat_array,'msg'=>$msg]);
              }
              #****************************************************
              # SAVE NEW RECORD INFORMATION & REDIRECT TO THE LIST
              #****************************************************
              $model = new MngUserEmployeePos;
              $model->attributes = Yii::$app->request->post();
              $model['inst_id'] = Yii::$app->session['emisaf_inst_id'];
              if($model->validate()){
                if($model->save()){
                  $msg = ['type'=>'alert alert-success','strong'=>Yii::t('app','success'),'text'=>Yii::t('app','save_with_success')];
                }
                $inst_pos = Yii::$app->mainCLass->get_inst_pos();
                return $this->render('/site/users_position/positions', ['inst_pos'=>$inst_pos, 'msg'=>$msg, 'model'=>$model]);

              }
            }
          break;

          case'update':
            $modify_this = Yii::$app->request->post()['modify_this']; // id of the component about to update record
            $inst_cat_array = Yii::$app->mainCLass->get_cat_in_array();
            if(empty($cat_id) || empty($title) || empty($description) || empty($modify_this) || $status<=0){
              $msg = ['type'=>'alert alert-danger','strong'=>Yii::t('app','warning'),'text'=>Yii::t('app','empty_warning_msg')];
              return $this->render('/site/users_position/position', ['model'=>$model,'inst_cat'=>$inst_cat,'msg'=>$msg, 'inst_cat_array'=>$inst_cat_array]);
            }
            #*****************************
            # CHECK AND AVOID DUPLICATION
            #*****************************
            $val = Yii::$app->mainCLass->avoid_cat_duplicate($title, 'position',$submit_action, $modify_this); // AVOID DUPLICATION
            if($val > 0){// THIS RECORD ALREADY EXIST IN THE SYSTEM
              $msg = ['type'=>'alert alert-danger','strong'=>Yii::t('app','warning'),'text'=>Yii::t('app','duplication_msg')];
              return $this->render('/site/users_position/position', ['model'=>$model,'inst_cat'=>$inst_cat,'msg'=>$msg, 'inst_cat_array'=>$inst_cat_array]);
            }
            #****************************************************
            # SAVE NEW RECORD INFORMATION & REDIRECT TO THE LIST
            #****************************************************
            $model = new MngUserEmployeeCat; #
            $model->attributes = Yii::$app->request->post();
            $model['inst_id'] = Yii::$app->session['emisaf_inst_id'];
            if($model->validate()){
              $connection = \Yii::$app->db;
              $resutl = $connection
                          ->createCommand('UPDATE employee_pos SET cat_id=:cat_id, title=:title, description=:description, status=:status WHERE id=:id')
                          ->bindValues([':cat_id'=>$cat_id, ':title'=>$title, ':description'=>$description, ':status'=>$status, ':id'=>$modify_this])
                          ->execute();
              return $this->redirect( Yii::$app->request->baseUrl.'/users/positions');
            }

          break;
        }
      }else{
        return $this->render('/site/users_position/position', ['model'=>$model, 'inst_cat_array'=>$inst_cat_array]);
      }
    } # END : ACTION POS




    #*****************************************
    #*****************************************
    # START SYSTEM USERS
    # CREATION
    # MODIFYCATION
    # LIST
    #*****************************************
    #*****************************************
    public function actionAlllist(){ # START ACTION : LIST ALL USERS
      $model = (!empty($model))?$model:null;
      $cat_count = Yii::$app->mainCLass->get_inst_pos_count();
      if($cat_count>0){
        $connection = \Yii::$app->db;
        if(Yii::$app->request->isPost){ } else {
          $inst_pos = Yii::$app->mainCLass->get_inst_pos();
          $inst_sys_users = Yii::$app->mainCLass->get_inst_sys_users(); # GET ALL ACTIVE SYSTEM USERS
          return $this->render('/site/users_sysusers/listusers', ['inst_pos'=>$inst_pos, 'inst_sys_users'=>$inst_sys_users, 'model'=>$model]);
        }

      }
      return $this->render('/site/users_position/setup_required_msg', ['set_users_config'=>Yii::t('app','')]);
    }


    public function actionNewuser(){
      $all_countries = Yii::$app->mainCLass->get_countries_in_array();
      $sys_credent = Yii::$app->mainCLass->get_sys_credent();
      $inst_cat_array = Yii::$app->mainCLass->get_cat_in_array();
      $model = (!empty($model))?$model:null;
      $model = new MngUserEmployeePos;
      if(Yii::$app->request->isPost){

      }
      return $this->render('/site/users_sysusers/adduser', ['model'=>$model, 'inst_cat_array'=>$inst_cat_array,'all_countries'=>$all_countries, 'sys_credent'=>$sys_credent]);
    }

    #****************
    # AJAX STATEMENT
    #****************
    Public function actionLoad_positions(){ // LOAD POSITIONS BASE ON THE CATEGORY ID SELECTED AND REVEIVED
      $rslt = Null;
      $data = $_POST['data'];
      if(isset($data) && isset($_POST['_csrf'])){
        $connection = \Yii::$app->db;
        $rqst = $connection
                  ->createCommand('SELECT * FROM employee_pos WHERE cat_id=:cat_id AND status=:status')
                  ->bindValues([':cat_id'=>$data, ':status'=>'1']);
        $rqst = $rqst->queryAll();
        $rqst_count = count($rqst);
        if( $rqst_count >0){
          $rslt = '<option value="0">'.Yii::t('app','select_one').'</option>';
          for ($i=0; $i < $rqst_count; $i++) {
            $rslt .= '<option value="'.$rqst[$i]['id'].'">'.$rqst[$i]['title'].'</option>';
          }
          return $rslt;
        }
        return "<option>".Yii::t('app','no_record_found')."</option>";
      }
      return false;
    }

    #*********************************************#
    # AJAX STATEMENT : GET RESIDENCE'S PHONE CODE #
    #*********************************************#
    Public function actionGet_country_code(){
      $country_id = $_POST['data'];
      if(isset($country_id)  && $country_id > 0){
        $connection = \Yii::$app->db;
        $stmt = $connection
                ->createCommand('SELECT tel_code FROM sys_countries WHERE id=:id')
                ->bindValues([':id'=>$country_id]);
        $rslt = $stmt->queryOne();
        $max_array = count($rslt);
        if($max_array > 0){
          return $rslt['tel_code'];
        }
        return false;
      }
      return false;
    }

   

}
