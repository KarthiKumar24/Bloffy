let navItems = document.querySelector('.nav-items');
let openNavBtn = document.querySelector('#open-nav-btn');
let closeNavBtn = document.querySelector('#close-nav-btn');


// open
let openNav = () => {
    navItems.style.display = "flex";
    openNavBtn.style.display = "none";
    closeNavBtn.style.display = "inline-block";
}
//CLose
let closeNav = () => {
    navItems.style.display = "none";
    openNavBtn.style.display = "inline-block";
    closeNavBtn.style.display = "none";
}

openNavBtn.addEventListener('click', openNav);
closeNavBtn.addEventListener('click', closeNav);


// Nav side
let sidebar = document.querySelector('aside');
let showSidebarBtn = document.querySelector('#show-sidebar-btn');
let hideSidebarBtn = document.querySelector('#hide-sidebar-btn');

// Show
let showSidebar = () => {
    sidebar.style.left = '0%';
    showSidebarBtn.style.display = "none";
    hideSidebarBtn.style.display = "inline-block";
    console.log("wors");
}
console.log("urbcsnbdj");

// hide
let hideSidebar= () => {
    sidebar.style.left = '-100%';
    showSidebarBtn.style.display = "inline-block";
    hideSidebarBtn.style.display = "none";
}
showSidebarBtn.addEventListener('click', showSidebar)
hideSidebarBtn.addEventListener('click', hideSidebar)