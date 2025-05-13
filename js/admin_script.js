document.addEventListener('DOMContentLoaded', () => {
    let body = document.body;
    let profile = document.querySelector('.header .flex .profile');
    let searchForm = document.querySelector('.header .flex .search-form');
    let sideBar = document.querySelector('.side-bar');
    let toggleBtn = document.querySelector('#toggle-btn');
    let darkMode = localStorage.getItem('dark-mode');

    let userBtn = document.querySelector('#user-btn');
    if (userBtn) {
        userBtn.onclick = () => {
            profile.classList.toggle('active');
            searchForm.classList.remove('active');
        };
    }

    let searchBtn = document.querySelector('#search-btn');
    if (searchBtn) {
        searchBtn.onclick = () => {
            searchForm.classList.toggle('active');
            profile.classList.remove('active');
        };
    }

    let menuBtn = document.querySelector('#menu-btn');
    if (menuBtn) {
        menuBtn.onclick = () => {
            sideBar.classList.toggle('active');
            body.classList.toggle('active');
            footer.classList.toggle('active');
        };
    }

    let closeBar = document.querySelector('#close-bar');
    if (closeBar) {
        closeBar.onclick = () => {
            sideBar.classList.remove('active');
        };
    }

    window.onscroll = () => {
        profile.classList.remove('active');
        searchForm.classList.remove('active');

        if (window.innerWidth < 1200) {
            sideBar.classList.remove('active');
            body.classList.remove('active');
        }
    };

    // Перевірка темної теми при завантаженні
    const enableDarkMode = () => {
        toggleBtn.classList.replace('fa-sun', 'fa-moon');
        body.classList.add('dark');
        localStorage.setItem('dark-mode', 'enabled');
    }

    const disableDarkMode = () => {
        toggleBtn.classList.replace('fa-moon', 'fa-sun');
        body.classList.remove('dark');
        localStorage.setItem('dark-mode', 'disabled');
    }

    // Використовуємо темну тему, якщо вона активована в localStorage
    if (darkMode === 'enabled') {
        enableDarkMode();
    }

    // Логіка перемикання темної теми
    toggleBtn.onclick = () => {
        let darkMode = localStorage.getItem('dark-mode');
        if (darkMode === 'disabled') {
            enableDarkMode();
        } else {
            disableDarkMode();
        }
    }

});
