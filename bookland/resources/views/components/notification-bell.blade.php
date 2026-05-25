@once
@push('styles')
<style>
@keyframes bell-ring {
    0%, 100% { transform: rotate(0); }
    15% { transform: rotate(10deg); }
    30% { transform: rotate(-10deg); }
    45% { transform: rotate(5deg); }
    60% { transform: rotate(-5deg); }
    75% { transform: rotate(2deg); }
    85% { transform: rotate(-2deg); }
}
.animate-bell {
    animation: bell-ring 1.5s ease infinite;
    transform-origin: top center;
}
.bell-link:hover svg {
    color: var(--blue) !important;
}
</style>
@endpush
@endonce

<div x-data="notificationBell()" class="d-inline-flex align-items-center w-100 justify-content-center mb-3 mt-2">
    <a href="{{ route('notifications.index') }}" 
       class="position-relative d-inline-flex align-items-center justify-content-center bell-link w-100"
       style="height: 42px; border-radius: var(--r-sm); background: rgba(255,255,255,.06); border: 1px solid rgba(255,255,255,.1); color: var(--sb-text); transition: all var(--t); text-decoration: none; gap: 10px;"
       @mouseenter="hover = true"
       @mouseleave="hover = false"
       :style="hover ? 'background: rgba(255,255,255,.12); color: #fff;' : ''"
       title="Notifications"
       aria-label="Notifications">
       
        <!-- Modern SVG Bell Icon -->
        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" 
             :class="unreadCount > 0 ? 'animate-bell text-white' : ''"
             style="transition: color var(--t);">
            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
            <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
        </svg>

        <!-- Count Badge or Status Dot -->
        <span x-show="unreadCount > 0" 
              class="position-absolute d-flex align-items-center justify-content-center text-white font-monospace fw-bold" 
              style="top: -4px; right: -4px; min-width: 16px; height: 16px; border-radius: 10px; background-color: var(--rose); border: 2px solid var(--bg-card); font-size: 8px; padding: 0 3px; box-shadow: 0 0 0 2px rgba(232,80,106,0.15);"
              x-text="unreadCount > 99 ? '99+' : unreadCount"
              x-transition:enter="transition ease-out duration-300"
              x-transition:enter-start="opacity-0 scale-50"
              x-transition:enter-end="opacity-100 scale-100">
        </span>
    </a>
</div>

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        // Prevent registering multiple times if component is rendered twice on same page
        if (Alpine.store('notificationBellRegistered')) return;
        Alpine.store('notificationBellRegistered', true);
        
        Alpine.data('notificationBell', () => ({
            unreadCount: 0,
            hover: false,
            
            init() {
                this.fetchUnreadCount();
                
                // Refresh count every minute
                setInterval(() => {
                    this.fetchUnreadCount();
                }, 60000);
                
                // Listen to local events from the Notification Center
                window.addEventListener('notifications-updated', (e) => {
                    this.unreadCount = e.detail.count;
                });
                
                // Listen for Reverb events
                const userId = {{ auth()->id() ?? 'null' }};
                if (userId && window.Echo) {
                    window.Echo.private(`App.Models.User.${userId}`)
                        .listen('NewNotification', (e) => {
                            this.unreadCount++;
                        });
                }
            },
            
            async fetchUnreadCount() {
                try {
                    const response = await axios.get('/api/notifications/unread-count');
                    this.unreadCount = response.data.count;
                } catch (error) {
                    console.error('Error fetching unread count', error);
                }
            }
        }));
    });
</script>
@endpush
