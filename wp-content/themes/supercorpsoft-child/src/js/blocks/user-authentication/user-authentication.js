class UserAuthBlock {
  constructor(blockElement) {
    this.blockElement = blockElement;
    this.ajaxUrl = authData.ajaxUrl;
    this.loginNonce = authData.loginNonce;
    this.logoutNonce = authData.logoutNonce;
    this.isLoggedIn = authData.isLoggedIn === "true";
    this.init();
  }

  init() {
    this.showRegistration =
      this.blockElement.querySelector("#show-registration");
    this.showLogin = this.blockElement.querySelector("#show-login");
    this.registerWrapper = this.blockElement.querySelector(
      "#user-registration-form-wrapper"
    );
    this.loginWrapper = this.blockElement.querySelector(
      "#user-login-form-wrapper"
    );
    this.registrationForm =
      this.blockElement.querySelector("#user-registration");
    this.loginForm = this.blockElement.querySelector("#user-login");

    this.registrationError = this.blockElement.querySelector(
      "#registration-error"
    );
    this.loginError = this.blockElement.querySelector("#login-error");

    this.setupEventListeners();

    // Check login status and display the appropriate content
    if (this.isLoggedIn) {
      this.showLoggedInState();
    } else {
      this.toggleForms("registration");
    }
  }

  setupEventListeners() {
    this.showRegistration.addEventListener("click", () =>
      this.toggleForms("registration")
    );
    this.showLogin.addEventListener("click", () => this.toggleForms("login"));

    this.loginForm.addEventListener("submit", (e) =>
      this.handleFormSubmit(
        e,
        "user_login_action",
        this.loginError,
        this.loginNonce
      )
    );
    this.registrationForm.addEventListener("submit", (e) =>
      this.handleFormSubmit(
        e,
        "user_registration_action",
        this.registrationError,
        this.loginNonce
      )
    );
  }

  toggleForms(formType) {
    if (formType === "registration") {
      this.registerWrapper.style.display = "block";
      this.loginWrapper.style.display = "none";
    } else {
      this.loginWrapper.style.display = "block";
      this.registerWrapper.style.display = "none";
    }
    this.registrationError.style.display = "none";
    this.loginError.style.display = "none";
  }

  async handleFormSubmit(e, action, errorElement, nonce) {
    e.preventDefault();
    errorElement.innerHTML = ""; // Clear previous errors

    const formData = new FormData(e.target);
    formData.append("action", action);
    formData.append("ajax_nonce", nonce);

    try {
      const response = await fetch(this.ajaxUrl, {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded",
        },
        body: new URLSearchParams(formData),
      });

      if (response.status === 403) {
        throw new Error("Forbidden");
      }

      const data = await response.json();

      if (data.success) {
        if (action === "user_registration_action") {
          this.registerWrapper.style.display = "none"; // Hide the registration form
          errorElement.innerHTML = `${data.data.success_message} <p><button id="registration-complete-login">Login Here</button></p>`;
          document
            .getElementById("registration-complete-login")
            .addEventListener("click", () => this.showLogin.click());
        } else if (action === "user_login_action") {
          window.location.reload(); // Refresh the page after a successful login
        }
      } else {
        console.log("error");
        console.log(data);
        errorElement.innerHTML = data.data.message;
      }

      errorElement.style.display = "block"; // Ensure the error element is visible
    } catch (error) {
      console.error("Error:", error);
      errorElement.innerHTML =
        "An unexpected error occurred. Please try again.";
      errorElement.style.display = "block"; // Ensure the error element is visible
    }
  }

  showLoggedInState() {
    // Hide forms and display a message indicating the user is logged in
    this.registerWrapper.style.display = "none";
    this.loginWrapper.style.display = "none";
    this.blockElement.innerHTML = `<p>You are logged in! <button id="logout-button" type="button">Logout</button>?</p>`;
    this.setupLogoutButton(); // Set up the logout button
  }

  async setupLogoutButton() {
    const logoutButton = document.getElementById("logout-button");
    logoutButton.addEventListener("click", async () => {
      try {
        const response = await fetch(this.ajaxUrl, {
          method: "POST",
          headers: {
            "Content-Type": "application/x-www-form-urlencoded",
          },
          body: new URLSearchParams({
            action: "user_logout_action",
            ajax_nonce: this.logoutNonce,
          }),
        });

        if (response.status === 403) {
          throw new Error("Forbidden");
        }

        const data = await response.json();

        if (data.success) {
          window.location.href = "/"; // Redirect to homepage after logout
        } else {
          console.error("Logout failed: ", data.message);
        }
      } catch (error) {
        console.error("Error:", error);
      }
    });
  }
}

document.addEventListener("DOMContentLoaded", () => {
  const blockElement = document.querySelector(".user-authentication-block");
  if (blockElement) {
    new UserAuthBlock(blockElement);
  }
});
