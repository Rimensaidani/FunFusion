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
            if (this.value.trim().length < 4 && this.value.trim().length > 0) {
                showError(this, "Minimum 4 caractères");
            } else {
                clearError(this);
            }
        });
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
            if (parseInt(this.value) <= 50 && this.value.trim().length > 0) {
                showError(this, "Score doit être > 50");
            } else {
                clearError(this);
            }
        });
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
    if (errorDiv) {
        errorDiv.remove();
    }
}

function clearAllErrors(form) {
    form.querySelectorAll('.is-invalid').forEach(input => {
        clearError(input);
    });
}

// Initialisation au chargement
document.addEventListener('DOMContentLoaded', initFormValidation);