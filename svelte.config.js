import preprocess from "svelte-preprocess";

/** @type {import('@sveltejs/kit').Config} */
export default {
  preprocess: [
    preprocess({
      postcss: true,
    }),
  ],

  vitePlugin: {
    inspector: {
      toggleKeyCombo: 'meta-shift',
      showToggleButton: 'never',
      toggleButtonPos: 'bottom-right'
    },
  }
};
