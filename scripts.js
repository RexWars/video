

document.addEventListener('DOMContentLoaded', function() {
    const feedbackForm = document.getElementById('feedback-form');
    feedbackForm.addEventListener('submit', handleFormSubmit);

    function handleFormSubmit(event) {
        event.preventDefault();

        const form = event.target;
        const formData = new FormData(form);

        const ratingSelected = formData.get('rating');
        if (!ratingSelected) {
            showErrorMessage("Vă rugăm să selectați o stea înainte de a trimite feedback-ul.");
            return;
        }

        fetch(form.action, {
            method: form.method,
            body: formData,
            headers: {
                'Accept': 'application/json'
            }
        }).then(response => {
            if (response.ok) {
                showSuccessMessage();
            } else {
                response.json().then(data => {
                    if (Object.hasOwn(data, 'errors')) {
                        alert(data["errors"].map(error => error["message"]).join(", "));
                    } else {
                        alert("Oops! There was a problem submitting your form");
                    }
                })
            }
        }).catch(error => {
            alert("Oops! There was a problem submitting your form");
        });
    }

    function showSuccessMessage() {
        const successMessage = document.getElementById('success-message');
        successMessage.classList.remove('hidden');
        setTimeout(() => {
            successMessage.classList.add('hidden');
            document.getElementById('feedback-form').reset();
        }, 3000);
    }

    function showErrorMessage(message) {
        const errorMessage = document.getElementById('error-message');
        errorMessage.textContent = message;
        errorMessage.classList.remove('hidden');
        setTimeout(() => {
            errorMessage.classList.add('hidden');
        }, 3000);
    }
});

function toggleMode() {
    const body = document.body;
    const header = document.querySelector('header');
    const navLinks = document.querySelectorAll('header nav ul li a');
    const toggleButton = document.querySelector('.toggle-button');
    const intro = document.getElementById('intro');
    const features = document.getElementById('features');
    const faqs = document.getElementById('faqs');
    const feedback = document.getElementById('feedback');
    const access = document.getElementById('access');
    const footer = document.querySelector('footer');
    const ratings = document.querySelectorAll('.rating');
  
    body.classList.toggle('light-mode');
    body.classList.toggle('dark-mode');
  
    header.classList.toggle('light-mode');
    header.classList.toggle('dark-mode');
  
    navLinks.forEach(link => {
      link.classList.toggle('light-mode');
      link.classList.toggle('dark-mode');
    });
  
    toggleButton.classList.toggle('light-mode');
    toggleButton.classList.toggle('dark-mode');
  
    intro.classList.toggle('light-mode');
    intro.classList.toggle('dark-mode');
  
    features.classList.toggle('light-mode');
    features.classList.toggle('dark-mode');
  
    faqs.classList.toggle('light-mode');
    faqs.classList.toggle('dark-mode');
  
    feedback.classList.toggle('light-mode');
    feedback.classList.toggle('dark-mode');
  
    access.classList.toggle('light-mode');
    access.classList.toggle('dark-mode');
  
    footer.classList.toggle('light-mode');
    footer.classList.toggle('dark-mode');
  
    ratings.forEach(rating => {
      rating.classList.toggle('light-mode');
      rating.classList.toggle('dark-mode');
    });
  
    // Change icon on toggle button
    if (toggleButton.classList.contains('light-mode')) {
      toggleButton.textContent = '🌙';
    } else {
      toggleButton.textContent = '☀️';
    }
  }
