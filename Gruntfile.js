module.exports = function(grunt) {
  require('jit-grunt')(grunt);

  grunt.initConfig({
    less: {
      development: {
        options: {
          compress: true,
          optimization: 2
        },
        files: {
          "public/Gastro24.css": "less/Gastro24.less",
          "public/templates/default/job.css": "public/templates/default/less/job.less", // destination file and source file
          "public/templates/modern/job.css": "public/templates/modern/less/job.less", // destination file and source file
          "public/templates/classic/job.css": "public/templates/classic/less/job.less" // destination file and source file
        }
      }
    },
    watch: {
      styles: {
        files: ['less/**/*.less'], // which files to watch
        tasks: ['less'],
        options: {
          nospawn: true
        }
      }
    }
  });

  grunt.registerTask('default', ['less', 'watch']);
};
