// Dans ce fichier, on définit la task pour build les fichiers js

var babelify   = require('babelify');            // Used to convert ES6 & JSX to ES5
var browserify = require('browserify');          // Providers "require" support, CommonJS
var buffer     = require('vinyl-buffer');        // Vinyl stream support
var duration   = require('gulp-duration');       // Time aspects of your gulp process
var gulp       = require('gulp');                // Base gulp package
var gutil      = require('gulp-util');           // Provides gulp utilities, including logging and beep
var notify     = require('gulp-notify');         // Provides notification to both the console and Growel
var merge      = require('utils-merge');         // Object merge tool
var rename     = require('gulp-rename');         // Rename sources
var source     = require('vinyl-source-stream'); // Vinyl stream support
var sourcemaps = require('gulp-sourcemaps');     // Provide external sourcemap files
var stringify  = require('stringify');           // Require text files like templates
var uglify     = require('gulp-uglify');         // Require text files like templates
var vueify     = require('vueify');              // Allow you to write vue files
var watchify   = require('watchify');            // Watchify for source changes

var mapError   = require('../error');

// On centralise la config, vous pouvez changer librement les valeurs selon vos besoins / envies
var config = {
  src       : 'src/js/main.js',  // Fichier principal à build
  outputDir : './www/assets/js', // Chemin ou va être généré le build
  outputFile: 'build.js',        // Nom du fichier build
};

// On créé une task Gulp pour build le js
gulp.task('browserify', function() {
  // On prépare les options pour Browserify, on rajoute aussi Watchify pour que le build se refasse automatiquement quand il y a une modification
  var args = merge(watchify.args, { debug: true });

  // On créé un objet qui contient tout le tralala pour build
  var bundler = watchify(browserify(config.src, args));
  // On ajoute des transformations
  bundler
    .transform(stringify,{ appliesTo: { includeExtensions: ['.html'] }, minify: true })
    .transform(babelify, { presets: ['es2015'] }) // Babel, pour l'ES6
    .transform(vueify)                            // Vueify, pour ecrire des fichiers .vue

  // La fonction qui sera appellée à chaque fois qu'une modification sera faite sur un fichier js
  // Vous pouvez ajouter pipe()
  // Libre à vous de virer ce qui ne vous sert pas
  function rebundle() {
    var bundleTimer = duration('Javascript bundle time');

    bundler
      .bundle()
      .on('error', mapError)                   // Map error reporting
      .pipe(source('main.js'))                 // Set source name
      .pipe(buffer())                          // Convert to gulp pipeline
      .pipe(rename(config.outputFile))         // Rename the output file
      .pipe(sourcemaps.init({loadMaps: true})) // Extract the inline sourcemaps
      .pipe(uglify())                          // Minify the build file
      .pipe(sourcemaps.write('./'))            // Set folder for sourcemaps to output to
      .pipe(gulp.dest(config.outputDir))       // Set the output folder
      .pipe(notify({
        onLast: true,
        message: 'Generated file: <%= file.relative %>',
      }))                                      // Output the file being created
      .pipe(bundleTimer)                       // Output time timing of the file creation
  }

  // On ajoute un eventlistener pour relancer le build à chaque modification
  bundler.on('update', function() {
    rebundle();
  });

  return rebundle();
});
