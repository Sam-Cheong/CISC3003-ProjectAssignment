document.addEventListener("DOMContentLoaded", function () {
    const toggleMenu = document.querySelector('.toggle-menu');
    const navLinks = document.querySelector('.nav-links');
    
    // 创建并添加遮罩元素
    let blurOverlay = document.createElement('div');
    blurOverlay.classList.add('blur-overlay');
    blurOverlay.style.display = 'none';
    document.body.appendChild(blurOverlay);

    toggleMenu.addEventListener('click', function () {
        navLinks.classList.toggle('active');
        if (navLinks.classList.contains('active')) {
            blurOverlay.style.display = 'block';
        } else {
            blurOverlay.style.display = 'none';
        }
    });

    // 点击遮罩也关闭下拉菜单
    blurOverlay.addEventListener('click', function () {
        navLinks.classList.remove('active');
        blurOverlay.style.display = 'none';
    });
});