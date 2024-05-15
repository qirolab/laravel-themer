import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import path from "path";
%tailwind_import%
%vue_import%
%react_import%

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "%theme_path%%app_css_input%",
                "%theme_path%js/app.js"
            ],
            buildDirectory: "%theme_name%",
        }),
        %vue_plugin_config%
        %react_plugin_config%
        {
            name: "blade",
            handleHotUpdate({ file, server }) {
                if (file.endsWith(".blade.php")) {
                    server.ws.send({
                        type: "full-reload",
                        path: "*",
                    });
                }
            },
        },
    ],
    resolve: {
        alias: {
            '@': '/%theme_path%js',
            %bootstrap%
        }
    },
    %css_config%
});
