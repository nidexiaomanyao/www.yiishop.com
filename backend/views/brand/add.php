<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\JsExpression;
use xj\uploadify\Uploadify;
?>
<div class="brand-add">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name') ?>
    <?= $form->field($model, 'sort') ?>
    <?= $form->field($model, 'status')->radioList(\backend\models\Brand::$status) ?>
    <?= $form->field($model,'logo')->widget(\manks\FileInput::className(),[]);?>
    <?= $form->field($model, 'intro') ?>

    <div class="form-group">
        <?= Html::submitButton('提交', ['class' => 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>