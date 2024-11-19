


(function () {

	'use strict'

	
	AOS.init({
		duration: 800,
		easing: 'slide',
		once: true
	});

	var rellax = new Rellax('.rellax');

	var preloader = function() {

		var loader = document.querySelector('.loader');
		var overlay = document.getElementById('overlayer');

		function fadeOut(el) {
			el.style.opacity = 1;
			(function fade() {
				if ((el.style.opacity -= .1) < 0) {
					el.style.display = "none";
				} else {
					requestAnimationFrame(fade);
				}
			})();
		};

		setTimeout(function() {
			fadeOut(loader);
			fadeOut(overlay);
		}, 200);
	};
	preloader();
var tinyslier = function() {
	// Initialize the sliders
// var sliderOne = tns({
// 	container: ".wide-slider-testimonial-one",
// 	items: 1,
// 	slideBy: 1,
// 	axis: "horizontal",
// 	swipeAngle: false,
// 	speed: 700,
// 	nav: false,
// 	loop: true,
// 	edgePadding: 0, // Set edge padding to 50%
// 	controls: true,
// 	controlsPosition: "bottom",
// 	autoHeight: true,
// 	autoplay:false,
// 	mouseDrag: false,
// 	autoplayHoverPause: true,
// 	autoplayTimeout: 3500,
// 	autoplayButtonOutput: false,
// 	controlsContainer: "#prevnext-testimonial-one",
// 	responsive: {
// 	  350: {
// 		items: 1
// 	  },
// 	  500: {
// 		items: 1
// 	  },
// 	  600: {
// 		items: 1
// 	  },
// 	  900: {
// 		items: 1
// 	  },
// 	  1600: {
// 		items: 1
// 	  }
// 	}
//   });
  
//   var sliderTwo = tns({
// 	container: ".wide-slider-testimonial-two",
// 	items: 1,
// 	slideBy: 1,
// 	axis: "horizontal",
// 	swipeAngle: false,
// 	speed: 700,
// 	nav: false,
// 	loop: true,
// 	edgePadding: 0, // Set edge padding to 50%
// 	controls: true,
// 	controlsPosition: "bottom",
// 	autoHeight: true,
// 	autoplay: false,
// 	mouseDrag: false,
// 	autoplayHoverPause: true,
// 	autoplayTimeout: 3500,
// 	autoplayButtonOutput: false,
// 	controlsContainer: "#prevnext-testimonial-one", 
// 	gutter: 20, // Add a 20px gap between slides
// 	responsive: {
// 	  350: {
// 		items: 1
// 	  },
// 	  500: {
// 		items: 1
// 	  },
// 	  600: {
// 		items: 1
// 	  },
// 	  900: {
// 		items: 1
// 	  },
// 	  1600: {
// 		items: 1
// 	  }
// 	}
//   });
  
//   var sliderThree = tns({
// 	container: ".wide-slider-testimonial-three",
// 	items: 1,
// 	slideBy: 1,
// 	axis: "horizontal",
// 	swipeAngle: false,
// 	speed: 700,
// 	nav: false,
// 	loop: true,
// 	edgePadding: 0, // Set edge padding to 50%
// 	controls: true,
// 	controlsPosition: "bottom",
// 	autoHeight: true,
// 	autoplay: false,
// 	mouseDrag: false,
// 	autoplayHoverPause: true,
// 	autoplayTimeout: 3500,
// 	autoplayButtonOutput: false,
// 	controlsContainer: "#prevnext-testimonial-one",
// 	responsive: {
// 	  350: {
// 		items: 1
// 	  },
// 	  500: {
// 		items: 1
// 	  },
// 	  600: {
// 		items: 1
// 	  },
// 	  900: {
// 		items: 1
// 	  },
// 	  1600: {
// 		items: 1
// 	  }
// 	}
//   });
// var wsttInterval = setInterval(() => {
//     console.log(1);
//         if($('.wide-slider-testimonial-three').length) {
//             var sliderThree = tns({
//                 container: ".wide-slider-testimonial-three",
//                 items: 1,
//                 slideBy: 1,
//                 axis: "horizontal",
//                 swipeAngle: false,
//                 speed: 700,
//                 nav: false,
//                 loop: true,
//                 edgePadding: 0, // Set edge padding to 50%
//                 controls: true,
//                 controlsPosition: "bottom",
//                 autoHeight: true,
//                 autoplay: false,
//                 mouseDrag: false,
//                 autoplayHoverPause: true,
//                 autoplayTimeout: 3500,
//                 autoplayButtonOutput: false,
//                 controlsContainer: "#prevnext-testimonial-one",
//                 responsive: {
//                 350: {
//                     items: 1
//                 },
//                 500: {
//                     items: 1
//                 },
//                 600: {
//                     items: 1
//                 },
//                 900: {
//                     items: 1
//                 },
//                 1600: {
//                     items: 1
//                 }
//                 }
//             });

//             clearInterval(wsttInterval);
//         }
//     }, 100);
  
// Get the arrow elements for each slider
var sliderOnePrevArrow = document.querySelector('.wide-slider-testimonial-one .prev');
var sliderOneNextArrow = document.querySelector('.wide-slider-testimonial-one .next');

// Add event listeners to the arrow elements
// sliderOnePrevArrow.addEventListener('click', function() {
//   sliderOne.goTo('prev');
// });

// sliderOneNextArrow.addEventListener('click', function() {
//   sliderOne.goTo('next');
// });

}
tinyslier();


	var tinyslier = function() {
	var el = document.querySelectorAll('.wide-slider-testimonial');
	if (el.length > 0) {
		var slider = tns({
		  container: ".wide-slider-testimonial",
		  items: 1,
		  slideBy: 1,
		  axis: "horizontal",
		  swipeAngle: false,
		  speed: 700,
		  nav: false,
		  loop: true,
		  edgePadding: 250, // Set edge padding to 50%
		  controls: true,
		  controlsPosition: "bottom",
		  autoHeight: true,
		  autoplay: false,
		  mouseDrag: true,
		  autoplayHoverPause: true,
		  autoplayTimeout: 3500,
		  autoplayButtonOutput: false,
		  controlsContainer: "#prevnext-testimonial",
		  responsive: {
			350: {
			  items: 1
			},
			500: {
			  items: 1
			},
			600: {
			  items: 1
			},
			900: {
			  items: 1
			},
			1600: {
			  items: 1
			}
		  },
		});
	  }


		var destinationSlider = document.querySelectorAll('.destination-slider');

		if ( destinationSlider.length > 0 ) {
			var desSlider = tns({
				container: ".destination-slider",
				mouseDrag: true,
				items: 1,
				axis: "horizontal",
				swipeAngle: true,
				speed: 700,
				loop: false,
				// edgePadding: 50,
				nav: false,
				gutter: 30,
				autoplay: true,
				autoplayButtonOutput: false,
				controls: true,
				controlsPosition: "center",
				controlsContainer: "#destination-controls",
				responsive: {
					350: {
						items: 1
					},
					
					500: {
						items: 1
					},
					600: {
						items: 1
					},
					900: {
						items: 2
					}
				},
			})
		}

		var clientSlider1 = document.querySelectorAll('.client-slider');

		if ( clientSlider1.length > 0 ) {
			var cliSlider = tns({
				container: ".client-slider",
				mouseDrag: true,
				items: 6,
				axis: "horizontal",
				swipeAngle: true,
				speed: 700,
				loop: false,
				// edgePadding: 50,
				nav: false,
				gutter: 30,
				autoplay: true,
				autoplayButtonOutput: false,
				controls: true,
				controlsPosition: "center",
				controlsContainer: "#prevnext-client",
				responsive: {
					350: {
						items: 3
					},
					
					500: {
						items: 3
					},
					600: {
						items: 4
					},
					900: {
						items: 5
					}
				},
			})
		}

		var serviceSection = document.querySelectorAll('.service-sec');

		if ( serviceSection.length > 0 ) {
			var cliSlider = tns({
				container: ".service-sec",
				mouseDrag: true,
				items: 2,
				axis: "horizontal",
				swipeAngle: true,
				speed: 700,
				loop: false,
				nav: false,
				gutter: 20,
				autoplay: true,
				autoplayButtonOutput: false,
				controls: true,
				controlsPosition: "center",
				controlsContainer: "#prevnext-service-one",
				responsive: {
					350: {
						items: 1
					},
					
					500: {
						items: 1
					},
					600: {
						items: 2
					},
					900: {
						items: 3
					},
					1400: {
						items: 4
					},
					1800: {
						items: 5
					}
				},
			})
		}



	}
	tinyslier();

	


	var lightbox = function() {
		var lightboxVideo = GLightbox({
			selector: '.glightbox3'
		});
	};
	lightbox();



})();