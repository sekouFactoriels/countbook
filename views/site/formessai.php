 <!-- This example requires Tailwind CSS v2.0+ -->
 <!-- Be sure to use this with a layout container that is full-width on mobile -->

 <?php include('script/demande_js.php');?>
 
 <div class="flex justify-center" style="background-color: #dde0e4;">
   <div class="bg-white sm:w-3/6 shadow sm:rounded-lg mb-20 mt-40">
     <div class="px-4 py-5 sm:p-6">

       <div class="sm:mx-auto sm:w-full sm:max-w-md">
         <img class="mx-auto h-11 w-auto" src="<?= yii::$app->request->baseUrl . '/web/slimstok/assets/images/lg-svg.png' ?>" alt="Workflow">
       </div>


       <div class="relative mt-5">
         <div class="absolute inset-0 flex items-center" aria-hidden="true">
           <div class="w-full border-t border-gray-300"></div>
         </div>
         <div class="relative flex justify-center">
           <span class="px-2 bg-white text-rose-900 uppercase bold text-2xl"> Demande d'inscription </span>
         </div>
       </div>

       <!-- information generale -->
       <div class="m-5" id="partie1">

         <div class="mt-5">
           <h3 class="text-rose-900 uppercase bold text-lg">INFORMATION PERSONNELLE</h3>
         </div>
         <div class="">
           <div class="pt-2">
             <label for="userName" class="block text-bg font-medium text-gray-700"> Nom Complet <span class="text-red-600">*</span></label>
             <div class="mt-1">
               <input id="non" name="nom" value="" type="text" placeholder="Nom et Prénom" autocomplete="off" class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
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
         </div>

         <div class=" mt-4">
           <div class="text-right">
             <button type="button" onclick="cont1()" class="mt-2 uppercase inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-full shadow-sm text-white bg-pink-900 hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Continuer >></button>
           </div>

         </div>

       </div>

       <!-- information de la societe -->
       <div class="m-5" id="partie2" style="display: none;">

         <div class="mt-5">
           <h3 class="text-rose-900 uppercase bold text-lg">Votre société</h3>
         </div>
         <div class="">
           <div class="pt-2">
             <label for="userName" class="block text-bg font-medium text-gray-700"> Nom de la société <span class="text-red-600">*</span></label>
             <div class="mt-1">
               <input id="non" name="nom" value="" type="text" placeholder="Email ou numero de telephone" autocomplete="off" class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
             </div>
           </div>

           <div>
             <label for="motPass" class="block text-bg font-medium text-gray-700"> Adresse <span class="text-red-600">*</span></label>
             <div class="mt-1">
               <input id="tel" name="tel" type="text" value="" placeholder="Téléphone" autocomplete="off" class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
             </div>
           </div>

           <div>
             <label for="sugarpot" class="block text-bg font-medium text-gray-700"> Ville<span class="text-red-600">*</span></label>
             <div class="mt-1">
               <input id="email" name="email" type="email" value="" autocomplete="off" class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="exemple@gmail.com" required>
             </div>
           </div>
         </div>


         <div class="grid grid-cols-2 gap-2 mt-4">

           <div class="text-left">
             <button type="button" onclick="prec1()" class="mt-2 uppercase inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-full shadow-sm text-white bg-gray-400 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
               << Précédent</button>
           </div>

           <div class="text-right">
             <button type="button" onclick="cont2()" class="mt-2 uppercase inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-full shadow-sm text-white bg-pink-900 hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Continuer >></button>
           </div>

         </div>


       </div>

       <!-- information de la societe -->
       <div class="m-5" id="partie3" style="display: none;">

         <div class="mt-5">
           <h3 class="text-rose-900 uppercase bold text-lg">finalisation</h3>
         </div>
         <div class="">
           <div class="pt-2">
             <label for="userName" class="block text-bg font-medium text-gray-700"> Activité <span class="text-red-600">*</span></label>
             <div class="mt-1">
               <input id="non" name="nom" value="" type="text" placeholder="Email ou numero de telephone" autocomplete="off" class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
             </div>
           </div>

           <div>
             <label for="motPass" class="block text-bg font-medium text-gray-700"> Licence <span class="text-red-600">*</span></label>
             <div class="mt-1">
               <input id="tel" name="tel" type="text" value="" placeholder="Téléphone" autocomplete="off" class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
             </div>
           </div>

         </div>


         <div class="grid grid-cols-2 gap-2 mt-4">

           <div class="text-left">
             <button type="button" onclick="prec2()" class="mt-2 uppercase inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-full shadow-sm text-white bg-gray-400 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
               << Précédent</button>
           </div>

           <div class="text-right">
             <button type="button" class="mt-2 uppercase inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-full shadow-sm text-white bg-pink-900 hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Terminé</button>
           </div>

         </div>


       </div>
     </div>
   </div>
 </div>
