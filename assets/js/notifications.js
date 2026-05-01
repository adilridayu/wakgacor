// Notification System with Sound Effect
function injectNotificationStyles() {
    if (document.getElementById('notification-styles')) return;

    const style = document.createElement('style');
    style.id = 'notification-styles';
    style.innerText = `
        .notification {
            position: fixed;
            top: 1rem;
            right: 1rem;
            max-width: 360px;
            padding: 1rem 1.25rem;
            border-radius: 0.75rem;
            box-shadow: 0 18px 40px rgba(0, 0, 0, 0.15);
            color: #fff;
            z-index: 9999;
            opacity: 0;
            transform: translateY(-10px);
            transition: opacity 0.3s ease, transform 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }
        .notification.show {
            opacity: 1;
            transform: translateY(0);
        }
        .notification.success {
            background-color: #16a34a;
        }
        .notification.error {
            background-color: #dc2626;
        }
        .notification .notification-icon {
            width: 2.25rem;
            height: 2.25rem;
            border-radius: 9999px;
            background: rgba(255, 255, 255, 0.15);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
        }
        .notification p {
            margin: 0;
            line-height: 1.5;
            font-weight: 600;
        }
    `;
    document.head.appendChild(style);
}

function showNotification(message, type = 'success') {
    injectNotificationStyles();

    const notification = document.createElement('div');
    notification.className = `notification show ${type}`;
    const icon = document.createElement('span');
    icon.className = 'notification-icon';
    icon.innerHTML = type === 'error' ? '&#x26A0;' : '&#x2714;';

    const text = document.createElement('p');
    text.innerText = message;

    notification.appendChild(icon);
    notification.appendChild(text);

    const scriptElement = document.currentScript || document.scripts[document.scripts.length - 1];
    const baseUrl = scriptElement ? scriptElement.src.replace(/\/js\/[^\/]+$/, '/') : '/assets/';
    const sound = new Audio(baseUrl + 'sounds/notification.mp3');
    sound.play().catch(() => {
        // Ignore autoplay restrictions.
    });

    document.body.appendChild(notification);

    setTimeout(() => {
        notification.classList.remove('show');
        notification.remove();
    }, 4000);
}

document.addEventListener('DOMContentLoaded', function () {
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('success')) {
        showNotification(urlParams.get('success'), 'success');
    }
    if (urlParams.has('error')) {
        showNotification(urlParams.get('error'), 'error');
    }
});