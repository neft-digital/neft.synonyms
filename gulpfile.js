"use strict";

let gulp = require('gulp');
let build = require('gulp-build-bitrix-modul')({
    name: 'neft.synonyms',
    tools: {
        'project.tools': ['Project', 'Tools']
    },
    encode: [
        // 'include.php',
        // 'project.tools/**/*.php',
        // '!project.tools/modules/install.php'
    ]
});

// Сборка текущей версии модуля
gulp.task('release', build.release);

// Сборка текущей версии модуля для маркетплейса
gulp.task('last_version', build.last_version);

// Сборка обновления модуля (разница между последней и предпоследней версией по тегам git)
gulp.task('build_update', build.update);

// Дефолтная задача. Собирает все по очереди
gulp.task('default', gulp.series('release', 'last_version', 'build_update'));

// достаточно указать 'last_version', так как команда вызывает код release и build_update
gulp.task('default', gulp.series('last_version'));
