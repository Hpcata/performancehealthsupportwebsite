function playVideoInCard(e) {
    const t = document.getElementById(`video-player-${e}`),
        o = t.querySelector(".video-backdrop"),
        n = t.querySelector("video"),
        s = t.querySelector(".play-btn");
    (o.style.display = "none"),
        (s.style.display = "none"),
        (n.style.display = "block"),
        n.play(),
        t.classList.add("playing"),
        n.addEventListener("ended", () => {
            (o.style.display = "block"),
                (s.style.display = "flex"),
                (n.style.display = "none"),
                t.classList.remove("playing");
        }),
        n.addEventListener("click", () => {
            n.paused ? n.play() : n.pause();
        });
}
document.addEventListener("DOMContentLoaded", () => {
    const e = document.querySelectorAll(".tab"),
        t = document.querySelector(".meal-cards"),
        o = {
            breakfast: [
                {
                    name: "Energy breakfast Oats with banana and berries",
                    image: "images/food1.webp",
                },
                {
                    name: "Protein Pancakes with Greek Yogurt",
                    image: "images/food2.webp",
                },
                { name: "Avocado Toast with Eggs", image: "images/food1.webp" },
            ],
            lunch: [
                {
                    name: "Grilled Chicken Salad Bowl",
                    image: "images/food1.webp",
                },
                { name: "Quinoa Power Bowl", image: "images/food2.webp" },
                { name: "Turkey and Hummus Wrap", image: "images/food1.webp" },
            ],
            dinner: [
                {
                    name: "Salmon with Sweet Potato",
                    image: "images/food1.webp",
                },
                { name: "Lean Beef Stir Fry", image: "images/food2.webp" },
                { name: "Vegetarian Buddha Bowl", image: "images/food1.webp" },
            ],
            supplements: [
                {
                    name: "Pre-Workout Energy Boost",
                    image: "images/food1.webp",
                },
                {
                    name: "Post-Workout Recovery Shake",
                    image: "images/food2.webp",
                },
                { name: "Daily Multivitamin Pack", image: "images/food1.webp" },
            ],
            snacks: [
                { name: "Mixed Nuts and Berries", image: "images/food1.webp" },
                { name: "Greek Yogurt with Honey", image: "images/food2.webp" },
                { name: "Protein Energy Balls", image: "images/food1.webp" },
            ],
            drinks: [
                { name: "Green Smoothie Blend", image: "images/food1.webp" },
                {
                    name: "Electrolyte Sports Drink",
                    image: "images/food2.webp",
                },
                { name: "Protein Recovery Shake", image: "images/food1.webp" },
            ],
        };
    e.forEach((n) => {
        n.addEventListener("click", function () {
            e.forEach((e) => e.classList.remove("active")),
                this.classList.add("active");
            const n = this.getAttribute("data-tab");
            !(function (e) {
                (t.innerHTML = ""),
                    e.forEach((e) => {
                        const o = document.createElement("div");
                        (o.className = "meal-card"),
                            (o.innerHTML = `\n                <img src="${e.image}" alt="${e.name}">\n                <h3>${e.name}</h3>\n            `),
                            t.appendChild(o);
                    });
            })(o[n] || o.breakfast);
        });
    });
}),
    document.querySelectorAll('a[href^="#"]').forEach((e) => {
        e.addEventListener("click", function (e) {
            e.preventDefault();
            const t = document.querySelector(this.getAttribute("href"));
            t && t.scrollIntoView({ behavior: "smooth", block: "start" });
        });
    }),
    document.querySelectorAll("button").forEach((e) => {
        e.onclick &&
            e.onclick.toString().includes("window.open") &&
            e.addEventListener("click", function () {
                const e = this.textContent;
                (this.textContent = "Loading..."),
                    (this.disabled = !0),
                    setTimeout(() => {
                        (this.textContent = e), (this.disabled = !1);
                    }, 1e3);
            });
    }),
    document.querySelectorAll(".clickable").forEach((e) => {
        e.addEventListener("mouseenter", function () {
            this.style.transform = "translateY(-2px)";
        }),
            e.addEventListener("mouseleave", function () {
                this.style.transform = "translateY(0)";
            });
    });
