<div id="print-plan-modal"
  style="display: none; position: fixed; z-index: 10000; left: 0; top: 0; width: 100vw; height: 100vh; background: rgba(0, 0, 0, 0.5);">
  <div style="position: absolute; left: 50%; top: 50%; transform: translate(-50%, -50%); background: #fff; border-radius: 20px; width: 90vw; max-width: 1200px; max-height: 800px; box-shadow: 0 8px 32px rgba(0, 0, 0, 0.18); padding: 0; display: flex; flex-direction: column;" >
    <button id="download-pdf-close" aria-label="Close" style="background: none; border: none; font-size: 2rem; color: #222; cursor: pointer; text-align: end; padding: 24px 40px; border-bottom: 1px solid #d8d8d8;" >
      &times;
    </button>

    <div style="flex: 1 1 auto; overflow-y: auto; padding: 36px;">
      <div id="pdf-preview" style="width: 100%; height: 100%; max-height: 500px; " >
        <div id="pdf-content" style="background: #fff; max-width: 940px; width: 100%; font-family: 'Inter', Arial, sans-serif;" >
          <!-- Hero Section -->
          <div style="margin-bottom: 24px;">
            <div style="position: relative; width: 100%; min-height: 200px; border-radius: 18px; overflow: hidden; background-color: #3b3b3b;" >
              <img src="images/sports-hero-bg.webp" alt="Hero Banner"
                style="width: 340px; height: 200px; object-fit: cover; display: block; position: absolute; right: 0; border-radius: 81px 0 0 0;"/>
              <div style="position: absolute; inset: 0; background: linear-gradient(90deg, rgba(0, 0, 0, 0.55) 0%, rgba(0, 0, 0, 0.15) 100%);"></div>
              <div style="position: absolute; left: 0; top: 0; width: 100%; height: 100%; display: flex; align-items: center; padding: 0 32px;">
                <div>
                  <img src="images/logo.png" alt="2LS Logo" style="height: 36px; margin-bottom: 50px;"/>
                  <div style="color: #fff; font-size: 1.1rem; font-weight: 500; margin-bottom: 12px;">
                    Ava's
                  </div>
                  <div style="color: #fff; font-size: 20px; font-weight: 700; line-height: 1.1;">
                    <span style="font-weight: 400; color: #e0e0e0;">Nutrition Plan | Sports Training Plan</span>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Category Label -->
          <div style="margin-bottom: 18px;">
            <span style="color: #3b82f6; font-weight: 600; font-size: 1.1rem; cursor: pointer;">Breakfast</span>
            <span style="color: #3b82f6; font-weight: 400; font-size: 1.1rem;"> | </span>
            <span style="color: #3b82f6; font-weight: 500; font-size: 1.1rem; cursor: pointer;">Sweet Breakfast</span>
          </div>

          <!-- Food Card -->
          <div style="display: flex; gap: 18px; background: #f5f5f5; border-radius: 16px; padding: 18px; margin-bottom: 16px; align-items: flex-start;background-color:#fff;">
            <img src="images/sports-training/fooditem4.webp" alt="Power Oatmeal Bowl" style="width: 190px; min-height: 190px; object-fit: cover; border-radius: 12px;" />
            <div>
              <div style="font-size: 1.1rem; font-weight: 700; color: #222;">Power Oatmeal Bowl</div>
              <div style="font-size: 14px; color: #444; margin-bottom: 4px;">
                Hearty oats topped with fruit and nuts for sustained energy.
              </div>
              <div style="font-size: 14px; color: #222; margin-bottom: 12px;">
                <b>Note:</b> Swap Almond Butter to Protein Powder for extra recovery support
              </div>
              <div style="font-size: 14px; display: flex; flex-wrap: wrap; gap: 12px;">
                <span style="color: #967500; font-weight: 600;">● Energy: 2090kJ</span>
                <span style="color: #a60015; font-weight: 600;">● Protein: 28g</span>
                <span style="color: #3e8e00; font-weight: 600;">● Carb: 68g</span>
                <span style="color: #0077b6; font-weight: 600;">● Fat: 33g</span>
              </div>
            </div>
            <div style="max-width: 310px;">
              <div style="font-size: 1.05rem; font-weight: 700; color: #222; margin-bottom: 4px;">Ingredients</div>
              <ul style="font-size: 14px; color: #444; margin: 0; padding-left: 18px;">
                <li style="margin-bottom: 4px;">5g or 1 teaspoon Rolled Traditional Oats</li>
                <li style="margin-bottom: 4px;">250mL or 1 cup Full Cream Milk</li>
                <li style="margin-bottom: 4px;">118g or 1 large Cavendish Bananas</li>
                <li style="margin-bottom: 4px;">17g or 1 tablespoon Macro Black Chia Seeds</li>
                <li style="margin-bottom: 4px;">5g or 1 teaspoon Natural Almond Butter</li>
                <li style="margin-bottom: 4px;">5g or 1 teaspoon Capilano Honey Squeeze</li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>

    
  </div>
</div>