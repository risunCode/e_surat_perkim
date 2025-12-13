{{-- Toast Notification Container - Top Right below navbar in main content --}}
<div id="toast-container"></div>

<style>
    /* Toast Container - Positioned in main content area (right side, below navbar) */
    #toast-container {
        position: fixed;
        top: 80px; /* Below navbar h-16 (64px) + some padding */
        right: 20px;
        display: flex;
        flex-direction: column;
        gap: 12px;
        pointer-events: none;
        max-width: 360px;
    }
    
    /* Toast Notification Styles */
    .toast-notification {
        pointer-events: auto;
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 14px 16px;
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        cursor: pointer;
        transform: translateX(400px);
        opacity: 0;
        transition: all 0.3s ease-out;
        width: 340px;
    }
    
    .toast-notification.show {
        transform: translateX(0);
        opacity: 1;
    }
    
    .toast-notification:hover {
        border-color: var(--accent-color);
        box-shadow: 0 10px 50px rgba(0,0,0,0.2);
    }
    
    .toast-icon {
        flex-shrink: 0;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--accent-color);
        color: white;
        border-radius: 10px;
        font-size: 20px;
    }
    
    .toast-content {
        flex: 1;
        min-width: 0;
    }
    
    .toast-title {
        font-weight: 600;
        font-size: 14px;
        color: var(--text-primary);
        margin-bottom: 2px;
    }
    
    .toast-message {
        font-size: 12px;
        color: var(--text-secondary);
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    
    .toast-close {
        flex-shrink: 0;
        width: 28px;
        height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #666;
        background: rgba(0,0,0,0.08);
        border-radius: 50%;
        font-size: 18px;
        transition: all 0.2s;
    }
    
    .toast-close:hover {
        background: rgba(220,53,69,0.15);
        color: #dc3545;
    }
    
    /* Badge pulse animation */
    @keyframes badge-pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.1); }
    }
    
    .animate-pulse {
        animation: badge-pulse 2s ease-in-out infinite;
    }
</style>

{{-- Real-time Notification Script --}}
<script src="{{ asset('js/realtime-notifications.js') }}"></script>
