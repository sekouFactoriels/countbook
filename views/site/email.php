<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

$this->title = Yii::t('app', 'connexion');

// $msg = (!empty($msg['type'])) ? $msg : null;
// $userName = !empty($userName) ? $userName : null;
// $motPass = !empty($motPass) ? $motPass : null;
// $sugarpot = !empty($sugarpot) ? $sugarpot : null;
?>

<body class="signin md:pt-12" style="background-color: #dde0e4;">

  <!-- Preloader -->
  <div id="preloader">
    <div id="status"><i class="fa fa-spinner fa-spin"></i></div>
  </div>

  <div class="min-h-full flex flex-col justify-center py-2 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md hidden">
      <img class="mx-auto h-12 w-auto" src="<?= yii::$app->request->baseUrl . '/web/slimstok/assets/images/lg-svg.svg' ?>" alt="Workflow">
    </div>

    <div class="mt-0 sm:mx-auto sm:w-full sm:max-w-md">
      <div class="bg-white py-4 px-4 shadow sm:rounded-lg sm:px-10">
        <?php
        $form = ActiveForm::begin(
          [
            'id' => 'email-form',
            'options' => [
              'name' => 'email-form',
              'class' => 'space-y-6'
            ],
          ]
        );
        ?>

        <input type="hidden" name="action_key" id="action_key" value=""/>

        <div class="sm:mx-auto sm:w-full sm:max-w-md">
          <img class="mx-auto h-8 w-auto" src="<?= yii::$app->request->baseUrl . '/web/slimstok/assets/images/lg-svg.jpg' ?>" alt="Workflow">
        </div>


        <div class="pt-2">
          <label for="userName" class="block text-bg font-medium text-gray-700"> Nom Complet <span class="text-red-600">*</span></label>
          <div class="mt-1">
            <input id="non" name="nom" value="" type="text" placeholder="Email ou numero de telephone" autocomplete="off" class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
          </div>
        </div>

        <div>
          <label for="sugarpot" class="block text-bg font-medium text-gray-700">Société</label>
          <div class="mt-1">
            <input id="nomEntreprise" name="nomEntreprise" type="text" value="" autocomplete="off" class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Denomination de l'entreprise">
          </div>
        </div>

        <div>
          <label for="motPass" class="block text-bg font-medium text-gray-700"> Téléphone <span class="text-red-600">*</span></label>
          <div class="mt-1">
            <input id="tel" name="tel" type="text" value="" placeholder="Téléphone" autocomplete="off" class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
          </div>
        </div>

        <div>
          <label for="sugarpot" class="block text-bg font-medium text-gray-700"> Email<span class="text-red-600">*</span></label>
          <div class="mt-1">
            <input id="email" name="email" type="email" value="" autocomplete="off" class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="exemple@gmail.com" required>
          </div>
        </div>



        <div>
          <label for="sugarpot" class="block text-bg font-medium text-gray-700"> Poste Occupé<span class="text-red-600">*</span></label>
          <div class="mt-1">
            <input id="post" name="post" type="text" value="" autocomplete="off" class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
          </div>
        </div>

        <div>
          <button type="submit" style="background-color:#35679a;" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-bg font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" onclick="$('#action_key').val(1);">Envoyer</button>

        </div>
        
        <?php ActiveForm::end(); ?>

      </div>
    </div>
  </div>



  <!-- This example requires Tailwind CSS v2.0+ -->
  <!-- <div class="bg-white">
    <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:py-16 lg:px-8">

      <h3 class="text-center text-2xl tracking-tight font-extrabold text-gray-900 sm:text-5xl md:text-2xl">
        <span class="block xl:inline">Ils accompagnent les entrepreneurs qui utilisent</span>
        <span class="block text-blue-600 xl:inline">countbook</span>
      </h3>

      <div class="mt-6 grid grid-cols-2 gap-0.5 md:grid-cols-3 lg:mt-8">
        <div class="col-span-1 flex justify-center py-8 px-8 bg-gray-50">
          <img class="max-h-18" src="<?= yii::$app->request->baseUrl . '/web/medias/prescripteur/logo/big-svg.svg' ?>" alt="Workcation">
        </div>
        <div class="col-span-1 flex justify-center py-8 px-8 bg-gray-50">
          <img class="max-h-18" src="<?= yii::$app->request->baseUrl . '/web/medias/prescripteur/logo/big-svg.svg' ?>" alt="Workcation">
        </div>
        <div class="col-span-1 flex justify-center py-8 px-8 bg-gray-50">
          <img class="max-h-18" src="<?= yii::$app->request->baseUrl . '/web/medias/prescripteur/logo/big-svg.svg' ?>" alt="Workcation">
        </div>
      </div>
    </div>
  </div> -->

  <!-- This example requires Tailwind CSS v2.0+ -->
  <!-- <div class="relative bg-gray-800">
    <div class="h-56 bg-indigo-600 sm:h-72 md:absolute md:left-0 md:h-full md:w-1/2">
      <img class="w-full h-full object-cover" src="https://images.unsplash.com/photo-1525130413817-d45c1d127c42?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1920&q=60&blend=6366F1&sat=-100&blend-mode=multiply" alt="">
    </div>


    <div class="relative max-w-7xl mx-auto px-4 py-12 sm:px-6 lg:px-8 lg:py-16">
      <div class="md:ml-auto md:w-1/2 md:pl-10">
        <h2 class="text-base font-semibold uppercase tracking-wider text-gray-300">Award winning support</h2>
        <p class="mt-2 text-white text-3xl font-extrabold tracking-tight sm:text-4xl">We�re here to help</p>
        <p class="mt-3 text-lg text-gray-300">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Et, egestas tempus tellus etiam sed. Quam a scelerisque amet ullamcorper eu enim et fermentum, augue. Aliquet amet volutpat quisque ut interdum tincidunt duis.</p>
        <div class="mt-8">
          <div class="inline-flex rounded-md shadow">
            <a href="#" class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-gray-900 bg-white hover:bg-gray-50">
              Visit the help center -->
              <!-- Heroicon name: solid/external-link -->
              <!-- <svg class="-mr-1 ml-3 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path d="M11 3a1 1 0 100 2h2.586l-6.293 6.293a1 1 0 101.414 1.414L15 6.414V9a1 1 0 102 0V4a1 1 0 00-1-1h-5z" />
                <path d="M5 5a2 2 0 00-2 2v8a2 2 0 002 2h8a2 2 0 002-2v-3a1 1 0 10-2 0v3H5V7h3a1 1 0 000-2H5z" />
              </svg>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div> -->