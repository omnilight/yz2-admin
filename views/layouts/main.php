<?php
use yii\helpers\Html;
use yii\bootstrap\NavBar;
use yii\bootstrap\Nav;
use yz\icons\Icons;

/**
 * @var \yii\base\View $this
 * @var string $content
 */
?>
<?php $this->beginContent('@yz/admin/views/backend/layouts/base.php'); ?>
	<!-- Top bar-->
	<nav class="navbar navbar-fixed-top b-top">
		<div class="container-fluid">
			<div class="navbar-header">
				<a class="navbar-brand"
				   href="<?= Yii::$app->homeUrl ?>"><?= Yii::t('backend', 'Administration panel') ?></a>
			</div>

			<?php echo Nav::widget([
				'options' => ['class' => 'navbar-nav navbar-right'],
				'encodeLabels' => false,
				'items' => [
					[
						'label' => Icons::p('user fa-fw') . Html::encode(Yii::$app->user->identity->name),
						'items' => [
							['label' => Icons::p('user fa-fw') . Yii::t('yz/admin', 'Your profile'), 'url' => ['/admin/users/profile']],
							['label' => Icons::p('power-off fa-fw') . Yii::t('yz/admin', 'Logout'), 'url' => ['/admin/main/logout']],
						]
					]
				]
			]); ?>
		</div>
	</nav>

<?= \yz\admin\widgets\MainMenu::widget(); ?>

<?php echo \yii\widgets\Breadcrumbs::widget([
	'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
]); ?>

	<div class="container-fluid b-content">
		<div class="col-md-12">

			<?= \yz\admin\widgets\Flashes::widget(); ?>

			<?= $content; ?>
		</div>
	</div>
<?php $this->endContent(); ?>