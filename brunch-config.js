
exports.config = {
    files: {
        javascripts: {
            joinTo: {
                'js/app.js': /^front/,
                'js/calendar.js': /^front/,
                'js/editlist.js': /^front/,
                'js/search.js': /^front/,
                'js/vendor.js': /^node_modules/,
            }
        },
        stylesheets: {
            joinTo: "css/app.css"
        }
    },


    paths: {
        watched: ["front"],
        public: "web"
    },


    plugins: {
        copycat: {
          fonts: ['./node_modules/font-awesome/fonts']
        },
        sass: {
            options:{
                includePaths: ['./node_modules/bootstrap/scss/', './node_modules/font-awesome/scss/'],
                precision: 8
            }
        }
    },

    modules: {
        autoRequire: {
            "js/app.js": ["front/js/app"],
            "js/calendar.js": ["front/js/calendar"],
            "js/editlist.js": ["front/js/editlist"],
            "js/search.js": ["front/js/search"],
        }
    },

    npm: {
        enabled: true,
        globals: {
            $: 'jquery',
            jQuery: 'jquery',
            bootstrap: 'bootstrap'
        }
    }
};