// document.querySelectorAll(".dropdown").forEach((e) => {
//     let t;
//     e.addEventListener("mouseenter", function () {
//         clearTimeout(t);
//         const e = this.querySelector(".dropdown-content");
//         (e.style.opacity = "1"), (e.style.visibility = "visible"), (e.style.transform = "translateY(0)");
//     }),
//         e.addEventListener("mouseleave", function () {
//             const e = this.querySelector(".dropdown-content");
//             t = setTimeout(() => {
//                 (e.style.opacity = "0"), (e.style.visibility = "hidden"), (e.style.transform = "translateY(-10px)");
//             }, 100);
//         });
// });
const observerOptions = { threshold: 0.1, rootMargin: "0px 0px -50px 0px" },
    observer = new IntersectionObserver((e) => {
        e.forEach((e) => {
            e.isIntersecting &&
                ((e.target.style.opacity = "1"),
                (e.target.style.transform = "translateY(0)"));
        });
    }, observerOptions);
function openVideoPopup(e) {
    const t = document.getElementById("video-popup"),
        o = document.getElementById("popup-video");
    (o.src = e), t.classList.add("show"), o.play();
}
function closeVideoPopup() {
    const e = document.getElementById("video-popup"),
        t = document.getElementById("popup-video");
    t.pause(), (t.currentTime = 0), (t.src = ""), e.classList.remove("show");
}
function toggleMobileMenu() {
    document.getElementById("mobile-menu").classList.toggle("open");
}
function openVideoPopup(e) {
    (document.getElementById("popup-video").src = e),
        document.getElementById("video-popup").classList.add("show"),
        document.body.classList.add("no-scroll");
}
function closeVideoPopup() {
    document.getElementById("video-popup").classList.remove("show"),
        (document.getElementById("popup-video").src = ""),
        document.body.classList.remove("no-scroll");
}
function openFullscreenVideoPopup(e) {
    const t = document.getElementById("fullscreen-video-popup");
    t && t.remove();
    const o = document.createElement("div");
    (o.id = "fullscreen-video-popup"),
        (o.style.position = "fixed"),
        (o.style.inset = "0"),
        (o.style.width = "100vw"),
        (o.style.height = "100vh"),
        (o.style.background = "rgba(0,0,0,0.95)"),
        (o.style.zIndex = "10000"),
        (o.style.display = "flex"),
        (o.style.alignItems = "center"),
        (o.style.justifyContent = "center");
    const n = document.createElement("video");
    (n.src = e),
        (n.controls = !0),
        (n.autoplay = !0),
        (n.style.width = "100vw"),
        (n.style.height = "100vh"),
        (n.style.objectFit = "contain"),
        (n.style.background = "#000");
    const s = document.createElement("span");
    (s.innerHTML = "&times;"),
        (s.style.position = "absolute"),
        (s.style.top = "1.5rem"),
        (s.style.right = "2.5rem"),
        (s.style.fontSize = "3rem"),
        (s.style.color = "#fff"),
        (s.style.cursor = "pointer"),
        (s.style.zIndex = "10001"),
        (s.style.background = "rgba(0,0,0,0.3)"),
        (s.style.borderRadius = "50%"),
        (s.style.width = "3rem"),
        (s.style.height = "3rem");
}

let mealCardsSlider = null;

function initMealCardsSlider() {
    const mealCardsWrapper = document.getElementById('meal-cards-wrapper');
    if (mealCardsWrapper && mealCardsWrapper.children.length > 0) {
        mealCardsSlider = tns({
            container: '#meal-cards-wrapper',
            items: 1,
            slideBy: 'page',
            autoplay: false,
            mouseDrag: true,
            nav: false,
            controls: true,
            controlsContainer: '.training-plan .slider-wrapper',
            responsive: {
                768: {
                    items: 2
                },
                992: {
                    items: 3
                },
                1200: {
                    items: 4
                }
            }
        });
    }
}

// Call the initialization function when the DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    initMealCardsSlider();
});

// Re-initialize slider on window resize if needed
window.addEventListener('resize', () => {
    if (mealCardsSlider) {
        mealCardsSlider.destroy();
        mealCardsSlider = null;
    }
    initMealCardsSlider();
});
        (s.style.display = "flex"),
        (s.style.alignItems = "center"),
        (s.style.justifyContent = "center"),
        (s.onclick = function () {
            o.remove();
        }),
        o.appendChild(n),
        o.appendChild(s),
        document.body.appendChild(o);
}
document.querySelectorAll("section").forEach((e) => {
    (e.style.opacity = "0"),
        (e.style.transform = "translateY(20px)"),
        (e.style.transition = "opacity 0.6s ease, transform 0.6s ease"),
        observer.observe(e);
});
const selectWrapper = document.querySelector(".select-wrapper"),
    select = document.querySelector(".two-line-select");
