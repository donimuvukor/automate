//Initialise glide js slider
new Glide('.glide', 
    {
        type: 'slider',
        startAt: 0,
        perView: 2,
        autoplay: 2500,
        bound: true,
        breakpoints: {
            600: {
                perView: 1
            },
            900: {
                perView: 2
            }
        }
    }
).mount()


//Initialise animate on scroll effects
AOS.init();