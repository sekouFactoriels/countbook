<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\RgUsrInfo;
use yii\db\Query;

class AuthController extends Controller {
	public $request;
	public $cruizer = false;
	public function __construct(){
		$cruizer = true;
	}

	public static function store_auth($idUniqUser, $userName ,$typeUser){
		$ListCorpMedHostos = (isset($ListCorpMedHostos))?$ListCorpMedHostos:false;
		if(in_array($typeUser, Yii::$app->params['utilisateurSys'])){
			$listEntiteId = Yii::$app->mainCLass->listEntiteId($idUniqUser);
		}
		$userAuthDtlsArray = ['idUniqUser'=>$idUniqUser, 'userName'=>$userName];
		Yii::$app->session[Yii::$app->params['userSessionDtls']] = serialize($userAuthDtlsArray);
		Yii::$app->session['token'] = base64_encode(md5($userName));
		return true;
	}

	private static function compareDateToToday($dte_in_string){
		$cmparer = Null;
		$dteJour = date('Y-m-d');
		$data = explode('-',$dteJour);
		$dtetoday = $data['0'].$data['1'].$data['2'];
		$licencedate = base64_decode($dte_in_string);
		if($dtetoday >= $licencedate){
			$cmparer = '2692';
		}
		return $cmparer;
	}

	public static function UserAuthDtls($usrName,$motPass){
		$vraisMotPass = false;
		$dteJour = date('Y-m-d');
		$estPermit = false;
		$connection = \Yii::$app->db;

		if(!empty($usrName)){
			$usr = $usrName;
			$pswrd = md5($motPass);
			$userAuthDtls = $connection
								->createCommand('SELECT * FROM slim_userauth WHERE userName = :userName && motPasse=:motPasse')
								->bindValues([':userName'=>$usrName, ':motPasse'=>$pswrd]);
			$userAuthDtls = $userAuthDtls->queryOne(); // WE QUERY JUST THE FIRST ROW MACHES OUR STATEMENT
			if(!is_array($userAuthDtls) OR $userAuthDtls==false){ # MEANING ,NO RECORD MATCHES THE userName SERCHED
				return 22;
			}else{
				$vraisMotPass = '1'; # RASSURER QUE MOT PASS EST CORRECT
				switch ($vraisMotPass) {
					case true: # THE PASSWORD PROVIDED IS CORRECT
					case 1: # THE PASSWORD PROVIDED IS CORRECT
						# BEFORE STORING PROCESS, LETS MAKE SURE, AUTHENTIFIED USER IS ALLOWED
						if(AuthController::compareDateToToday($userAuthDtls['dteLimit']) == '2692'){
							$estPermit = false;
						} else { $estPermit = true;}
						# TESTER SI L'UTILISATEUR EST PERMI D'ACCEDER A SON COMPTE
						switch ($estPermit) {
							case true:
								case 1:
								# DEBUT PROCESSUS DE STORAGE
								$storage_process = AuthController::store_auth($userAuthDtls['id'],$userAuthDtls['userName'], $userAuthDtls['typeUser']); // PROCESSE TO THE USER'S DATA STORAGE
								switch ($storage_process) {
									case true:
									case 1: if(Yii::$app->mainCLass->creerEvent('001','Connexion avec success', $userAuthDtls['listEntite'][0])){ return 'success'; } break;
									default: 	return 22; break;
								}
							break;

							default: return 33; break;
						}
						return 22;
					break;

					default: // PASSWORD IS INCORECT
						return 22;
					break;
				}
			}
		} else {return false;}
	}

	#****************************************************
	# AUTHENTIFICATION DES UTILISATEURS
	#****************************************************
	public static function Authentifcation(){
		// START : DECLARE VARIABLES
		$cruizer = true;
		$session = null;
		// END : DECLARE VARIABLES

		$request = Yii::$app->request;
		$userName = $request->post('userName');
		$motPass = $request->post('motPass');
		$sugarpot = $request->post('sugarpot');
		$token = isset($session['token']) ? $session['token'] : ''; 

		# GET THE VALUE OF THE TOKEN PREVIOUSLY SET
		if(!empty($token) ){ # WE CHEQUE IF TOKEN HAS BEEN SET
			if($usr_confirmed){
				if(Yii::$app->mainCLass->validiteToken($token)){
					return 'success';
				}else{
					return 22; # INVALIDE AUTHENTIFICATION
				}
			}else{return $usr_confirmed;}
		}

		if($userName == Null || $motPass == Null){ // ICHECK IF ONE FIELD IS EMPTY OR NOT
			return 11;
		}

		if(!empty($sugarpot) OR $sugarpot!=""){
			return 12;
		}

		if($cruizer){ // WE CONFIRM THE userName
			$UserAuthDtls = AuthController::UserAuthDtls($userName, $motPass);
			return $UserAuthDtls;
		}
	}

}
