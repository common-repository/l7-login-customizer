module.exports = function(grunt) {

  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),
    concat: {
      options: {
        separator: ';'
      },
      dist: {
        src: [ 'assets/js/src/*.js' ],
        dest: 'assets/js/<%= pkg.name %>.js'
      }
    },
    uglify: {
      options: {
        banner: '/*! <%= pkg.name %> <%= grunt.template.today("dd-mm-yyyy") %> */\n'
      },
      dist: {
        files: {
          'assets/js/<%= pkg.name %>.min.js': ['<%= concat.dist.dest %>']
        }
      }
    },
    sass: {
      dist: {
        files: {
          'assets/css/<%= pkg.name %>.css' : 'assets/css/scss/<%= pkg.name %>.scss'
        }
      }
    },
    watch: {
      css: {
        files: 'assets/css/scss/*.scss',
        tasks: ['sass']
      },
      livereload: {
        files: 'assets/css/*.css',
        options: { 
          livereload: true 
        }
      }
    }
  });

  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-contrib-watch');
  grunt.loadNpmTasks('grunt-contrib-concat');
  grunt.loadNpmTasks('grunt-contrib-sass');

  grunt.registerTask('default', ['sass', 'concat', 'uglify']);
  grunt.registerTask('dev',['sass']);

};