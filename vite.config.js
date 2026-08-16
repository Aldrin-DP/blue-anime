import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue";
import tailwindcss from "@tailwindcss/vite";

export default defineConfig({
  plugins: [
    tailwindcss(),
    laravel({
      input: ["resources/js/app.js", "resources/css/app.css"],
      refresh: true,
    }),
    vue(),
  ],

  server: {
    host: "0.0.0.0",
    hmr: {
      host: "192.168.100.55",
    },
  },
});
