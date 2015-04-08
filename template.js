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
			default: 'MIGHTYminnow'
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
		// init.prompt( 'author_name' ),
		// init.prompt( 'author_email' ),
		// init.prompt( 'author_url' ),
		{
			name   : 'author_name',
			message: 'Author name',
			default: 'MIGHTYminnow Web Studio & School'
		},
		{
			name   : 'author_email',
			message: 'Author email',
			default: 'info@mightyminnow.com'
		},
		{
			name   : 'author_url',
			message: 'Author URL',
			default: 'http://mightyminnow.com/plugin-landing-page?utm_source=' + slug + '&utm_medium=plugin-repo&utm_campaign=WordPress%20Plugins'
		},
		{
			name   : 'git_repo',
			message: 'Github repo',
			default: 'https://github.com/MIGHTYminnow/' + slug
		},
		{
			name   : 'svn_repo',
			message: 'WordPress SVN repo',
			default: 'http://plugins.svn.wordpress.org/' + slug
		},
	], function( err, props ) {
		props.keywords = [];
		props.devDependencies = {
			'grunt':                  'latest',
			'load-grunt-tasks':       'latest',
			'grunt-dev-update':       'latest',
			'grunt-prompt':           'latest',
			'grunt-text-replace':     'latest',
			'grunt-wp-i18n':          'latest',
			'grunt-contrib-copy':     '^0.7.0',
		};

		/**
		 * Sanitize names where we need to for PHP/JS
		 */

		// Generate safe name (e.g. My Plugin => My_Plugin)
		props.safe_name = props.title.replace(/[\W_]+/g, '_');

		// Generate underscored slug (e.g. my_plugin)
		props.underscored_slug = props.slug.replace(/[-]+/g, '_');

		// Files to copy and process
		var files = init.filesToCopy( props );

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

		// Set up Github repo - doesn't work yet because exec runs asynchronously
		// exec( "git init" );
		// exec( "git add -A" );
		// exec( "git remote add origin " + props.git_repo );

		// Set up SVN repo
		exec( "svn co " + props.svn_repo + " svn" );

		// Generate package.json file
		init.writePackageJSON( 'package.json', props );

		// Done!
		done();

	});
};


// Set up command line functionality
var sys = require('sys');
var execProcess = require('child_process').exec;
function exec( command ) { execProcess( command ), puts }
function puts(error, stdout, stderr) { sys.puts(stdout) }
