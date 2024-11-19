

$(document).ready(function() {

    // URL of your API endpoint

    const apiUrl = 'https://booking.biohealthpassport.com.au/api/booking-types/kerry-obryan';



    // Fetch data from the API

    $.ajax({

        url: apiUrl,

        method: 'GET',

        success: function(response) {

            if (response.success == true) {

                // Clear the container

                $('#bookingtypecontainer').empty();

                

                if(response.data && response.data.length){

                     // Iterate over each item in the data array

                        $.each(response.data, function(index, item) {

                            // Construct the HTML for each item

                            var html = `

                                <div class="media-entry p-0" data-aos="fade-up" data-aos-delay="${index * 100}">

                                    <div class="text-services">

                                        <h3 class="text-center">

                                            <a href="https://booking.biohealthpassport.com.au/kerry-obryan" class="">${item.name}</a>

                                        </h3>

                                    </div>

                                    <a href="https://booking.biohealthpassport.com.au/kerry-obryan">
                                        `;
                                        html += `<div class="booking-type-box">
                                        <img src="${item.thumbnail_image}" alt="${item.name}" class="img-fluid">`;
                                        if(item.description != null){
                                        html += `<div class="booking-desc-box">
                                                ${item.description}
                                            </div>
                                        </div>`;        
                                        }
                                    html += `</a>

                                    <div data-aos="fade-up">

                                        <a href="https://booking.biohealthpassport.com.au/kerry-obryan" class="btn btn-primary mt-2 w-100">

                                            <span class="me-1">View Services </span>

                                            <svg width="13" height="13" viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg">

                                                <path d="M10.2334 2.26696L0.821276 11.8513L10.2334 2.26696Z" fill="white"/>

                                                <path d="M11.2203 10.9062L11.3313 1.14895L1.57769 1.43685M10.2334 2.26696L0.821276 11.8513" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>

                                            </svg>

                                        </a>

                                    </div>

                                </div>

                            `;

        

                            // Append the generated HTML to the container

                            $('#bookingtypecontainer').append(html);

                        });   

                        

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

                } else {

                    $('.booking-type-main-div').hide();

                }

                

                var organization = response.organization;

                if(organization && organization.top) {

                    // Populate the top marquee

                    populateMarquee('marquee-top', organization.top, 250, 0);

                }

                

                if(organization && organization.bottom) {

                    // Populate the bottom marquee

                    populateMarquee('marquee-bottom', organization.bottom, 0, 150);

                }

                

                if(response && response.testimonials) {

                    var testimonialHtml = '';

                    $('.testimonial-section-main-div').show();

                    $.each(response.testimonials, function(index, testimonial){

                        testimonialHtml += `<div class="">

                            <div class="d-flex flex-sm-row flex-column align-items-center" id="using-${index}">

                                <div class="pe-xxl-5 pe-sm-3 mb-sm-0 mb-3 border-custom-left">

                                    <img src="${testimonial.image}" alt="" class="slider-added-imges" style="">

                                </div>

                                <div class="no-display-half-part">

                                    <div class="quote-using">

                                        <i class="fa-solid fa-quote-left"></i>

                                    </div>

                                    <p class="mb-1">${testimonial.review}</p>

                                    <h5 class="mb-0">${testimonial.name}</h5>

                                    <p class="position mb-0">${testimonial.designation}</p>

                                </div>

                            </div>

                        </div>`;

                    });

                    $('.wide-slider-testimonial-two').html(testimonialHtml);

                    

                    var sliderTwo = tns({

                    	container: ".wide-slider-testimonial-two",

                    	items: 1,

                    	slideBy: 1,

                    	axis: "horizontal",

                    	swipeAngle: false,

                    	speed: 700,

                    	nav: false,

                    	loop: true,

                    	edgePadding: 0, // Set edge padding to 50%

                    	controls: true,

                    	controlsPosition: "bottom",

                    	autoHeight: true,

                    	autoplay: false,

                    	mouseDrag: false,

                    	autoplayHoverPause: true,

                    	autoplayTimeout: 3500,

                    	autoplayButtonOutput: false,

                    	controlsContainer: "#prevnext-testimonial-one", 

                    	gutter: 20, // Add a 20px gap between slides

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

                    	}

                      });

                } else {

                    $('.testimonial-section-main-div').hide();

                }

            }

            else {

                $('.booking-type-main-div').hide();   

                $('.testimonial-section-main-div').hide();

            }

        },

        error: function(xhr, status, error) {

            console.error('Failed to fetch data from API:', error);

        }

    });

    

    function populateMarquee(marqueeId, images, imgWidth, imgHeight) {

        var $marqueeGroups = $('#' + marqueeId + ' .marquee__group');

        $marqueeGroups.each(function(index) {

            var $group = $(this);

            images.forEach(function(img) {

                var $imgDiv = $('<div></div>');

                var $img = $('<img/>', {

                    src: img,

                    alt: 'client',

                    class: 'img-fluid',

                    width: imgWidth,

                    height: imgHeight

                });

                $imgDiv.append($('<span></span>').append($img));

                $group.append($imgDiv);

            });

        });

    }

});