<!-- Modal -->
<!-- Meal ItemsModal -->
{{-- 
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
--}}

<div class="modal" id="mealItemModel" tabindex="-1" aria-labelledby="mealItemsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        
        <div class="p-0 modal-body">
          <!-- Restore original modal content below -->
          <div class="recipe-dialog">

            <div class="dialog-content">
              <!-- Smart Swap View -->
              <div class="smart-swap-body2">
                <div class="swap-header">
                  <span class="swap-title">Oats with banana and berries</span>

                  <button
                    class="dialog-close meal-item-modal-close"
                    id="dialog-close-btn"
                    aria-label="Close"
                  >
                    &times;
                  </button>
                </div>
                <div class="swap-list">
                  <!-- Swap Item 1 -->
                  <div class="swap-item">
                    <img
                      src="images/dialog/food.png"
                      alt="Cavendish Bananas"
                      class="swap-item-img"
                    />
                    <div class="">
                    <div class="swap-item-info">
                      <div class="swap-item-name">Cavendish Bananas</div>
                      <div class="swap-item-qty"><b>Qty :</b> 118g or 1 large</div>
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
                          "
                        />Smart swap
                      </button>
                      <button class="smart-swap-btn">
                        <img
                          src="{{ frontAssets('images/dialog/Info.svg') }}"
                          alt="Snap"
                          style="width: 18px; vertical-align: middle"
                        />
                      </button>
                    </div>
</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
</div>
