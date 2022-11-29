<?php
/** @var yii\web\View $this */

?>
<div class="site-index">
    <div class="body-content pt-4">

        <?= $this->render('_form', ['title' => $title, 'action' => $action, 'product' => $product]) ?>

    </div>
    <script>
        $(function(){
            $('#productForm').attr('method', 'PUT');
        })
    </script>
</div>
