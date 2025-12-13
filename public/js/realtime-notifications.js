/**
 * Real-time Notification System for E-Surat Perkim
 * Features: Badge counts, Toast notifications, Sound alerts
 */

(function() {
    'use strict';

    // Configuration
    const POLL_INTERVAL = 30000; // 30 seconds
    
    // State
    let lastNotificationIds = new Set();
    let isFirstLoad = true;
    let audioContext = null;

    // Initialize Web Audio API
    function initSound() {
        if (audioContext) return; // Already initialized
        
        try {
            audioContext = new (window.AudioContext || window.webkitAudioContext)();
            // Resume immediately if possible
            if (audioContext.state === 'suspended') {
                audioContext.resume();
            }
            console.log('🔊 Audio initialized');
        } catch (e) {
            console.log('Web Audio API not supported');
        }
    }
    
    // Initialize audio on first user interaction (browser policy)
    function initSoundOnInteraction() {
        if (audioContext) return;
        initSound();
        // Remove listeners after first init
        document.removeEventListener('click', initSoundOnInteraction);
        document.removeEventListener('keydown', initSoundOnInteraction);
        document.removeEventListener('touchstart', initSoundOnInteraction);
    }

    /**
     * Play notification sound using Web Audio API (no external file needed)
     * 
     * SOUND OPTIONS (jangan dihapus - cadangan jika ingin diganti):
     * ============================================================
     * 
     * [ACTIVE] Windows Style Extended (~1.5s):
     * [[440,0,0.18],[554,0.15,0.18],[659,0.3,0.18],[880,0.45,0.25],[659,0.65,0.12],[880,0.75,0.12],[1047,0.88,0.5]]
     * 
     * [BACKUP] Office Chime Professional (~1.3s):
     * [[440,0,0.2],[554,0.12,0.2],[659,0.24,0.2],[880,0.36,0.3],[659,0.55,0.15],[880,0.68,0.15],[1047,0.82,0.5]]
     * 
     * Cara ganti: copy array notes yang diinginkan, paste ke const notes dibawah
     */
    function playNotificationSound() {
        // Auto-init if not yet initialized
        if (!audioContext) {
            initSound();
            if (!audioContext) return; // Still failed, give up
        }
        
        try {
            // Resume context if suspended (browser autoplay policy)
            if (audioContext.state === 'suspended') {
                audioContext.resume();
            }

            const now = audioContext.currentTime;
            
            // Windows Style Extended - ascending dengan bounce ending
            const notes = [
                [440, 0, 0.18],      // A4
                [554, 0.15, 0.18],   // C#5
                [659, 0.3, 0.18],    // E5
                [880, 0.45, 0.25],   // A5 (peak)
                [659, 0.65, 0.12],   // E5 (bounce)
                [880, 0.75, 0.12],   // A5 (bounce)
                [1047, 0.88, 0.5],   // C6 (final)
            ];
            
            notes.forEach(([freq, start, duration]) => {
                const osc = audioContext.createOscillator();
                const gain = audioContext.createGain();
                
                osc.connect(gain);
                gain.connect(audioContext.destination);
                
                osc.type = 'sine';
                osc.frequency.value = freq;
                
                // Envelope: smooth attack and release
                gain.gain.setValueAtTime(0, now + start);
                gain.gain.linearRampToValueAtTime(0.22, now + start + 0.03);
                gain.gain.exponentialRampToValueAtTime(0.001, now + start + duration);
                
                osc.start(now + start);
                osc.stop(now + start + duration);
            });
        } catch (e) {
            // Silently fail
        }
    }

    // Show toast notification (bottom-left slide)
    function showToastNotification(notification) {
        const container = document.getElementById('toast-container');
        if (!container) return;

        const toast = document.createElement('div');
        toast.className = 'toast-notification';
        toast.innerHTML = `
            <div class="toast-icon">
                <i class="bx ${notification.icon}"></i>
            </div>
            <div class="toast-content">
                <p class="toast-title">${notification.title}</p>
                <p class="toast-message">${notification.message}</p>
            </div>
            <button class="toast-close" onclick="this.parentElement.remove()">
                <i class="bx bx-x"></i>
            </button>
        `;

        // Click to navigate
        toast.addEventListener('click', (e) => {
            if (!e.target.closest('.toast-close')) {
                markAndGo(notification.id, notification.link);
            }
        });

        container.appendChild(toast);

        // Animate in
        setTimeout(() => toast.classList.add('show'), 10);

        // Auto remove after 8 seconds
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 300);
        }, 8000);
    }

    // Update sidebar badges
    function updateBadges(data) {
        const incomingBadge = document.getElementById('badge-incoming');
        const outgoingBadge = document.getElementById('badge-outgoing');

        if (incomingBadge) {
            if (data.incoming_count > 0) {
                incomingBadge.textContent = data.incoming_count;
                incomingBadge.classList.remove('hidden');
            } else {
                incomingBadge.classList.add('hidden');
            }
        }

        if (outgoingBadge) {
            if (data.outgoing_count > 0) {
                outgoingBadge.textContent = data.outgoing_count;
                outgoingBadge.classList.remove('hidden');
            } else {
                outgoingBadge.classList.add('hidden');
            }
        }
    }

    // Fetch notifications with enhanced data
    function fetchNotificationsEnhanced() {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
        
        fetch('/notifications/realtime', {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        })
        .then(response => response.json())
        .then(data => {
            // Update navbar badge
            const navBadge = document.getElementById('notification-badge');
            if (navBadge) {
                if (data.unread_count > 0) {
                    navBadge.textContent = data.unread_count > 99 ? '99+' : data.unread_count;
                    navBadge.classList.remove('hidden');
                } else {
                    navBadge.classList.add('hidden');
                }
            }

            // Update sidebar badges
            updateBadges(data);

            // Update notification dropdown
            const container = document.getElementById('notification-container');
            if (container && data.notifications) {
                container.innerHTML = data.notifications.map(n => `
                    <div onclick="markAndGo(${n.id}, '${n.link}')" class="cursor-pointer block px-4 py-3 hover:opacity-80 border-b transition-colors ${n.is_read ? 'opacity-60' : ''}" style="border-color: var(--border-color); ${n.is_read ? 'background-color: var(--bg-input);' : ''}">
                        <div class="flex items-start gap-3">
                            <i class="bx ${n.icon} text-xl" style="color: ${n.is_read ? 'var(--text-secondary)' : 'var(--accent-color)'};"></i>
                            <div>
                                <p class="text-sm ${n.is_read ? '' : 'font-medium'}" style="color: ${n.is_read ? 'var(--text-secondary)' : 'var(--text-primary)'};">${n.title}</p>
                                <p class="text-xs" style="color: var(--text-secondary);">${n.message}</p>
                            </div>
                        </div>
                    </div>
                `).join('') || '<p class="px-4 py-3 text-sm" style="color: var(--text-secondary);">Tidak ada notifikasi</p>';
            }

            // Check for new notifications (not on first load)
            if (!isFirstLoad && data.notifications) {
                const currentIds = new Set(data.notifications.filter(n => !n.is_read).map(n => n.id));
                
                data.notifications.forEach(n => {
                    if (!n.is_read && !lastNotificationIds.has(n.id)) {
                        // New notification!
                        showToastNotification(n);
                        playNotificationSound();
                    }
                });

                lastNotificationIds = currentIds;
            } else if (data.notifications) {
                // First load - just store IDs
                lastNotificationIds = new Set(data.notifications.filter(n => !n.is_read).map(n => n.id));
            }

            isFirstLoad = false;
        })
        .catch(error => {
            console.error('Failed to fetch notifications:', error);
        });
    }

    // Initialize on DOM ready
    document.addEventListener('DOMContentLoaded', function() {
        // Create toast container if not exists
        if (!document.getElementById('toast-container')) {
            const container = document.createElement('div');
            container.id = 'toast-container';
            document.body.appendChild(container);
        }

        // Add listeners to init audio on first user interaction (browser autoplay policy)
        document.addEventListener('click', initSoundOnInteraction);
        document.addEventListener('keydown', initSoundOnInteraction);
        document.addEventListener('touchstart', initSoundOnInteraction);

        // Initial fetch
        fetchNotificationsEnhanced();

        // Poll for updates
        setInterval(fetchNotificationsEnhanced, POLL_INTERVAL);
    });

    // Mark notification as read and navigate
    function markAndGo(notificationId, link) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
        
        // Prevent default if called from event
        if (event) {
            event.preventDefault();
            event.stopPropagation();
        }
        
        // Navigate first, mark as read in background
        if (link && link !== '#' && link !== 'null' && link !== 'undefined') {
            // Mark as read asynchronously (fire and forget)
            fetch(`/notifications/${notificationId}/read`, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                credentials: 'same-origin'
            }).catch(() => {}); // Ignore errors
            
            // Navigate immediately
            window.location.href = link;
        } else {
            // No link, just mark as read
            fetch(`/notifications/${notificationId}/read`, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                credentials: 'same-origin'
            })
            .then(() => fetchNotificationsEnhanced())
            .catch(() => {});
        }
        
        return false;
    }

    // Mark all notifications as read
    function markAllAsRead() {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
        
        fetch('/notifications/read-all', {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        })
        .then(() => {
            // Refresh badges immediately
            fetchNotificationsEnhanced();
        });
    }

    // Expose functions globally
    window.refreshNotifications = fetchNotificationsEnhanced;
    window.markAndGo = markAndGo;
    window.markAllAsRead = markAllAsRead;
    
    // Test sound only (untuk testing di console)
    window.testNotificationSound = function() {
        if (!audioContext) initSound();
        playNotificationSound();
        console.log('🔔 Playing notification sound...');
    };
    
    // Test toast + sound (simulasi notifikasi baru)
    window.testNotification = function() {
        if (!audioContext) initSound();
        showToastNotification({
            id: 0,
            icon: 'bx-envelope-open',
            title: 'Test Notifikasi',
            message: 'Ini adalah test notifikasi dari console',
            link: '#'
        });
        playNotificationSound();
        console.log('🔔 Test notification triggered!');
    };
})();
