const imagePreview = document.querySelector(".image-preview");
const fileInput = document.querySelector(".file-input");

fileInput.addEventListener("change", function () {
  const file = this.files[0];

  if (file) {
    const reader = new FileReader();

    reader.addEventListener("load", function () {
      const image = new Image();

      image.addEventListener("load", function () {
        imagePreview.innerHTML = "";
        imagePreview.appendChild(image);
      });

      image.src = reader.result;
    });

    reader.readAsDataURL(file);
  } else {
    imagePreview.innerHTML = "";
  }
});


const emailInput = document.getElementById("email");
const passwordInput = document.getElementById("password");
const nameInput = document.getElementById("name");

emailInput.addEventListener("focus", function() {
  emailInput.removeAttribute("placeholder");
});

emailInput.addEventListener("blur", function() {
  emailInput.setAttribute("placeholder", "Enter your email");
});

passwordInput.addEventListener("focus", function() {
  passwordInput.removeAttribute("placeholder");
});

passwordInput.addEventListener("blur", function() {
  passwordInput.setAttribute("placeholder", "Enter your password");
});

nameInput.addEventListener("focus", function() {
  nameInput.removeAttribute("placeholder");
});

nameInput.addEventListener("blur", function() {
  nameInput.setAttribute("placeholder", "Enter your name");
});
