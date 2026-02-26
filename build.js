/**
 * build.js - Bundle Alpine.js dan HTMX dari node_modules
 * Jalankan: node build.js
 */

const fs = require('fs');
const path = require('path');

const outputDir = path.join(__dirname, 'public', 'js');
if (!fs.existsSync(outputDir)) {
    fs.mkdirSync(outputDir, { recursive: true });
}

// Copy Alpine.js
const alpineSrc = path.join(__dirname, 'node_modules', 'alpinejs', 'dist', 'cdn.js');
const alpineDest = path.join(outputDir, 'alpine.js');
if (fs.existsSync(alpineSrc)) {
    fs.copyFileSync(alpineSrc, alpineDest);
    console.log('✅ Alpine.js copied to public/js/alpine.js');
} else {
    console.error('❌ Alpine.js not found. Run: npm install');
}

// Copy Alpine.js minified
const alpineMinSrc = path.join(__dirname, 'node_modules', 'alpinejs', 'dist', 'cdn.min.js');
const alpineMinDest = path.join(outputDir, 'alpine.min.js');
if (fs.existsSync(alpineMinSrc)) {
    fs.copyFileSync(alpineMinSrc, alpineMinDest);
    console.log('✅ Alpine.min.js copied');
}

// Copy HTMX
const htmxSrc = path.join(__dirname, 'node_modules', 'htmx.org', 'dist', 'htmx.js');
const htmxDest = path.join(outputDir, 'htmx.js');
if (fs.existsSync(htmxSrc)) {
    fs.copyFileSync(htmxSrc, htmxDest);
    console.log('✅ HTMX copied to public/js/htmx.js');
} else {
    console.error('❌ HTMX not found. Run: npm install');
}

// Copy HTMX minified
const htmxMinSrc = path.join(__dirname, 'node_modules', 'htmx.org', 'dist', 'htmx.min.js');
const htmxMinDest = path.join(outputDir, 'htmx.min.js');
if (fs.existsSync(htmxMinSrc)) {
    fs.copyFileSync(htmxMinSrc, htmxMinDest);
    console.log('✅ HTMX.min.js copied');
}

