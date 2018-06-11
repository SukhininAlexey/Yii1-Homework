<?php
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
?>
<div class="post">
    <h2><?= Html::encode($model->name) ?></h2>
    <p><strong>Дедлайн: </strong><?= $model->date ?></p>
    <p><strong>Описание действия: </strong><?= $model->description ?></p>
    <p><strong>Ответственный: </strong><?= $user->login ?></p>
</div>

