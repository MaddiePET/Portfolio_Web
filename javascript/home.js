document.getElementById('start-now').addEventListener('click', function() {
    const buttonSection = document.getElementById('button');
    buttonSection.style.opacity = 1;
    
    const fadeOutEffect = setInterval(function () {
        if (!buttonSection.style.opacity) {
            buttonSection.style.opacity = 1; // Gradually fade out the button section
        }
        if (buttonSection.style.opacity > 0) {
            buttonSection.style.opacity -= 0.1; // Reduce opacity
        } else {
            clearInterval(fadeOutEffect); // Stop fading and hide button
            buttonSection.style.display = 'none'; 
        }
    }, 50);

    setTimeout(function() {
        const featureSection = document.getElementById('key-features');
        featureSection.classList.remove('hidden'); // Show section
        featureSection.classList.add('show'); // Apply fade-in effect

        featureSection.scrollIntoView({ behavior: 'smooth' }); // Scroll to section
    }, 600); 
});
