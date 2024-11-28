function themeSwitcher() {
    return {
        menu: false,
        // Default to system theme if no preference is stored
        theme: localStorage.getItem("theme") || undefined,
        darkMode() {
            this.theme = "dark";
            localStorage.setItem("theme", "dark");
            this.setDarkClass();
        },
        lightMode() {
            this.theme = "light";
            localStorage.setItem("theme", "light");
            this.setDarkClass();
        },
        systemMode() {
            this.theme = undefined;
            localStorage.removeItem("theme");
            this.setDarkClass();
        },
        setDarkClass() {
            // Apply the dark class based on theme or system preference
            document.documentElement.classList.toggle(
                "dark",
                this.theme === "dark" ||
                    (this.theme === undefined &&
                        window.matchMedia("(prefers-color-scheme: dark)")
                            .matches)
            );
        },
        init() {
            // Set the initial class based on the theme or system preference
            this.setDarkClass();
        },
    };
}
