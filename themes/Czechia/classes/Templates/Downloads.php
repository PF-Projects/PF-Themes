<?php
/*-------------------------------------------------------+
| PHPFusion Content Management System
| Copyright (C) PHP Fusion Inc
| https://phpfusion.com/
+--------------------------------------------------------+
| Filename: Downloads.php
| Author: RobiNN
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/
namespace CzechiaTheme\Templates;

use CzechiaTheme\Core;
use PHPFusion\Panels;

class Downloads extends Core {
    public static function renderDownloads($info) {
        $locale = fusion_get_locale();

        add_to_css('.item .card-footer {margin-top: 10px;}');

        echo '<h1 class="text-center">'.$locale['download_1000'].'</h1>';

        echo render_breadcrumbs();

        Panels::addPanel('menu_panel', self::menu($info), Panels::PANEL_RIGHT, iGUEST, 1);

        if ($info['get']['download_id'] && !empty($info['download_item'])) {
            self::displayDownloadItem($info);
        } else {
            self::displayDownloadIndex($info);
        }
    }

    private static function displayDownloadIndex($info) {
        $locale = fusion_get_locale();
        $dl_settings = get_settings('downloads');

        if (!empty($info['download_cat_description'])) {
            echo '<div class="display-block">'.$info['download_cat_description'].'</div>';
        }

        if (!empty($info['download_item'])) {
            echo '<div class="row equal-height m-b-10">';
                foreach ($info['download_item'] as $data) {
                    echo '<div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">';
                        echo '<div class="card item">';
                            $link = DOWNLOADS.'downloads.php?cat_id='.$data['download_cat_id'].'&download_id='.$data['download_id'];

                            echo '<h4 class="m-t-5 text-center"><a href="'.$link.'">'.trimlink($data['download_title'], 20).'</a></h4>';

                            if ($dl_settings['download_screenshot'] == 1) {
                                echo '<div class="preview display-inline-block image-wrap thumb text-center overflow-hide m-2">';
                                    if (!empty($data['download_thumb']) && file_exists($data['download_thumb'])) {
                                        echo '<img style="width: 140px;height: 140px;" class="img-responsive" src="'.$data['download_thumb'].'" alt="'.$data['download_title'].'"/>';
                                    } else {
                                        echo get_image('imagenotfound', $data['download_title'], 'width: 140px;height: 140px;');
                                    }
                                echo '</div>';
                            }

                            echo '<div class="card-body">';
                                echo '<div class="card-meta text-center">';
                                    echo '<a class="m-r-5" href="'.DOWNLOADS.'downloads.php?cat_id='.$data['download_cat_id'].'">'.$data['download_cat_name'].'</a>';
                                    echo '<br/><time>'.$data['download_post_time'].'</time>';
                                echo '</div>';

                                echo '<div class="publisher m-t-10 text-center">';
                                    echo self::setLocale('12').' ';
                                    echo !empty($data['user_id']) ? profile_link($data['user_id'], $data['user_name'], $data['user_status']) : $locale['user_na'];
                                echo '</div>';
                            echo '</div>';

                            echo '<div class="card-footer">';
                                echo '<a href="'.$link.'" class="btn btn-link btn-sm btn-block">';
                                    echo '<i class="fa fa-download fa-fw"></i> '.$locale['download_1007'];
                                echo '</a>';
                            echo '</div>';
                        echo '</div>';
                    echo '</div>';
                }
            echo '</div>';

            echo !empty($info['download_nav']) ? '<div class="text-center m-b-20">'.$info['download_nav'].'</div>' : '';
        } else {
            echo '<div class="card text-center">'.$locale['download_3000'].'</div>';
        }
    }

    private static function displayDownloadItem($info) {
        $locale = fusion_get_locale();
        $dl_settings = get_settings('downloads');
        self::setParam('content_container', FALSE);
        $data = $info['download_item'];

        echo '<div class="card">';
            if ($data['admin_link']) {
                $admin_actions = $data['admin_link'];
                echo '<div class="btn-group pull-right">';
                    echo '<a class="btn btn-default btn-sm" href="'.$admin_actions['edit'].'">';
                        echo '<i class="fa fa-pencil"></i> '.$locale['edit'];
                    echo '</a>';

                    echo '<a class="btn btn-danger btn-sm" href="'.$admin_actions['delete'].'">';
                        echo '<i class="fa fa-trash"></i> '.$locale['delete'];
                    echo '</a>';
                echo '</div>';
            }

            echo '<h3 class="m-t-0 m-b-0">'.$data['download_title'].'</h3>';
            echo $data['download_description_short'];
            echo '<hr/>';

            echo '<div class="row m-b-20">';
                if ($dl_settings['download_screenshot'] == 1) {
                    echo '<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">';
                        if ($data['download_image'] && file_exists(DOWNLOADS.'images/'.$data['download_image'])) {
                            echo thumbnail(DOWNLOADS.'images/'.$data['download_image'], '200px');
                        } else {
                            echo get_image('imagenotfound', $data['download_title'], 'width: 200px;height: 200px;');
                        }
                    echo '</div>';

                    $grid = 9;
                } else {
                    $grid = 12;
                }

                echo '<div class="col-xs-12 col-sm-'.$grid.' col-md-'.$grid.' col-lg-'.$grid.'">';
                    $profile = profile_link($data['user_id'], $data['user_name'], $data['user_status']);
                    echo '<strong>'.$locale['global_050'].'</strong>: '.$profile.'<br />';
                    echo '<strong>'.$locale['download_1017'].'</strong>: '.$data['download_homepage'].'<br/>';
                    echo '<strong>'.self::setLocale('08').': </strong> ';
                    $link = DOWNLOADS.'downloads.php?cat_id='.$data['download_cat_id'];
                    echo '<a href="'.$link.'">'.$data['download_cat_name'].'</a>';
                    echo '<br/>';
                    echo '<a href="'.$data['download_file_link'].'" class="btn btn-primary m-b-20"><i class="fa fa-download"></i> '.self::setLocale('09').($data['download_filesize'] ? ' ('.$data['download_filesize'].')' : '').'</a>';
                echo '</div>';
            echo '</div>';

            if ($data['download_description']) {
                echo '<div class="p-10 m-b-20" style="border: 1px solid #ddd;">';
                    echo '<strong>'.self::setLocale('10').'</strong><br/>';
                    echo $data['download_description'];
                echo '</div>';
            }

            if ($dl_settings['download_screenshot'] == 1 && $data['download_image'] && file_exists(DOWNLOADS.'images/'.$data['download_image'])) {
                echo '<div class="p-10 m-b-20" style="border: 1px solid #ddd;">';
                    echo '<strong>'.self::setLocale('11').'</strong><br/>';
                    $link = DOWNLOADS.'images/'.$data['download_image'];
                    echo '<img src="'.$link.'" alt="'.$data['download_title'].'" class="img-responsive"/>';
                echo '</div>';
            }

            echo '<div class="p-10" style="border: 1px solid #ddd;">';
                echo '<strong>'.$locale['download_1010'].'</strong>';
                echo '<div class="row">';
                    echo '<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">';
                        echo '<span class="strong text-smaller text-lighter">'.$locale['download_1011'].': </span>';
                        echo $data['download_version'];
                    echo '</div>';

                    echo '<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">';
                        echo '<span class="strong text-smaller text-lighter">'.$locale['download_1012'].': </span>';
                        echo $data['download_count'];
                    echo '</div>';

                    echo '<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">';
                        echo '<span class="strong text-smaller text-lighter">'.$locale['download_1021'].': </span>';
                        echo $data['download_post_time'];
                    echo '</div>';
                echo '</div>';
                echo '<hr/>';
                echo '<div class="row">';
                    echo '<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">';
                        echo '<span class="strong text-smaller text-lighter">'.$locale['download_1013'].': </span>';
                        echo $data['download_license'];
                    echo '</div>';

                    echo '<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">';
                        echo '<span class="strong text-smaller text-lighter">'.$locale['download_1014'].': </span>';
                        echo $data['download_os'];
                    echo '</div>';

                    echo '<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">';
                        echo '<span class="strong text-smaller text-lighter">'.$locale['download_1015'].': </span>';
                        echo $data['download_copyright'];
                    echo '</div>';
                echo '</div>';
            echo '</div>';

        echo '</div>';

        echo $data['download_allow_comments'] && fusion_get_settings('comments_enabled') == 1 ? '<div class="card">'.$data['download_show_comments'].'</div>' : '';
        $ratings = $data['download_allow_ratings'] && fusion_get_settings('ratings_enabled') == 1 ? '<div class="m-b-20 ratings-box">'.$data['download_show_ratings'].'</div>' : '';
        self::setParam('right_middle_content', $ratings);
    }

    private static function displayCatMenu($info, $cat_id = 0, $level = 0) {
        $html = '';

        if (!empty($info[$cat_id])) {
            foreach ($info[$cat_id] as $download_cat_id => $cdata) {
                $active = (!empty($_GET['cat_id']) && $_GET['cat_id'] == $download_cat_id) ? ' active' : '';
                $link = DOWNLOADS.'downloads.php?cat_id='.$download_cat_id;
                $html .= str_repeat('&nbsp;', $level);
                $html .= '<a href="'.$link.'" class="list-group-item p-5 p-l-15'.$active.'">'.$cdata['download_cat_name'].'</a>';

                if (!empty($info[$download_cat_id])) {
                    $html .= self::displayCatMenu($info, $download_cat_id, $level + 1);
                }
            }
        }

        return $html;
    }

    private static function menu($info) {
        $locale = fusion_get_locale();

        ob_start();

        echo '<ul class="list-style-none m-t-5 m-b-20">';
        echo '<li><a title="'.$locale['download_1001'].'" href="'.DOWNLOADS.'downloads.php"><span>'.$locale['download_1001'].'</span></a></li>';

        $filter_ = $info['download_filter'];

        foreach ($filter_ as $filter_key => $filter) {
            $active = isset($_GET['type']) && $_GET['type'] === $filter_key ? ' class="active strong"' : '';
            echo '<li'.$active.'><a href="'.$filter['link'].'">'.$filter['title'].'</a></li>';
        }
        echo '</ul>';

        openside('<i class="fa fa-list"></i> '.$locale['download_1003']);
        echo '<div class="list-group">';
            $download_cat_menu = self::displayCatMenu($info['download_categories']);
            echo !empty($download_cat_menu) ? $download_cat_menu : '<p>'.$locale['download_3001'].'</p>';
        echo '</div>';
        closeside();

        openside('<i class="fa fa-users"></i> '.$locale['download_1004']);
        echo '<ul class="list-style-none">';
            if (!empty($info['download_author'])) {
                foreach ($info['download_author'] as $author_info) {
                    echo '<li'.($author_info['active'] ? ' class="active strong"' : '').'>';
                        echo '<a href="'.$author_info['link'].'">'.$author_info['title'].'</a> ';
                        echo '<span class="badge m-l-10">'.$author_info['count'].'</span>';
                    echo '</li>';
                }
            } else {
                echo '<li>'.$locale['download_3002'].'</li>';
            }
        echo '</ul>';
        closeside();

        $html = ob_get_contents();
        ob_end_clean();

        return $html;
    }
}
