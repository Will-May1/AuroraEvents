// Interactions
document.addEventListener("DOMContentLoaded", () => {
    // Event category filters
    const filterButtons = document.querySelectorAll(".filter-btn");
    const eventCards = document.querySelectorAll(".event-item");
    const noEventsMessage = document.getElementById("noEvents");

    filterButtons.forEach((button) => {
        button.addEventListener("click", () => {
            const filter = button.dataset.filter;
            let visibleCount = 0;

            filterButtons.forEach((item) => {
                item.classList.remove("active");
                item.setAttribute("aria-pressed", "false");
            });

            button.classList.add("active");
            button.setAttribute("aria-pressed", "true");

            eventCards.forEach((card) => {
                const matches =
                    filter === "all" || card.dataset.type === filter;

                card.classList.toggle("d-none", !matches);

                if (matches) {
                    visibleCount++;
                }
            });

            if (noEventsMessage) {
                noEventsMessage.classList.toggle(
                    "d-none",
                    visibleCount !== 0
                );
            }
        });

        button.setAttribute(
            "aria-pressed",
            button.classList.contains("active") ? "true" : "false"
        );
    });

    // Unified multi-textarea character counters & typing feedback
    const textareas = document.querySelectorAll("textarea[maxlength]");
    
    textareas.forEach(textarea => {
        // Create dynamic counter element right below the text area if it doesn't exist
        let counter = textarea.parentNode.querySelector(".character-counter");
        if (!counter) {
            counter = document.createElement("div");
            counter.className = "form-text text-end character-counter mt-1";
            counter.style.fontSize = "0.85em";
            // Insert it elegantly next to or replacing existing help layout items
            textarea.parentNode.appendChild(counter);
        }

        function updateCounter() {
            const length = textarea.value.length;
            const min = parseInt(textarea.getAttribute("minlength") || "0", 10);
            const max = textarea.getAttribute("maxlength");
            
            counter.textContent = `${length} / ${max}`;
            
            // Visual accent feedback if it falls short of minimum target bounds
            if (length < min) {
                counter.style.color = "#FFC96D"; // warning tone
            } else {
                counter.style.color = "#c5f7d3"; // valid tone
            }
            
            // Real-time cleanup of Bootstrap validation red outlines upon active typing
            if (textarea.checkValidity()) {
                textarea.classList.remove("is-invalid");
            }
        }

        textarea.addEventListener("input", updateCounter);
        updateCounter(); // Initialize bounds upon render tracking
    });

    // Basic client-side form fallback
    const form = document.getElementById("applicationForm");
    if (form) {
        form.addEventListener("submit", (event) => {
            if (!form.checkValidity()) {
                event.preventDefault();
                form.reportValidity();
            }
        });
    }
});

