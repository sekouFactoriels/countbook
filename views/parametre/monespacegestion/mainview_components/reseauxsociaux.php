<h5 class="subtitle"><?= yii::t('app','nos_cannaux_comm')?></h5>
<ul class="profile-social-list">
  <?php 
  if(isset($espace_gestion['reseaux_sociaux']) && $espace_gestion['reseaux_sociaux'] !="")
  { 
  ?>
    <li><i class="fa fa-twitter"></i> <a href="#">twitter.com/eileensideways</a></li>
    <li><i class="fa fa-facebook"></i> <a href="#">facebook.com/eileen</a></li>
    <li><i class="fa fa-youtube"></i> <a href="#">youtube.com/eileen22</a></li>
    <li><i class="fa fa-linkedin"></i> <a href="#">linkedin.com/4ever-eileen</a></li>
    <li><i class="fa fa-pinterest"></i> <a href="#">pinterest.com/eileen</a></li>
    <li><i class="fa fa-instagram"></i> <a href="#">instagram.com/eiside</a></li>
  <?php
  }
  ?>
</ul>