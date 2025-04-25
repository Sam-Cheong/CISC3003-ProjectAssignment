document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("register-form");
    const username = document.getElementById("username");
    const email = document.getElementById("email");
    const password = document.getElementById("password");
    const repeatPwd = document.getElementById("repeatPwd");

    // Helper: append message container
    function createMsgContainer(input) {
        const el = document.createElement("div");
        el.className = "input-error";
        input.parentNode.appendChild(el);
        return el;
    }

    const msg = {
        username: createMsgContainer(username),
        email: createMsgContainer(email),
        password: createMsgContainer(password),
        repeatPwd: createMsgContainer(repeatPwd),
    };

    // Create strength bar
    const bar = document.createElement("div");
    bar.className = "strength-bar";
    const fill = document.createElement("div");
    fill.className = "strength-fill";
    bar.appendChild(fill);
    password.parentNode.appendChild(bar);

    // Validation rules
    const rules = {
        username: (v) => v.trim() !== "",
        email: (v) => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v),
        password: (v) => v.length >= 6,
        repeatPwd: (v) => v === password.value,
    };

    // Validate single field
    function validate(field) {
        const v = field.value;
        const ok = rules[field.id](v);
        const el = msg[field.id];
        if (!ok) {
            let text = "";
            switch (field.id) {
                case "username":
                    text = "Username is required";
                    break;
                case "email":
                    text = "Invalid email";
                    break;
                case "password":
                    text = "Password must be ≥6 chars";
                    break;
                case "repeatPwd":
                    text = "Passwords do not match";
                    break;
            }
            el.textContent = text;
            el.className = "form-message form-message-red";
        } else {
            el.textContent = "";
            el.className = "input-error";
        }
        return ok;
    }

    // Update strength bar
    function updateStrength() {
        const v = password.value;
        let pct = 0;
        if (v.length >= 6) pct = 33;
        if (v.length >= 10 && /[A-Z]/.test(v) && /\d/.test(v)) pct = 66;
        if (
            v.length >= 12 &&
            /[A-Z]/.test(v) &&
            /\d/.test(v) &&
            /[^A-Za-z0-9]/.test(v)
        )
            pct = 100;
        fill.style.width = pct + "%";
        if (pct <= 33) fill.style.backgroundColor = "#f23f30";
        else if (pct <= 66) fill.style.backgroundColor = "#f29e00";
        else fill.style.backgroundColor = "#34ad21";
    }

    // Event listeners
    username.addEventListener("input", () => validate(username));
    email.addEventListener("input", () => validate(email));
    password.addEventListener("input", () => {
        validate(password);
        updateStrength();
        if (repeatPwd.value) validate(repeatPwd);
    });
    repeatPwd.addEventListener("input", () => validate(repeatPwd));

    // On submit
    form.addEventListener("submit", (e) => {
        let all = [username, email, password, repeatPwd].map((f) =>
            validate(f)
        );
        if (all.includes(false)) e.preventDefault();
    });
});
