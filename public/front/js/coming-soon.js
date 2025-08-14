// Learn more tooltip functionality
function showLearnMoreTooltip(button, planType) {
    // Remove any existing learn more tooltips
    const existingTooltip = document.querySelector(".learn-more-tooltip");
    if (existingTooltip) {
        existingTooltip.remove();
    }

    // Create tooltip element
    const tooltip = document.createElement("div");
    tooltip.className = "learn-more-tooltip";
    tooltip.textContent = `${planType}`;

    // Get button position for better tooltip placement
    const buttonRect = button.getBoundingClientRect();

    // Position tooltip above the button
    tooltip.style.position = "fixed";
    tooltip.style.top = (buttonRect.top - 50) + "px";
    tooltip.style.left = (buttonRect.left + buttonRect.width / 2) + "px";
    tooltip.style.transform = "translateX(-50%)";
    tooltip.style.zIndex = "9999";
    // Add tooltip to body instead of button for better positioning
    document.body.appendChild(tooltip);

    // Auto-hide tooltip after 3 seconds
    setTimeout(() => {
        const tooltipToRemove = document.querySelector(".learn-more-tooltip");
        if (tooltipToRemove) {
            tooltipToRemove.remove();
        }
    }, 3000);
}

// Make function globally accessible
window.showLearnMoreTooltip = showLearnMoreTooltip;

// Add CSS for tooltip
const tooltipStyle = document.createElement("style");
tooltipStyle.textContent = `
    .coming-soon-tooltip {
        background-color: #333;
        color: white;
        padding: 8px 12px;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 500;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        animation: tooltipFadeIn 0.3s ease-out;
        white-space: nowrap;
        pointer-events: none;
    }

    .coming-soon-tooltip::after {
        content: '';
        position: absolute;
        top: 100%;
        left: 50%;
        transform: translateX(-50%);
        border: 6px solid transparent;
        border-top-color: #333;
    }

    .learn-more-tooltip {
        background-color: #333;
        color: white;
        padding: 8px 12px;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 500;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        animation: tooltipFadeIn 0.3s ease-out;
        white-space: nowrap;
        pointer-events: none;
        position: fixed;
        z-index: 9999;
    }

    .learn-more-tooltip::after {
        content: '';
        position: absolute;
        top: 100%;
        left: 50%;
        transform: translateX(-50%);
        border: 6px solid transparent;
        border-top-color: #333;
    }

    @keyframes tooltipFadeIn {
        from {
            opacity: 0;
            transform: translateY(10px) translateX(-50%);
        }
        to {
            opacity: 1;
            transform: translateY(0) translateX(-50%);
        }
    }`;
document.head.appendChild(tooltipStyle);

function hideComingSoonTooltip() {
    const existingTooltip = document.querySelector(".coming-soon-tooltip");
    if (existingTooltip) {
        existingTooltip.remove();
    }
}
