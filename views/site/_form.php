<div class="site-index">

    <div class="jumbotron text-center bg-transparent">
        <h1 class="display-4"><?= $title ?></h1>
    </div>

    <div class="body-content pt-4">
        <div class="card">
            <div class="card-body m-3">
                <form action="<?= $action ?>" id="productForm" method="post">
                    <div class="form-group pt-3">
                        <label for="product-name" class="form-label">Termék neve:</label>
                        <input type="text" class="form-control" id="product-name" name="name" value="<?= $product['name'] ?? '' ?>">
                    </div>
                    <div class="form-group pt-3">
                        <label for="product-description" class="form-label">Termék leírása:</label>
                        <input type="text" class="form-control" id="product-description" name="description"  value="<?= $product['description'] ?? '' ?>">
                    </div>
                    <div class="form-group pt-3">
                        <label for="product-price" class="form-label">Termék ára:</label>
                        <input type="text" class="form-control" id="product-price" name="price"  value="<?= $product['price'] ?? '' ?>">
                    </div>
                    <div class="row w-25 pt-3">
                        <input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>" />
                        <button type="submit" class="btn btn-primary" value="0"><i class="fa fa-save"></i> Mentés</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
