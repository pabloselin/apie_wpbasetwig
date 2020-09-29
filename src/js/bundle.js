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

const init = () => {
	const backButton = document.getElementById("backToSearch");
	if (backButton) {
		backButton.addEventListener("click", () => {
			history.back();
		});
	}

	const toggleMenu = document.getElementsByClassName('toggleMenu');
	const mainNav = document.getElementById('nav-main');

	for(i = 0; i < toggleMenu.length; i++) {
		toggleMenu[i].addEventListener('click', () => {
			mainNav.classList.toggle('active');
		});	
	}
	
};



ready(init);

console.log("bundle");
