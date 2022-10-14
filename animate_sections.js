// https://jsbin.com/wogeyey/1/edit
const details = document.querySelectorAll('details');

// Step 1: open ALL the detail tags.
details.forEach(detail =>{
    detail.open = true;
});

// Step 2: initialize the open/closed heights, and attach the event listener.
details.forEach(detail => {
    const detailContent = detail.querySelector('div');

    // close the detail to get its height when closed
    detail.open = false;
    const detailClosedHeight = detail.scrollHeight;
    detailContent.style.setProperty('--details-content-height-closed', detailContent.scrollHeight + 'px');
    detail.style.setProperty('--details-height-closed', detailClosedHeight + 'px');

    // open it back up so any nested details get handled correctly
    detail.open = true;

    // pass it to the the element as CSS property
    detailContent.style.setProperty('--details-content-height-open',
                                    detailContent.scrollHeight + 'px');
    detail.style.setProperty('--details-height-open', detailContent.scrollHeight + detailClosedHeight + 'px');
    //detail.style.setProperty('--details-height-open', detailContent.scrollHeight + 'px');

    detail.addEventListener('click', (ev) => {
        const container = ev.target.parentElement;
        // get time of transition from CSS property
        const timeout = getComputedStyle(container.querySelector('div')).getPropertyValue('--details-transition-time');
        console.log(getComputedStyle(container.querySelector('div')).getPropertyValue('--details-transition-time'));
        console.log(getComputedStyle(container.querySelector('div')).getPropertyValue('--details-height-closed'));
        
        // we can't use [open] as it will be only removed after the transition
        container.classList.toggle('is--open');
        
        // remove the open attribute once the transition is done, because otherwise we won't see the transition
        if (container.open) {
            ev.preventDefault();
            setTimeout(function() {
                container.open = false;
            }, parseInt(timeout))
        }
    })
});

// Step 3: close the detail tags again.
details.forEach(detail =>{
    detail.open = false;
});
