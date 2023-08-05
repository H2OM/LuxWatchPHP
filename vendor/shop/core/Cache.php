<?php
    namespace shop;


    class Cache {
        use Tsingleton;
        
        public function set($key, $data, $seconds = 3600) {
            if($seconds) {
                $content['data'] = $data;
                $content['end_time'] = time() + $seconds;
                if(file_put_contents(CACHE . '/' . md5($key) . '.json', json_encode($content))) {
                    return true;
                }
                return false;
            }
        }
        public function get($key) {
            $file = CACHE . '/' . md5($key) . '.json';
            if(file_exists($file)) {
                $content = json_decode(file_get_contents($file), true);
                if(time() <= $content['end_time']) {
                    return $content;
                }
                unlink($file);
            }
            return false;
        }
        public function delete($key) {
            $file = CACHE . '/' . md5($key) . '.json';
            if(file_exists($file)) {
                unlink($file);
            }
        }
    }