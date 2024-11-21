<?php
if(!defined("ABSPATH")){
    http_response_code(403);
    exit;
}

if(!class_exists("PerpetualWP_Updater")){
    class PerpetualWP_Updater{
        const RELEASE_URL = "https://api.github.com/repos/jsgm/perpetual-wp/releases/latest";
        const VERSION_PATTERN = '~^\d+(\.\d+){1,2}$~';
        const META_LINE_PATTERN = '~^\s*(?<name>.+?):\s+(?<value>.+?)\s*$~';

        public static function setup() {
            new static();
        }

        private function __construct() {
            add_filter('pre_set_site_transient_update_plugins', [$this, 'check']);
            add_filter('install_plugins_pre_plugin-information', [$this, 'information'], 0, 0);
        }

        public function check($result) {
            try {
                return $this->doCheck($result);
            } catch (RuntimeException $e) {
                error_log(sprintf(
                    'Caught %s while checking for update: (%d) %s',
                    get_class($e),
                    $e->getCode(),
                    $e->getMessage()
                ));
                return $result;
            }
        }

        private function doCheck($result) {
            try {
                $release = $this->getLatestRelease();

                if(!$release || $release == null) return $result;

                // No assets available.
                if(empty($release["assets"])) return $result;

                 // Don't update prereleases.
                if(isset($release["prerelease"]) && $release["prerelease"] === true) return $result;

                // No tag name or invalid content.
                if(!isset($release["tag_name"]) || !preg_match(self::VERSION_PATTERN, $release['tag_name'])){
                    throw new RuntimeException(
                        "Tag on latest release does not look like a version number: ".$release["tag_name"]
                    );
                }

                if (!version_compare(PW_VERSION, $release['tag_name'], '<')) return $result;

                $filename = "perpetual-wp-".$release["tag_name"].".zip";
                
                $result->response[$this->getPluginFilename()] = (object) [
                    'slug' => 'perpetual-wp',
                    'version' => PW_VERSION,
                    'new_version' => $release['tag_name'],
                    'last_updated' => date('Y-m-d', strtotime($release['published_at'])),
                    'package' => $this->getAssetUrl($release, $filename),
                    'homepage' => PW_HOMEPAGE,
                    'icons' => [
                        'default' => 'https://avatars.githubusercontent.com/u/188994398?s=200&v=4'
                    ]
                ];
            }catch(Exception $error){
                echo $error->getMessage();
                exit;
            }

            return $result;
        }

        private function getLatestRelease() {
            $response = wp_remote_get(self::RELEASE_URL);

            if ($response instanceof WP_Error) {
                throw new RuntimeException(sprintf(
                    'Could not download latest release info from GitHub: (%s) %s',
                    $response->get_error_code(),
                    $response->get_error_message()
                ));
            }

            if ($response['response']['code'] < 200 || $response['response']['code'] > 299) {
                throw new RuntimeException(sprintf(
                    'Got HTTP %d from GitHub releases API',
                    $response['response']['code']
                ));
            }

            $data = json_decode($response['body'], true);

            if (!is_array($data)) {
                throw new RuntimeException('Did not receive expected JSON object from GitHub releases API');
            }
            return $data;
        }

        private function getPluginFilename() {
            return plugin_basename(PW_PLUGIN_FILE);
        }

        private function getAssetUrl(array $data, $filename) {
            foreach ($data['assets'] as $asset) {
                if ($asset['name'] === $filename) {
                    return $asset['browser_download_url'];
                }
            }
            throw new RuntimeException("No asset named {$filename} on GitHub release");
        }

        private function getMeta(array $data, $name) {
            foreach (explode("\n", $data['body']) as $line) {
                if (preg_match(self::META_LINE_PATTERN, $line, $match)
                    && !strcasecmp($match['name'], $name)
                    && $match['value'] != ''
                ) {
                    return $match['value'];
                }
            }
            return null;
        }

        public function information() {
            if(empty($_REQUEST['plugin']) || $_REQUEST['plugin'] !== 'perpetual-wp') {
                return;
            } ?>
            <!doctype html>
            <style>
            html, body {
                height: 100%;
                margin: 0;
                padding: 0;
            }
            body {
                font-family: sans-serif;
                font-size: 18px;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            </style>
            <p>Read more about x on <a href="https://github.com/x/x/#readme" target="_blank" rel="noopener">GitHub</a>.</p>
            <?php

            exit;
        }
    }
}

PerpetualWP_Updater::setup();
