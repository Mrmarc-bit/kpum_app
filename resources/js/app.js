import './bootstrap';
import Alpine from 'alpinejs';

// ===== GLOBAL ALPINE COMPONENTS =====
// Didaftarkan di sini agar tersedia di SEMUA halaman (termasuk navigasi via Turbo).
// Jangan daftarkan di dalam blade <script> karena alpine:init tidak akan fired ulang.

Alpine.data('dropdownSearch', (config) => ({
    selected: config.selected || '',
    options: config.options || [],
    open: false,
    search: '',

    get filteredOptions() {
        if (this.search === '') return this.options;

        const q = this.search.toLowerCase();
        return this.options.filter(option => {
            if (option.isGroup) return false;
            return option.label.toLowerCase().includes(q);
        });
    }
}));

// ===== START ALPINE =====
if (!window.Alpine) {
    window.Alpine = Alpine;
    Alpine.start();
}
