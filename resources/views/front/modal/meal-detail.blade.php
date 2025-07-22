<div class="modal" id="recipeDialogModal" tabindex="-1" aria-labelledby="recipeDialogModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <div class="p-0 modal-body">
                <!-- Restore original modal content below -->
                <div class="recipe-dialog">
                    <button class="dialog-close" data-bs-dismiss="modal" aria-label="Close">
                        &times;
                    </button>
                    
                    <div class="dialog-content">
                        <!-- Default View -->
                        <div class="dialog-main-view">
                            <div class="dialog-header">
                                <div>
                                    <h2></h2>
                                    <p></p>
                                    <div class="dialog-actions">
                                        <button class="snap-btn">
                                            <img
                                                src="{{ frontAssets('images/dialog/snap.svg') }}"
                                                alt="Snap"
                                                style="
                                                width: 18px;
                                                vertical-align: middle;
                                                margin-right: 4px;
                                                " />
                                            Snap <span style="margin-left: 4px">5</span>
                                        </button>
                                        <button class="share-btn">Share</button>
                                    </div>
                                </div>
                                <img
                                    src="{{ frontAssets('images/dialog/fooditem4.webp') }}"
                                    alt="Oats with banana and berries"
                                    class="dialog-img" />
                            </div>
                            <div class="dialog-body">
                                <h3>Ingredients</h3>
                                <ul>
                                    <li></li>
                                </ul>
                                <button class="smart-swap-btn" id="smart-swap-btn">
                                    <img
                                        src="{{ frontAssets('images/dialog/swap.svg') }}"
                                        alt="Snap"
                                        style="width: 18px; vertical-align: middle; margin-right: 4px" />Smart swap
                                </button>
                                <h3>Instructions</h3>
                                <p></p>
                                <p class="note">
                                    <strong>Note:</strong> 
                                </p>
                                <div class="nutrition-info">
                                    <span style="color: #967500">● Energy: 0 kJ</span>
                                    <span style="color: #a60015">● Protein: 0 g</span>
                                    <span style="color: #3e8e00">● Carb: 0 g</span>
                                    <span style="color: #0077b6">● Fat: 0 g</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
