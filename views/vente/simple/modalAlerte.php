<div class="modal fade" id="modalAlert" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="myModalLabel"><?php echo Yii::t('app', 'alertRouge'); ?></h4>
				</div>
				<div class="modal-body">
					<p id='contenuModalAlert'>La quantit&#233; demand&#233;e est superieur &#224; c&#39;elle disponible !</p>
				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-circle btn-warning" data-dismiss="modal"><?= Yii::t('app', 'ok'); ?></button>
				</div>
			</div>
		</div>
	</div>