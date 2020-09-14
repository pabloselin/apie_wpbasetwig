import { src, dest, watch, series, parallel } from "gulp";
import yargs from "yargs";
import sass from "gulp-sass";
import cleanCss from "gulp-clean-css";
import gulpif from "gulp-if";
import postcss from "gulp-postcss";
import sourcemaps from "gulp-sourcemaps";
import autoprefixer from "autoprefixer";
import del from "del";
import webpack from "webpack-stream";
import browserSync from "browser-sync";
import replace from "gulp-replace";
import zip from "gulp-zip";
import info from "./package.json";
import setTitle from "node-bash-title";

setTitle('🖍 CTCI Theme');


const PRODUCTION = yargs.argv.prod;

export const scripts = () => {
	return src("src/js/bundle.js")
		.pipe(
			webpack({
				module: {
					rules: [
						{
							test: /\.js$/,
							use: {
								loader: "babel-loader",
								options: {
									presets: [],
								},
							},
						},
					],
				},
				mode: PRODUCTION ? "production" : "development",
				devtool: !PRODUCTION ? "inline-source-map" : false,
				output: {
					filename: "bundle.js",
				},
			})
		)
		.pipe(dest("dist/js"));
};

export const styles = () => {
	return src("src/sass/style.scss")
		.pipe(gulpif(!PRODUCTION, sourcemaps.init()))
		.pipe(sass().on("error", sass.logError))
		.pipe(gulpif(PRODUCTION, postcss([autoprefixer])))
		.pipe(gulpif(PRODUCTION, cleanCss({ compatibility: "ie10" })))
		.pipe(gulpif(!PRODUCTION, sourcemaps.write()))
		.pipe(dest("dist/css"))
		.pipe(server.stream());
};

export const init = () => {
	return src([
		"**/*",
		"!node_modules{,/**}",
		"!bundled{,/**}",
		"!src{,/**}",
		"!.babelrc",
		"!.gitignore",
		"!gulpfile.babel.js",
		"!package.json",
		"!package-lock.json",
	])
		.pipe(replace("themename_, info.name"))
		.pipe(zip(`${info.name}.zip`))
		.pipe(dest("bundled"));
};

const server = browserSync.create();
export const serve = (done) => {
	server.init({
		proxy: "http://ctcidocs.local",
	});
	done();
};
export const reload = (done) => {
	server.reload();
	done();
};

export const watchForChanges = () => {
	watch("src/sass/**/*.scss", styles);
	watch("src/js/**/*.js", series(scripts, reload));
	watch("**/*.php", reload);
	watch("views/**/*.twig", reload)
};

export const clean = () => del(["dist"]);

export const dev = series(
	clean,
	parallel(styles, scripts),
	serve,
	watchForChanges
);
export const build = series(clean, parallel(styles, scripts));

export default dev;
