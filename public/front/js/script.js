console.log('script.js loaded');

function enableDragScroll(selector) {
    const containers = document.querySelectorAll(selector);
    containers.forEach(container => {
        let isDown = false;
        let startX;
        let scrollLeft;

        container.addEventListener('mousedown', (e) => {
            isDown = true;
            container.classList.add('dragging');
            startX = e.pageX - container.offsetLeft;
            scrollLeft = container.scrollLeft;
        });
        container.addEventListener('mouseleave', () => {
            isDown = false;
            container.classList.remove('dragging');
        });
        container.addEventListener('mouseup', () => {
            isDown = false;
            container.classList.remove('dragging');
        });
        container.addEventListener('mousemove', (e) => {
            if (!isDown) return;
            e.preventDefault();
            const x = e.pageX - container.offsetLeft;
            const walk = (x - startX) * 1.5;
            container.scrollLeft = scrollLeft - walk;
        });

        // Touch events for mobile
        container.addEventListener('touchstart', (e) => {
            isDown = true;
            startX = e.touches[0].pageX - container.offsetLeft;
            scrollLeft = container.scrollLeft;
        });
        container.addEventListener('touchend', () => {
            isDown = false;
        });
        container.addEventListener('touchmove', (e) => {
            if (!isDown) return;
            const x = e.touches[0].pageX - container.offsetLeft;
            const walk = (x - startX) * 1.5;
            container.scrollLeft = scrollLeft - walk;
        });
    });
}

function enableWheelHorizontalScroll(selector) {
    document.querySelectorAll(selector).forEach(container => {
        container.addEventListener('wheel', function(e) {
            if (container.scrollWidth > container.clientWidth) {
                e.preventDefault();
                container.scrollLeft += e.deltaY;
            }
        }, { passive: false });
    });
}

function setupArrowScroll(wrapperSelector) {
    console.log('setupArrowScroll running');
    document.querySelectorAll(wrapperSelector).forEach(wrapper => {
        const scrollContainer = wrapper.querySelector('.horizontal-scroll');
        const leftArrow = wrapper.querySelector('.scroll-arrow-left');
        const rightArrow = wrapper.querySelector('.scroll-arrow-right');
      
        if (!scrollContainer) return;
        if (leftArrow) {
            leftArrow.addEventListener('click', function(e) {
                e.preventDefault();
                console.log('Left arrow clicked');
                scrollContainer.scrollBy({ left: -scrollContainer.clientWidth * 0.8, behavior: 'smooth' });
            });
        }
        if (rightArrow) {
            rightArrow.addEventListener('click', function(e) {
                e.preventDefault();
                console.log('Right arrow clicked');
                scrollContainer.scrollBy({ left: scrollContainer.clientWidth * 0.8, behavior: 'smooth' });
            });
        }
    });
}

document.addEventListener('DOMContentLoaded', function() {
    enableDragScroll('.horizontal-scroll');
    enableDragScroll('.challenge-cards');
    enableWheelHorizontalScroll('.horizontal-scroll');
    setupArrowScroll('.horizontal-scroll-arrow-wrapper');
});