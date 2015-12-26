<?php
//require_once('/var/www/html/google-api-php-client/vendor/autoload.php');
//require_once('/var/www/html/config_youtube.php');
$dir = dirname(__FILE__);
require_once("$dir/google-api-php-client/vendor/autoload.php");

require_once("$dir/config_youtube.php");

// This code will execute if the user entered a search query in the form
// and submitted the form. Otherwise, the page displays the form above.

class searchMusic
{
    public function search($name)
    {
        // Call set_include_path() as needed to point to your client library.

    /*
     * Set $DEVELOPER_KEY to the "API key" value from the "Access" tab of the
     * Google Developers Console <https://console.developers.google.com/>
     * Please ensure that you have enabled the YouTube Data API for your project.
    */

    $DEVELOPER_KEY = DEVELOPER_KEY;

        $client = new Google_Client();
        $client->setDeveloperKey($DEVELOPER_KEY);

    // Define an object that will be used to make all API requests.
    $youtube = new Google_Service_YouTube($client);

        try {
            // Call the search.list method to retrieve results matching the specified
      // query term.

      $searchResponse = $youtube->search->listSearch('id,snippet', array(
                                     'q' => $name,
                                     'type'=>'video',
                                     'videoCategoryId' => 10,
                                     'maxResults' => 1,
                                     ));

            $videos = '';
            $channels = '';
            $playlists = '';

      // Add each result to the appropriate list, and then display the lists of
      // matching videos, channels, and playlists.

      foreach ($searchResponse['items'] as $searchResult) {
          switch ($searchResult['id']['kind']) {
        case 'youtube#video':
          $videos .= sprintf('https://www.youtube.com/watch?v=%s %s',
                 $searchResult['id']['videoId'], $searchResult['snippet']['title']);
          break;
          }
      }
        } catch (Google_Service_Exception $e) {
            echo sprintf('<p>A service error occurred: <code>%s</code></p>',
           htmlspecialchars($e->getMessage()));
        } catch (Google_Exception $e) {
            echo sprintf('<p>An client error occurred: <code>%s</code></p>',
           htmlspecialchars($e->getMessage()));
        }

        return $videos;
    }
}
