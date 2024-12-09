// NAVICONS  --------------------------------------------------------------------- //

document.addEventListener("DOMContentLoaded", function () {
  const navicons = document.querySelector(".navicons");
  const icons = document.querySelectorAll(".icon");
  const sections = document.querySelectorAll(".section");

  // Funkcia pre kontrolu stavu ikon
  function updateIcons() {
    const naviconsRect = navicons.getBoundingClientRect();

    sections.forEach((section) => {
      const sectionRect = section.getBoundingClientRect();
      const iconId = `icon-${section.id}`;
      const icon = document.getElementById(iconId);

      if (
        naviconsRect.bottom > sectionRect.top &&
        naviconsRect.top < sectionRect.bottom
      ) {
        // Navigácia je nad touto sekciou
        if (section.classList.contains("dark")) {
          // Ak je sekcia "dark"
          icons.forEach((icon) => (icon.style.color = "white")); // Všetky biele
          icon.style.color = "#d6bc88"; // Aktívna zlatá
        } else {
          // Ak je sekcia normálna
          icons.forEach((icon) => (icon.style.color = "")); // Všetky sivé (CSS default)
          icon.style.color = "#d6bc88"; // Aktívna zlatá
        }
      }
    });
  }

  // Sledujeme skrolovanie
  window.addEventListener("scroll", updateIcons);

  // Prvé spustenie
  updateIcons();
});

// NAVTABS GALLERY  ----------------------------------------------------------------- //

// Vyberieme všetky tlačidlá v navigácii
const buttons = document.querySelectorAll('.nav-link');
  
// Pridáme event listener pre hover efekt
buttons.forEach(button => {
  button.addEventListener('mouseover', () => {
    // Získame ID sekcie priradenej k tlačidlu
    const targetTab = document.querySelector(`#${button.getAttribute('aria-controls')}`);
    
    // Skryjeme všetky sekcie
    document.querySelectorAll('.tab-pane').forEach(tab => {
      tab.classList.remove('show', 'active');
    });

    // Zobrazíme len tú sekciu, ktorá je pre daný hover
    targetTab.classList.add('show', 'active');
  });

  button.addEventListener('mouseout', () => {
    // Pri opustení hoveru zobrazíme predvolenú sekciu (Room 1)
    const defaultTab = document.querySelector('.nav-link.active');
    const defaultTabContent = document.querySelector(`#${defaultTab.getAttribute('aria-controls')}`);
    
    document.querySelectorAll('.tab-pane').forEach(tab => {
      tab.classList.remove('show', 'active');
    });

    defaultTabContent.classList.add('show', 'active');
  });
});