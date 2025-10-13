import {IssueController} from "./js/git/controller/IssueController.js";

const routes = {
    "ADMIN": {
        "GET": {},
        "POST": {},
    },
    "USER": {
        "GET":{},
        "POST":{},
    },
    "PUBLIC": {
        "GET":{
            "": {controller: "MainController", method: "index"}
        },
        "POST":{},
    }
}

const controllerFactory = {
    MainController: () => new MainController(new MainView("app"), new Main()),
};

let currentController = null;

function dispatch(method, path) {
    const route = routes.ADMIN[method]?.[path];
    console.log("Dispatching:", method, path, route);
    if (!route) {
        document.body.innerHTML = "<h1>404 - Not Found</h1>";
        return;
    }

    const createController = controllerFactory[route.controller];
    if (!createController) {
        console.error("Controller not found in factory:", route.controller);
        return;
    }

    // Create and store the controller instance globally
    currentController = createController();

    // Assign to window.controller so buttons can access it
    window.controller = currentController;

    // Bind important methods so 'this' works properly inside button handlers
    if (currentController.loadIssues) {
        currentController.loadIssues = currentController.loadIssues.bind(currentController);
    }
    if (currentController.close) {
        currentController.close = currentController.close.bind(currentController);
    }
    if (currentController.update) {
        currentController.update = currentController.update.bind(currentController);
    }
    // Bind other methods as needed here

    if (typeof currentController[route.method] === "function") {
        currentController[route.method]();
    } else {
        console.error("Method not found on controller:", route.method);
    }
}
window.addEventListener("hashchange", () => {
    const path = window.location.hash.replace("#", "");
    dispatch("GET", path);
});

window.addEventListener("load", () => {
    const path = window.location.hash.replace("#", "");
    if(path) dispatch("GET", path);
});
