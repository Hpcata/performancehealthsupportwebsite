// Countries data
const countries = [
    { name: "Australia", code: "au", dial_code: "+61" },
    { name: "India", code: "in", dial_code: "+91" },
    // Add more countries as needed
];

const listElement = document.getElementById("login-country-list");
const dropdown = document.getElementById("login-dropdown");
const searchInput = document.getElementById("login-search-input");
const selectedFlag = document.getElementById("selected-flag");
const selectedCode = document.getElementById("selected-code");

function createCountryItem(country) {
    const li = document.createElement("li");
    li.innerHTML = `<span class="fi fi-${country.code}"></span> ${country.name} (${country.dial_code})`;
    li.onclick = () => selectCountry(country);
    return li;
}

function populateCountries(list = countries) {
    listElement.innerHTML = "";
    list.forEach((c) => listElement.appendChild(createCountryItem(c)));
}

function toggleDropdown() {
    dropdown.classList.toggle("hidden");
    const dropdownWrapper = document.querySelector(".dropdown-wrapper");
    dropdownWrapper.classList.toggle("active");
}

function selectCountry(country) {
    selectedFlag.className = `fi fi-${country.code}`;
    selectedCode.textContent = country.dial_code;
    dropdown.classList.add("hidden");
    const dropdownWrapper = document.querySelector(".dropdown-wrapper");
    dropdownWrapper.classList.remove("active");
}

function filterCountries(query) {
    const filtered = countries.filter(
        (c) =>
            c.name.toLowerCase().includes(query.toLowerCase()) ||
            c.dial_code.includes(query)
    );
    populateCountries(filtered);
}

// Initialize
populateCountries();

// Live search
searchInput.addEventListener("input", (e) => filterCountries(e.target.value));

// Terms modal functionality
function openTermsModal() {
    const termsModal = new bootstrap.Modal(
        document.getElementById("termsModal")
    );
    termsModal.show();
}

// Coming soon tooltip functionality
function showComingSoonTooltip(button, platform) {
    // Remove any existing tooltips
    const existingTooltip = document.querySelector('.coming-soon-tooltip');
    if (existingTooltip) {
        existingTooltip.remove();
    }

    // Create tooltip element
    const tooltip = document.createElement('div');
    tooltip.className = 'coming-soon-tooltip';
    tooltip.textContent = 'Coming Soon!';

    // Position tooltip above the button
    const buttonRect = button.getBoundingClientRect();
    tooltip.style.position = 'fixed';
    tooltip.style.top = (buttonRect.top - 40) + 'px';
    tooltip.style.left = (buttonRect.left + buttonRect.width / 2 - 50) + 'px';
    tooltip.style.zIndex = '9999';

    // Add tooltip to body
    document.body.appendChild(tooltip);
}

function hideComingSoonTooltip() {
    const existingTooltip = document.querySelector('.coming-soon-tooltip');
    if (existingTooltip) {
        existingTooltip.remove();
    }
}

// Step navigation functionality
function showStep(stepNumber) {
    // Hide all steps
    for (let i = 1; i <= 4; i++) {
        const step = document.getElementById(`step${i}`);
        if (step) {
            step.style.display = "none";
        }
    }

    // Show the requested step
    const currentStep = document.getElementById(`step${stepNumber}`);
    if (currentStep) {
        currentStep.style.display = "flex";
    }
}

function closeModal() {
    const modal = bootstrap.Modal.getInstance(
        document.getElementById("signupModalathlete")
    );
    if (modal) {
        modal.hide();
    }
    // Reset to step 1 when modal is closed
    setTimeout(() => {
        showStep(1);
    }, 300);
}

// Radio button functionality using event delegation
document.addEventListener("change", function (event) {
    // Handle user type radio buttons
    if (event.target.name === "userType") {
        // Remove selected class from all user type boxes
        document
            .querySelectorAll("#user-type-section-id .user-type-box")
            .forEach((box) => {
                box.classList.remove("selected");
            });
        // Add selected class to the parent of the checked radio
        if (event.target.checked) {
            event.target
                .closest("#user-type-section-id .user-type-box")
                .classList.add("selected");
        }
    }

    // Handle age range radio buttons
    if (event.target.name === "ageRange") {
        // Remove selected class from all age boxes
        document.querySelectorAll("#age-groups-id .age-box").forEach((box) => {
            box.classList.remove("selected");
        });
        // Add selected class to the parent of the checked radio
        if (event.target.checked) {
            event.target
                .closest("#age-groups-id .age-box")
                .classList.add("selected");
        }
    }
});

// Debug: Add click handlers to age boxes as backup
document.addEventListener("click", function (event) {
    if (event.target.closest(".age-box")) {
        const ageBox = event.target.closest(".age-box");
        const radio = ageBox.querySelector('input[type="radio"]');
        if (radio) {
            radio.checked = true;
            // Trigger change event
            radio.dispatchEvent(new Event("change"));
        }
    }
});

$(document).ready(function () {
    $("#user-type-section-id .user-type-box").click(function () {
        console.log(2);
        if ($(this).find('input[type="radio"]').val() == "athlete") {
            console.log("athlete");
            $("#age-groups-id").removeClass("d-none");
            console.log($("#age-groups-id"));
            $("#select-sports-id").removeClass("d-none");
        } else {
            console.log(3);
            $("#age-groups-id").addClass("d-none");
            $("#select-sports-id").addClass("d-none");
        }
    });
});

function toggleLoginDropdown() {
    const dropdown = document.getElementById("login-dropdown");
    const dropdownWrapper = document.querySelector(
        "#signupModalathlete .dropdown-wrapper"
    );
    dropdown.classList.toggle("hidden");
    dropdownWrapper.classList.toggle("active");
}

