    <!-- Modal -->
    <div class="modal" id="smartSwapModal" tabindex="-1" aria-labelledby="smartSwapModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
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
                             <div class="">
                            <div class="swap-item-info">
                            <div class="swap-item-name">Cavendish Bananas</div>
                            <div class="swap-item-qty"><b>Qty :</b> 118g or 1 large</div>
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
                                <div class="swap-item-name">Rolled Traditional Oats</div>
                                <div class="swap-item-qty"><b>Qty :</b> 5g or 1 teaspoon</div>
                            </div>
                            <div class="swap-item-actions">
                                <button class="smart-swap-btn swap-btn">
                                    <img
                                        src="{{ frontAssets('images/dialog/swap.svg') }}"
                                        alt="Snap"
                                        style="
                            width: 18px;
                            vertical-align: middle;
                            margin-right: 4px;
                        " /><span>Swap</span>
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
                        class="btn btn-primary apply-changes-btn"
                        style="border-radius: 6px; height: 46px"
                    >
                        Apply Changes
                    </button>
                </div>
            </div>
        </div>
    </div>