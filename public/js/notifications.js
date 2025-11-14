// Modern Apple-style notification system
const Notifications = {
    container: null,

    init() {
        if (!this.container) {
            this.container = document.createElement('div');
            this.container.id = 'notification-container';
            this.container.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                z-index: 10000;
                display: flex;
                flex-direction: column;
                gap: 12px;
                max-width: 400px;
            `;
            document.body.appendChild(this.container);
        }
    },

    show(message, type = 'info', duration = 4000) {
        this.init();

        const notification = document.createElement('div');
        notification.className = 'notification-apple notification-' + type;

        const icons = {
            success: 'bi-check-circle-fill',
            error: 'bi-x-circle-fill',
            warning: 'bi-exclamation-triangle-fill',
            info: 'bi-info-circle-fill'
        };

        const colors = {
            success: '#34c759',
            error: '#ff3b30',
            warning: '#ff9500',
            info: '#007aff'
        };

        notification.innerHTML = `
            <div style="display: flex; align-items: start; gap: 12px;">
                <i class="bi ${icons[type]}" style="font-size: 20px; color: ${colors[type]}; flex-shrink: 0; margin-top: 2px;"></i>
                <div style="flex: 1; font-size: 15px; line-height: 1.4;">${message}</div>
                <button onclick="this.parentElement.parentElement.remove()"
                        style="background: none; border: none; color: var(--text-tertiary); cursor: pointer; padding: 0; font-size: 20px; line-height: 1; flex-shrink: 0;">
                    <i class="bi bi-x"></i>
                </button>
            </div>
        `;

        notification.style.cssText = `
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(0, 0, 0, 0.1);
            border-radius: 12px;
            padding: 16px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            animation: slideInRight 0.3s ease-out;
            cursor: pointer;
        `;

        this.container.appendChild(notification);

        // Auto remove after duration
        if (duration > 0) {
            setTimeout(() => {
                notification.style.animation = 'slideOutRight 0.3s ease-out';
                setTimeout(() => notification.remove(), 300);
            }, duration);
        }

        // Click to dismiss
        notification.addEventListener('click', (e) => {
            if (e.target.tagName !== 'BUTTON') {
                notification.style.animation = 'slideOutRight 0.3s ease-out';
                setTimeout(() => notification.remove(), 300);
            }
        });
    },

    success(message, duration = 4000) {
        this.show(message, 'success', duration);
    },

    error(message, duration = 5000) {
        this.show(message, 'error', duration);
    },

    warning(message, duration = 4500) {
        this.show(message, 'warning', duration);
    },

    info(message, duration = 4000) {
        this.show(message, 'info', duration);
    },

    confirm(message, onConfirm, onCancel = null) {
        this.init();

        const overlay = document.createElement('div');
        overlay.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            z-index: 10001;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: fadeIn 0.2s ease-out;
        `;

        const modal = document.createElement('div');
        modal.style.cssText = `
            background: white;
            border-radius: 18px;
            padding: 24px;
            max-width: 400px;
            width: 90%;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: scaleIn 0.3s ease-out;
        `;

        modal.innerHTML = `
            <div style="font-size: 17px; line-height: 1.5; margin-bottom: 24px; color: var(--text-primary);">
                ${message}
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                <button class="btn-apple btn-apple-secondary" id="cancel-btn">Annuler</button>
                <button class="btn-apple btn-apple-primary" id="confirm-btn">Confirmer</button>
            </div>
        `;

        overlay.appendChild(modal);
        document.body.appendChild(overlay);

        const close = () => {
            overlay.style.animation = 'fadeOut 0.2s ease-out';
            setTimeout(() => overlay.remove(), 200);
        };

        modal.querySelector('#cancel-btn').addEventListener('click', () => {
            close();
            if (onCancel) onCancel();
        });

        modal.querySelector('#confirm-btn').addEventListener('click', () => {
            close();
            onConfirm();
        });

        overlay.addEventListener('click', (e) => {
            if (e.target === overlay) {
                close();
                if (onCancel) onCancel();
            }
        });
    }
};

// Add animations CSS
const style = document.createElement('style');
style.textContent = `
    @keyframes slideInRight {
        from {
            transform: translateX(400px);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes slideOutRight {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(400px);
            opacity: 0;
        }
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes fadeOut {
        from { opacity: 1; }
        to { opacity: 0; }
    }

    @keyframes scaleIn {
        from {
            transform: scale(0.9);
            opacity: 0;
        }
        to {
            transform: scale(1);
            opacity: 1;
        }
    }

    .notification-apple:hover {
        transform: translateY(-2px);
        box-shadow: 0 15px 50px rgba(0, 0, 0, 0.2);
        transition: all 0.2s ease;
    }
`;
document.head.appendChild(style);

// Make it globally available
window.Notifications = Notifications;
