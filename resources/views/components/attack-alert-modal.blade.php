{{-- Attack Alert Modal - Shows when attack is detected --}}
<div id="attackAlertModal" class="fixed inset-0 z-[9999] hidden">
    {{-- Backdrop --}}
    <div class="fixed inset-0 bg-red-900/80 backdrop-blur-sm"></div>
    
    {{-- Modal --}}
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl max-w-4xl w-full border-4 border-red-500 animate-pulse-border overflow-hidden">
            {{-- Header --}}
            <div class="bg-red-600 text-white p-4 flex items-center gap-3">
                <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                    <i class='bx bx-shield-x text-3xl animate-pulse'></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold">⚠️ PERINGATAN KEAMANAN</h2>
                    <p class="text-red-100 text-sm">Serangan terdeteksi pada sistem</p>
                </div>
            </div>
            
            {{-- Content --}}
            <div class="p-6">
                <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4 mb-4">
                    <p class="text-red-800 dark:text-red-200 font-medium mb-2">
                        <i class='bx bx-error-circle'></i> Sistem mendeteksi percobaan serangan!
                    </p>
                    <p class="text-red-600 dark:text-red-300 text-sm">
                        Tim keamanan telah diberitahu dan sedang menangani masalah ini.
                    </p>
                </div>

                {{-- Attack Details --}}
                <div id="attackDetails" class="space-y-3 text-sm">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-gray-100 dark:bg-gray-800 rounded-lg p-3">
                            <span class="text-gray-500 dark:text-gray-400 text-xs">Jenis Serangan</span>
                            <p id="attackType" class="font-semibold text-red-600 dark:text-red-400 uppercase">-</p>
                        </div>
                        <div class="bg-gray-100 dark:bg-gray-800 rounded-lg p-3">
                            <span class="text-gray-500 dark:text-gray-400 text-xs">Tingkat Bahaya</span>
                            <p id="attackSeverity" class="font-semibold uppercase">-</p>
                        </div>
                    </div>
                    <div class="bg-gray-100 dark:bg-gray-800 rounded-lg p-3">
                        <span class="text-gray-500 dark:text-gray-400 text-xs">IP Address Penyerang</span>
                        <p id="attackIp" class="font-mono font-semibold">-</p>
                    </div>
                    <div class="bg-gray-100 dark:bg-gray-800 rounded-lg p-3">
                        <span class="text-gray-500 dark:text-gray-400 text-xs">Target URL</span>
                        <p id="attackUrl" class="font-mono text-xs break-all">-</p>
                    </div>
                    <div class="bg-gray-100 dark:bg-gray-800 rounded-lg p-3">
                        <span class="text-gray-500 dark:text-gray-400 text-xs">Browser / User Agent</span>
                        <p id="attackUserAgent" class="font-mono text-xs break-all">-</p>
                    </div>
                    <div class="bg-gray-100 dark:bg-gray-800 rounded-lg p-3">
                        <span class="text-gray-500 dark:text-gray-400 text-xs">Waktu Terdeteksi</span>
                        <p id="attackTime" class="font-semibold">-</p>
                    </div>
                </div>

                {{-- Multiple Alerts --}}
                <div id="multipleAlerts" class="hidden mt-4 p-3 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg">
                    <p class="text-yellow-800 dark:text-yellow-200 text-sm">
                        <i class='bx bx-info-circle'></i> 
                        <span id="alertCount">0</span> serangan terdeteksi dalam 1 jam terakhir
                    </p>
                </div>
            </div>
            
            {{-- Footer --}}
            <div class="bg-gray-100 dark:bg-gray-800 p-4 flex items-center justify-between">
                <p class="text-xs text-gray-500 dark:text-gray-400">
                    <i class='bx bx-lock-alt'></i> Hanya admin yang dapat menutup peringatan ini
                </p>
                @if(auth()->check() && auth()->user()->isAdmin())
                <button onclick="dismissAttackAlert()" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2">
                    <i class='bx bx-check'></i> Dismiss Alert
                </button>
                @else
                <span class="text-xs text-red-500 font-medium">
                    <i class='bx bx-lock'></i> Menunggu admin...
                </span>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
@keyframes pulse-border {
    0%, 100% { border-color: rgb(239 68 68); }
    50% { border-color: rgb(252 165 165); }
}
.animate-pulse-border {
    animation: pulse-border 1s ease-in-out infinite;
}
</style>

<script>
let attackAlertInterval = null;
let currentAlertId = null;

// Check for active alerts every 30 seconds
function startAttackAlertPolling() {
    checkAttackAlerts();
    attackAlertInterval = setInterval(checkAttackAlerts, 30000);
}

async function checkAttackAlerts() {
    try {
        const response = await fetch('/api/attack-alerts/active');
        const data = await response.json();
        
        if (data.count > 0) {
            showAttackAlert(data.alerts[0], data.count);
        }
    } catch (error) {
        console.error('Failed to check attack alerts:', error);
    }
}

function showAttackAlert(alert, totalCount) {
    currentAlertId = alert.id;
    
    document.getElementById('attackType').textContent = alert.type.toUpperCase();
    document.getElementById('attackSeverity').textContent = alert.severity.toUpperCase();
    document.getElementById('attackSeverity').className = `font-semibold uppercase ${getSeverityColor(alert.severity)}`;
    document.getElementById('attackIp').textContent = alert.ip;
    document.getElementById('attackUrl').textContent = alert.url;
    document.getElementById('attackUserAgent').textContent = alert.user_agent || 'Unknown';
    document.getElementById('attackTime').textContent = alert.time;
    
    if (totalCount > 1) {
        document.getElementById('multipleAlerts').classList.remove('hidden');
        document.getElementById('alertCount').textContent = totalCount;
    } else {
        document.getElementById('multipleAlerts').classList.add('hidden');
    }
    
    document.getElementById('attackAlertModal').classList.remove('hidden');
}

function getSeverityColor(severity) {
    switch (severity) {
        case 'critical': return 'text-red-600 dark:text-red-400';
        case 'high': return 'text-orange-600 dark:text-orange-400';
        case 'medium': return 'text-yellow-600 dark:text-yellow-400';
        case 'low': return 'text-blue-600 dark:text-blue-400';
        default: return 'text-gray-600 dark:text-gray-400';
    }
}

async function dismissAttackAlert() {
    if (!currentAlertId) return;
    
    try {
        const response = await fetch(`/admin/attack-alerts/${currentAlertId}/dismiss`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        
        if (response.ok) {
            document.getElementById('attackAlertModal').classList.add('hidden');
            currentAlertId = null;
        }
    } catch (error) {
        console.error('Failed to dismiss alert:', error);
    }
}

// Start polling when page loads
document.addEventListener('DOMContentLoaded', startAttackAlertPolling);
</script>
