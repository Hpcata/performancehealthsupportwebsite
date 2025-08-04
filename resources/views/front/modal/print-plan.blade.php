<div id="print-plan-modal"
  style="display: none; position: fixed; z-index: 10000; left: 0; top: 0; width: 100vw; height: 100vh; background: rgba(0, 0, 0, 0.5);">
  <div style="position: absolute; left: 50%; top: 50%; transform: translate(-50%, -50%); background: #fff; border-radius: 20px; width: 90vw; max-width: 1200px; max-height: 800px; box-shadow: 0 8px 32px rgba(0, 0, 0, 0.18); padding: 0; display: flex; flex-direction: column;" >
    <button id="download-pdf-close" aria-label="Close" style="background: none; border: none; font-size: 2rem; color: #222; cursor: pointer; text-align: end; padding: 24px 40px; border-bottom: 1px solid #d8d8d8;" >
      &times;
    </button>

    <div style="flex: 1 1 auto; overflow-y: auto; padding: 36px;">
      <div id="pdf-preview" style="width: 100%; height: 100%; max-height: 500px; " >
        <div id="pdf-content" style="background: #fff; max-width: 940px; width: 100%; font-family: 'Inter', Arial, sans-serif;" >
          
        </div>
      </div>
    </div>

    
  </div>
</div>