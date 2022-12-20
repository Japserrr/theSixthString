window.onload = () => {
    showNotification();
    prefillSearchField();
    generateProductList();
}

function showNotification() {
    if (typeof notification !== 'undefined') {
        let hexColor = '';
        let icon = '';
        switch (notification.type) {
            case 'success':
                hexColor = '#198754';
                icon = `
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#198754" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                        <path
                            d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                    </svg>
                `;
                break;
            case 'danger':
                hexColor = '#dc3545';
                icon = `
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#dc3545" class="bi bi-exclamation-circle-fill" viewBox="0 0 16 16">
                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4zm.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2z"/>
                    </svg>
                `;
                break;
        }

        document.getElementById('notification-container').innerHTML = `
            <div class='toast rounded show fade overflow-hidden bg-${notification.type} border-${notification.type}' role='alert' aria-live='assertive' aria-atomic='true'>
                <div class='toast-header rounded-0 border-0'>
                    ${icon}
                    <span class='ms-1 me-auto text-${notification.type}'>${notification.message}</span>
                    <button type='button' class='btn' data-bs-dismiss='toast' aria-label='Close'>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="${hexColor}" class="bi bi-x-lg" viewBox="0 0 16 16">
  <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8 2.146 2.854Z"/>
</svg>
                    </button>
                </div>
            </div>
        `;

        setTimeout(() => {
            document.getElementById('notification-container').innerHTML = '';
            window.location.href = './product-overview';
        }, 2500);
    }
}

function prefillSearchField() {
    if (typeof search !== 'undefined') {
        document.getElementById('search-field').value = search;
    }
}

