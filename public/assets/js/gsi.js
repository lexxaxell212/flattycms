function handleGSI(response) {
    fetch('/api/auth/gsi.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-Token': CONFIG.csrfToken
        },
        body: JSON.stringify({ token: response.credential })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) window.location.href = data.redirect;
    });
}

window.onGoogleLibraryLoad = function () {
    if (typeof google !== 'undefined' && google.accounts && google.accounts.id) {
        
        google.accounts.id.initialize({
            client_id: "353704633244-8jts0jtja4qlq58vd3b926h60j5psaka.apps.googleusercontent.com", 
            callback: handleGSI,
            auto_select: false,
            cancel_on_tap_outside: true
        });

        const buttonElement = document.getElementById("google-login-btn");
        if (buttonElement) {
            google.accounts.id.renderButton(buttonElement, {
                theme: "outline",
                size: "medium",
                type: "icon",
                shape: "circle"
            });
        }

        initGSIScrollObserver();
    }
};

function initGSIScrollObserver() {
    const targetElement = document.getElementById("show-gsi");

    if (targetElement) {
        const observer = new IntersectionObserver((entries, observerInstance) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    if (typeof google !== 'undefined' && google.accounts && google.accounts.id) {
                        
                        google.accounts.id.prompt();
                        
                        observerInstance.unobserve(entry.target);
                    }
                }
            });
        }, { 
            root: null, 
            threshold: 0.1 
        });

        observer.observe(targetElement);
    }
}
