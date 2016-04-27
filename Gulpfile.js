var gulp = require('gulp');
var autoprefixer = require('gulp-autoprefixer');
var less = require('gulp-less');

gulp.task('watch', function () {
    gulp.watch('assets/admin-lte-asset/build/**/*.less', ['less-admin-lte']);
    gulp.watch('assets/admin-asset/less/*.less', ['less-admin']);
});

gulp.task ('less-admin-lte', function() {
    return gulp.src(['assets/admin-lte-asset/build/less/AdminLTE.less'])
        .pipe(less())
        .pipe(autoprefixer())
        .pipe(gulp.dest('assets/admin-lte-asset/dist/css'));
});

gulp.task ('less-admin', function() {
    return gulp.src(['assets/admin-asset/less/admin.less'])
        .pipe(less())
        .pipe(autoprefixer())
        .pipe(gulp.dest('assets/admin-asset/css'));
});

gulp.task('build', [
    'less-admin-lte',
    'less-admin'
], function() {
});

