<!-- Shopping List Modal -->
<div id="print-shopping-list-modal" style="display: none; position: fixed; z-index: 10000; left: 0; top: 0; width: 100vw; height: 100vh; background: rgba(0, 0, 0, 0.5);">
    <div style="position: absolute; left: 50%; top: 50%; transform: translate(-50%, -50%); background: #fff; border-radius: 16px; width: 95vw; max-width: 800px; max-height: 90vh; box-shadow: 0 8px 32px rgba(0, 0, 0, 0.18); padding: 0; display: flex; flex-direction: column;">
        <div style="padding: 24px; border-bottom: 1px solid #d8d8d8; display: flex; align-items: center; justify-content: space-between;">
            <span style="font-size: 24px; font-weight: 700; color: #080808;">Print Shopping list</span>
            <button id="print-shopping-list-close" aria-label="Close" style="background: none; border: none; font-size: 1.6rem; color: #222; cursor: pointer;">&times;</button>
        </div>
        <div style="flex: 1 1 auto; overflow-y: auto; padding: 18px 24px 24px 24px; max-height: 70vh;">
            <div id="shopping-list-content" style="font-size: 1rem; color: #222;">
                
            </div>
        </div>
        <div style="    text-align: end;
    padding: 12px 16px;
    border-top: 1px solid #d8d8d8;" class="responsive-modal-footer">
            <button id="download-pdf" class="btn btn-primary" style="padding: 12px 28px; font-size: 1rem; font-weight: 500; cursor: pointer; box-shadow: 0 2px 8px rgba(42, 92, 164, 0.08);">Download PDF</button>
        </div>
    </div>
</div>

