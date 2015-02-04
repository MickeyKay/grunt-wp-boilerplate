/**
 * grunt-wp-boilerplate
 * https://github.com/fooplugins/grunt-wp-boilerplate
 *
 * Copyright (c) 2014 Brad Vincent, FooPlugins LLC
 * Licensed under the MIT License
 */

'use strict';

// Basic template description
exports.description = 'Create a WordPress plugin.';

// Template-specific notes to be displayed before question prompts.
exports.notes = '';

// Template-specific notes to be displayed after the question prompts.
exports.after = '';

// Any existing file or directory matching this wildcard will cause a warning.
exports.warnOn = '*';

// Set default slug to current directory name
var slug = process.cwd().substring(process.cwd().lastIndexOf('/')+1)

// Set default plugin title
var title = slug.replace(/-/gi,' ').replace(/\w\S*/gi, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});

// Set default function prefix
var prefix = '';
slug.split('-').forEach(function(element) {
	prefix += element.substring(0,1);
});

// The actual init template
exports.template = function( grunt, init, done ) {
	init.process( {}, [
		// Prompt for these values.
		init.prompt( 'title', title ),
		{
			name   : 'slug',
			message: 'Plugin slug (used for text domain)',
			default: slug
		},
		// {
		// 	name   : 'prefix',
		// 	message: 'PHP function prefix (alpha and underscore characters only)',
		// 	default: prefix
		// },
		{
			name   : 'version',
			message: 'Version',
			default: '1.0.0'
		},
		{
			name   : 'description',
			message: 'Description',
			default: 'The best WordPress extension ever made!'
		},
		{
			name   : 'wp_contributors',
			message: 'Contributors (wordpress.org usernames)',
			default: 'McGuive7'
		},
		{
			name   : 'tags',
			message: 'Tags',
			default: 'e.g. custom, post, type'
		},
		{
			name   : 'homepage',
			message: 'Homepage',
			default: 'http://wordpress.org/plugins/' + slug
		},
		
		// Check ~/.grunt-init/defaults.json for global/system defaults
		init.prompt( 'author_name' ),
		init.prompt( 'author_email' ),
		init.prompt( 'author_url' ),
		{
			name: 'css_type',
			message: 'CSS Preprocessor: Will you use "Sass", "LESS", or "none" for CSS with this project?',
			default: 'none'
		}
	], function( err, props ) {
		props.keywords = [];
		props.devDependencies = {
			'grunt':                  'latest',
			'grunt-contrib-concat':   'latest',
			'grunt-contrib-uglify':   'latest',
			'grunt-contrib-cssmin':   'latest',
			'grunt-contrib-jshint':   'latest',
			'grunt-contrib-nodeunit': 'latest',
			'grunt-contrib-watch':    'latest',
			'grunt-contrib-clean':    'latest',
			'grunt-contrib-copy':     'latest',
			'grunt-csscomb':          'latest',
			'grunt-regex-replace':    'latest',
			'grunt-contrib-compress': 'latest',
			'grunt-dev-update':       'latest',
			'grunt-wp-i18n':          'latest',
			'load-grunt-tasks':       'latest'
		};
		
		/**
		 * Sanitize names where we need to for PHP/JS
		 */
		
		// Generate safe name (e.g. My Plugin => My_Plugin)
		props.safe_name = props.title.replace(/[\W_]+/g, '_');

		// Generate underscored slug (e.g. my_plugin)
		props.underscored_slug = props.slug.replace(/[-]+/g, '_');
		
		// Development prefix (i.e. to prefix PHP function names, variables)
		// props.prefix = props.prefix.replace('/[^a-z_]/i', '').toLowerCase();
		
		// Development prefix in all caps (e.g. for constants)
		//props.prefix_caps = props.prefix.toUpperCase();

		// Files to copy and process
		var files = init.filesToCopy( props );

		switch( props.css_type.toLowerCase()[0] ) {
			case 'l':
				delete files[ 'assets/css/sass/' + props.slug + '.scss'];
				delete files[ 'assets/css/src/' + props.slug + '.css' ];
				
				props.devDependencies["grunt-contrib-less"] = "~0.5.0";
				props.css_type = 'less';
				break;
			case 'n':
			case undefined:
				delete files[ 'assets/css/less/' + props.slug + '.less'];
				delete files[ 'assets/css/sass/' + props.slug + '.scss'];
				
				props.css_type = 'none';
				break;
			// SASS is the default
			default:
				delete files[ 'assets/css/less/' + props.slug + '.less'];
				delete files[ 'assets/css/src/' + props.slug + '.css' ];
				
				props.devDependencies["grunt-contrib-sass"] = "~0.2.2";
				props.css_type = 'sass';
				break;
		}
		
		console.log( files );
		
		// Actually copy and process files
		init.copyAndProcess( files, props );

		/**
		 * Rename files - replacing 'plugin-path' with the actual plugin slug
		 *
		 * This is typically done via rename.json, however I wasn't
		 * able to find a good way to wildcard search and replace for
		 * 'plugin-name'.
		 */
		grunt.file.recurse(process.cwd(), function ( abspath, rootdir, subdir, filename ) { 
			
			// Rename any file with 'plugin-name' in the filename
			if ( filename.indexOf( 'plugin-name' ) > -1 ) {
				
				// Generate new file name
				var newAbspath = abspath.replace( 'plugin-name', props.slug);
				
				// Copy original template file into new named file
				grunt.file.copy(abspath, newAbspath);

				// Delete original template file
				grunt.file.delete(abspath);

			}

		});
		
		// Generate package.json file
		init.writePackageJSON( 'package.json', props );
		
		// Done!
		done();

	});
};
