'use strict';


var gulp = require('gulp'),
    watch = require('gulp-watch'),
    prefixer = require('gulp-autoprefixer'),
    // uglify = require('gulp-uglify'),
    sass = require('gulp-sass'),
    sourcemaps = require('gulp-sourcemaps'),
    // rigger = require('gulp-rigger'),
     cssmin = require('gulp-minify-css'),
    // imagemin = require('gulp-imagemin'),
    // pngquant = require('imagemin-pngquant'),
    // rimraf = require('rimraf'),
    // browserSync = require("browser-sync"),
    // reload = browserSync.reload
    livereload = require('gulp-livereload')
;

gulp.task('default', [
      'build'
    , 'livereload'
    , 'watch'
]);

/*-------------------------------*/

/*gulp.task('watch1', function(){

    console.log('Test gulpa');

    livereload.listen();

    gulp.watch('resources/views/!**!/!*.blade.php',
        livereload.reload);


});*/

/*-------------------------------*/

// При запуске main
gulp.task('build', [



    'Style:build'
    /*,'main.js:build'
    ,'main.css:build'
    ,'Ang:build'*/

]);

gulp.task('livereload', livereload.listen());

gulp.task('Style:build', function () {

    console.log('task(Style:build)');

    gulp.src('resources/views/Style.scss') //Находим наш main.scss
        .pipe(sourcemaps.init()) //То же самое что и с js
        .pipe(sass()).on('error', sass.logError) //Скомпилируем
        .pipe(prefixer()) //Добавим вендорные префиксы
        .pipe(cssmin()) //Сожмем
        .pipe(sourcemaps.write())
        .pipe(gulp.dest('public/css/'))
        .pipe(livereload());
});
gulp.task('Blade:build', function () {

    console.log('task(Blade:build))');

    livereload.reload();

});
gulp.task('watch', function() {

    console.log('task(watch');

    watch(['resources/views/!**!/!*.scss'], function(event, cb) {
        gulp.start('Style:build');
    });
    watch(['resources/views/!**!/!*.blade.php'],function(event, cb) {
        gulp.start('Blade:build');
    });

});

/*


gulp.task('Ang:build', function () {

    gulp.src('resources/views/Ang.js') //Найдем наш main файл
        .pipe(rigger()) //Прогоним через rigger
        .pipe(sourcemaps.init()) //Инициализируем sourcemap
        // .pipe(uglify()) //Сожмем наш js, нельзя для Angular
        .pipe(sourcemaps.write()) //Пропишем карты
        .pipe(gulp.dest('public/js/')); //И перезагрузим сервер
});

gulp.task('Blade:build', function () {

    livereload.reload();

});

gulp.task('watch', function(){

     watch(['resources/js/!**!/!*.js'], function(event, cb) {
         gulp.start('main.js:build');
     });

    watch(['resources/css/!**!/!*.scss'], function(event, cb) {
        gulp.start('main.css:build');
    });

/!*------------------------------------------------*!/

    watch(['resources/views/!**!/!*.js'], function(event, cb) {
        gulp.start('Ang:build');
    });
    watch(['resources/views/!**!/!*.scss'], function(event, cb) {
        gulp.start('Style:build');
    });
    watch(['resources/views/!**!/!*.blade.php'],function(event, cb) {

        gulp.start('Blade:build');
    });

});

/!*-------------------------------------------*!/


gulp.task('main.js:build', function () { console.log('main.js');
    gulp.src('resources/js/main.js') //Найдем наш main файл
        .pipe(rigger()) //Прогоним через rigger
        .pipe(sourcemaps.init()) //Инициализируем sourcemap
        .pipe(uglify()) //Сожмем наш js
        .pipe(sourcemaps.write()) //Пропишем карты
        .pipe(gulp.dest('public/js/')); //И перезагрузим сервер
});*/

/*gulp.task('main.css:build', function () {

    console.log('main.css');

    gulp.src('resources/css/main.scss') //Находим наш main.scss
        .pipe(sourcemaps.init()) //То же самое что и с js
        .pipe(sass()).on('error', sass.logError) //Скомпилируем
        .pipe(prefixer()) //Добавим вендорные префиксы
        .pipe(cssmin()) //Сожмем
        .pipe(sourcemaps.write())
        .pipe(gulp.dest('public/css/'));
});*/

























