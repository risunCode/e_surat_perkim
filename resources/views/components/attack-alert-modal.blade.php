{{-- Attack Alert Toast - Compact notification for admin --}}
@if(auth()->check() && auth()->user()->isAdmin())

{{-- Floating Alert Toast (Bottom Right) --}}
<div id="attackAlertToast" class="fixed bottom-4 right-4 z-[9999] hidden max-w-sm w-full">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl border-l-4 border-red-500 overflow-hidden">
        {{-- Header --}}
        <div class="flex items-center justify-between px-4 py-2 bg-red-500 text-white">
            <div class="flex items-center gap-2">
                <i class='bx bx-shield-x text-lg animate-pulse'></i>
                <span class="font-semibold text-sm">Attack Detected</span>
                <span id="alertBadge" class="bg-white/20 text-xs px-1.5 py-0.5 rounded-full hidden">+0</span>
            </div>
            <button onclick="hideAttackToast()" class="hover:bg-white/20 rounded p-0.5">
                <i class='bx bx-x text-lg'></i>
            </button>
        </div>
        
        {{-- Content --}}
        <div class="p-3 space-y-2">
            <div class="flex items-center gap-3">
                <div class="flex-1 grid grid-cols-2 gap-2 text-xs">
                    <div>
                        <span class="text-gray-500 dark:text-gray-400">Type</span>
                        <p id="attackType" class="font-bold text-red-600 dark:text-red-400">XSS</p>
                    </div>
                    <div>
                        <span class="text-gray-500 dark:text-gray-400">Severity</span>
                        <p id="attackSeverity" class="font-bold">HIGH</p>
                    </div>
                </div>
            </div>
            
            <div class="text-xs">
                <span class="text-gray-500 dark:text-gray-400">From</span>
                <p id="attackIp" class="font-mono font-semibold text-gray-900 dark:text-white">127.0.0.1</p>
            </div>
            
            <div class="text-xs">
                <span class="text-gray-500 dark:text-gray-400">Target</span>
                <p id="attackUrl" class="font-mono text-gray-600 dark:text-gray-300 truncate">/api/test</p>
            </div>
            
            <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400 pt-1 border-t border-gray-200 dark:border-gray-700">
                <span id="attackTime">Just now</span>
                <span id="attackCount" class="hidden"><span id="countNum">0</span> attacks</span>
            </div>
        </div>
        
        {{-- Actions --}}
        <div class="flex border-t border-gray-200 dark:border-gray-700 text-xs">
            <a href="{{ route('admin.attack-alerts.index') }}" onclick="markAlertsSeen()" class="flex-1 py-2 text-center text-white bg-red-500 hover:bg-red-600 transition-colors font-medium">
                <i class='bx bx-shield-quarter'></i> View Alerts
            </a>
            <button onclick="dismissFor30Min()" class="flex-1 py-2 text-center text-orange-600 hover:bg-orange-50 dark:hover:bg-orange-900/20 transition-colors border-l border-gray-200 dark:border-gray-700">
                <i class='bx bx-time'></i> Snooze 30m
            </button>
        </div>
    </div>
</div>

<script>
let attackPollInterval = null;
let dismissedUntil = null;
let seenAlertIds = [];

const DISMISS_KEY = 'attack_alert_snoozed_until';
const SEEN_KEY = 'attack_alert_seen_ids';

function initAttackAlertState() {
    // Check snooze
    const snoozed = localStorage.getItem(DISMISS_KEY);
    if (snoozed) {
        dismissedUntil = parseInt(snoozed);
        if (Date.now() > dismissedUntil) {
            localStorage.removeItem(DISMISS_KEY);
            dismissedUntil = null;
        }
    }
    
    // Load seen IDs
    try {
        seenAlertIds = JSON.parse(localStorage.getItem(SEEN_KEY) || '[]');
    } catch (e) {
        seenAlertIds = [];
    }
}

function startAttackPolling() {
    initAttackAlertState();
    checkAttacks();
    attackPollInterval = setInterval(checkAttacks, 10000); // Every 10s
}

