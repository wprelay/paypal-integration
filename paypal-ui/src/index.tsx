import React from "react";
import App from "./App";
// import "./styles/index.css"
import {createRoot} from "react-dom/client";


// @ts-ignore
let target = document.getElementById("wp-relay-main");
if (target) {
    let root = createRoot(target);
    root.render(<App/>);
} else {
    setTimeout(() => {
        // @ts-ignore
        target = document.getElementById("wp-relay-main");
        if (target) {
            let root = createRoot(target);
            root.render(<App/>);
        }
    }, 500)
}

