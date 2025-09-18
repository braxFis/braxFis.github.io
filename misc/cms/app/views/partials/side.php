<?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'){ ?>}
<div class="navigation-rail">
    <div class="menu-fab">
        <div class="menu">
            <div class="container">
                <div class="state-layer nav-item">
                    <a href="#/issues">
                        <p class="make-cooler fab fa-github"></p>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="menu-fab">
        <div class="menu">
            <div class="container">
                <div class="state-layer nav-item">
                    <a href="#/content">
                        <p class="make-cooler fab fa-github"></p>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php } ?>