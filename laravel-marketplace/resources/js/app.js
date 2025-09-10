import Alpine from 'alpinejs'

window.Alpine = Alpine

Alpine.start()

// Global functions
window.startConversation = function(listingId) {
    // Implementation for starting conversation
    console.log('Starting conversation for listing:', listingId);
    // This would typically make an API call to start a conversation
}

// Form validation helpers
window.validateJMBG = function(jmbg) {
    if (jmbg.length !== 13 || !/^\d{13}$/.test(jmbg)) {
        return false;
    }

    const weights = [7, 6, 5, 4, 3, 2, 7, 6, 5, 4, 3, 2];
    let sum = 0;
    
    for (let i = 0; i < 12; i++) {
        sum += parseInt(jmbg[i]) * weights[i];
    }
    
    const remainder = sum % 11;
    const checkDigit = remainder < 2 ? remainder : 11 - remainder;
    
    return checkDigit === parseInt(jmbg[12]);
}

window.validatePassword = function(password) {
    const hasUpperCase = /[A-Z]/.test(password);
    const hasLowerCase = /[a-z]/.test(password);
    const hasNumbers = /\d/.test(password);
    const hasSpecialChar = /[!@#$%^&*(),.?":{}|<>]/.test(password);
    const isLongEnough = password.length >= 8;

    return isLongEnough && hasUpperCase && hasLowerCase && hasNumbers && hasSpecialChar;
}

// Image upload helpers
window.handleImageUpload = function(event, images) {
    const files = Array.from(event.target.files);
    files.forEach(file => {
        if (file.size > 2 * 1024 * 1024) {
            alert('File size must be less than 2MB');
            return;
        }
        
        const reader = new FileReader();
        reader.onload = (e) => {
            images.push({
                file: file,
                preview: e.target.result
            });
        };
        reader.readAsDataURL(file);
    });
}

// Flash message auto-hide
document.addEventListener('DOMContentLoaded', function() {
    const flashMessages = document.querySelectorAll('[x-data*="show"]');
    flashMessages.forEach(message => {
        setTimeout(() => {
            message.style.display = 'none';
        }, 5000);
    });
});