function generateProductList() {
    const productListEL = document.getElementById('productList');
    if (products.length === 0) {
        productListEL.innerHTML = '<span>Geen producten gevonden.</span>';
        return;
    }

    let productList = '';
    products.forEach((product) => {
        let brandOptions = '';
        brands.forEach((brand) => {
            product.brandId === brand.id
                ? brandOptions += `<option selected value='${brand.id}'>${brand.brandName}</option>`
                : brandOptions += `<option value='${brand.id}'>${brand.brandName}</option>`;
        })


        let categoryOptions = '';
        categories.forEach((category) => {
            categoryOptions += `
                <div class="form-check">
                    <input id='category-checkbox-${category.id}' class="form-check-input" style='cursor:pointer;' type="checkbox" name='categoryIds[]' value="${category.id}">
                    <label class="form-check-label" for="category-checkbox-${category.id}" style='cursor:pointer;' >${category.categoryName}</label>
                </div>
            `;
        })

        productList += `
            <div class='row mb-3'>
                <div class='offset-1 col-10 p-0'>
                    <div id='accordion-${product.productId}' class="accordion overflow-hidden border rounded">
                        <div class="accordion-item border-0">
                            
                            <!-- Edit product header. -->
                            <div id='heading-${product.productId}' class="accordion-header shadow-sm bg-light d-flex align-items-center justify-content-between pe-4">
                                <div class="d-flex align-items-center">
                                    <img class='product-image' src='${product.imagePath ?? 'https://placehold.co/80x80?text=placeholder'}' alt='Productfoto' loading="lazy">
                                    <span class='h4 ms-4'>${product.productName}</span>
                                </div>
                                <div class="btn-group">
                                    <button class='btn btn-success border-light border-top-0 border-right-0 border-bottom-0' data-bs-toggle='collapse' data-bs-target='#collapse-${product.productId}'>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-fill" viewBox="0 0 16 16">
                                            <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z"/>
                                        </svg>
                                        Wijzigen
                                    </button>
                                    <button class='btn btn-success border-light border-top-0 border-right-0 border-bottom-0' type='button' onclick="deleteProduct(${product.productId}, '${product.productName}')">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                                            <path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z"/>
                                        </svg>
                                        Verwijderen
                                    </button>
                                </div>
                            </div>
                          
                            <!-- Edit product form. -->
                            <div id="collapse-${product.productId}" class="accordion-collapse collapse border-top" data-bs-parent='#accordion-${product.productId}'>
                                <div class="accordion-body p-4">
                                    <form action='./edit-product' method="post" enctype='multipart/form-data'>
                                        <div class='row mb-4'>
                                        
                                            <!-- Left side of the form. -->
                                            <div class='col-7'>
                                                <div class='form-group row mb-1'>
                                                    <div class='col-12'>
                                                        <input id='product-name-input' class='input form-control' type='text' name='productName' maxlength='255' value='${product.productName}' required>
                                                            <label class='form-label' for='product-name-input'>
                                                                Productnaam <span class='text-danger'>*</span>
                                                            </label>
                                                    </div>
                                                </div>
                                                <div class='form-group row mb-1'>
                                                    <div class='col-6'>
                                                        <select id="brand-input" class="input form-control" name="brandId" required>${brandOptions}</select>
                                                        <label class="form-label" for="brand-input">
                                                            Merk <span class='text-danger'>*</span>
                                                        </label>
                                                        <input id='quantity-input' class='input form-control' type='number' name='quantity' min='0' max='9999' value='${product.quantity}' required>
                                                        <label class='form-label' for='quantity-input'>
                                                            Aantal <span class='text-danger'>*</span>
                                                        </label>
                                                        <div class="input-group"> 
                                                            <span class="input-group-text bg-success border-success text-white">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-currency-euro" viewBox="0 0 16 16">
                                                                    <path d="M4 9.42h1.063C5.4 12.323 7.317 14 10.34 14c.622 0 1.167-.068 1.659-.185v-1.3c-.484.119-1.045.17-1.659.17-2.1 0-3.455-1.198-3.775-3.264h4.017v-.928H6.497v-.936c0-.11 0-.219.008-.329h4.078v-.927H6.618c.388-1.898 1.719-2.985 3.723-2.985.614 0 1.175.05 1.659.177V2.194A6.617 6.617 0 0 0 10.341 2c-2.928 0-4.82 1.569-5.244 4.3H4v.928h1.01v1.265H4v.928z"/>
                                                                </svg>
                                                            </span>
                                                            <input id='price-input' class='input form-control' type='number' name='price' min='0' max='99999.99' value='${parseFloat(product.price)}' required>
                                                        </div>
                                                        <label class='form-label' for='price-input'>
                                                            Prijs per stuk <span class='text-danger'>*</span>
                                                        </label
                                                        </div>
                                                    </div>
                                                    <div class='col-6 '>
                                                        <div class='input form-control' style='height:11.15rem; overflow: hidden; overflow-y: auto;'>
                                                            ${categoryOptions}
                                                        </div>
                                                        <label class="form-label" for="category-input">
                                                            Categorie
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class='form-group row mb-1'>
                                                    <div class='col-12'>
                                                        <textarea id='description-add-input' class='input description-add-input form-control' name='description'>${product.description}</textarea>
                                                        <label class='form-label' for='description-add-input'>Omschrijving</label>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- Right side of the form. -->
                                            <div class='col-5'>
                                                <div class='form-group row mb-1'>
                                                    <div class='col-12 position-relative'>
                                                        <div class='image-input-container border rounded overflow-hidden'>
                                                            <button class='remove-image-btn btn-close position-absolute' type='button' onclick='removeImage()'></button>
                                                            <img id='image-preview' class='w-100' src='${product.imagePath ?? 'https://placehold.co/300x300?text=placeholder'}' alt='Productfoto' loading="lazy">
                                                            <input id='image-input' class='image-input form-control' type='file' name='image' accept='image/*' onChange='previewImage()'>
                                                        </div>
                                                        <label class='form-label' for='image-input'>Afbeelding</label>
                                                    </div>
                                                </div>
                                                <div class='form-group row'>
                                                    <div class='col-12'>
                                                        <input id='video-input' class='input form-control' type='url' name='videoUrl' maxlength='255' value='${product.videoUrl}'>
                                                            <label class='form-label' for='video-input'>Video link</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-3">
                                                <span class="text-danger">* Verplicht</span>
                                            </div>
                                            <div class="offset-5 col-4">
                                                <button class="btn btn-success w-100" type="submit" data-bs-target="#add-brand-modal" data-bs-dismiss="modal" name='productId' value='${product.productId}'>Opslaan</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
    });
    productListEL.innerHTML = productList;
}

function toggleCollapseProduct(productId) {
    document.getElementById(`collapse-${productId}`).classList.toggle('show');
}



function previewImage(productFormId) {
    if (productFormId) {
        document.getElementById(`image-preview-${productFormId}`).src = URL.createObjectURL(document.getElementById(`image-input-${productFormId}`).files[0]);
    } else {
        if (document.getElementById('image-input').files[0]) {
            document.getElementById('image-preview').src = URL.createObjectURL(document.getElementById('image-input').files[0]);
        } else {
            document.getElementById('image-preview').src = 'https://placehold.co/300x300?text=placeholder';
        }
    }
}

function removeImage() {
    document.getElementById('image-preview').src = 'https://placehold.co/300x300?text=placeholder';
    document.getElementById('image-input').value = '';
}

function deleteProduct(productId, productName) {
    if(confirm('Weet je zeker dat je dit product wilt verwijderen?')) {
        window.location.href = `./delete-product?id=${productId}&name=${productName}`;
    }
}

function openAddProductForm() {
    let brandOptions = '';
    brands.forEach((brand) => {
        brandOptions += `<option value='${brand.id}'>${brand.brandName}</option>`;
    })

    let categoryOptions = "";
    categories.forEach((category) => {
        categoryOptions += `
            <div class="form-check">
                <input id='category-checkbox-${category.id}' class="form-check-input" style='cursor:pointer;' type="checkbox" name='categoryIds[]' value="${category.id}">
                <label class="form-check-label" for="category-checkbox-${category.id}" style='cursor:pointer;' >${category.categoryName}</label>
            </div>
        `;
    })

    document.getElementById('add-product-modal-form').innerHTML = `
        <form action='./add-product' method="post" enctype='multipart/form-data' onsubmit="closeModal('product')">
            <div class='row mb-4'>
                <!-- Left side of the form. -->
                <div class='col-7'>
                    <div class='form-group row mb-1'>
                        <div class='col-12'>
                            <input id='product-name-input' class='input form-control' type='text' name='productName' maxlength='255' required>
                                <label class='form-label' for='product-name-input'>
                                    Productnaam <span class='text-danger'>*</span>
                                </label>
                        </div>
                    </div>
                    <div class='form-group row mb-1'>
                        <div class='col-6'>
                            <select id="brand-input" class="input form-control" name="brandId" required>${brandOptions}</select>
                            <label class="form-label" for="brand-input">
                                Merk <span class='text-danger'>*</span>
                            </label>
                            <input id='quantity-input' class='input form-control' type='number' name='quantity' min='0' max='9999' required>
                            <label class='form-label' for='quantity-input'>
                                Aantal <span class='text-danger'>*</span>
                            </label>
                            <div class="input-group"> 
                                <span class="input-group-text bg-success border-success text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-currency-euro" viewBox="0 0 16 16">
                                        <path d="M4 9.42h1.063C5.4 12.323 7.317 14 10.34 14c.622 0 1.167-.068 1.659-.185v-1.3c-.484.119-1.045.17-1.659.17-2.1 0-3.455-1.198-3.775-3.264h4.017v-.928H6.497v-.936c0-.11 0-.219.008-.329h4.078v-.927H6.618c.388-1.898 1.719-2.985 3.723-2.985.614 0 1.175.05 1.659.177V2.194A6.617 6.617 0 0 0 10.341 2c-2.928 0-4.82 1.569-5.244 4.3H4v.928h1.01v1.265H4v.928z"/>
                                    </svg>
                                </span>
                                <input id='price-input' class='input form-control' type='number' name='price' min='0' max='99999.99' required>
                            </div>
                            <label class='form-label' for='price-input'>
                                Prijs per stuk <span class='text-danger'>*</span>
                            </label
                            </div>
                        </div>
                        <div class='col-6 '>
                            <div class='input form-control' style='height:11.15rem; overflow: hidden; overflow-y: auto;'>
                                ${categoryOptions}
                            </div>
                            <label class="form-label" for="category-input">
                                Categorie
                            </label>
                        </div>
                    </div>
                    <div class='form-group row mb-1'>
                        <div class='col-12'>
                            <textarea id='description-add-input' class='input description-add-input form-control' name='description'></textarea>
                            <label class='form-label' for='description-add-input'>Omschrijving</label>
                        </div>
                    </div>
                </div>
                <!-- Right side of the form. -->
                <div class='col-5'>
                    <div class='form-group row mb-1'>
                        <div class='col-12 position-relative'>
                            <div class='image-input-container border rounded overflow-hidden'>
                                <button class='remove-image-btn btn-close position-absolute' type='button' onclick='removeImage()'></button>
                                <img id='image-preview' class='w-100' src='https://placehold.co/300x300?text=placeholder' alt='Productfoto'>
                                <input id='image-input' class='image-input form-control' type='file' name='image' accept='image/*' onChange='previewImage()'>
                            </div>
                            <label class='form-label' for='image-input'>Afbeelding</label>
                        </div>
                    </div>
                    <div class='form-group row'>
                        <div class='col-12'>
                            <input id='video-input' class='input form-control' type='url' name='videoUrl' maxlength='255'>
                                <label class='form-label' for='video-input'>Video link</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-3">
                    <span class="text-danger">* Verplicht</span>
                </div>
                <div class="offset-5 col-4">
                    <button class="btn btn-success w-100" type="submit">Opslaan</button>
                </div>
            </div>
        </form>
    `;
}

function closeModal(modalType) {
    document.getElementById( `add-${modalType}-modal`).classList.toggle('show');
}


