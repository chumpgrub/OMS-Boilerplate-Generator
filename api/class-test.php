<?php

class MyClass {
    const POST_TYPE_EVENT = 'event';
    const POST_TYPE_WEBINAR = 'webinar';
    const TAXONOMY_LOCATION = 'location';
    const TAXONOMY_VENUE = 'venue';

    public static function getConstants() {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }

    public static function getPostTypes() {
        $consts = self::getConstants();
        if (!empty($consts)) {
            $post_types = array_filter($consts, function($const) {
                return strpos($const,'POST_TYPE_') === 0;
            }, ARRAY_FILTER_USE_KEY);
            return array_values($post_types);
        }
    }
    public static function getTaxonomies() {
        $consts = self::getConstants();
        if (!empty($consts)) {
            $taxonomies = array_filter($consts, function($const) {
                return strpos($const,'TAXONOMY_') === 0;
            }, ARRAY_FILTER_USE_KEY);
            return array_values($taxonomies);
        }
    }
}
