<?php

$params = require(__DIR__ . '/params.php');

$config = [
  'id' => 'basic',
  'basePath' => dirname(__DIR__),
  'bootstrap' => ['log'],
  'language' => $_SESSION['lang'],
  'layout' => 'dash_layout',
  'components' => [
    // BEGIN SOME OF OUR CUSTOMIZED || OUR OWN CLASS
    // THE ARRAY ELEMENT : ''CLASS'' MUST BE IN SMALLEST LETTER

    'fileuploadClass' => [
      'class' => 'app\components\fileuploadClass', // CLass pour les fichiers
    ],

    'paiementClass' => [
      'class' => 'app\components\paiementClass',
    ],

    'fournisseurClass' => [
      'class' => 'app\components\fournisseurClass',
    ],

    'clientClass' => [
      'class' => 'app\components\clientClass',
    ],

    'devisClass' => [
      'class' => 'app\components\devisClass',
    ],

    'diverClass' => [
      'class' => 'app\components\diverClass',
    ],

    'parametreClass' => [
      'class' => 'app\components\parametreClass',
    ],
    # CLASSE DE L'AJUSTEMENT
    'stokClass' => [
      'class' => 'app\components\stokClass',
    ],
    #CLASSE DE VENTE Aliou
    'venteClass' => [
      'class' => 'app\components\venteClass',
    ],
    #CLASSE DE PARAMETRE
    'parametreClass' => ['class' => 'app\components\parametreClass'],
    # CLASSE DES INVENTAIRES
    'inventaireClass' => [
      'class' => 'app\components\inventaireClass',
    ],
    'cryptoClass' => [
      'class' => 'app\components\cryptoClass', // CLASS FOR PASSWORD ENCRYPTION
    ],

    'leftMenuCLass' => [
      'class' => 'app\components\leftMenuCLass',
    ],

    'mainCLass' => [
      'class' => 'app\components\mainClass',
    ],

    'nonSqlClass' => [
      'class' => 'app\components\nonSqlClass',
    ],

    'accessClass' => [
      'class' => 'app\components\accessClass',
    ],

    // END HERE
    'request' => [
      // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
      'cookieValidationKey' => '&*()@A^)!@#$%^&*(%^&*)(*^%$_SDF$%^&%^',
    ],
    'cache' => [
      'class' => 'yii\caching\FileCache',
    ],

    'user' => [
      'identityClass' => 'app\models\User',
      'enableAutoLogin' => false,
    ],

    'errorHandler' => [
      'errorAction' => 'site/error',
    ],

    'mailer' => [
      'class' => 'yii\swiftmailer\Mailer',
      'transport' => [
        'class' => 'Swift_SmtpTransport',
        'host' => 'smtp-api.infobip.com',
        'username' => 'Sale@factoriels.com',
        'password' => 'Espace@123',
        'port' => '587',
        'encryption' => 'STARTTLS',
      ],
      // send all mails to a file by default. You have to set
      // 'useFileTransport' to false and configure a transport
      // for the mailer to send real emails.
      'useFileTransport' => false,
    ],
    'log' => [
      'traceLevel' => YII_DEBUG ? 3 : 0,
      'targets' => [
        [
          'class' => 'yii\log\FileTarget',
          'levels' => ['error', 'warning'],
        ],
      ],
    ],

    'db' => require(__DIR__ . '/db.php'),

    'urlManager' => [
      'enablePrettyUrl' => true,
      'showScriptName' => false,
      'rules' => [

        //Im visitor
        md5('imvisitor_tarifs')=>'imvisitor/tarifs',


        //Bon de command
        md5('bondcommand_themain') => 'bondcommand/themain',

        //Devis
        md5('devis_themain') => 'devis/themain',

        // reagistement
        md5('reajustement_themain') => 'reajustement/themain',

        //Paiement
        md5('paiement_themain') => 'paiement/themain',

        //Fournisseur
        md5('fournisseur_themain') => 'fournisseur/themain',

        // Client
        md5('client_themain') => 'client/themain',
        #Diver
        md5('diver_charge') => 'diver/charge',

        # Rapport
        md5('rg_diver') => 'rg/diver',
        md5('rg_vente') => 'rg/vente',
        md5('rg_invent') => 'rg/invent',

        # Vente
        md5('vente_client') => 'vente/client',
        md5('vente_simple') => 'vente/simple',

        # Parametre
        md5('parametre_users') => 'parametre/users',
        md5('parametre_entreprises') => 'parametre/entreprises',
        md5('parametre_newentreprise') => 'parametre/newentreprise',
        md5('parametre_listentreprise') => 'parametre/listentreprise',
        md5('parametre_comptebancaire') => 'parametre/banque',
        md5('parametre_campagnes') => 'parametre/campagnes',
        md5('parametre_user') => 'parametre/user',
        md5('parametre_entite') => 'parametre/entite',
        md5('parametre_motifsenrgclient') => 'parametre/motifsenrgclient',

        # Stok Controller
        md5('stok_ajustmentunitaire') => 'stok/ajustmentunitaire',
        md5('stok_ajustmentmasse') => 'stok/ajustmentmasse',
        md5('stok_achat') => 'stok/achat',

        # FOURNISSUR
        md5('stok_fournisseur') => 'stok/fournisseur',

        # INVENTAIRE CONTROLLER
        md5('inventaire_reaprovision') => 'inventaire/reaprovision',
        md5('inventaire_nproduit') => 'inventaire/nproduit',
        md5('inventaire_produit') => 'inventaire/produit',
        md5('inventaire_produits') => 'inventaire/produits',

        # UVA
        md5('inventaire_udms') => 'inventaire/udms',
        # CATEGORY
        md5('inventaire_cats') => 'inventaire/cats',
        # PRODUCT GROUP
        md5('inventaire_groups') => 'inventaire/groups',
        # PRODUCT MARQUES
        md5('inventaire_marques') => 'inventaire/marques',
        # PRODUCT GENERIC NAME
        md5('inventaire_genericname') => 'inventaire/genericname',
        # MAIN CONTROLLER

          //Premier conrolleur
        md5('site_index') => 'site/index',
        md5('index') => 'site/index',

          //Controlleur de deconnexion
        md5('deconnexion') => 'site/login',
        md5('login') => 'site/login',
        '' => 'site/index',

          //Condition d'utilisation générale
        md5('site_signagreement')=>'site/signagreement',
        md5('site_email')=>'site/email', 

        md5('imvisitor_faireesessais')=>'imvisitor/tarifs',
        md5('imvisitor_faireesessais').'/<forfait:\w+>'=>'imvisitor/faireesessais',       
       
        # DEFAULT RULES
        //'repport/analyseinventaire/<data:\d+>' => 'repport/analyseinventaire',
        
        '<controller:\w+>/<id:\d+>' => '<controller>/view',
        '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
        '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
      ],
    ],
  ],
  'params' => $params,
];

if (YII_ENV_DEV) {
  # configuration adjustments for 'dev' environment

  $config['modules']['debug'] = [
    'class' => 'yii\debug\Module',
  ];

  $config['bootstrap'][] = 'debug'; # WE USE THIS FOR THE DEBUG
  //$config['bootstrap'][] = 'gii';

  $config['modules']['gii'] = [
    'class' => 'yii\gii\Module',
  ];
}

return $config;
