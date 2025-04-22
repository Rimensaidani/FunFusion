/* validation.js
function initFormValidation() {
    const forms = document.querySelectorAll('.challenges-form');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            let isValid = true;
            const submitter = e.submitter?.name;
            
            if (submitter === 'ajouter' || submitter === 'modifier') {
                const title = form.querySelector('input[name="title"]');
                const type = form.querySelector('select[name="type"]');
                const date = form.querySelector('input[name="creation_date"]');
                const score = form.querySelector('input[name="score"]');
                
                form.querySelectorAll('.error-message').forEach(el => el.remove());
                [title, type, date, score].forEach(field => {
                    field.classList.remove('error-field');
                });
                
                if (title.value.trim().length < 4) {
                    showError(title, "Le titre doit contenir au moins 4 caractères");
                    isValid = false;
                }
                
                if (parseInt(score.value) <= 50) {
                    showError(score, "Le score doit être supérieur à 50");
                    isValid = false;
                }
                
                if (!date.value) {
                    showError(date, "La date est obligatoire");
                    isValid = false;
                }
                
                if (!isValid) {
                    e.preventDefault();
                    showGlobalError(form);
                }
            }
        });
        
        setupRealTimeValidation(form);
    });
}

function setupRealTimeValidation(form) {
    const titleInput = form.querySelector('input[name="title"]');
    if (titleInput) {
        titleInput.addEventListener('input', function() {
            if (this.value.trim().length < 4 && this.value.trim().length > 0) {
                showError(this, "Minimum 4 caractères");
            } else {
                clearError(this);
            }
        });
    }
    
    const scoreInput = form.querySelector('input[name="score"]');
    if (scoreInput) {
        scoreInput.addEventListener('input', function() {
            if (parseInt(this.value) <= 50 && this.value.trim().length > 0) {
                showError(this, "Score doit être > 50");
            } else {
                clearError(this);
            }
        });
    }
}

function showError(input, message) {
    clearError(input);
    input.classList.add('error-field');
    
    const errorDiv = document.createElement('div');
    errorDiv.className = 'error-message';
    errorDiv.textContent = message;
    input.parentNode.appendChild(errorDiv);
}

function clearError(input) {
    input.classList.remove('error-field');
    const errorDiv = input.parentNode.querySelector('.error-message');
    if (errorDiv) {
        errorDiv.remove();
    }
}

function showGlobalError(form) {
    let globalError = form.querySelector('.global-error-message');
    if (!globalError) {
        globalError = document.createElement('div');
        globalError.className = 'global-error-message';
        globalError.textContent = "Veuillez corriger les erreurs avant de soumettre";
        form.prepend(globalError);
    }
    
    const firstError = form.querySelector('.error-field');
    if (firstError) {
        firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
}

document.addEventListener('DOMContentLoaded', initFormValidation);*/