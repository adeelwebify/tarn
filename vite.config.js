import { defineConfig } from 'vite';
import path from 'path';

export default defineConfig({
	root: 'resources',
	css: {
		devSourcemap: true, // works in dev
	},
	build: {
		sourcemap: true, // <-- this enables .map files in production build
		outDir: '../public/build',
		emptyOutDir: true,
		manifest: true,
		rollupOptions: {
			input: {
				// only include the JS entry; styles are built with the `sass` script
				main: path.resolve(__dirname, 'resources/js/main.js'),
			},
			output: {
				entryFileNames: '[name].js',
				chunkFileNames: '[name].js',
				assetFileNames: '[name][extname]',
			},
		},
	},
});