// Create app.js (Alpine init + custom JS)
const appJs = `
/**
 * app.js - Main Application JavaScript
 * Alpine.js + HTMX sudah di-load via script tag terpisah
 * File ini untuk custom Alpine components dan global scripts
 */

// =============================================================================
// Alpine.js Components
// =============================================================================

document.addEventListener('alpine:init', () => {

    // Booking Form Component
    Alpine.data('bookingForm', (vehicleId, pricePerDay, pricePerWeek, pricePerMonth) => ({
        vehicleId: vehicleId,
        pricePerDay: parseFloat(pricePerDay) || 0,
        pricePerWeek: parseFloat(pricePerWeek) || 0,
        pricePerMonth: parseFloat(pricePerMonth) || 0,
        
        startDate: '',
        endDate: '',
        customerName: '',
        customerPhone: '',
        customerEmail: '',
        pickupLocation: '',
        returnLocation: '',
        specialRequests: '',
        
        get days() {
            if (!this.startDate || !this.endDate) return 0;
            const start = new Date(this.startDate);
            const end = new Date(this.endDate);
            const diff = (end - start) / (1000 * 60 * 60 * 24);
            return Math.max(0, diff);
        },
        
        get totalPrice() {
            const d = this.days;
            if (d <= 0) return 0;
            
            const months = Math.floor(d / 30);
            const weeks = Math.floor((d % 30) / 7);
            const days = d % 7;
            
            let total = 0;
            if (months > 0 && this.pricePerMonth > 0) {
                total += months * this.pricePerMonth;
                const remainingDays = d - (months * 30);
                const remainingWeeks = Math.floor(remainingDays / 7);
                const lastDays = remainingDays % 7;
                if (remainingWeeks > 0 && this.pricePerWeek > 0) total += remainingWeeks * this.pricePerWeek;
                if (lastDays > 0) total += lastDays * this.pricePerDay;
            } else if (d >= 7 && this.pricePerWeek > 0) {
                const w = Math.floor(d / 7);
                const lastD = d % 7;
                total = w * this.pricePerWeek;
                if (lastD > 0) total += lastD * this.pricePerDay;
            } else {
                total = d * this.pricePerDay;
            }
            return total;
        },
        
        get formattedTotal() {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(this.totalPrice);
        },
        
        get minEndDate() {
            if (!this.startDate) return '';
            const date = new Date(this.startDate);
            date.setDate(date.getDate() + 1);
            return date.toISOString().split('T')[0];
        },
        
        get today() {
            return new Date().toISOString().split('T')[0];
        },
        
        async submitBooking() {
            if (!this.customerName || !this.customerPhone || !this.startDate || !this.endDate) {
                alert('Mohon lengkapi semua field yang diperlukan');
                return;
            }
            
            const bookingCode = 'RNT-' + Date.now();
            
            // Submit to backend via HTMX or fetch
            const formData = new FormData();
            formData.append('vehicle_id', this.vehicleId);
            formData.append('booking_code', bookingCode);
            formData.append('customer_name', this.customerName);
            formData.append('customer_phone', this.customerPhone);
            formData.append('customer_email', this.customerEmail);
            formData.append('start_date', this.startDate);
            formData.append('end_date', this.endDate);
            formData.append('pickup_location', this.pickupLocation);
            formData.append('return_location', this.returnLocation);
            formData.append('special_requests', this.specialRequests);
            formData.append('total_price', this.totalPrice);
            
            try {
                const response = await fetch('/booking/store', {
                    method: 'POST',
                    body: formData
                });
                const data = await response.json();
                
                if (data.success) {
                    // Redirect ke WhatsApp
                    this.openWhatsApp(data.booking_code, data.whatsapp_number, data.whatsapp_template);
                } else {
                    alert(data.message || 'Terjadi kesalahan');
                }
            } catch (error) {
                alert('Terjadi kesalahan jaringan');
            }
        },
        
        openWhatsApp(bookingCode, waNumber, template) {
            const message = template
                .replace('{booking_code}', bookingCode)
                .replace('{customer_name}', this.customerName)
                .replace('{customer_phone}', this.customerPhone)
                .replace('{start_date}', this.startDate)
                .replace('{end_date}', this.endDate)
                .replace('{total_price}', this.formattedTotal);
                
            const url = \`https://wa.me/\${waNumber}?text=\${encodeURIComponent(message)}\`;
            window.open(url, '_blank');
        }
    }));

    // Image Gallery Component
    Alpine.data('imageGallery', (images) => ({
        images: images || [],
        current: 0,
        
        prev() {
            this.current = this.current > 0 ? this.current - 1 : this.images.length - 1;
        },
        
        next() {
            this.current = this.current < this.images.length - 1 ? this.current + 1 : 0;
        },
        
        select(idx) {
            this.current = idx;
        }
    }));

    // Admin sidebar
    Alpine.data('adminLayout', () => ({
        sidebarOpen: window.innerWidth >= 1024,
        
        toggleSidebar() {
            this.sidebarOpen = !this.sidebarOpen;
        }
    }));

    // Flash message auto-hide
    Alpine.data('flashMessage', () => ({
        show: true,
        
        init() {
            setTimeout(() => {
                this.show = false;
            }, 4000);
        }
    }));

    // Theme color picker (admin settings)
    Alpine.data('themePicker', (currentColor) => ({
        color: currentColor || '#3b82f6',
        presets: [
            { name: 'Biru', value: '#3b82f6' },
            { name: 'Hijau', value: '#10b981' },
            { name: 'Ungu', value: '#8b5cf6' },
            { name: 'Merah', value: '#ef4444' },
            { name: 'Oranye', value: '#f97316' },
            { name: 'Pink', value: '#ec4899' },
            { name: 'Teal', value: '#14b8a6' },
            { name: 'Indigo', value: '#6366f1' },
        ],
        
        selectPreset(value) {
            this.color = value;
            document.getElementById('primary_color').value = value;
            this.updatePreview(value);
        },
        
        updatePreview(value) {
            document.documentElement.style.setProperty('--theme-primary-600', value);
        }
    }));

    // Confirm delete
    Alpine.data('confirmDelete', (message) => ({
        message: message || 'Apakah Anda yakin ingin menghapus data ini?',
        
        confirm() {
            return window.confirm(this.message);
        }
    }));
});

// =============================================================================
// HTMX Event Listeners
// =============================================================================

document.body.addEventListener('htmx:afterRequest', function(event) {
    // Handle success responses
    if (event.detail.successful) {
        const response = event.detail.xhr.response;
        try {
            const data = JSON.parse(response);
            if (data.message) {
                showNotification(data.message, data.success ? 'success' : 'error');
            }
        } catch (e) {}
    }
});

document.body.addEventListener('htmx:beforeRequest', function(event) {
    // Add CSRF token to all HTMX requests
    const csrf = document.querySelector('meta[name="csrf-token"]');
    if (csrf) {
        event.detail.xhr.setRequestHeader('X-CSRF-Token', csrf.getAttribute('content'));
    }
});

// =============================================================================
// Helper Functions
// =============================================================================

function showNotification(message, type = 'success') {
    const el = document.createElement('div');
    el.className = \`fixed top-4 right-4 z-50 px-6 py-4 rounded-xl shadow-lg text-white font-medium transition-all animate-slide-up \${
        type === 'success' ? 'bg-green-600' : 'bg-red-600'
    }\`;
    el.textContent = message;
    document.body.appendChild(el);
    setTimeout(() => el.remove(), 3000);
}

function formatRupiah(amount) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(amount);
}

// Image preview on file input
function previewImage(input, previewId) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById(previewId).src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
}
`;

fs.writeFileSync(path.join(outputDir, 'app.js'), appJs);
console.log('✅ app.js created');

console.log('\n🎉 Build complete!');
console.log('Files in public/js:');
fs.readdirSync(outputDir).forEach(f => console.log(' -', f));