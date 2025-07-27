// src/plugins/toast.ts
import Toast, { type PluginOptions, toast as baseToast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';

export const toast = baseToast;

export default {
  install(app: never) {
    app.use(Toast, {
      autoClose: 8000,
      position: 'bottom-right',
    } as PluginOptions);
  }
};