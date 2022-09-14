<div class="relative bg-white shadow ">
  <div class="max-w-7xl mx-auto px-4 sm:px-4">
    <div class="flex justify-between items-center py-6 md:justify-start md:space-x-10">
      <div class="flex justify-start lg:w-0 lg:flex-1">
        <a href="javascript:;">
          <span class="sr-only">Countbook</span>
          <img class="mx-auto h-9 w-auto" src="<?= yii::$app->request->baseUrl . '/web/slimstok/assets/images/lg-svg.png' ?>" alt="Countbook">
        </a>
      </div>
      <div class="hidden md:flex items-center justify-end md:flex-1 lg:w-0">
        <a href="<?= yii::$app->request->baseurl.'/'.md5("login")?>" class="flex ml-5 items-center justify-center px-4 py-3 border border-black text-base font-medium rounded-md shadow-sm text-black bg-transparent bg-opacity-60 hover:bg-red-800 hover:text-white sm:px-8"> Retour connexion </a>
      </div>
    </div>
  </div>
</div>
<div class="bg-gray-900">
  <div class="pt-12 px-4 sm:px-6 lg:px-8 lg:pt-20">
    <div class="text-center">
      <h2 class="text-lg leading-6 font-semibold text-gray-300 uppercase tracking-wider">Nos tarifs</h2>
      <p class="mt-2 text-3xl font-extrabold text-white sm:text-4xl lg:text-5xl">Choisissez le forfait adap&#233; &#224; votre affaire </p>
    </div>
  </div>

  <div class="mt-16 bg-white pb-12 lg:mt-20 lg:pb-20">
    <div class="relative z-0">
      <div class="absolute inset-0 h-5/6 bg-gray-900 lg:h-2/3"></div>
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="relative lg:grid lg:grid-cols-7">
          <div class="mx-auto max-w-md lg:mx-0 lg:max-w-none lg:col-start-1 lg:col-end-3 lg:row-start-2 lg:row-end-3">
            <div class="h-full flex flex-col rounded-lg shadow-lg overflow-hidden lg:rounded-none lg:rounded-l-lg">
              <div class="flex-1 flex flex-col">
                <div class="bg-white px-6 py-10">
                  <div>
                    <h3 class="text-center text-2xl font-medium text-gray-900" id="tier-hobby">Essentiel</h3>
                    <div class="mt-4 flex items-center justify-center">
                      <span class="px-3 flex items-start text-3xl tracking-tight text-gray-900">
                        <span class="font-extrabold"> 900,000 GNF </span>
                      </span>
                      <span class="text-xl font-medium text-gray-500"> / an </span>
                    </div>
                  </div>
                </div>
                <div class="flex-1 flex flex-col justify-between border-t-2 border-gray-100 p-6 bg-gray-50 sm:p-10 lg:p-6 xl:p-10">
                  <ul role="list" class="space-y-4">
                    <li class="flex items-start">
                      <div class="flex-shrink-0">
                        <!-- Heroicon name: outline/check -->
                        <svg class="flex-shrink-0 h-6 w-6 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                      </div>
                      <p class="ml-3 text-base font-medium text-gray-500">Gestion de point de vente</p>
                    </li>

                    <li class="flex items-start">
                      <div class="flex-shrink-0">
                        <!-- Heroicon name: outline/check -->
                        <svg class="flex-shrink-0 h-6 w-6 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                      </div>
                      <p class="ml-3 text-base font-medium text-gray-500">Gestion de Stock</p>
                    </li>


                    <li class="flex items-start">
                      <div class="flex-shrink-0">
                        <!-- Heroicon name: outline/check -->
                        <svg class="flex-shrink-0 h-6 w-6 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                      </div>
                      <p class="ml-3 text-base font-medium text-gray-500">1 espace de gestion</p>
                    </li>

                    <li class="flex items-start">
                      <div class="flex-shrink-0">
                        <!-- Heroicon name: outline/check -->
                        <svg class="flex-shrink-0 h-6 w-6 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                      </div>
                      <p class="ml-3 text-base font-medium text-gray-500">3 Utilisateurs</p>
                    </li>

                  </ul>
                  <div class="mt-8">
                    <a href="<?= yii::$app->request->baseurl.'/'.md5('imvisitor_faireesessais').'/'.md5('essentiel')?>" class="flex ml-5 items-center justify-center px-4 py-3 border border-black text-base font-medium rounded-md shadow-sm text-white  bg-opacity-60 hover:bg-red-800 hover:text-white sm:px-12" style="background-color:#fc1919;"> Faites un essai </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="mt-10 max-w-lg mx-auto lg:mt-0 lg:max-w-none lg:mx-0 lg:col-start-3 lg:col-end-6 lg:row-start-1 lg:row-end-4">
            <div class="relative z-10 rounded-lg shadow-xl">
              <div class="pointer-events-none absolute inset-0 rounded-lg border-2 border-indigo-600" aria-hidden="true"></div>
              <div class="absolute inset-x-0 top-0 transform translate-y-px">
                <div class="flex justify-center transform -translate-y-1/2">
                  <span class="inline-flex rounded-full bg-indigo-600 px-4 py-1 text-sm font-semibold tracking-wider uppercase text-white"> Recommend&#233; aux PME </span>
                </div>
              </div>
              <div class="bg-white rounded-t-lg px-6 pt-12 pb-10">
                <div>
                  <h3 class="text-center text-3xl font-semibold text-gray-900 sm:-mx-6" id="tier-growth">Standard</h3>
                  <div class="mt-4 flex items-center justify-center">
                      <span class="px-3 flex items-start text-3xl tracking-tight text-gray-900">
                        <span class="font-extrabold"> 2,000,000 GNF </span>
                      </span>
                      <span class="text-xl font-medium text-gray-500"> / an </span>
                    </div>
                </div>
              </div>
              <div class="border-t-2 border-gray-100 rounded-b-lg pt-10 pb-8 px-6 bg-gray-50 sm:px-10 sm:py-10">
                <ul role="list" class="space-y-4">
                  	<li class="flex items-start">
                      <div class="flex-shrink-0">
                        <!-- Heroicon name: outline/check -->
                        <svg class="flex-shrink-0 h-6 w-6 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                      </div>
                      <p class="ml-3 text-base font-medium text-gray-500">Gestion de Trésorerie</p>
                    </li>

                    <li class="flex items-start">
                      <div class="flex-shrink-0">
                        <!-- Heroicon name: outline/check -->
                        <svg class="flex-shrink-0 h-6 w-6 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                      </div>
                      <p class="ml-3 text-base font-medium text-gray-500">Gestion de Stock</p>
                    </li>

                    <li class="flex items-start">
                      <div class="flex-shrink-0">
                        <!-- Heroicon name: outline/check -->
                        <svg class="flex-shrink-0 h-6 w-6 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                      </div>
                      <p class="ml-3 text-base font-medium text-gray-500">Facturation / Devis / Commande</p>
                    </li>


                    <li class="flex items-start">
                      <div class="flex-shrink-0">
                        <!-- Heroicon name: outline/check -->
                        <svg class="flex-shrink-0 h-6 w-6 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                      </div>
                      <p class="ml-3 text-base font-medium text-gray-500">1 espace de gestion</p>
                    </li>

                    <li class="flex items-start">
                      <div class="flex-shrink-0">
                        <!-- Heroicon name: outline/check -->
                        <svg class="flex-shrink-0 h-6 w-6 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                      </div>
                      <p class="ml-3 text-base font-medium text-gray-500">1e branche</p>
                    </li>

                    <li class="flex items-start">
                      <div class="flex-shrink-0">
                        <!-- Heroicon name: outline/check -->
                        <svg class="flex-shrink-0 h-6 w-6 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                      </div>
                      <p class="ml-3 text-base font-medium text-gray-500">7 Utilisateurs</p>
                    </li>

                  </ul>
                  <div class="mt-8">
                    <a href="#" class="flex ml-5 items-center justify-center px-4 py-3 border border-black text-base font-medium rounded-md shadow-sm text-white  bg-opacity-60 hover:bg-red-800 hover:text-white sm:px-12" style="background-color:#fc1919;"> Faites un essai </a>
                  </div>
              </div>
            </div>
          </div>
          <div class="mt-10 mx-auto max-w-md lg:m-0 lg:max-w-none lg:col-start-6 lg:col-end-8 lg:row-start-2 lg:row-end-3">
            <div class="h-full flex flex-col rounded-lg shadow-lg overflow-hidden lg:rounded-none lg:rounded-r-lg">
              <div class="flex-1 flex flex-col">
                <div class="bg-white px-6 py-10">
                  <div>
                    <h3 class="text-center text-2xl font-medium text-gray-900" id="tier-scale">Premium</h3>
                    <div class="mt-4 flex items-center justify-center">
                      <span class="px-3 flex items-start text-3xl tracking-tight text-gray-900">
                        <span class="font-extrabold"> 3,400,000 GNF </span>
                      </span>
                      <span class="text-xl font-medium text-gray-500"> / an </span>
                    </div>
                  </div>
                </div>
                <div class="flex-1 flex flex-col justify-between border-t-2 border-gray-100 p-6 bg-gray-50 sm:p-10 lg:p-6 xl:p-10">
                  <ul role="list" class="space-y-4">
                    <li class="flex items-start">
                      <div class="flex-shrink-0">
                        <!-- Heroicon name: outline/check -->
                        <svg class="flex-shrink-0 h-6 w-6 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                      </div>
                      <p class="ml-3 text-base font-medium text-gray-500">Gestion de Trésorerie</p>
                    </li>

                    <li class="flex items-start">
                      <div class="flex-shrink-0">
                        <!-- Heroicon name: outline/check -->
                        <svg class="flex-shrink-0 h-6 w-6 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                      </div>
                      <p class="ml-3 text-base font-medium text-gray-500">Gestion de Stock / branche</p>
                    </li>

                    <li class="flex items-start">
                      <div class="flex-shrink-0">
                        <!-- Heroicon name: outline/check -->
                        <svg class="flex-shrink-0 h-6 w-6 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                      </div>
                      <p class="ml-3 text-base font-medium text-gray-500">Facturation / Devis / Commande</p>
                    </li>


                    <li class="flex items-start">
                      <div class="flex-shrink-0">
                        <!-- Heroicon name: outline/check -->
                        <svg class="flex-shrink-0 h-6 w-6 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                      </div>
                      <p class="ml-3 text-base font-medium text-gray-500">1 espace de gestion</p>
                    </li>

                    <li class="flex items-start">
                      <div class="flex-shrink-0">
                        <!-- Heroicon name: outline/check -->
                        <svg class="flex-shrink-0 h-6 w-6 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                      </div>
                      <p class="ml-3 text-base font-medium text-gray-500">5 branches</p>
                    </li>

                    <li class="flex items-start">
                      <div class="flex-shrink-0">
                        <!-- Heroicon name: outline/check -->
                        <svg class="flex-shrink-0 h-6 w-6 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                      </div>
                      <p class="ml-3 text-base font-medium text-gray-500">17 Utilisateurs</p>
                    </li>
                  </ul>
                  <div class="mt-8">
                    <a href="#" class="flex ml-5 items-center justify-center px-4 py-3 border border-black text-base font-medium rounded-md shadow-sm text-white  bg-opacity-60 hover:bg-red-800 hover:text-white sm:px-12" style="background-color:#fc1919;"> Faites un essai </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>