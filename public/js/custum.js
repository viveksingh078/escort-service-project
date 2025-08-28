document.addEventListener("DOMContentLoaded", () => {
  const button = document.querySelector(".hide_filters");
  const filters = document.querySelector(".otherFilters");
  const toggleText = document.querySelector(".toggle-text");

  if (!button || !filters || !toggleText) return;

  button.addEventListener("click", () => {
    filters.classList.toggle("d-none");

    toggleText.textContent = filters.classList.contains("d-none")
      ? "Show filter options"
      : "Hide filter options";
  });
});
