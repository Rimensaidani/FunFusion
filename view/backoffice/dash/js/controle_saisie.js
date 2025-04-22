<<<<<<< HEAD
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
=======
// Fonction principale d'initialisation
function initFormValidation() {
    // Validation à la soumission des formulaires
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!validateForm(this, e.submitter)) {
                e.preventDefault(); // Bloque l'envoi si validation échoue
            }
        });
    });

    // Validation en temps réel
    setupRealTimeValidation();
}

// Validation des formulaires
function validateForm(form, submitter) {
    let isValid = true;
    clearAllErrors(form);

    // On ne valide que pour ajout/modification
    if (submitter.name === 'ajouter' || submitter.name === 'modifier') {
        const title = form.querySelector('input[name="title"]');
        const type = form.querySelector('input[name="type"]');
        const score = form.querySelector('input[name="score"]');

        // Validation titre (4 caractères min)
        if (title.value.trim().length < 4) {
            showError(title, "Le titre doit contenir au moins 4 caractères");
            isValid = false;
        }

        // Validation type (mission, quiz ou mini_jeu)
        const validTypes = ['mission', 'quiz', 'mini_jeu'];
        if (!validTypes.includes(type.value.toLowerCase())) {
            showError(type, "Le type doit être: mission, quiz ou mini_jeu");
            isValid = false;
        }

        // Validation score (>50)
        if (parseInt(score.value) <= 50) {
            showError(score, "Le score doit être supérieur à 50");
            isValid = false;
        }
    }

    return isValid;
}

// Validation en temps réel
function setupRealTimeValidation() {
    document.querySelectorAll('input[name="title"]').forEach(input => {
        input.addEventListener('input', function() {
>>>>>>> b7eeaa49221d74decf66b23262e6792e3fc08798
            if (this.value.trim().length < 4 && this.value.trim().length > 0) {
                showError(this, "Minimum 4 caractères");
            } else {
                clearError(this);
            }
        });
<<<<<<< HEAD
    }
    
    const scoreInput = form.querySelector('input[name="score"]');
    if (scoreInput) {
        scoreInput.addEventListener('input', function() {
=======
    });

    document.querySelectorAll('input[name="type"]').forEach(input => {
        input.addEventListener('input', function() {
            const validTypes = ['mission', 'quiz', 'mini_jeu'];
            if (!validTypes.includes(this.value.toLowerCase()) && this.value.trim().length > 0) {
                showError(this, "Types valides: mission, quiz, mini_jeu");
            } else {
                clearError(this);
            }
        });
    });

    document.querySelectorAll('input[name="score"]').forEach(input => {
        input.addEventListener('input', function() {
>>>>>>> b7eeaa49221d74decf66b23262e6792e3fc08798
            if (parseInt(this.value) <= 50 && this.value.trim().length > 0) {
                showError(this, "Score doit être > 50");
            } else {
                clearError(this);
            }
        });
<<<<<<< HEAD
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
=======
    });
}

// Affichage des erreurs
function showError(input, message) {
    clearError(input);
    input.classList.add('is-invalid');
    
    const errorDiv = document.createElement('div');
    errorDiv.className = 'invalid-feedback';
    errorDiv.textContent = message;
    
    input.parentNode.appendChild(errorDiv);
}

// Nettoyage des erreurs
function clearError(input) {
    input.classList.remove('is-invalid');
    const errorDiv = input.parentNode.querySelector('.invalid-feedback');
>>>>>>> b7eeaa49221d74decf66b23262e6792e3fc08798
    if (errorDiv) {
        errorDiv.remove();
    }
}

<<<<<<< HEAD
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
=======
function clearAllErrors(form) {
    form.querySelectorAll('.is-invalid').forEach(input => {
        clearError(input);
    });
}

// Initialisation au chargement
document.addEventListener('DOMContentLoaded', initFormValidation);
>>>>>>> b7eeaa49221d74decf66b23262e6792e3fc08798
