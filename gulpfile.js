const gulp = require('gulp');
const sass = require('gulp-sass');
const autoprefixer = require('gulp-autoprefixer');
const sourcemaps = require('gulp-sourcemaps');
const rename = require('gulp-rename');
const flipper = require('gulp-css-flipper');
const runSequence = require('run-sequence');

gulp.task('sass', function () {
	return gulp.src('assets/css/scss/*.scss')
		.pipe(sourcemaps.init())
		.pipe(sass({indentWidth: 1, outputStyle: 'expanded', indentType: 'tab'}).on('error', sass.logError))
		.pipe(autoprefixer({browsers: ['last 3 versions'],}))
		.pipe(sourcemaps.write('.'))
		.pipe(gulp.dest('assets/css'));
});

gulp.task('css-rtl', function () {
	return gulp.src(['assets/css/*.css', '!assets/css/*-rtl.css'])
		.pipe(flipper())
		.pipe(rename(
			{suffix: "-rtl"}
		))
		.pipe(gulp.dest('assets/css/'));
});

gulp.task('sass-production', function () {
	return gulp.src('assets/css/scss/*.scss')
		.pipe(sass({indentWidth: 1, outputStyle: 'expanded', indentType: 'tab'}).on('error', sass.logError))
		.pipe(autoprefixer({browsers: ['last 3 versions'],}))
		.pipe(gulp.dest('assets/css'));
});

gulp.task('default', function () {
	runSequence(
		'sass',
		'css-rtl'
	);
	gulp.watch('assets/css/scss/**/*.scss', ['sass', 'css-rtl']);
});

gulp.task('production', function () {
	runSequence(
		'sass-production',
		'css-rtl'
	);
});
