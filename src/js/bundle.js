//bundle.js

//import "./extras/customizer";
//import "./extras/navigation";
//import "../../node_modules/bootstrap/dist/js/bootstrap.bundle.js"

function ready(fn) {
	if (document.readyState != "loading") {
		fn();
	} else {
		document.addEventListener("DOMContentLoaded", fn);
	}
}

const back = () => {
	const backButton = document.getElementById("backToSearch");
	if (backButton) {
		backButton.addEventListener("click", () => {
			history.back();
		});
	}
};

ready(back);

console.log("bundle");
