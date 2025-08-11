<div class="modal" id="recipeDialogModal" tabindex="-1" aria-labelledby="recipeDialogModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <div class="p-0 modal-body">
                <!-- Restore original modal content below -->
                <div class="recipe-dialog">
                    <button class="dialog-close" data-bs-dismiss="modal" aria-label="Close">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"
                            fill="none">
                            <path
                                d="M0.366171 2.13422C-0.122057 1.64599 -0.122057 0.8544 0.366171 0.366171C0.8544 -0.122057 1.64599 -0.122057 2.13422 0.366171L9.99993 8.23198L17.8655 0.366388C18.3538 -0.12184 19.1454 -0.12184 19.6335 0.366388C20.1217 0.854617 20.1217 1.64621 19.6335 2.13444L11.7681 9.99993L19.6335 17.8655C20.1217 18.3538 20.1217 19.1454 19.6335 19.6335C19.1454 20.1217 18.3538 20.1217 17.8655 19.6335L9.99993 11.7681L2.13422 19.6338C1.64599 20.1221 0.8544 20.1221 0.366171 19.6338C-0.122057 19.1456 -0.122057 18.3539 0.366171 17.8657L8.23198 9.99993L0.366171 2.13422Z"
                                fill="#3B3B3B" />
                        </svg>
                    </button>

                    <div class="dialog-content">
                        <!-- Default View -->
                        <div class="dialog-main-view">
                            <div class="dialog-header" style="position:relative;">
                                <div class="">
                                    <h2></h2>
                                    <p></p>
                                    <div class="dialog-actions">
                                        <button class="snap-btn hover-card">
                                            <img src="{{ frontAssets('images/dialog/snap.svg') }}" alt="Snap"
                                                style="
                                                width: 18px;
                                                vertical-align: middle;
                                                margin-right: 4px;
                                                " />
                                            Snap
                                            <span style="margin-left: 4px;display: flex;align-items: center;"><svg
                                                    xmlns="http://www.w3.org/2000/svg" width="15" height="13"
                                                    viewBox="0 0 15 13" fill="none" style="margin-right: 4px">
                                                    <path
                                                        d="M7.87326 0.360751C7.78906 0.190078 7.6152 0.0820312 7.42486 0.0820312C7.2346 0.0820312 7.06073 0.190078 6.97653 0.360751L5.20705 3.9461L1.25038 4.52104C1.06204 4.54841 0.905565 4.68033 0.846752 4.86133C0.787938 5.04234 0.836992 5.24104 0.973278 5.37389L3.83634 8.1647L3.16046 12.1054C3.1283 12.293 3.2054 12.4825 3.35938 12.5944C3.51335 12.7063 3.71748 12.721 3.88594 12.6324L7.42486 10.7719L10.9639 12.6324C11.1323 12.721 11.3365 12.7063 11.4904 12.5944C11.6444 12.4825 11.7215 12.293 11.6893 12.1054L11.0135 8.1647L13.8765 5.37389C14.0128 5.24104 14.0619 5.04234 14.0031 4.86133C13.9442 4.68033 13.7877 4.54841 13.5994 4.52104L9.64273 3.9461L7.87326 0.360751Z"
                                                        fill="#EF7F00" />
                                                </svg>0</span>
                                        </button>
                                        <button class="share-btn hover-card"><svg xmlns="http://www.w3.org/2000/svg"
                                                width="17" height="18" viewBox="0 0 17 18" fill="none">
                                                <g clip-path="url(#clip0_2686_6373)">
                                                    <path
                                                        d="M1.00391 8.99692V14.9757C1.00391 15.3721 1.19638 15.7523 1.53898 16.0326C1.88158 16.3129 2.34625 16.4704 2.83076 16.4704H13.7919C14.2764 16.4704 14.741 16.3129 15.0836 16.0326C15.4262 15.7523 15.6187 15.3721 15.6187 14.9757V8.99692M11.965 4.51283L8.31131 1.52344M8.31131 1.52344L4.65761 4.51283M8.31131 1.52344V11.239"
                                                        stroke="#3B3B3B" stroke-width="1.43864" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                </g>
                                                <defs>
                                                    <clipPath id="clip0_2686_6373">
                                                        <rect width="16.4417" height="16.4417" fill="white"
                                                            transform="translate(0.0898438 0.777344)" />
                                                    </clipPath>
                                                </defs>
                                            </svg>Share</button>
                                    </div>
                                </div>
                                <img src="/public/front/images/dialog/fooditem4.webp" alt="Oats with banana and berries"
                                    class="dialog-img" />
                            </div>
                            <div class="dialog-body">
                                <div class="heading-wrap">
                                <h3>Ingredients</h3>
                                    <button class="smart-swap-btn meal-item-btn">
                                        <img src="{{ frontAssets('images/dialog/swap.svg') }}" alt="Snap"
                                            style="width: 18px; vertical-align: middle;" />Smart swap
                                    </button>
                                </div>
                                <ul>
                                    <li></li>
                                </ul>
                                <h3>Instructions</h3>
                                <p></p>
                                <p class="note">
                                    <strong>Note:</strong>
                                </p>

                                <h3 style="margin-bottom:8px;">Nutrition information</h3>
                                <div class="nutrition-info">

                                    <span style="color: #8cc900">● <span style="color:rgba(59, 59, 59, 1)">Energy: 0
                                            kJ</span></span>
                                    <span style="color: #eb4c60">● <span style="color:rgba(59, 59, 59, 1)">Protein: 0
                                            g</span></span>
                                    <span style="color: #f1b020">● <span style="color:rgba(59, 59, 59, 1)">Carb: 0
                                            g</span></span>
                                    <span style="color: #659cf1">● <span style="color:rgba(59, 59, 59, 1)">Fat: 0
                                            g</span></span>
                                </div>

                                <div class="dialog-actions mobile-responsive">
                                    <button class="snap-btn">
                                        <img src="{{ frontAssets('images/dialog/snap.svg') }}" alt="Snap"
                                            style="
                                                width: 18px;
                                                vertical-align: middle;
                                                margin-right: 4px;
                                                " />
                                        Snap

                                        <span style="margin-left: 4px;display: flex;align-items: center;"><svg
                                                xmlns="http://www.w3.org/2000/svg" width="15" height="13"
                                                viewBox="0 0 15 13" fill="none" style="margin-right: 4px">
                                                <path
                                                    d="M7.87326 0.360751C7.78906 0.190078 7.6152 0.0820312 7.42486 0.0820312C7.2346 0.0820312 7.06073 0.190078 6.97653 0.360751L5.20705 3.9461L1.25038 4.52104C1.06204 4.54841 0.905565 4.68033 0.846752 4.86133C0.787938 5.04234 0.836992 5.24104 0.973278 5.37389L3.83634 8.1647L3.16046 12.1054C3.1283 12.293 3.2054 12.4825 3.35938 12.5944C3.51335 12.7063 3.71748 12.721 3.88594 12.6324L7.42486 10.7719L10.9639 12.6324C11.1323 12.721 11.3365 12.7063 11.4904 12.5944C11.6444 12.4825 11.7215 12.293 11.6893 12.1054L11.0135 8.1647L13.8765 5.37389C14.0128 5.24104 14.0619 5.04234 14.0031 4.86133C13.9442 4.68033 13.7877 4.54841 13.5994 4.52104L9.64273 3.9461L7.87326 0.360751Z"
                                                    fill="#EF7F00" />
                                            </svg>5</span>
                                    </button>
                                    <button class="share-btn"><svg xmlns="http://www.w3.org/2000/svg" width="17"
                                            height="18" viewBox="0 0 17 18" fill="none">
                                            <g clip-path="url(#clip0_2686_6373)">
                                                <path
                                                    d="M1.00391 8.99692V14.9757C1.00391 15.3721 1.19638 15.7523 1.53898 16.0326C1.88158 16.3129 2.34625 16.4704 2.83076 16.4704H13.7919C14.2764 16.4704 14.741 16.3129 15.0836 16.0326C15.4262 15.7523 15.6187 15.3721 15.6187 14.9757V8.99692M11.965 4.51283L8.31131 1.52344M8.31131 1.52344L4.65761 4.51283M8.31131 1.52344V11.239"
                                                    stroke="#3B3B3B" stroke-width="1.43864" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </g>
                                            <defs>
                                                <clipPath id="clip0_2686_6373">
                                                    <rect width="16.4417" height="16.4417" fill="white"
                                                        transform="translate(0.0898438 0.777344)" />
                                                </clipPath>
                                            </defs>
                                        </svg>Share</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Coming Soon Modal -->
