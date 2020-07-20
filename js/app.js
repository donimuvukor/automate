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


//Countdown Clock
const deadline = 'July 31, 2020';
const clockItems = ['days', 'hours', 'minutes', 'seconds'];

function getTimeRemaining (endtime) {
    const total = Date.parse(endtime) - Date.parse( new Date() );
    const seconds = Math.floor( (total/1000) % 60 );
    const minutes = Math.floor( (total/1000/60) % 60 );
    const hours = Math.floor( (total/(1000*60*60)) % 24 );
    const days = Math.floor( total/(1000*60*60*24) );
    return {
        total,
        days,
        hours,
        minutes,
        seconds
    };
}

function initializeClock (id, endtime) {
    const clockItem = document.getElementById(id);
    const timeinterval = setInterval( () => {
        const timeRemaining = getTimeRemaining(endtime);
        clockItem.innerHTML = timeRemaining[clockItem.id];
        if (timeRemaining.total <= 0) {
            clearInterval(timeinterval);
        }
    }, 1000);
}

//Start all items of countdown clock
for (let i = 0; i < clockItems.length; i++) {
    initializeClock(clockItems[i], deadline);
}
