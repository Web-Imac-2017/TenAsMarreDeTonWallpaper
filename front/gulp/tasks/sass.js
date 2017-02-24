// Dans ce fichier, on va créer la task pour build les fichiers scss.
// La configuration est similaire à celle du build pour les fichiers js

var autoprefixer = require('gulp-autoprefixer'); // Auto prefixer for css
var gulp         = require('gulp');              // Base gulp package
var minify       = require('gulp-minify-css');   // Minify CSS
var notify       = require('gulp-notify');       // Provides notification to both the console and Growel
var rename       = require('gulp-rename');       // Rename sources
var sass         = require('gulp-sass');         // Used to build sass files
var sourcemaps   = require('gulp-sourcemaps');   // Provide external sourcemap files

var mapError     = require('../error');

var config = {
  scss      : './src/scss/*',         // Les fichiers à watch
  src       : './src/scss/main.scss', // Le fichier principal
  outputDir : './www/assets/css',     // Le dossier ou le build sera généré
  outputFile: 'style.css'             // Le nom du fichier build
};

// La tache pour générer le build scss.
// C'est un peu similaire à la tache js.
gulp.task('sass', function() {
  return gulp.src(config.src)
    .pipe(sourcemaps.init({ loadMaps: true }))
    .pipe(sass())
    .on('error', mapError)
    .pipe(rename(config.outputFile))
    .pipe(autoprefixer())                 // Auto prefix css rules for each browsers
    .pipe(minify({processImport: false})) // Minify build file
    .pipe(sourcemaps.write('./'))
    .pipe(gulp.dest(config.outputDir))
    .pipe(notify({
      onLast: true,
      message: 'Generated file: <%= file.relative %>',
    }));
});

// Task pour watch les modifications sur les fichiers scss
gulp.task('watch', ['sass'], function() {
	gulp.watch(config.scss, ['sass']);
})
