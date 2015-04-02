/*jslint node: true */
"use strict";

module.exports = function( grunt ) {

	// Grab package as variable for later use/
	var pkg = grunt.file.readJSON( 'package.json' );

	// Load all tasks.
	require('load-grunt-tasks')(grunt, {scope: 'devDependencies'});

	// Project configuration
	grunt.initConfig( {
		pkg: pkg,
		devUpdate: {
	        main: {
	            options: {
	                updateType: 'prompt',
	                packages: {
	                    devDependencies: true
	                },
	            }
	        }
	    },
		jshint: {
			all: [
				'Gruntfile.js'
			],
			options: {
				curly:   true,
				eqeqeq:  true,
				immed:   true,
				latedef: true,
				newcap:  true,
				noarg:   true,
				sub:     true,
				undef:   true,
				boss:    true,
				eqnull:  true,
				globals: {
					exports: true,
					module:  false
				}
			}
		},
		// https://www.npmjs.org/package/grunt-wp-i18n
	    makepot: {
	        target: {
	            options: {
	                domainPath: '/languages/',    // Where to save the POT file.
	                potFilename: '{%= slug %}.pot',   // Name of the POT file.
	                type: 'wp-plugin'  // Type of project (wp-plugin or wp-theme).
	            }
	        }
	    },
		clean: {
			main: ['release/<%= pkg.version %>']
		},
		copy: {
			// Copy the plugin to a versioned release directory
			main: {
				src:  [
					'**',
					'!node_modules/**',
					'!release/**',
					'!.git/**',
					'!.sass-cache/**',
					'!css/src/**',
					'!js/src/**',
					'!img/src/**',
					'!assets/**',
					'!design/**',
					'!Gruntfile.js',
					'!package.json',
					'!.gitignore',
					'!.gitmodules',
					'!composer*',
					'!vendor/autoload.php',
					'!vendor/composer/**'
				],
				dest: 'release/<%= pkg.version %>/{%= slug %}/',
				options: {
					process: function (content, srcpath) {
						// Update the version number in various files
						return content.replace( /%VERSION%/g, pkg.version );
					},
				},
			}
		},
		compress: {
			main: {
				options: {
					mode: 'zip',
					archive: './release/<%= pkg.version %>/{%= slug %}.zip'
				},
				expand: true,
				cwd: 'release/<%= pkg.version %>/{%= slug %}/',
				src: ['**/*'],
				dest: '{%= slug %}/'
			}
		}
	} );

	grunt.registerTask( 'default', [
		'jshint',
		'makepot'
	] );

	grunt.registerTask( 'build', [
		'devUpdate',
		'default',
		'makepot',
		'clean',
		'copy',
		'compress'
	] );

	grunt.util.linefeed = '\n';
};