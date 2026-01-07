
// ============================================
// GLOBAL APP OBJECT
// ============================================
const App = {
    /**
     * Show toast notification
     */
    toast: function(icon, message, position = 'top-end') {
        const Toast = Swal.mixin({
            toast: true,
            position: position,
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
            }
        });
        
        Toast.fire({
            icon: icon,
            title: message
        });
    },

    /**
     * Confirm dialog
     */
    confirm: function(options) {
        const defaults = {
            title: 'Apakah Anda yakin?',
            text: 'Tindakan ini tidak dapat dibatalkan!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, lanjutkan!',
            cancelButtonText: 'Batal'
        };
        
        const config = { ...defaults, ...options };
        
        return Swal.fire(config);
    },

    /**
     * Show loading overlay
     */
    loading: function(title = 'Loading...',message = 'memuat...') {
        Swal.fire({
            title: title,
            text : message,
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
    },

    /**
     * Close loading overlay
     */
    closeLoading: function() {
        Swal.close();
    },

    /**
     * Success alert
     */
    success: function(title, text) {
        return Swal.fire({
            icon: 'success',
            title: title,
            text: text,
            confirmButtonText: 'OK'
        });
    },

    /**
     * Error alert
     */
    error: function(title, text) {
        return Swal.fire({
            icon: 'error',
            title: title,
            text: text,
            confirmButtonText: 'OK'
        });
    },

    /**
     * AJAX helper with Axios
     */
    ajax: async function(url, method = 'GET', data = null) {
        try {
            const config = {
                method: method,
                url: url,
                headers: {
                    'X-CSRF-TOKEN': this.getCsrfToken(),
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            };
            
            if (data) {
                if (method.toLowerCase() === 'get') {
                    config.params = data;
                } else {
                    config.data = data;
                }
            }
            
            const response = await axios(config);
            return response.data;
        } catch (error) {
            // console.error('AJAX Error:', error);
            
            // Handle common errors
            if (error.response?.status === 419) {
                this.error('Session Expired', 'Halaman akan dimuat ulang');
                setTimeout(() => window.location.reload(), 2000);
            }
            
            throw error;
        }
    },

    /**
     * Get CSRF Token
     */
    getCsrfToken: function() {
        return document.querySelector('meta[name="csrf-token"]')?.content || '';
    },

    /**
     * Form validation helper
     */
    validateForm: function(formElement) {
        const requiredFields = formElement.querySelectorAll('[required]');
        let isValid = true;
        const errors = [];
        
        requiredFields.forEach(field => {
            // Remove previous validation
            field.classList.remove('is-invalid');
            
            if (!field.value.trim()) {
                isValid = false;
                field.classList.add('is-invalid');
                errors.push(field.getAttribute('name') || field.getAttribute('id'));
            }
        });
        
        if (!isValid) {
            this.toast('error', 'Mohon lengkapi semua field yang wajib diisi');
        }
        
        return isValid;
    }
};

// ============================================
// DOCUMENT READY
// ============================================
document.addEventListener('DOMContentLoaded', function() {
    
    // Initialize tooltips (Bootstrap)
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
    
    // Form validation on submit
    const forms = document.querySelectorAll('form[data-validate]');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!App.validateForm(this)) {
                e.preventDefault();
            }
        });
    });
    
    // Auto-hide alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
    alerts.forEach(alert => {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });
    
    // Confirm before leaving page with unsaved changes
    let formChanged = false;
    document.querySelectorAll('form input, form textarea, form select').forEach(input => {
        input.addEventListener('change', () => {
            formChanged = true;
        });
    });
    
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', () => {
            formChanged = false;
        });
    });
    
    window.addEventListener('beforeunload', function(e) {
        if (formChanged) {
            e.preventDefault();
            e.returnValue = 'Ada perubahan yang belum disimpan. Yakin ingin keluar?';
        }
    });
});

// ============================================
// AXIOS SETUP
// ============================================
if (typeof axios !== 'undefined') {
    // Set default headers
    axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
    axios.defaults.headers.common['X-CSRF-TOKEN'] = App.getCsrfToken();
    
    // Response interceptor for error handling
    axios.interceptors.response.use(
        response => response,
        error => {
            if (error.response?.status === 401) {
                App.error('Unauthorized', 'Silakan login kembali');
                setTimeout(() => window.location.href = '/login', 2000);
            } else if (error.response?.status === 403) {
                App.error('Forbidden', 'Anda tidak memiliki akses ke resource ini');
            } else if (error.response?.status === 404) {
                App.error('Not Found', 'Resource tidak ditemukan');
            } else if (error.response?.status === 500) {
                App.error('Server Error', 'Terjadi kesalahan pada server');
            }
            
            return Promise.reject(error);
        }
    );
}

// ============================================
// MAKE AVAILABLE GLOBALLY
// ============================================
window.App = App;
