/** @type {import('tailwindcss').Config} */
export default {

    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],

    theme: {

        extend: {

            colors: {

                primary: {
                    50: "#ecfeff",
                    100: "#cffafe",
                    200: "#a5f3fc",
                    300: "#67e8f9",
                    400: "#22d3ee",
                    500: "#06b6d4",
                    600: "#0891b2",
                    700: "#0e7490",
                    800: "#155e75",
                    900: "#164e63",
                },

                success: "#16a34a",
                warning: "#f59e0b",
                danger: "#dc2626",
                info: "#2563eb",

            },

            fontFamily: {

                sans: [
                    "Inter",
                    "Poppins",
                    "sans-serif"
                ],

            },

            borderRadius: {

                card: "16px",
                button: "12px",
                input: "10px",

            },

            boxShadow: {

                card: "0 2px 8px rgba(15,23,42,.05)",
                dropdown: "0 12px 24px rgba(15,23,42,.10)",
                modal: "0 20px 45px rgba(15,23,42,.18)",

            },

        },

    },

    plugins: [],

};