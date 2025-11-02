let navbar = document.querySelector('.navbar');
let cartItem = document.querySelector('.cart-items-container');
let login = document.querySelector('.login-form');

document.querySelector('#menu-btn').onclick = () =>{
    navbar.classList.toggle('active');
    login.classList.remove('active');
    cartItem.classList.remove('active');
}


document.querySelector('#cart-btn').onclick = () =>{
    cartItem.classList.toggle('active');
    navbar.classList.remove('active');
    login.classList.remove('active');
}


document.querySelector('#login-btn').onclick = () =>{
    login.classList.toggle('active');
    navbar.classList.remove('active');
    cartItem.classList.remove('active');
}

window.onscroll = () =>{
    navbar.classList.remove('active');
    login.classList.remove('active');
    cartItem.classList.remove('active');
}