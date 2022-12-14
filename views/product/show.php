<body>
    <style>
    </style>
    <div class="container-lg mt-5">
        <div class="row h-100">
            <div class="col-lg-10">
                <div class="row mb-4">
                    <div class="col-lg-4">
                        <div class="card shadow h-100 p-4">
                            <img src="./public/img/product.jpg" class="card-img-top" alt="Product foto">
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="card shadow h-100">
                            <div class="card-header">
                                <h2>
                                    <?= $product['product_name'] ?>
                                </h2>
                            </div>
                            <div class="card-body d-flex align-items-start flex-column h-100">
                                <div class="mb-auto p-2">
                                    <p>
                                        <?= $product['description']?>
                                    </p>
                                </div>

                                <div class="p-2">
                                    &euro;
                                    <?= number_format($product['price'], 2, ',', '.') ?>
                                </div>

                                <div class="p-2">
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="input-group">
                                                <span role="button" class="input-group-text" onclick="inputMinus(this)">-</span>

                                                <input type="number" class="form-control text-center" value="1"
                                                    required>

                                                <span role="button" class="input-group-text" onclick="inputAdd(this)">+</span>
                                            </div>
                                        </div>
                                        <div class="col">

                                            <button class="btn btn-success">In winkelwagen</button>
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="card shadow h-100">
                            <div class="card-header">
                                <h4 class="text-center">Specificaties</h4>
                            </div>
                            <div class="card-body">
                                <ul>
                                    <li>Spec 1</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card shadow h-100">
                            <div class="card-header">
                                <h4 class="text-center">Voorraad informatie</h4>
                            </div>
                            <div class="card-body">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-2">
                <div class="card shadow h-100">
                    <div class="card-header">
                        <h4 class="text-center">Aanbevolen producten</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>