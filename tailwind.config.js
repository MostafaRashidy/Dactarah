/** @type {import('tailwindcss').Config} */
const defaultTheme = require("tailwindcss/defaultTheme");

export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
        "./resources/views/components/**/*.blade.php",
        "./resources/views/layouts/**/*.blade.php",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ["Figtree", ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // Add custom colors or ensure these colors are available
                blue: {
                    50: "#eff6ff",
                    100: "#dbeafe",
                    500: "#3b82f6",
                    600: "#2563eb",
                    700: "#1d4ed8",
                },
                green: {
                    50: "#f0fdf4",
                    100: "#dcfce7",
                    500: "#22c55e",
                    600: "#16a34a",
                    700: "#15803d",
                },
                red: {
                    50: "#fef2f2",
                    100: "#fee2e2",
                    500: "#ef4444",
                    600: "#dc2626",
                    700: "#b91c1c",
                },
                amber: {
                    50: "#fffbea",
                    100: "#fff3c4",
                    200: "#fce588",
                    300: "#fadb5f",
                    400: "#f7c948",
                    500: "#f0b429",
                    600: "#de911d",
                    700: "#cb6e17",
                    800: "#b44d12",
                    900: "#8d2b0b",
                },
                emerald: {
                    50: "#f0fdf4",
                    100: "#dcfce7",
                    200: "#bbf7d0",
                    300: "#86efac",
                    400: "#4ade80",
                    500: "#22c55e",
                    600: "#16a34a",
                    700: "#15803d",
                    800: "#166534",
                    900: "#14532d",
                },
                sky: {
                    50: "#f0f9ff",
                    100: "#e0f2fe",
                    200: "#bae6fd",
                    300: "#7dd3fc",
                    400: "#38bdf8",
                    500: "#0ea5e9",
                    600: "#0284c7",
                    700: "#0369a1",
                    800: "#075985",
                    900: "#0c4a6e",
                },
                rose: {
                    50: "#fff1f2",
                    100: "#ffe4e6",
                    200: "#fecdd3",
                    300: "#fda4af",
                    400: "#fb7185",
                    500: "#f43f5e",
                    600: "#e11d48",
                    700: "#be123c",
                    800: "#9f1239",
                    900: "#881337",
                },

                boxShadow: {
                    custom: "0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03)",
                },
                borderRadius: {
                    xl: "0.75rem",
                    "2xl": "1rem",
                },
                transitionProperty: {
                    height: "height",
                    spacing: "margin, padding",
                },
            },
        },
    },

    safelist: [
        {
            pattern: /bg-(amber|emerald|red)-700/,
        },
    ],

    plugins: [require("@tailwindcss/forms")],
};
