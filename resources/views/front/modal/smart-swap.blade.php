<!-- Modal -->
 <!-- Meal ItemsModal -->
    <div class="modal" id="mealItemModel" tabindex="-1" aria-labelledby="mealItemsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="mealItemsModalLabel">Title</h5>
                    <button type="button" class="btn-close meal-item-modal-close" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="mealItemsLoadingSpinner" class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>

                    <!-- Subcategories Content -->
                    <div id="mealItemsContainer" class="row g-4" style="display: none;"></div>
                    
                </div>
            </div>
        </div>
    </div>

{{-- <div class="modal" id="smartSwapModal" tabindex="-1" aria-labelledby="smartSwapModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title swap-title" id="smartSwapModalLabel">Swap: Cavendish Bananas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="swap-list">

                    <div class="swap-item" style="border-bottom: none">
                        <img
                        src="{{ frontAssets('images/dialog/food.png') }}"
                        alt="Cavendish Bananas"
                        class="swap-item-img"
                        />
                        <div class="swap-item-info">
                        <div class="swap-item-name"></div>
                        <div class="swap-item-qty"><b>Qty :</b> </div>
                        </div>
                        <div class="swap-item-actions">
                        
                        <button class="smart-swap-btn">
                            <img
                            src="{{ frontAssets('images/dialog/Info.svg') }}"
                            alt="Snap"
                            style="width: 18px; vertical-align: middle"
                            />
                        </button>
                        </div>
                    </div>

                    <div class="swap-item">
                        <h3>Swap with</h3>
                    </div>

                    <!-- Swap Item 2 -->
                    <div class="swap-item">
                        <img
                            src="{{ frontAssets('images/dialog/food.png') }}"
                            alt="Cavendish Bananas"
                            class="swap-item-img" />
                        <div class="swap-item-info">
                            <div class="swap-item-name"></div>
                            <div class="swap-item-qty"><b>Qty :</b> </div>
                        </div>
                        <div class="swap-item-actions">
                            <button class="smart-swap-btn">
                                <img
                                    src="{{ frontAssets('images/dialog/swap.svg') }}"
                                    alt="Snap"
                                    style="
                        width: 18px;
                        vertical-align: middle;
                        margin-right: 4px;
                      " />Swap
                            </button>
                            <button class="smart-swap-btn">
                                <img
                                    src="{{ frontAssets('images/dialog/Info.svg') }}"
                                    alt="Snap"
                                    style="width: 18px; vertical-align: middle" />
                            </button>
                        </div>
                    </div>
                    <!-- Repeat swap-item block for more items... -->

                </div>
            </div>
            <div class="modal-footer">
                <button
                    class="btn btn-primary"
                    style="border-radius: 6px; height: 46px"
                >
                    Apply Changes
                </button>
            </div>
        </div>
    </div>
</div> --}}