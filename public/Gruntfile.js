module.exports = function(grunt) {

  var targetDir = grunt.config.get('targetDir');
  var moduleDir = "./module/Gastro24";
  
  grunt.config.merge({
    less: {
      gastro24: {
        options: {
          compress: true,
          optimization: 2
        },
        files: [
            {
              dest: targetDir + "/modules/Gastro24/Gastro24.css",
              src: moduleDir + "/less/Gastro24.less"
            },
            { dest: moduleDir + "/public/templates/default/job.css", src: moduleDir + "/public/templates/default/less/job.less"}, // destination file and source file
            { dest: moduleDir + "/public/templates/modern/job.css", src: moduleDir + "public/templates/modern/less/job.less"}, // destination file and source file
            { dest: moduleDir + "/public/templates/classic/job.css", src: moduleDir + "public/templates/classic/less/job.less"} // destination file and source file
          ]
        
      }
    }
  });

  grunt.registerTask('yawik:gastro24', ['less:gastro24']);
};

