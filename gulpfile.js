const { src, dest, series, parallel, watch } = require("gulp");
const fibers = require("fibers");
const sass = require("gulp-sass");
const imagemin = require("gulp-imagemin");
const browserify = require("browserify");
const source = require("vinyl-source-stream");
const babelify = require("babelify");
const postcss = require("gulp-postcss");
const importer = require("postcss-import");
const autoprefixer = require("autoprefixer");
sass.compiler = require("sass");
require("dotenv").config();

const path = {
  build: `./${process.env.WORDPEWSS_THEME_NAME}`,
  src: `./${process.env.WORDPEWSS_THEME_NAME}/src`,
};

const style = () => {
  const outputStyle =
    process.env.NODE_ENV === "develop" ? "expanded" : "compressed";

  return src(path.src + "/**/*.scss")
    .pipe(
      sass({
        fibers: fibers,
        outputStyle: outputStyle,
      })
    )
    .pipe(
      postcss([
        autoprefixer({ cascade: false }),
        importer({ path: ["node_modules"] }),
      ])
    )
    .pipe(dest(path.build));
};

const script = () => {
  return browserify(path.src + "/assets/scripts/main.js")
    .transform(babelify, { presets: ["@babel/preset-env"] })
    .bundle()
    .pipe(source("bundle.js"))
    .pipe(dest(path.build + "/assets/scripts/"));
};

const image = () => {
  return src(path.src + "/assets/images/**/*.{jpg,jpeg,png,gif,svg}")
    .pipe(
      imagemin([
        imagemin.optipng({
          quality: [0.65, 0.8],
          speed: 1,
        }),
        imagemin.mozjpeg({
          quality: 80,
        }),
      ])
    )
    .pipe(dest(path.build + "/assets/images/"));
};

const watcher = () => {
  watch(path.src + "/assets/scripts/*.js", series(script));
  watch(path.src + "/**/*.scss", series(style));
  watch(path.src + "/assets/images/*", series(image));
};

exports.default = series(parallel(style, script, image), parallel(watcher));

exports.build = series(parallel(style, script, image));
