<div class="modal" id="recipeDialogModal" tabindex="-1" aria-labelledby="recipeDialogModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <div class="p-0 modal-body">
                <!-- Restore original modal content below -->
                <div class="recipe-dialog">
                    <button class="dialog-close" data-bs-dismiss="modal" aria-label="Close">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
  <path d="M0.366171 2.13422C-0.122057 1.64599 -0.122057 0.8544 0.366171 0.366171C0.8544 -0.122057 1.64599 -0.122057 2.13422 0.366171L9.99993 8.23198L17.8655 0.366388C18.3538 -0.12184 19.1454 -0.12184 19.6335 0.366388C20.1217 0.854617 20.1217 1.64621 19.6335 2.13444L11.7681 9.99993L19.6335 17.8655C20.1217 18.3538 20.1217 19.1454 19.6335 19.6335C19.1454 20.1217 18.3538 20.1217 17.8655 19.6335L9.99993 11.7681L2.13422 19.6338C1.64599 20.1221 0.8544 20.1221 0.366171 19.6338C-0.122057 19.1456 -0.122057 18.3539 0.366171 17.8657L8.23198 9.99993L0.366171 2.13422Z" fill="#3B3B3B"/>
</svg>
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
                                    src="/public/front/images/dialog/fooditem4.webp"
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
