<head>
<!-- Add to your <head> -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<!--<link rel="stylesheet" href="../../../css/side.css">-->
<style>
    body {
        margin: 0;
        font-family: sans-serif;
    }

    /* BURGER */
    .burger {
        width: 30px;
        height: 30px;
        cursor: pointer;
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 1001;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    .burger .line {
        width: 30px;
        height: 4px;
        background: #fff;
        margin: 4px 0;
        transition: 0.4s;
        border-radius: 2px;
    }

    /* BURGER TO CROSS */
    .burger.open .line:nth-child(1) {
        transform: rotate(45deg) translate(5px, 5px);
    }
    .burger.open .line:nth-child(2) {
        opacity: 0;
    }
    .burger.open .line:nth-child(3) {
        transform: rotate(-45deg) translate(5px, -5px);
    }

    /* MENU */
    .main-menu {
        position: fixed;
        top: 0;
        right: 0;
        width: 100%;
        height: 100vh;
        background: #111;
        transform: translateX(100%);
        transition: transform 0.3s ease;
        z-index: 1000;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .main-menu.open {
        transform: translateX(0%);
    }

    .main-menu ul {
        list-style: none;
        padding: 0;
        margin: 0;
        text-align: center;
    }

    .main-menu li {
        margin: 20px 0;
    }

    .main-menu a {
        color: white;
        text-decoration: none;
        font-size: 2em;
    }

    .custom-color {
        color: grey !important;
    }
    .main-menu ul li{
        display: block;
    }

    /* Floating Action Button (FAB) */
    .menu-fab {
        margin-bottom: 20px;
    }

    /* Navigation Items */
    .destinations {
        width: 100%;
    }

    .nav-item, .div {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px;
        cursor: pointer;
        border-radius: 5px;
        transition: background 0.3s;
    }

    .nav-item:hover, .div:hover {
        background: #555;
    }

    /* Icons */
    .icon {
        width: 30px;
        height: 30px;
    }

    /* Labels */
    .label, .text-wrapper {
        flex-grow: 1;
        text-align: left;
    }

    /* Sidebar */
    .navigation-rail {
        position: fixed;
        top: 0;
        right: 0;
        width: 20px; /* Collapsed width */
        height: 100vh;
        background: #000;
        overflow: hidden;
        transition: width 0.3s ease-in-out;
        z-index: 1000;
        display: flex;
        flex-direction: column;
        align-items: center;
        padding-top: 10px;
    }

    /* Expand sidebar on hover */
    .navigation-rail:hover {
        width: 200px; /* Expanded width */
    }

    /* Navigation items */
    .destinations {
        display: flex;
        flex-direction: column;
        align-items: center;
        width: 100%;
    }

    .nav-item {
        display: flex;
        align-items: center;
        padding: 15px;
        color: white;
        cursor: pointer;
        width: 100%;
        transition: background 0.2s;
    }

    .nav-item:hover {
        background: #444;
    }

    /* Icons */
    .icon {
        width: 20px;
        margin-right: 10px;
    }

    /* Text hidden by default */
    .nav-item .label {
        opacity: 0;
        transition: opacity 0.2s ease-in-out;
        white-space: nowrap;
    }

    /* Show text when expanded */
    .navigation-rail:hover .nav-item .label {
        opacity: 1;
    }

    /* Main content adjustment */
    .main-content {
        margin-left: 60px; /* Same as collapsed width */
        padding: 20px;
        transition: margin-left 0.3s ease-in-out;
    }

    .navigation-rail:hover + .main-content {
        margin-left: 300px; /* Adjust when sidebar expands */
    }

    .make-cooler{
        color: white;
        text-transform: uppercase;

    }

    .issue-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1rem;
    }

    .issue-card {
        padding: 1rem;
        border: 1px solid #ddd;
        border-radius: 8px;
        background: #f9f9f9;
    }

    .pagination {
        margin-top: 1rem;
        display: flex;
        justify-content: center;
        gap: 1rem;
        align-items: center;
    }

    .add-border > p{
      border: 1px solid black
    }

</style>
</head>
<body>

<?php include 'side.php'; ?>
<!-- Burger icon -->
<div class="burger" id="burger">
    <i class="fas fa-bars"></i>
</div>

<!-- Search Widget -->
<?php
use app\widgets\SearchWidget;
echo SearchWidget::renderSearch();
?>
<!-- End Search Widget -->

<nav class="main-menu" id="mainMenu">
    <ul style="text-align: left">
        <?php foreach ($menus as $menu): ?>
            <li class="fa-solid fa-plus">
                <a class="custom-color" href="<?= htmlspecialchars($menu->url) ?>"><?= htmlspecialchars($menu->label) ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>

<script>
    const burger = document.getElementById('burger');
    const icon = burger.querySelector('i');
    const menu = document.getElementById('mainMenu');

    burger.addEventListener('click', () => {
        menu.classList.toggle('open');
        icon.classList.toggle('fa-bars');
        icon.classList.toggle('fa-times');
    });
</script>
