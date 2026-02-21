<!-- Calendar Section -->
<section id="jadwal" class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header -->
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-gray-900">Jadwal Pemilwa</h2>
            <p class="mt-2 text-gray-600">Pantau setiap tahapan penting pemilihan umum mahasiswa</p>
        </div>

        <!-- Calendar -->
        <div class="bg-white rounded-lg shadow overflow-hidden" x-data="{
                events: @js($timelines ?? []),
                month: new Date().getMonth(),
                year: new Date().getFullYear(),
                days: [],
                blanks: [],
                trailingBlanks: [],
                monthNames: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
                
                init() { 
                    this.generateCalendar();
                },

                generateCalendar() {
                    const firstDay = new Date(this.year, this.month, 1).getDay();
                    const daysInMonth = new Date(this.year, this.month + 1, 0).getDate();
                    this.blanks = Array(firstDay).fill(0);
                    this.days = Array.from({length: daysInMonth}, (_, i) => i + 1);
                    
                    const totalCells = firstDay + daysInMonth;
                    const trailingCount = totalCells % 7 === 0 ? 0 : 7 - (totalCells % 7);
                    this.trailingBlanks = Array(trailingCount).fill(0);
                },

                prevMonth() { this.month--; if (this.month < 0) { this.month = 11; this.year--; } this.generateCalendar(); },
                nextMonth() { this.month++; if (this.month > 11) { this.month = 0; this.year++; } this.generateCalendar(); },
                goToday() { const now = new Date(); this.month = now.getMonth(); this.year = now.getFullYear(); this.generateCalendar(); },
                isToday(day) { const today = new Date(); return day === today.getDate() && this.month === today.getMonth() && this.year === today.getFullYear(); },
                
                getEventsForDay(day) {
                    if (!this.events || !Array.isArray(this.events)) return [];
                    const d = new Date(this.year, this.month, day);
                    d.setHours(0,0,0,0);
                    const time = d.getTime();
                    
                    return this.events.filter(e => {
                        // Priority 1: Check start_date (and optional end_date range)
                        if (e.start_date) {
                            const start = new Date(e.start_date);
                            start.setHours(0,0,0,0);
                            
                            const end = e.end_date ? new Date(e.end_date) : new Date(e.start_date);
                            end.setHours(0,0,0,0);
                            
                            return time >= start.getTime() && time <= end.getTime();
                        }
                        // Priority 2: Check legacy 'date' (single day)
                        if (e.date) {
                            const ed = new Date(e.date);
                            ed.setHours(0,0,0,0);
                            return ed.getTime() === time;
                        }
                        return false; 
                    });
                },

                openModal(event) {
                    // DEEP CLONE untuk mematikan Proxy -> Solusi tombol mati/data kosong
                    const plainEvent = JSON.parse(JSON.stringify(event));
                    this.$dispatch('open-event', plainEvent);
                }
            }">

            <!-- Month Nav -->
            <div class="flex items-center justify-between px-6 py-4 border-b">
                <h3 class="text-lg font-semibold text-gray-900" x-text="monthNames[month] + ' ' + year"></h3>
                <div class="flex gap-2">
                    <button @click="prevMonth()" class="p-2 hover:bg-gray-100 rounded">‹</button>
                    <button @click="goToday()"
                        class="px-3 py-1 text-sm bg-indigo-600 text-white rounded hover:bg-indigo-700">Hari Ini</button>
                    <button @click="nextMonth()" class="p-2 hover:bg-gray-100 rounded">›</button>
                </div>
            </div>

            <!-- Calendar Grid -->
            <div>
                <div class="border-l border-t border-gray-200 bg-white rounded-lg overflow-hidden">
                    <div class="grid grid-cols-7 border-b border-gray-200">
                        <template x-for="day in ['Min','Sen','Sel','Rab','Kam','Jum','Sab']">
                            <div class="py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r border-gray-200"
                                x-text="day"></div>
                        </template>
                    </div>

                    <div class="grid grid-cols-7">
                        <template x-for="blank in blanks">
                            <div class="h-20 md:h-32 bg-white border-r border-b border-gray-200"></div>
                        </template>

                        <template x-for="day in days" :key="day">
                            <div class="h-20 md:h-32 bg-white p-1 md:p-2 border-r border-b border-gray-200 hover:bg-gray-50 transition relative group"
                                :class="{'bg-blue-50/30': isToday(day)}">
                                <div class="flex justify-between items-start mb-1">
                                    <div class="text-xs font-medium text-gray-700"
                                        :class="isToday(day) ? 'bg-indigo-600 text-white rounded-full w-6 h-6 flex items-center justify-center' : 'p-1'">
                                        <span x-text="day"></span>
                                    </div>
                                </div>

                                <div class="space-y-1 overflow-y-auto max-h-[85px] relative z-10">
                                    <template x-for="event in getEventsForDay(day)" :key="event.id">
                                        <div @click.stop="openModal(event)"
                                            class="text-[9px] md:text-[10px] px-1 py-0.5 md:px-1.5 md:py-1 rounded border-l-2 cursor-pointer hover:shadow-md transition-all active:scale-95 transform relative z-20"
                                            :class="event.is_completed ? 'bg-emerald-50 text-emerald-800 border-emerald-500 hover:bg-emerald-100' : 'bg-indigo-50 text-indigo-800 border-indigo-500 hover:bg-indigo-100'">
                                            <div class="font-medium truncate" x-text="event.title"></div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </template>

                        <template x-for="blank in trailingBlanks">
                            <div class="h-20 md:h-32 bg-white border-r border-b border-gray-200"></div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Global Event Modal -->