document.addEventListener("DOMContentLoaded", function () {
    const loginDropdown = document.getElementById("login-dropdown");
    const loginSearchInput = document.getElementById("login-search-input");
    const loginCountryList = document.getElementById("login-country-list");
    const loginSelectedFlag = document.getElementById("selected-flag");
    const loginSelectedCode = document.getElementById("selected-code");

    if (loginDropdown && loginSearchInput && loginCountryList) {
        // Create country item for login modal
        function createLoginCountryItem(country) {
            const li = document.createElement("li");
            li.innerHTML = `<span class="fi fi-${country.code}"></span> ${country.name} (${country.dial_code})`;
            li.onclick = () => selectLoginCountry(country);
            return li;
        }

        // Populate countries for login modal
        function populateLoginCountries(list = countries) {
            loginCountryList.innerHTML = "";
            list.forEach((c) =>
                loginCountryList.appendChild(createLoginCountryItem(c))
            );
        }

        // Prevent login dropdown from closing when clicking inside it
        document.addEventListener("click", function (event) {
            const loginDropdownWrapper = document.querySelector(
                "#signupModalathlete .dropdown-wrapper"
            );

            if (
                !loginDropdown.classList.contains("hidden") &&
                !loginDropdown.contains(event.target) &&
                !loginDropdownWrapper.contains(event.target)
            ) {
                loginDropdown.classList.add("hidden");
                loginDropdownWrapper.classList.remove("active");
            }
        });

        function filterLoginCountries(query) {
            const filtered = countries.filter(
                (c) =>
                    c.name.toLowerCase().includes(query.toLowerCase()) ||
                    c.dial_code.includes(query)
            );
            populateLoginCountries(filtered);
        }

        // Initialize login dropdown
        populateLoginCountries();

        // Live search for login dropdown
        loginSearchInput.addEventListener("input", (e) =>
            filterLoginCountries(e.target.value)
        );
    }
});

function selectCountry(country) {
    selectedFlag.className = `fi fi-${country.code}`;
    selectedCode.textContent = country.dial_code;
    dropdown.classList.add("hidden");
    const dropdownWrapper = document.querySelector(".dropdown-wrapper");
    dropdownWrapper.classList.remove("active");
}

// Function for login modal country selection
function selectLoginCountry(country) {
    const loginSelectedFlag = document.getElementById("selected-flag");
    const loginSelectedCode = document.getElementById("selected-code");
    const loginDropdown = document.getElementById("login-dropdown");
    const loginDropdownWrapper = document.querySelector(
        "#signupModalathlete .dropdown-wrapper"
    );

    loginSelectedFlag.className = `fi fi-${country.code}`;
    loginSelectedCode.textContent = country.dial_code;
    loginDropdown.classList.add("hidden");
    loginDropdownWrapper.classList.remove("active");
}

// Function for quiz modal country selection
function selectQuizCountry(country) {
    const quizSelectedFlag = document.getElementById("quiz-selected-flag");
    const quizSelectedCode = document.getElementById("quiz-selected-code");
    const quizDropdown = document.getElementById("quiz-phone-dropdown");
    const quizDropdownWrapper = document.querySelector(
        "#quizModal .dropdown-wrapper"
    );

    quizSelectedFlag.className = `fi fi-${country.code}`;
    quizSelectedCode.textContent = country.dial_code;
    quizDropdown.classList.add("hidden");
    quizDropdownWrapper.classList.remove("active");
}

function filterCountries(query) {
    const filtered = countries.filter(
        (c) =>
            c.name.toLowerCase().includes(query.toLowerCase()) ||
            c.dial_code.includes(query)
    );
    populateCountries(filtered);
}

// Initialize
populateCountries();

// Initialize login dropdown
const loginCountryList = document.getElementById("login-country-list");
if (loginCountryList) {
    loginCountryList.innerHTML = "";
    countries.forEach((c) =>
        loginCountryList.appendChild(createCountryItem(c))
    );
}

// Live search
searchInput.addEventListener("input", (e) => filterCountries(e.target.value));

// Live search for login dropdown
const loginSearchInput = document.getElementById("login-search-input");
if (loginSearchInput) {
    loginSearchInput.addEventListener("input", (e) => {
        const query = e.target.value;
        const filtered = countries.filter(
            (c) =>
                c.name.toLowerCase().includes(query.toLowerCase()) ||
                c.dial_code.includes(query)
        );
        loginCountryList.innerHTML = "";
        filtered.forEach((c) =>
            loginCountryList.appendChild(createCountryItem(c))
        );
    });
}

// Add 'selected' class to the label of the selected ageGroup radio button
document.querySelectorAll('input[name="ageGroup"]').forEach(function (radio) {
    radio.addEventListener("change", function () {
        // Remove 'selected' class from all labels
        document
            .querySelectorAll('input[name="ageGroup"]')
            .forEach(function (r) {
                const label = r.closest("label");
                if (label) label.classList.remove("selected");
            });
        // Add 'selected' class to the label of the checked radio
        const selectedLabel = this.closest("label");
        if (selectedLabel) selectedLabel.classList.add("selected");
    });
});

// Scroll to contact section functionality
function scrollToContact() {
    const contactSection = document.querySelector("#contact-section");
    if (contactSection) {
        const offset = 80; // Offset in pixels from the top
        const elementPosition = contactSection.getBoundingClientRect().top;
        const offsetPosition = elementPosition + window.pageYOffset - offset;

        window.scrollTo({
            top: offsetPosition,
            behavior: "smooth",
        });
    }
}
