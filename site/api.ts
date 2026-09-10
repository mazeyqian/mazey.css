document
  .querySelectorAll<HTMLElement>("[data-current-year]")
  .forEach((node) => {
    node.textContent = String(new Date().getFullYear());
  });