// Multi-Step Core Engine Wizard Closures
(() => {
  const form = document.getElementById('applicationForm');
  if (!form) return;

  const steps = [...document.querySelectorAll('.form-step')];
  const stepButtons = [...document.querySelectorAll('[data-step-button]')];
  const progressBar = document.getElementById('progressBar');
  const progress = document.querySelector('.progress[role="progressbar"]');
  let currentStep = 0;
  let highestUnlocked = 0;

  function showStep(index) {
    if (index > highestUnlocked || index < 0 || index >= steps.length) return;
    currentStep = index;
    steps.forEach((step, i) => {
      const active = i === index;
      step.classList.toggle('d-none-step', !active);
      step.setAttribute('aria-hidden', String(!active));
    });
    stepButtons.forEach((button, i) => {
      button.classList.toggle('active', i === index);
      button.classList.toggle('complete', i < index);
      button.toggleAttribute('aria-current', i === index);
      if (i === index) button.setAttribute('aria-current', 'step');
      else button.removeAttribute('aria-current');
      button.disabled = i > highestUnlocked;
    });
    const percent = ((index + 1) / steps.length) * 100;
    if (progressBar) progressBar.style.width = percent + '%';
    if (progress) progress.setAttribute('aria-valuenow', String(percent));
    
    window.scrollTo({ top: document.querySelector('.application-shell').getBoundingClientRect().top + window.scrollY - 90, behavior: 'smooth' });
    const heading = steps[index].querySelector('h2');
    if (heading) heading.setAttribute('tabindex', '-1'), heading.focus({preventScroll: true});
  }

  function validateStep(index) {
    const section = steps[index];
    let valid = true;
    section.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));

    // Native required, type, minlength, and maxlength validation.
    const controls = [...section.querySelectorAll('input, textarea, select')];
    controls.forEach(control => {
      if (control.type === 'radio' || control.type === 'checkbox') return;
      if (!control.checkValidity()) {
        control.classList.add('is-invalid');
        valid = false;
      }
    });

    // Radio groups need one selected answer.
    const radioNames = [...new Set([...section.querySelectorAll('input[type="radio"][required]')].map(el => el.name))];
    radioNames.forEach(name => {
      const group = [...section.querySelectorAll(`input[type="radio"][name="${name}"]`)];
      const selected = group.some(el => el.checked);
      const feedback = section.querySelector(`[data-radio-feedback="${name}"]`);
      if (!selected) {
        valid = false;
        if (feedback) feedback.hidden = false;
        group.forEach(el => el.classList.add('is-invalid'));
      } else if (feedback) {
        feedback.hidden = true;
        group.forEach(el => el.classList.remove('is-invalid'));
      }
    });

    if (index === 0) {
      const ageSelected = section.querySelector('input[name="age_group"]:checked');
      const ageFeedback = document.getElementById('ageFeedback');
      if (ageFeedback) ageFeedback.hidden = !!ageSelected;
      if (!ageSelected) valid = false;
    }

    if (index === 3) {
      const confirmation = document.getElementById('confirm_truth');
      const confirmFeedback = document.getElementById('confirmFeedback');
      if (confirmFeedback && confirmation) confirmFeedback.hidden = confirmation.checked;
      if (confirmation && !confirmation.checked) valid = false;
    }

    if (!valid) {
      const firstInvalid = section.querySelector('.is-invalid') || section.querySelector('#ageFeedback:not([hidden]), #confirmFeedback:not([hidden])');
      if (firstInvalid && firstInvalid.focus) firstInvalid.focus();
    }
    return valid;
  }

  document.querySelectorAll('.next-btn').forEach(button => {
    button.addEventListener('click', () => {
      if (!validateStep(currentStep)) return;
      highestUnlocked = Math.max(highestUnlocked, currentStep + 1);
      showStep(currentStep + 1);
    });
  });

  document.querySelectorAll('.back-btn').forEach(button => {
    button.addEventListener('click', () => showStep(currentStep - 1));
  });

  stepButtons.forEach((button, index) => {
    button.addEventListener('click', () => {
      if (index <= highestUnlocked) showStep(index);
    });
  });

  form.addEventListener('submit', event => {
    if (!validateStep(currentStep)) {
      event.preventDefault();
      return;
    }
    for (let i = 0; i < steps.length; i++) {
      if (!validateStep(i)) {
        event.preventDefault();
        showStep(i);
        return;
      }
    }
  });

  form.querySelectorAll('input[type="radio"]').forEach(radio => {
    radio.addEventListener('change', () => {
      const feedback = form.querySelector(`[data-radio-feedback="${radio.name}"]`);
      if (feedback && form.querySelector(`input[name="${radio.name}"]:checked`)) feedback.hidden = true;
      if (radio.name === 'age_group' && document.getElementById('ageFeedback')) document.getElementById('ageFeedback').hidden = true;
    });
  });
  
  document.getElementById('confirm_truth')?.addEventListener('change', event => {
    const confirmFeedback = document.getElementById('confirmFeedback');
    if (confirmFeedback) confirmFeedback.hidden = event.target.checked;
  });
})();
