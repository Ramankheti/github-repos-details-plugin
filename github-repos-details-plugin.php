<?php
/*
Plugin Name: GitHub Repository Info Plugin
Description: Display release date, stars, and forks for a GitHub repository.
*/

// Shortcode to display the number of stars
function display_github_stars($atts) {
    $atts = shortcode_atts(array(
        'repository' => '',
        'username' => '',
    ), $atts);

    $repository = $atts['repository'];
    $username = $atts['username'];

    if (empty($repository) || empty($username)) {
        return 'Please provide the GitHub repository and username in the shortcode.';
    }

    // Merge the common headers with the specific shortcode headers
    $stars_headers = $headers;

    $github_data = wp_remote_get("https://api.github.com/repos/$username/$repository");

    if (is_wp_error($github_data)) {
        return 'Error fetching data from GitHub API';
    }

    $github_data = json_decode(wp_remote_retrieve_body($github_data));

    if (!empty($github_data) && is_object($github_data)) {
        // Get the number of stars
        $stars = $github_data->stargazers_count;
        return "
        <button style='border: 0px solid black; border-radius: 4px; padding: 3px; width: 85px; height: 30px;'>
       <div style='display: inline-block; vertical-align: middle;     margin-left: -35px;'>
      <svg xmlns='http://www.w3.org/2000/svg' width='18' height='24' viewBox='0 0 24 24'>
      <path d='M12 2l2.1 6.3h6.9l-5.6 4.5 2.1 6.3-5.5-4.5-5.6 4.5 2.1-6.3-5.5-4.5h6.9z' fill='black'/>
      </svg>
     </div>
    <div style='display: inline-block; vertical-align: middle; padding-right: 8px;'>
    <span>$stars</span>
    </div>
     </button>";
    } 
}

add_shortcode('github_stars', 'display_github_stars');

// Shortcode to display the number of forks
function display_github_forks($atts) {
    $atts = shortcode_atts(array(
        'repository' => '',
        'username' => '',
    ), $atts);

    $repository = $atts['repository'];
    $username = $atts['username'];

    if (empty($repository) || empty($username)) {
        return 'Please provide the GitHub repository and username in the shortcode.';
    }

    $forks_headers = $headers;

    $github_data = wp_remote_get("https://api.github.com/repos/$username/$repository");

    if (is_wp_error($github_data)) {
        return 'Error fetching data from GitHub API';
    }

    $github_data = json_decode(wp_remote_retrieve_body($github_data));

    if (!empty($github_data) && is_object($github_data)) {

        $forks = $github_data->forks_count;

        return "
          <button style='border: 0px solid black; border-radius: 4px; padding: 3px; width: 85px; height: 30px; width:auto; >
         <div style='display: inline-block; vertical-align: middle;     margin-left: -35px;'>
         <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24'>
          <path d='M5 5.372v.878c0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75v-.878a2.25 2.25 0 1 1 1.5 0v.878a2.25 2.25 0 0 1-2.25 2.25h-1.5v2.128a2.251 2.251 0 1 1-1.5 0V8.5h-1.5A2.25 2.25 0 0 1 3.5 6.25v-.878a2.25 2.25 0 1 1 1.5 0ZM5 3.25a.75.75 0 1 0-1.5 0 .75.75 0 0 0 1.5 0Zm6.75.75a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm-3 8.75a.75.75 0 1 0-1.5 0 .75.75 0 0 0 1.5 0Z'>
        </svg>
     </div>
     <div style='display: inline-block; vertical-align: middle; padding-right: 8px;
        padding-bottom: 17px;'>
    <span>$forks</span>
    </div>
     </button>";
    } 
}

add_shortcode('github_forks', 'display_github_forks');

// Shortcode to display release date with authorization
function display_github_release_date($atts) {
    $atts = shortcode_atts(array(
        'repository' => '',
        'username' => '',
    ), $atts);

    $repository = $atts['repository'];
    $username = $atts['username'];

    if (empty($repository) || empty($username)) {
        return 'Please provide the GitHub repository and username in the shortcode.';
    }

    $release_headers = $headers;

    $github_release_data = wp_remote_get("https://api.github.com/repos/$username/$repository/releases");

    if (is_wp_error($github_release_data)) {
        return 'Error fetching release data from GitHub API';
    }
    $github_release_data = json_decode(wp_remote_retrieve_body($github_release_data));

    if (!empty($github_release_data) && is_array($github_release_data)) {
        
        $latest_release_date = date('F j, Y', strtotime($github_release_data[0]->published_at));

        return "<p> $latest_release_date</p>";
    }
}

add_shortcode('github_release_date', 'display_github_release_date');
