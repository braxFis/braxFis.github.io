function displaySideMenu(){
    document.getElementById("menu").innerHTML =
    `
        <div class="navigation-rail">
  <div class="menu-fab">
    <div class="menu">
      <div class="container">
        <div class="state-layer">
            <a href="">
                <img class="icon" src="/img/icon-5.svg" />
            </a>
        </div>
      </div>
    </div>
    <div class="FAB-elevation">
      <div class="FAB">
        <div class="icon-wrapper">
            <a href="">
                <img class="icon" src="/img/image.svg" />
            </a>
        </div>
      </div>
    </div>
  </div>
  <div class="destinations">
    <div class="nav-item">
      <div class="state-layer-wrapper">
        <div class="img-wrapper">
            <a href="">
                <img class="icon" src="/img/icon-2.svg" />
            </a>
        </div>
      </div>
      <div class="label"></div>
    </div>
    <div class="nav-item">
      <div class="div-wrapper">
        <div class="img-wrapper">
            <a href="">
                <img class="icon" src="/img/icon-4.svg" />
            </a>
        </div>
      </div>
      <div class="text-wrapper"></div>
    </div>
    <div class="nav-item">
      <div class="div-wrapper">
        <div class="img-wrapper">
            <a href="">
                <img class="icon" src="/img/icon-3.svg" />
            </a>
        </div>
      </div>
      <div class="text-wrapper"></div>
    </div>
    <div class="div">
      <div class="div-wrapper">
        <div class="img-wrapper">
            <a href="">
                <img class="icon" src="/img/icon.svg" />
            </a>
        </div>
      </div>
      <div class="text-wrapper"></div>
    </div>
  </div>
</div>
`
}

displaySideMenu();
