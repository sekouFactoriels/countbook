<?php

return [
    /** SENSITIVE PARAMS **/
    'PBKDF2_HASH_ALGORITHM'=> "sha256",
    'PBKDF2_ITERATIONS'=> 1000,
    'PBKDF2_SALT_BYTE_SIZE'=> 24,
    'PBKDF2_HASH_BYTE_SIZE'=> 24,

    'HASH_SECTIONS'=> 4,
    'HASH_ALGORITHM_INDEX'=> 0,
    'HASH_ITERATION_INDEX'=> 1,
    'HASH_SALT_INDEX'=> 2,
    'HASH_PBKDF2_INDEX'=> 3,

    /** OTHER PARAMS **/
    'staff_id_prefix'=>'GN',
    'gen_key1_length'=>3,
    'gen_key2_length'=>2,
    'maxFileSize'=>'300', // 300 KB IS THE MAIMUM FILE SIZE
    'adminEmail' => 'admin@factoriels.com',

    'secret_word'=>['s0ft8ox'],
    'key_connector'=>'s0ft8ox',
    'word_separator'=>'@!#act@123@!#',
    'donneeRecherche'=>'donneeRecherche',

    'systemsname'=>'COUNTBOOK',
    'sysprovidername'=>'Factoriels',

    /** LIGNE DE MENU **/
    'defaultmenu'=>'repport,repport_ventelist,repport_inventlistarticle,repport_inventanalysearticle,repport_inventhistoriquearticle,repport_inventarticlealertstok,repport_articlestokinitial,repport_venteparproduit,repport_lignesdevente,repport_margeventeparproduit,repport_margenetventeparproduit,repport_fondroulement,repport_depensediver,repport_userdiver,repport_evenementdiver,repport_facture,parametre_users',

    'starterMenu'=>'site_index@parametre,parametre_entreprises,parametre_motifsenrgclient,parametre_campagnes',

    'ownerEssentiel'=>'repport,repport_ventelist,repport_inventlistarticle,repport_inventanalysearticle,repport_inventhistoriquearticle,repport_inventarticlealertstok,repport_articlestokinitial,repport_venteparproduit,repport_lignesdevente,repport_margeventeparproduit,repport_margenetventeparproduit,repport_fondroulement,repport_depensediver,repport_userdiver,repport_evenementdiver,repport_facture,parametre_users@site_index@site_signagreement@inventaire,inventaire_nproduit,inventaire_produits,inventaire_reaprovision,inventaire_udms,inventaire_cats@vente_simple@fournisseur_themain@paiement_themain@client_themain@diver,diver_charge@parametre,parametre_entreprises,parametre_motifsenrgclient,parametre_campagnes@rg,rg_invent,rg_vente,rg_diver',

    'menuStrgInitial'=>'repport,repport_ventelist,repport_inventlistarticle,repport_inventanalysearticle,repport_inventhistoriquearticle,repport_inventarticlealertstok,repport_articlestokinitial,repport_venteparproduit,repport_lignesdevente,repport_margeventeparproduit,repport_margenetventeparproduit,repport_fondroulement,repport_depensediver,repport_userdiver,repport_evenementdiver,repport_facture,parametre_users@site_index',
    'subMenu_inventaire'=>'@inventaire,inventaire_nproduit,inventaire_produit,inventaire_produits,inventaire_reaprovision,inventaire_udms,inventaire_cats',
    'subMenu_stok'=>'@stok,stok_ajustmentunitaire,stok_fournisseur@fournisseur_themain',
    'subMenu_vente'=>'@vente_simple@paiement_themain',
    'subMenu_client'=>'@client_themain',
    'subMenu_diver'=>'@diver,diver_charge',
    'subMenu_parametre'=>'@parametre,parametre_entreprises,parametre_newentreprise,parametre_listentreprise,parametre_motifsenrgclient,parametre_campagnes,parametre_comptebancaire',
    'submenu_rapport'=>'@rg',
    'submenu_rapport_inventaire'=>',rg_invent',
    'submenu_rapport_vente'=>',rg_vente',
    'submenu_rapport_diver'=>',rg_diver',
    'subMenu_bondcommand'=>'@bondcommand_themain',
    'subMenu_devis'=>'@devis_themain',
    'subMenu_reagistement'=>'@reajustement_themain',

        // SYSTEME VARIABLE
    'menuSeperator'=>'@',
    'subMenuSeperator'=>',',
    'translator_separator'=>'__',
    'simple_seperator'=>'_',

        // DEBUT AJAX DATA UDM
    'prixUnitaireAchat'=>'prixUnitaireAchat',
    'prixUnitaireVente'=>'prixUnitaireVente',
    'generiqueId'=>'generiqueId',
    'markId'=>'markId',
    'groupId'=>'groupId',
    'categoryId'=>'categoryId',
    'libelle'=>'libelle',
    'type'=>'type',
    'udmProduct'=>'udmProduct',
    'qteAjouter'=>'qteAjouter',
    'typeAjustation'=>'typeAjustation',
    'unitProduct'=>'unitProduct',
    'thisProductId'=>'thisProductId',
    'thisProductIdUdms'=>'thisProductIdUdms',

        //Allow controller
    'allowed_controller'=>['imvisitor'],

        // DEBUT AJAX DATA NOM GENERIQUE
    'generiqueNameId'=>'generiqueNameId',
    'productGenericName'=>'productGenericName',
    'productGenericDesc'=>'productGenericDesc',

        // USERS ALLOWED EDIT SALES
    'usersToEditSales'=>[9,8,7],

        // DEBUT AJAX DATA NEW MARQUE
    'idNewFabricant'=>'idNewFabricant',
    'nom'=>'nom',
    'description'=>'description',

        // FIN AJAX DATA NEW MARQUE
    'nomNouveauFabriquant'=>'nomNouveauFabriquant',
    'productMarqueDesc'=>'productMarqueDesc',
    'productMarqueName'=>'productMarqueName',
    'nomNouveauFabriquant'=>'nomNouveauFabriquant',
    'fabriquantId'=>'fabriquantId',
    'productGroupName'=>'productGroupName',
    'productGroupDesc'=>'productGroupDesc',
    'productCatDesc'=>'productCatDesc',
    'productCatName'=>'productCatName',
    'action_key'=>'action_key',
    'action_on_this'=>'action_on_this',
    'ajax_action_key'=>'ajax_action_key',
    'codeProduit'=>'codeProduit',
    'selectCriteria'=>'selectCriteria',
    'limit'=>'limit',

        // USER ACCESS LEVEL
    'entrepriseClt'=>11,
    'utilisateurSys'=>[1,2,3],

    #**************************************************
    # TOKEN DU FORMULAIRE DE POST & VARIABLE DE SESSION
    #**************************************************

        // SESSION ACTIVE DANS LE SYSTEME
    'postToken'=>'postToken',
    'tok2'=>'token2',
    'userSessionDtls'=>'slim_userSessionDtls',
    'activeEntiveUserEnFnct'=>'slim_activeEntiveUserEnFnct', # ID DE L'ACTIVE ENTITE
    'userActEntite'=>'slim_userActiveEntite',

        // SYSTEME MESSAGE CODE
    'emptyParamGiving'=>'1',
    'operationSucess'=>'2',
    'operationNoSucess'=>'3',
    'invalidParamGiving'=>'4',
    'codeValiderParAutreUser'=>'5',
    'justSessionSet'=>'6',
    'codeDjaUtilise'=>'7',
    'actionImconnu'=>'8',
    'codeNonValidPourEntite'=>'9',


        // DEBUT FIEDS FORMULAIRE : NOUVEAU PRODUIT
    'productPrixVente'=>'productPrixVente',
    'productPrixAchat'=>'productPrixAchat',
    'generiqueNameId'=>'generiqueNameId',
    'productMarque'=>'productMarque',
    'group'=>'group',
    'prodcutMinQtePV'=>'prodcutMinQtePV',
    'udm'=>'udm',
    'prodcutMinQteEntrep'=>'prodcutMinQteEntrep',
    'prodcutQte'=>'prodcutQte',
    'productCategory'=>'productCategory',
    'productName'=>'productName',
    'productType'=>'productType',
    'newVenteSimple'=>'newVenteSimple',
    'newVenteSimple'=>'newVenteSimple',
    'codevente'=>'codevente',
    'productCode'=>'productCode',
    'generiqueNameId'=>'generiqueNameId',
    'newCodeProduit'  =>'newCodeProduit',
    'imageProduct'  =>'logo',

    'csrf'=>'_csrf',
    'ServiceAjaxData'=>'ServiceAjaxData',
    'ajaxData'=>'ajaxData',
    'criterSelect'=>'criterSelect',
    'filtreSelection'=>'filtreSelection',
    'indexRecherche'=>'indexRecherche',

        // OTHER ELEMENT
    'hiddenaction'=>['repport','repport_inventlistarticle', 'site_signagreement','parametre_users','devis_themain','inventaire_produit','bondcommand_themain'],
    'usermedia'=>'web/medias/l0g0f1l3',
    'imgProd'=>'web/medias/imageProduct',
    '_logo'=>'logo',

];