<div class="modal" id="comingSoonModal" tabindex="-1" aria-labelledby="comingSoonLabel" aria-hidden="true">
    <div class="modal-dialog modal-confirm modal-coming-soon modal-dialog-centered">
        <div class="modal-content">
            <div class="justify-content-center modal-header">
                <div class="icon-box">
                    <i class="fas fa-clock"></i>
                </div>
                <button class="dialog-close coming-soon-close" data-bs-dismiss="modal"
                    aria-label="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"
                        fill="none">
                        <path
                            d="M0.366171 2.13422C-0.122057 1.64599 -0.122057 0.8544 0.366171 0.366171C0.8544 -0.122057 1.64599 -0.122057 2.13422 0.366171L9.99993 8.23198L17.8655 0.366388C18.3538 -0.12184 19.1454 -0.12184 19.6335 0.366388C20.1217 0.854617 20.1217 1.64621 19.6335 2.13444L11.7681 9.99993L19.6335 17.8655C20.1217 18.3538 20.1217 19.1454 19.6335 19.6335C19.1454 20.1217 18.3538 20.1217 17.8655 19.6335L9.99993 11.7681L2.13422 19.6338C1.64599 20.1221 0.8544 20.1221 0.366171 19.6338C-0.122057 19.1456 -0.122057 18.3539 0.366171 17.8657L8.23198 9.99993L0.366171 2.13422Z"
                            fill="#3B3B3B" />
                    </svg>
                </button>
            </div>
            <div class="text-center modal-body">
                <h4>Coming Soon!</h4>
                <p>This feature is coming soon.</p>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.snap-btn, .share-btn').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                var comingSoonModal = new bootstrap.Modal(document.getElementById(
                    'comingSoonModal'));
                comingSoonModal.show();
            });
        });
    });
</script>