select.addEventListener("focus", () => {
    selectWrapper.classList.add("open");
}),
    select.addEventListener("blur", () => {
        selectWrapper.classList.remove("open");
    });

// Dialog open/close logic for recipe-dialog modal removed. Bootstrap modal will be used instead.

// Smart Swap toggle logic
const smartSwapBtn = document.querySelector(".smart-swap-btn");
const dialogMainView = document.querySelector(".dialog-main-view");
const smartSwapBody = document.querySelector(".smart-swap-body");
const backBtn = document.querySelector(".smart-swap-body .back-btn");

if (smartSwapBtn && dialogMainView && smartSwapBody && backBtn) {
    smartSwapBtn.addEventListener("click", function () {
        dialogMainView.style.display = "none";
        smartSwapBody.style.display = "block";
    });

    backBtn.addEventListener("click", function () {
        smartSwapBody.style.display = "none";
        dialogMainView.style.display = "block";
    });
}

// Smart Swap close button in header
const closeBtn2 = document.getElementById("dialog-close-btn-2");
if (closeBtn2 && dialog) {
    closeBtn2.addEventListener("click", function () {
        dialog.style.display = "none";
        document.body.style.overflow = "";
    });
}

// Show smart-swap-body2 on info button click
document
    .querySelectorAll('.swap-item-actions .smart-swap-btn img[alt="Info"]')
    .forEach(function (img) {
        img.parentElement.addEventListener("click", function () {
            document.querySelector(".smart-swap-body").style.display = "none";
            document.querySelector(".smart-swap-body2").style.display = "block";
        });
    });

// Close smart-swap-body2
const closeBtn3 = document.getElementById("dialog-close-btn-3");
if (closeBtn3) {
    closeBtn3.addEventListener("click", function () {
        document.querySelector(".smart-swap-body2").style.display = "none";
        document.querySelector(".smart-swap-body").style.display = "block";
    });
}

// document.addEventListener("DOMContentLoaded", function () {
//     const shareBtn = document.querySelector(".btn-share");
//     const shareDropdown = document.getElementById("share-dropdown");
//     const shareDropdownClose = document.getElementById("share-dropdown-close");

//     if (shareBtn && shareDropdown && shareDropdownClose) {
//         shareBtn.addEventListener("click", function (e) {
//             e.stopPropagation();
//             console.log('Share button clicked');
//             shareDropdown.style.display = "block";
//             // Position dropdown below the button using getBoundingClientRect
//             const rect = shareBtn.getBoundingClientRect();
//             shareDropdown.style.position = "absolute";
//             shareDropdown.style.top = (window.scrollY + rect.bottom + 8) + "px";
//             shareDropdown.style.left = (window.scrollX + rect.left) + "px";
//             console.log('Dropdown positioned at', shareDropdown.style.top, shareDropdown.style.left);
//         });

//         shareDropdownClose.addEventListener("click", function () {
//             shareDropdown.style.display = "none";
//             console.log('Share dropdown closed by close button');
//         });

//         // Close dropdown when clicking outside
//         document.addEventListener("click", function (e) {
//             if (!shareDropdown.contains(e.target) && e.target !== shareBtn) {
//                 shareDropdown.style.display = "none";
//                 console.log('Share dropdown closed by outside click');
//             }
//         });
//     } else {
//         console.warn('Share dropdown elements not found:', { shareBtn, shareDropdown, shareDropdownClose });
//     }
// });
// document.addEventListener("DOMContentLoaded", function () {
//     // Download PDF Modal logic
//     const downloadPlanBtn = document.querySelector('.share-dropdown-item img[alt="Download"]')?.parentElement;
//     const downloadPdfModal = document.getElementById("download-pdf-modal");
//     const downloadPdfClose = document.getElementById("download-pdf-close");

//     if (downloadPlanBtn && downloadPdfModal && downloadPdfClose) {
//         downloadPlanBtn.addEventListener("click", function () {
//             downloadPdfModal.style.display = "block";
//         });
//         downloadPdfClose.addEventListener("click", function () {
//             downloadPdfModal.style.display = "none";
//         });
//         // Optional: close modal on overlay click
//         downloadPdfModal.addEventListener("click", function (e) {
//             if (e.target === downloadPdfModal) {
//                 downloadPdfModal.style.display = "none";
//             }
//         });
//     }

