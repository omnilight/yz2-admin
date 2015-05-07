<?php
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var \yii\web\View $this
 * @var string $name
 * @var string $message
 * @var \yii\base\Exception $exception
 */
$this->title = $name;
$this->params['breadcrumbs'][] = $this->title;
$this->params['header'] = $this->title;

if ($exception instanceof \yii\web\HttpException) {
    $code = $exception->statusCode;
    $class = 'text-yellow';
} else {
    $code = '';
    $class = 'text-red';
}

?>
<div class="error-page">
    <h2 class="headline <?= $class ?>"><?= Html::encode($code) ?></h2>
    <div class="error-content">
        <h3><i class="fa fa-warning <?= $class ?>"></i> <?= Html::encode($this->title) ?></h3>
        <p>
            <?= nl2br(Html::encode($message)) ?>
        </p>
    </div>
</div>