<div x-data="{ 
        open: false, 
        event: {},
        formatDate() {
            const fmt = (d) => {
                if (!d) return '-';
                try {
                    return new Date(d).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
                } catch(e) { return d; }
            };
            
            if (this.event.start_date) {
                const s = fmt(this.event.start_date);
                if (this.event.end_date && this.event.end_date !== this.event.start_date) {
                    return `${s} - ${fmt(this.event.end_date)}`;
                }
                return s;
            }
            return fmt(this.event.date);
        },
        addToGoogleCalendar() {
            if (!this.event || !this.event.title) return;
            const title = encodeURIComponent(this.event.title);
            const desc = encodeURIComponent(this.event.description || '');
            
            // Handle Start Date
            let sRaw = this.event.start_date || this.event.date || new Date().toISOString();
            let start = sRaw.split(' ')[0].replace(/-/g, '');
            
            // Handle End Date (Exclusive for GCal)
            let eRaw = this.event.end_date || sRaw;
            let endDate = new Date(eRaw);
            endDate.setDate(endDate.getDate() + 1);
            let end = endDate.toISOString().split('T')[0].replace(/-/g, '');
            
            window.open(`https://calendar.google.com/calendar/render?action=TEMPLATE&text=${title}&details=${desc}&dates=${start}/${end}`, '_blank');
        }
    }" @open-event.window="event = $event.detail; open = true" @keydown.escape.window="open = false">

    <template x-teleport="body">
        <div x-show="open" x-cloak class="fixed inset-0 z-[9999] flex items-center justify-center p-4"
            style="display: none;">

            <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" @click="open = false"></div>

            <div class="relative w-[90%] md:w-full bg-white rounded-[2rem] shadow-2xl p-6 sm:p-8 transform transition-all max-w-md mx-auto"
                style="max-width: 450px;" @click.stop>

                <button @click="open = false"
                    class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 transition-colors p-2 rounded-full hover:bg-slate-100 z-10 focus:outline-none">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <div class="flex flex-col items-center text-center">
                    <div class="mb-5 relative">
                        <div
                            class="w-16 h-16 rounded-2xl bg-gradient-to-br from-indigo-600 to-violet-600 flex items-center justify-center shadow-lg shadow-indigo-500/30 text-white">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    </div>

                    <h3 class="text-2xl font-bold text-slate-900 tracking-tight mb-2 leading-tight"
                        x-text="event.title"></h3>

                    <div
                        class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-slate-50 border border-slate-200 text-sm font-medium text-slate-600 mb-6">
                        <svg class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span x-text="formatDate()"></span>
                    </div>

                    <div class="w-full bg-slate-50 rounded-2xl p-5 border border-slate-100 mb-6 text-left">
                        <h5 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Deskripsi</h5>
                        <p class="text-slate-600 text-sm leading-relaxed whitespace-pre-wrap"
                            x-text="event.description || 'Tidak ada deskripsi.'"></p>
                    </div>

                    <div class="w-full grid grid-cols-2 gap-3">
                        <button @click="open = false"
                            class="px-5 py-3 rounded-xl border border-slate-200 text-slate-600 font-semibold hover:bg-slate-50 hover:border-slate-300 transition-all">Tutup</button>
                        <button @click="addToGoogleCalendar()"
                            class="px-5 py-3 rounded-xl bg-slate-900 text-white font-semibold hover:bg-slate-800 hover:shadow-lg hover:shadow-slate-900/20 active:scale-95 transition-all flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20a2 2 0 0 0 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V10h14v10zm0-12H5V6h14v2zm-7 5h5v5h-5v-5z" />
                            </svg> G-Calendar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>