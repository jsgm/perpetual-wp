<?php
if(!defined("ABSPATH")){
	http_response_code(403);
	die;
}

// Users will no longer see the option to filter media files by upload month.
// TO-DO: Only display this option when there is at least one year available or a substantial number of files.
add_filter( 'media_library_months_with_files', function() { return array(); });

// Disables the ability to create audio playlists.
add_filter( 'media_library_show_audio_playlist', function() { return false; });

// Disables the ability to create video playlists.
add_filter( 'media_library_show_video_playlist', function() { return false; });
