<?php

/** @var yii\web\View $this */

$this->title = 'Terlmékkezelő API próbafeladat';

?>
<div class="site-index">

    <div class="jumbotron text-center bg-transparent">
        <h1 class="display-4">Terméklista</h1>
    </div>

    <?php if ($message) { ?>

        <div class="row alert alert-info" onclick="messageClick(this)">
            <?= $message ?>
        </div>

    <?php } ?>

    <div class="body-content pt-4">
        <div class="row pt-4 pb-4">
            <a href="/site/create" class="btn btn-primary w-25"><i class="fa fa-plus"></i> Új termék</a>
        </div>
        <div class="row">
            <form action="site/view" id="productForm" >
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Terméknév</th>
                            <th>Leírás</th>
                            <th>Ár</th>
                            <th>Művelet</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        if (count($products) > 0) {
                            foreach ($products as $prod) {
                    ?>
                        <tr>
                            <td><?= $prod['id'] ?></td>
                            <td><?= $prod['name'] ?></td>
                            <td><?= $prod['description'] ?></td>
                            <td><?= $prod['price'] ?></td>
                            <td>
                                <button class="btn btn-success" title="Szerkesztés" id="edit" value="<?= $prod['id'] ?>" onclick="updateProduct(this)"><i class="fa fa-edit"></i></button>
                                <button class="btn btn-danger" title="Törlés" id="delete" value="<?= $prod['id'] ?>" onclick="deleteProduct(this)"><i class="fa fa-trash"></i></button>
                            </td>
                        </tr>
                    <?php
                            }
                        } else {
                    ?>
                    <tr class="text-center table">
                        <td colspan="5">Jelenleg nincs elérhető termék!</td>
                    </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </form>
        <?php  ?>
        </div>
    </div>
    <script type="text/javascript">
        function updateProduct(element) {
            let val = $(element).val();
            $('#productForm').attr('method', 'GET').attr('action', 'site/edit/' + val).submit();
        }

        function deleteProduct(element) {
            let val = $(element).val();
            $('#productForm').attr('method', 'GET').attr('action', 'site/delete/' + val).submit();
        }

        function messageClick(element) {
            $(element).hide();
        }
    </script>
</div>
