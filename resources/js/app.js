import './bootstrap';

// Import Alpine and Livewire from the same bundle — avoids double-initialisation
import { Livewire, Alpine } from '../../vendor/livewire/livewire/dist/livewire.esm';
import collapse from '@alpinejs/collapse';
import focus from '@alpinejs/focus';

Alpine.plugin(collapse);
Alpine.plugin(focus);

/* ── Global UI Store ──────────────────────────────────── */
Alpine.store('ui', {
    dark: (() => {
        const saved = localStorage.getItem('theme');
        if (saved) return saved === 'dark';
        return window.matchMedia('(prefers-color-scheme: dark)').matches;
    })(),

    sidebarOpen: window.innerWidth >= 1024,

    init() {
        this.applyDark();
    },

    toggleDark() {
        this.dark = !this.dark;
        localStorage.setItem('theme', this.dark ? 'dark' : 'light');
        this.applyDark();
    },

    applyDark() {
        document.documentElement.classList.toggle('dark', this.dark);
    },

    toggleSidebar() {
        this.sidebarOpen = !this.sidebarOpen;
    },

    closeSidebarOnMobile() {
        if (window.innerWidth < 1024) this.sidebarOpen = false;
    },
});

/* ── Resize handler ───────────────────────────────────── */
window.addEventListener('resize', () => {
    if (window.innerWidth >= 1024 && !Alpine.store('ui').sidebarOpen) {
        Alpine.store('ui').sidebarOpen = true;
    }
});

// Single start — Livewire.start() boots both Livewire and Alpine together
Livewire.start();
