document.addEventListener('DOMContentLoaded', () => {
    let footer = document.querySelector('.footer');
    let body = document.body;
    let profile = document.querySelector('.header .flex .profile');
    let searchForm = document.querySelector('.header .flex .search-form');
    let sideBar = document.querySelector('.side-bar');

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
});