// Download PDF functionality
const downloadPdfBtn = document.getElementById("download-pdf-btn");
if (downloadPdfBtn) {
    downloadPdfBtn.addEventListener("click", function () {
        const pdfContent = document.getElementById("pdf-content");
        if (!pdfContent) return;
        // Dynamically load html2pdf if not present
        if (typeof html2pdf === "undefined") {
            const script = document.createElement("script");
            script.src =
                "https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js";
            script.onload = () => {
                html2pdf()
                    .set({
                        margin: 0,
                        filename: "nutrition-plan.pdf",
                        image: { type: "jpeg", quality: 0.98 },
                        html2canvas: { scale: 2 },
                        jsPDF: {
                            unit: "pt",
                            format: "a4",
                            orientation: "portrait",
                        },
                    })
                    .from(pdfContent)
                    .save();
            };
            document.body.appendChild(script);
        } else {
            html2pdf()
                .set({
                    margin: 0,
                    filename: "nutrition-plan.pdf",
                    image: { type: "jpeg", quality: 0.98 },
                    html2canvas: { scale: 2 },
                    jsPDF: {
                        unit: "pt",
                        format: "a4",
                        orientation: "portrait",
                    },
                })
                .from(pdfContent)
                .save();
        }
    });
}

// Shopping List Modal logic
// const shoppingListBtn = document.querySelector(".btn-outline.btn");
// const shoppingListModal = document.getElementById("shopping-list-modal");
// const shoppingListClose = document.getElementById("shopping-list-close");
// if (shoppingListBtn && shoppingListModal && shoppingListClose) {
//     shoppingListBtn.addEventListener("click", function () {
//         shoppingListModal.style.display = "block";
//     });
//     shoppingListClose.addEventListener("click", function () {
//         shoppingListModal.style.display = "none";
//     });
//     shoppingListModal.addEventListener("click", function (e) {
//         if (e.target === shoppingListModal) {
//             shoppingListModal.style.display = "none";
//         }
//     });
// }

// Shopping List PDF download
const shoppingListDownload = document.getElementById("shopping-list-download");
if (shoppingListDownload) {
    shoppingListDownload.addEventListener("click", function () {
        const content = document.getElementById("shopping-list-content");
        if (!content) return;
        if (typeof html2pdf === "undefined") {
            const script = document.createElement("script");
            script.src =
                "https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js";
            script.onload = () => {
                html2pdf()
                    .set({
                        margin: 0,
                        filename: "shopping-list.pdf",
                        image: { type: "jpeg", quality: 0.98 },
                        html2canvas: { scale: 2 },
                        jsPDF: {
                            unit: "pt",
                            format: "a4",
                            orientation: "portrait",
                        },
                    })
                    .from(content)
                    .save();
            };
            document.body.appendChild(script);
        } else {
            html2pdf()
                .set({
                    margin: 0,
                    filename: "shopping-list.pdf",
                    image: { type: "jpeg", quality: 0.98 },
                    html2canvas: { scale: 2 },
                    jsPDF: {
                        unit: "pt",
                        format: "a4",
                        orientation: "portrait",
                    },
                })
                .from(content)
                .save();
        }
    });
}

// competition plan script
function toggleSection(section) {
    const content = document.getElementById(section + "-content");
    const expanded = document.getElementById(section + "-expanded");
    const arrow = document.getElementById(section + "-arrow");

    if (expanded.style.display === "none") {
        content.style.display = "none";
        expanded.style.display = "block";
        arrow.style.transform = "rotate(180deg)";
    } else {
        content.style.display = "block";
        expanded.style.display = "none";
        arrow.style.transform = "rotate(0deg)";
    }
}

// Simple countdown timer
function updateCountdown() {
    const countdownElements = document.querySelectorAll("[data-countdown]");
    // This would be connected to actual countdown logic
}

// Initialize countdown
setInterval(updateCountdown, 60000); // Update every minute

 // Target all horizontal scroll containers
$(document).ready(function() {
   
    $('.challenge-cards, .challenges .challenge-cards, .surfing-videos .video-grid, .training-plan .meal-cards, .consults-plans-grid').each(function() {
        var $el = $(this);
        var scrollTimeout;
        $el.on('scroll', function() {
            $el.addClass('show-scrollbar');
            clearTimeout(scrollTimeout);
            scrollTimeout = setTimeout(function() {
                $el.removeClass('show-scrollbar');
            }, 700); // Hide after 700ms of no scroll
        });
    });

    // Tiny-Slider initialization for #meal-cards-wrapper
    var mealCardsSlider = tns({
        container: '#meal-cards-wrapper',
        items: 1,
        slideBy: 'page',
        autoplay: false,
        mouseDrag: true,
        controls: true,
        nav: false,
        responsive: {
            640: { items: 2 },
            768: { items: 3 },
            1024: { items: 4 }
        }
    });
});