import { createApp } from "vue";
import App from "@/App.vue";
import { registerPlugins } from "@core/utils/plugins";
import ToastPlugin from "vue-toast-notification";
import "vue-toast-notification/dist/theme-bootstrap.css";
// Styles
import "@core-scss/template/index.scss";
import "@styles/styles.scss";

// Create vue app
const app = createApp(App);

app.use(ToastPlugin);
// Register plugins
registerPlugins(app);

// Mount vue app
app.mount("#app");
