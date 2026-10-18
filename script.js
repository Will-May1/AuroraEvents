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

    // Application statement character counter
    const reasonField = document.getElementById("reason");
    const reasonCounter = document.getElementById("reasonCounter");

    function updateCharacterCount() {
        if (!reasonField || !reasonCounter) return;

        const length = reasonField.value.length;
        reasonCounter.textContent = `${length} / 3000`;

        reasonCounter.style.color =
            length < 30 ? "#FFC96D" : "#c5f7d3";
    }

    if (reasonField) {
        reasonField.addEventListener("input", updateCharacterCount);
        updateCharacterCount();
    }

    // Basic client-side form feedback
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