async function checkAttacks() {
    // Skip if snoozed
    if (dismissedUntil && Date.now() < dismissedUntil) {
        console.log('[AttackAlert] Snoozed, skipping check');
        return;
    }
    
    try {
        console.log('[AttackAlert] Checking for attacks...');
        const response = await fetch('/api/attack-alerts/active');
        console.log('[AttackAlert] Response status:', response.status);
        if (!response.ok) {
            console.log('[AttackAlert] Response not OK');
            return;
        }
        
        const data = await response.json();
        console.log('[AttackAlert] Data:', data);
        console.log('[AttackAlert] Seen IDs:', seenAlertIds);
        
        if (data.count > 0) {
            // Filter out seen alerts - only show alerts not yet seen
            const unseenAlerts = data.alerts.filter(a => !seenAlertIds.includes(a.id));
            console.log('[AttackAlert] Unseen alerts:', unseenAlerts.length);
            
            if (unseenAlerts.length > 0) {
                showAttackToast(unseenAlerts[0], unseenAlerts.length);
            } else {
                hideAttackToast();
            }
        } else {
            // No active alerts - clear snooze and seen IDs
            clearAlertState();
            hideAttackToast();
        }
    } catch (e) {
        console.error('[AttackAlert] Poll error:', e);
    }
}

function showAttackToast(alert, count) {
    document.getElementById('attackType').textContent = alert.type.toUpperCase();
    
    const severityEl = document.getElementById('attackSeverity');
    severityEl.textContent = alert.severity.toUpperCase();
    severityEl.className = 'font-bold ' + getSeverityColor(alert.severity);
    
    document.getElementById('attackIp').textContent = alert.ip;
    document.getElementById('attackUrl').textContent = alert.url;
    document.getElementById('attackTime').textContent = alert.time;
    
    const badge = document.getElementById('alertBadge');
    const countEl = document.getElementById('attackCount');
    if (count > 1) {
        badge.textContent = '+' + (count - 1);
        badge.classList.remove('hidden');
        document.getElementById('countNum').textContent = count;
        countEl.classList.remove('hidden');
    } else {
        badge.classList.add('hidden');
        countEl.classList.add('hidden');
    }
    
    document.getElementById('attackAlertToast').classList.remove('hidden');
}

function hideAttackToast() {
    document.getElementById('attackAlertToast').classList.add('hidden');
}

// Mark all current alerts as seen (called when going to Attack Alerts page)
async function markAlertsSeen() {
    try {
        const response = await fetch('/api/attack-alerts/active');
        if (!response.ok) return;
        
        const data = await response.json();
        if (data.alerts) {
            // Add all current alert IDs to seen list
            data.alerts.forEach(a => {
                if (!seenAlertIds.includes(a.id)) {
                    seenAlertIds.push(a.id);
                }
            });
            // Keep only last 100 IDs
            if (seenAlertIds.length > 100) {
                seenAlertIds = seenAlertIds.slice(-100);
            }
            localStorage.setItem(SEEN_KEY, JSON.stringify(seenAlertIds));
        }
    } catch (e) {}
}

function dismissFor30Min() {
    hideAttackToast();
    dismissedUntil = Date.now() + (30 * 60 * 1000); // 30 minutes
    localStorage.setItem(DISMISS_KEY, dismissedUntil);
    
    if (typeof Toast !== 'undefined') {
        Toast.fire({ icon: 'info', title: 'Snoozed for 30 minutes' });
    }
}

function getSeverityColor(severity) {
    const colors = {
        'critical': 'text-red-600 dark:text-red-400',
        'high': 'text-orange-600 dark:text-orange-400',
        'medium': 'text-yellow-600 dark:text-yellow-400',
        'low': 'text-blue-600 dark:text-blue-400'
    };
    return colors[severity] || 'text-gray-600';
}

// Clear snooze and seen IDs when no active alerts
function clearAlertState() {
    localStorage.removeItem(DISMISS_KEY);
    localStorage.removeItem(SEEN_KEY);
    dismissedUntil = null;
    seenAlertIds = [];
    console.log('[AttackAlert] State cleared - no active alerts');
}

// Clear seen alerts when visiting attack alerts page
if (window.location.pathname.includes('/admin/attack-alerts')) {
    localStorage.removeItem(SEEN_KEY);
    seenAlertIds = [];
}

document.addEventListener('DOMContentLoaded', startAttackPolling);
</script>
@endif
