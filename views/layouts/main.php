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
<?php $this->beginContent('@yz/admin/views/layouts/base.php'); ?>
<?php
NavBar::begin([
	'brandLabel' => Yii::t('yz/admin', 'Administration panel'),
	'brandUrl' => Yii::$app->homeUrl,
	'options' => [
		'class' => 'navbar-default navbar-fixed-top',
	]
]);

echo Nav::widget([
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
]);

NavBar::end();
?>
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-2">
				<?= \yz\admin\widgets\MainMenu::widget(); ?>
			</div>
			<div class="col-md-8">

				<?= \yii\widgets\Breadcrumbs::widget([
					'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
				]); ?>

				<?= $content; ?>
			</div>
		</div>
	</div>
<?php $this->endContent(); ?>