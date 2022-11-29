<?php
/** @var yii\web\View $this */

$error = Yii::$app->session->getFlash();
?>
<div class="site-index">
    <div class="body-content pt-4">

        <?= $this->render('_form', ['title' => $title, 'action' => $action]) ?>

    </div>
</div>
