<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel app\models\tables\TaskSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Tasks';
$this->params['breadcrumbs'][] = $this->title;
?>



<div class="task-form">

<?php 

echo \yii\helpers\Html::beginForm('/Yii1_homework/Homework1/web/index.php?r=task/index', 'post');

echo \yii\helpers\Html::textInput('date', null, ['type' => 'month']);

echo \yii\helpers\Html::submitButton("Запросить", ['class' => 'btn btn-success']);

echo \yii\helpers\Html::endForm();


?>

</div>



<div class="task-index">

<table class="table table-hover" style="width:100%; max-width:1200px">
    
    <tr>
        <th class="text-center" style="width:150px">День</th>
        <th class="text-left">Задания</th>
        <th class="text-center" style="width:150px">Количество</th>
    </tr>
    
<?php foreach ($calendar as $day => $content): ?>

    <tr>
        <td class="text-center"><?= $day ?></td>
        <td class="text-left">
    <?php  foreach ($calendar[$day] as $key => $task):?>
            <p>
                <a href="/Yii1_homework/Homework1/web/index.php?r=task/single&id=<?= $calendar[$day][$key]->id ?>"><?= $calendar[$day][$key]->name ?></a>
                - <?= $calendar[$day][$key]->description ?>
            </p>
    <?php endforeach;?>
        </td>
        <td class="text-center"><?= count($calendar[$day]) ?></td>
    </tr>
    
    


<?php endforeach;?>
</table>
</div>
