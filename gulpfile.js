"use strict";

let gulp = require('gulp'),
  plugins = require('gulp-load-plugins')({
    pattern: ['*', '!stylelint'],
    replaceString: /^(gulp|postcss|imagemin)(-|\.)/,
    camelize: true
}),
buildFolder = 'build',
config = {
  path: [
    './**',
    '!{node_modules,node_modules/**}',
    '!{dist,dist/**}',
    '!{build,build/**}',
    '!*.js',
    '!*.json',
    '!{bxApiDocs,bxApiDocs/**}',
    '!*.lock',
    '!phpcs.xml',
    '!TODO.md',
    '!.editorconfig',
    '!{.git,.git/**}',
    '!{.history,.history/**}',
    '!.gitignore'
  ],
  encodeMask: '/**/*.{md,php,js}',
  build: buildFolder,
  last: buildFolder + '/.last_version',
  dist: buildFolder + '/dist',
  encoded: buildFolder + '/encoded',
  decoded: buildFolder + '/decoded'
};

gulp.task('init', function(done) {
  done();
});

gulp.task('clean', function() {
  return plugins.del(config.build + '/**', {
    force: true,
    dot: true
  });
});

gulp.task('copy', function() {
  return gulp.src(config.path, {
    base: './',
    dot: true
  })
    .pipe(gulp.dest(config.dist));
});

gulp.task('copyToEncode', function() {
  return gulp.src(config.dist + '/**/*', {
    dot: true
  })
    .pipe(gulp.dest(config.encoded));
});

gulp.task('encode', function() {
  return gulp.src(config.encoded + config.encodeMask)
    .pipe(plugins.iconv({
      decoding: 'utf8',
      encoding: 'win1251'
    }))
    .pipe(gulp.dest(function(file) {
      return file.base;
    }));
});

gulp.task('copyEncoded', function() {
  return gulp.src(config.encoded + '/**/*', {
    dot: true
  })
    .pipe(gulp.dest(config.decoded));
});

gulp.task('decode', function() {
  return gulp.src(config.decoded + config.encodeMask)
    .pipe(plugins.iconv({
      decoding: 'win1251',
      encoding: 'utf8'
    }))
    .pipe(gulp.dest(function(file) {
      return file.base;
    }));
});

gulp.task('compare', function() {
  return gulp.src(config.decoded + config.encodeMask)
    .pipe(plugins.diff(config.dist))
    .pipe(plugins.diff.reporter({
      fail: true
    }));
});

gulp.task('copyLast', function() {
  return gulp.src(config.encoded + '/**/*', {
    dot: true
  })
    .pipe(gulp.dest(config.last));
});

gulp.task('archive', function() {
  return gulp.src(config.last + '/**/*', {
    dot: true
  })
    .pipe(plugins.zip('.last_version.zip'))
    .pipe(gulp.dest(config.build));
});

gulp.task('default',
  gulp.series(
    'init',
    'clean',
    'copy',
    'copyToEncode',
    'encode',
    'copyEncoded',
    'decode',
    'compare',
    'copyLast',
    'archive'
  )
);
