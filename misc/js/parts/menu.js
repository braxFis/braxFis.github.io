function displayTopMenu(){
    document.getElementById("topMenu").innerHTML = `

        <label for="search-query">Search</label>
        <input type="search" name="search" id="search-query">
        <button type="button" onclick="search();">Search</button>

        <nav class="main-menu">
            <ul>
               <li><a href="#">Home</a></li>
                <li><a href="/html/static/about.html">About</a></li>
                <li><a href="/html/static/contact.html">Contact</a></li>
                <li><a href="#">Login</a></li>
                <li><a href="#">Register</a></li>
            </ul>
        </nav>
    `
}

displayTopMenu();
