// NAVICONS  --------------------------------------------------------------------- //

document.addEventListener("DOMContentLoaded", function () {
  const icons = document.querySelectorAll(".icon");
  const sections = document.querySelectorAll(".section");

  // Nastavujeme pozorovateľa
  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        const iconId = `icon-${entry.target.id}`;
        const icon = document.getElementById(iconId);

        if (entry.isIntersecting) {
         
          // Ak je nad konkrétnou sekciou a má konkrétnu ikonu
          if (entry.target.id === iconId.replace('icon-', '')) {
            icon.style.color = "#d6bc88";
          }
        } else {
          // Reset farby, keď sa sekcia opustí
          icon.style.color = ""; // Pôvodná farba (ak bola v CSS)
        }  
      });
    },
    { threshold: 0.1 } // Sledujeme, kedy je aspoň 50% sekcie viditeľné
  );

  // Pridáme každú sekciu na sledovanie
  sections.forEach((section) => observer.observe(section));
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