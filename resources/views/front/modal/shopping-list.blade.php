<div class="modal" id="shoppingListModal" tabindex="-1" aria-labelledby="shoppingListModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="shoppingListModalLabel">Shopping list</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="padding-bottom: 0;">
                <div class="mb-3">
                    <input class="form-check-input" type="checkbox" id="selectAllShoppingList">
                    <label class="ms-2 form-check-label" for="selectAllShoppingList">Select All</label>
                </div>
                <div class="mb-2" style="font-weight: bold; font-size: 1.2rem;">Savoury Breakfast</div>
                <div class="mb-3 card">
                    <div class="p-3 card-body">
                        <div class="mb-2" style="font-weight: 600; font-size: 1.1rem;">
                            <input class="me-2 form-check-input" type="checkbox" id="eggAvoEnergyToast">
                            <label class="form-check-label" for="eggAvoEnergyToast">Egg & Avo Energy Toast</label>
                        </div>
                        <ul class="mb-0 list-unstyled">
                            <li class="d-flex align-items-center mb-3">
                                <input class="me-3 form-check-input" type="checkbox" id="item-spinach">
                                <img src="https://via.placeholder.com/40x40?text=Img" alt="Baby Leaf Spinach" class="me-3" style="width:50px;height:50px;object-fit:cover;border-radius:4px;background-color:#f1f1f1;">
                                <div>
                                    <span style="font-weight: 600; color: #4b5c6b;">Baby Leaf Spinach</span><br>
                                    <span style="font-size: 0.97rem;"><b>QTY:</b> 13g or ½ handful</span>
                                </div>
                            </li>

                        </ul>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary btn-blue" id="print-shopping-list">Print Shopping List Now</button>
            </div>
        </div>
    </div>
</div>  