import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({


    server: {
        ignored: ['**/node_modules/**', '**/storage/**', '**/public/**'],
        optimizeDeps: {
            esbuildOptions: {
                maxWorkers: 2, // Reduce la cantidad de procesos en paralelo
            }
        },
        // ---
        host: "0.0.0.0",
        hmr: {
            clientPort: 3000,
            host: 'localhost',
            protocol: 'ws'
        },
        port: 3000,
        watch: {
            usePolling: false
            // usePolling: true
        }
    },

    plugins: [
        vue(),
        laravel({
            input: ["resources/css/app.css", "resources/js/app.js"],
            refresh: true,
        }),
    ],
    // optimizeDeps: {
    //     include: ['vue-final-modal'],
    // }
});

// export default defineConfig({



// export default defineConfig({
//      server: {
//         hmr: {
//             host: "0.0.0.0",
//         },
//         port: 3000,
//         // host: true,
//     },
//     plugins: [
//         vue(),
//         laravel({
//             input: ['resources/css/app.css', 'resources/js/app.js'],
//             refresh: true,
//         }),
//     ],
//     build: {
//         outDir: 'public/build',  // Asegúrate de que esta ruta esté configurada correctamente
//     },
// });
