/** @type {import('tailwindcss').Config} */
import preline from 'preline/plugin';

export default {
  content: [
    "./index.html",
    "./src/**/*.{js,ts,jsx,tsx}",
    "./node_modules/preline/preline.js"
  ],
  theme: {
    fontSize: {
      xs: ['12px', '16px'],
      sm: ['14px', '20px'],
      base: ['16px', '19.5px'],
      lg: ['18px', '21.94px'],
      xl: ['20px', '24.38px'],
      '2xl': ['24px', '29.26px'],
      '3xl': ['28px', '50px'],
      '4xl': ['48px', '58px'],
      '5xl': ['64px'],
      '8xl': ['96px', '106px']
    },
    extend: {
      transitionDuration: {
        '1500': '1500ms',
        '2000': '2000ms'
      },
      animation: {
        fade: 'fadeIn 5s ease-out',
      },
      fontFamily: {
        palanquin: ['Palanquin', 'sans-serif'],
        montserrat: ['Montserrat', 'sans-serif'],
        roboto: ['Roboto', 'sans-serif'],
        poppins: ['Poppins', 'sans-serif'],
        rokkitt: ['Rokkitt', 'serif'],
        mulish: ['Mulish', 'sans-serif']
      },
      colors: {
        'main': "#9EBBF1",
        'primary': "#2788E2",
        'sec': "#42B4EE",
        'grey' : "#3F4756",
        'footer': "#51565E"
      },
      boxShadow: {
        '3xl': '0 10px 40px rgba(0, 0, 0, 0.1)'
      },
      backgroundImage: {
        'hero': "url('assets/images/collection-background.svg')",
        'card': "url('assets/images/thumbnail-background.svg')",
      },
      screens: {
        "wide": "1440px"
      },
      borderWidth: {
        '1': '1px',
      },
    },
  },
  plugins: [
    preline
  ],
}