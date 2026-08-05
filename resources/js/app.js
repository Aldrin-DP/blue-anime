import { createApp, h } from "vue";
import { createInertiaApp, Head, Link } from "@inertiajs/vue3";
import Toast from "vue-toastification";
import "vue-toastification/dist/index.css";
import "@fontsource-variable/inter";
import "@fontsource-variable/outfit/wght.css";
import AppLayout from "./Layouts/AppLayout.vue";
import BaseButton from "./Components/Base/BaseButton.vue";
import BaseHeading from "./Components/Base/BaseHeading.vue";
import BaseText from "./Components/Base/BaseText.vue";
import BaseLabel from "./Components/Base/BaseLabel.vue";
import BaseInputError from "./Components/Base/BaseLabel.vue";

const options = {
  position: "top-right",
  timeout: 1500,
  closeOnClick: true,
  pauseOnHover: true,
  draggable: true,
};

createInertiaApp({
  title: (title) => {
    return `${title} Sea Anime`;
  },
  resolve: (name) => {
    const pages = import.meta.glob("./Pages/**/*.vue", { eager: true });
    let page = pages[`./Pages/${name}.vue`];
    page.default.layout = page.default.layout || AppLayout;
    return page;
  },
  setup({ el, App, props, plugin }) {
    createApp({ render: () => h(App, props) })
      .use(plugin)
      .use(Toast, options)
      .component("Head", Head)
      .component("Link", Link)
      .component("BaseButton", BaseButton)
      .component("BaseHeading", BaseHeading)
      .component("BaseText", BaseText)
      .component("BaseLabel", BaseLabel)
      .component("BaseInputError", BaseInputError)
      .mount(el);
  },
});
