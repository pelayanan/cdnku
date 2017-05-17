<?php

include 'include.php';

header('Content-type: application/xml');
$raw_feed_cache = "raw_feed_cache";
$class->delete_cache($sqlite_cache, $raw_feed_cache);
$this_cache = $class->get_cache($sqlite_cache, $raw_feed_cache);
if ($this_cache) {
    print $this_cache;
} else {

    include 'load_latest_post.php';
// set more namespaces if you need them
    $xmlns = 'xmlns:content="http://purl.org/rss/1.0/modules/content/"
    xmlns:wfw="http://wellformedweb.org/CommentAPI/"
    xmlns:dc="http://purl.org/dc/elements/1.1/"
    xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"';

// configure appropriately - pontikis.net is used as an example
    $a_channel = array(
        "title" => "Wacana.ga",
        "link" => "http://www.wacana.ga",
        "description" => "Wacana Express the guardian content",
        "language" => "id",
    );
    $site_url = 'http://www.wacana.ga'; // configure appropriately
    $site_name = 'Wacana Express the guardian content'; // configure appropriately

    $rss = new rss_feed($class, $xmlns, $a_channel, $site_url, $site_name, $latest_post);
    $contents = $rss->create_feed();
    $class->store_cache($sqlite_cache, $raw_feed_cache, $contents, rand(50, 120));
    print $contents;
}

/**
 * rss_feed (simple rss 2.0 feed creator php class)
 *
 * @author     Christos Pontikis http://pontikis.net
 * @copyright  Christos Pontikis
 * @license    MIT http://opensource.org/licenses/MIT
 * @version    0.1.0 (28 July 2013)
 *
 */
class rss_feed {

    /**
     * Constructor
     *
     * @param array $a_db database settings
     * @param string $xmlns XML namespace
     * @param array $a_channel channel properties
     * @param string $site_url the URL of your site
     * @param string $site_name the name of your site
     * @param bool $full_feed flag for full feed (all topic content)
     */
    public function __construct($class, $xmlns, $a_channel, $site_url, $site_name, $latest_post) {
// initialize
        $this->class = $class;
        $this->xmlns = ($xmlns ? ' ' . $xmlns : '');
        $this->channel_properties = $a_channel;
        $this->site_url = $site_url;
        $this->site_name = $site_name;
        $this->full_feed = $full_feed;
        $this->latest_post = $latest_post;
    }

    /**
     * Generate RSS 2.0 feed
     *
     * @return string RSS 2.0 xml
     */
    public function create_feed() {
        $xml = '<?xml version="1.0" encoding="utf-8"?>' . "\n";
        $xml .= '<rss version="2.0"' . $this->xmlns . '>' . "\n";
        // channel required properties
        $xml .= '<channel>' . "\n";
        $xml .= '<title>' . $this->channel_properties["title"] . '</title>' . "\n";
        $xml .= '<link>' . $this->channel_properties["link"] . '</link>' . "\n";
        $xml .= '<description>' . $this->channel_properties["description"] . '</description>' . "\n";
        // channel optional properties
        if (array_key_exists("language", $this->channel_properties)) {
            $xml .= '<language>' . $this->channel_properties["language"] . '</language>' . "\n";
        }
        if (array_key_exists("image_title", $this->channel_properties)) {
            $xml .= '<image>' . "\n";
            $xml .= '<title>' . $this->channel_properties["image_title"] . '</title>' . "\n";
            $xml .= '<link>' . $this->channel_properties["image_link"] . '</link>' . "\n";
            $xml .= '<url>' . $this->channel_properties["image_url"] . '</url>' . "\n";
            $xml .= '</image>' . "\n";
        }
        // get RSS channel items
        foreach ($this->latest_post as $rss_item) {
            $xml .= '<item>' . "\n";
            $xml .= '<title>' . $rss_item["array"]['title'] . '</title>' . "\n";
            $xml .= '<link>' . $this->class->create_link($rss_item) . '</link>' . "\n";
            $xml .= '<description>' . substr(strip_tags($rss_item["array"]["content"]), 0, 200) . '</description>' . "\n";
            $xml .= '<pubDate>' . date("D, d M Y H:i:s O", $rss_item['array']["waktu"]) . '</pubDate>' . "\n";
            $xml .= '<category>' . $rss_item['array']["cat"]["0"] . '</category>' . "\n";
            // $xml .= '<source>' . $rss_item['source'] . '</source>' . "\n";
            /**
              if ($this->full_feed) {
              $xml .= '<content:encoded>' . $rss_item['content'] . '</content:encoded>' . "\n";
              }
             * */
            $xml .= '</item>' . "\n";
        }
        $xml .= '</channel>';
        $xml .= '</rss>';
        return $xml;
    }

}
