/** @type {import('tailwindcss').Config} */
module.exports = {
    darkMode: ["class"],
    content: [
        './pages/**/*.{ts,tsx}',
        './components/**/*.{ts,tsx}',
        './app/**/*.{ts,tsx}',
        './src/**/*.{ts,tsx}',
    ],
    prefix: "rwt-",
    theme: {
        container: {
            center: true,
            padding: "2rem",
            screens: {
                "2xl": "1400px",
            },
        },
        extend: {
            colors: {
                border: "hsl(var(--border))",
                input: "hsl(var(--input))",
                ring: "hsl(var(--ring))",
                background: "hsl(var(--background))",
                foreground: "hsl(var(--foreground))",
                primary: {
                    DEFAULT: "hsl(var(--primary))",
                    foreground: "hsl(var(--primary-foreground))",
                },
                secondary: {
                    DEFAULT: "hsl(var(--secondary))",
                    foreground: "hsl(var(--secondary-foreground))",
                },
                grayprimary:{
                    DEFAULT:"#6E6A75",
                },
                destructive: {
                    DEFAULT: "hsl(var(--destructive))",
                    foreground: "hsl(var(--destructive-foreground))",
                },
                muted: {
                    DEFAULT: "hsl(var(--muted))",
                    foreground: "hsl(var(--muted-foreground))",
                },
                accent: {
                    DEFAULT: "hsl(var(--accent))",
                    foreground: "hsl(var(--accent-foreground))",
                },
                popover: {
                    DEFAULT: "hsl(var(--popover))",
                    foreground: "hsl(var(--popover-foreground))",
                },
                card: {
                    DEFAULT: "hsl(var(--card))",
                    foreground: "hsl(var(--card-foreground))",
                },
            },
            backgroundColor:{
                lightgray:"#F3F4F6"
            },
            borderRadius: {
                lg: "var(--radius)",
                md: "calc(var(--radius) - 2px)",
                sm: "calc(var(--radius) - 4px)",
            },
            keyframes: {
                "accordion-down": {
                    from: {height: "0"},
                    to: {height: "var(--radix-accordion-content-height)"},
                },
                "accordion-up": {
                    from: {height: "var(--radix-accordion-content-height)"},
                    to: {height: "0"},
                },
            },
            animation: {
                "accordion-down": "accordion-down 0.2s ease-out",
                "accordion-up": "accordion-up 0.2s ease-out",
            },
            width: {
                "90%": "90%",
                "20%": "20%",
                "25%": "25%",
                "30%": "30%",
                "40%": "40%",
                5.5: "22px",
                "60%": "60%",
                "100%": "100%",
                "50%": "50%",
                "70%": "70%",
                "1/7":"14.285714%",
                "340px":"340px",
                "6%":"6%",
            },
            minWidth:{
                "35%":"35%",
                "350px":"350px",
            },
            height: {
                "75vh": "75vh",
                "350px":'350px',
                11.5: "46px",
                18:"72px",
                "58px":"58px",
                85: "340px",
                90: "360px",
                "100%": "100%",
                "50%": "50%",
                "32.5rem":"32.5rem"

            },
            fontFamily: {
                customFont: ['"-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Oxygen-Sans,Ubuntu,Cantarell,"Helvetica Neue""', "sans-serif"],
            },
            fontSize: {
                2.5: "10px",
                3.5: "14px",
                4: "16px",
                4.5: "18px",
                "15px":"15px",
            },
            padding: {
                0.5: '2px',
                5.5: "22px"
            },

            borderColor: {
                "primary-gray": "#CCCACE",
                "secondary-gray": "#E5E4E7",
                "purple-primary": "#8545F7",

            },
            borderWidth: {
                1: "1px"
            }


        },
    },
    plugins: [require("tailwindcss-animate")],
}