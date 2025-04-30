// File: activite_reelle_validation.js

document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('activityForm');; // Adjust form id as needed

    if (!form) return;

    form.addEventListener('submit', function (e) {
        let errors = [];

        // Input elements
        const titre = form.querySelector('input[name="titre"]');
        const lieu = form.querySelector('input[name="lieu"]');
        const dateInput = form.querySelector('input[name="date"]');

        // Trimmed values
        const titreVal = titre ? titre.value.trim() : '';
        const lieuVal = lieu ? lieu.value.trim() : '';
        const dateVal = dateInput ? dateInput.value : '';

        // Validate titre not empty
        if (!titreVal) {
            errors.push('Title cannot be empty.');
            if(titre) titre.classList.add('input-error');
        } else {
            if(titre) titre.classList.remove('input-error');
        }

        // Validate lieu not empty
        if (!lieuVal) {
            errors.push('Location cannot be empty.');
            if(lieu) lieu.classList.add('input-error');
        } else {
            if(lieu) lieu.classList.remove('input-error');
        }

        // Validate date is valid and after today
        if (!dateVal) {
            errors.push('Date must be provided.');
            if(dateInput) dateInput.classList.add('input-error');
        } else {
            const today = new Date();
            today.setHours(0,0,0,0); // zero time for today
            const inputDate = new Date(dateVal);

            // Check invalid date
            if(isNaN(inputDate.getTime())) {
                errors.push('Date is invalid.');
                if(dateInput) dateInput.classList.add('input-error');
            } else if (inputDate <= today) {
                errors.push('Date must be after today.');
                if(dateInput) dateInput.classList.add('input-error');
            } else {
                if(dateInput) dateInput.classList.remove('input-error');
            }
        }

        // If any errors, prevent submit and show alert (or better: show messages in page)
        if (errors.length > 0) {
            e.preventDefault();
            alert(errors.join('\n'));
        }
    });
});