// Theme toggle functionality
function initTheme() {
    const themeToggle = document.querySelector('.theme-toggle');
    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    const storedTheme = localStorage.getItem('theme');

    // Set initial theme based on stored preference or system preference
    if (storedTheme) {
        document.documentElement.setAttribute('data-theme', storedTheme);
    } else if (prefersDark) {
        document.documentElement.setAttribute('data-theme', 'dark');
        localStorage.setItem('theme', 'dark');
    }

    themeToggle.addEventListener('click', () => {
        const currentTheme = document.documentElement.getAttribute('data-theme');
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
        
        document.documentElement.setAttribute('data-theme', newTheme);
        localStorage.setItem('theme', newTheme);
        
        // Update button icons
        const moonIcon = themeToggle.querySelector('.moon');
        const sunIcon = themeToggle.querySelector('.sun');
        
        moonIcon.style.display = newTheme === 'dark' ? 'none' : 'block';
        sunIcon.style.display = newTheme === 'dark' ? 'block' : 'none';
    });
}