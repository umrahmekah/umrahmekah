<?php
$codes      = $codes;
$template   = base_path() . '/resources/views/mmb/module/template/native/';
$controller = file_get_contents($template . 'controller.tpl');
$grid       = file_get_contents($template . 'grid.tpl');
$view       = file_get_contents($template . 'view.tpl');
$form       = file_get_contents($template . 'form.tpl');
$model      = file_get_contents($template . 'model.tpl');
$front      = file_get_contents($template . 'frontend.tpl');
$frontview  = file_get_contents($template . 'frontendview.tpl');
$frontform  = file_get_contents($template . 'frontform.tpl');

if (isset($config['subgrid']) && count($config['subgrid']) >= 1) {
    $view = file_get_contents($template . 'view_detail.tpl');
} else {
    $view = file_get_contents($template . 'view.tpl');
}

$build_controller = \SiteHelpers::blend($controller, $codes);
$build_view       = \SiteHelpers::blend($view, $codes);
$build_form       = \SiteHelpers::blend($form, $codes);
$build_grid       = \SiteHelpers::blend($grid, $codes);
$build_model      = \SiteHelpers::blend($model, $codes);
$build_front      = \SiteHelpers::blend($front, $codes);
$build_frontview  = \SiteHelpers::blend($frontview, $codes);
$build_frontform  = \SiteHelpers::blend($frontform, $codes);

if (! is_null($request->input('rebuild'))) {
    // rebuild spesific files
    if ('y' == $request->input('c')) {
        file_put_contents($dirC . "{$ctr}Controller.php", $build_controller);
    }
    if ('y' == $request->input('m')) {
        file_put_contents($dirM . "{$ctr}.php", $build_model);
    }

    if ('y' == $request->input('g')) {
        file_put_contents($dir . '/index.blade.php', $build_grid);
    }
    if ('' != $row->module_db_key) {
        if ('y' == $request->input('f')) {
            file_put_contents($dir . '/form.blade.php', $build_form);
        }

        if ('y' == $request->input('v')) {
            file_put_contents($dir . '/view.blade.php', $build_view);
        }

        // Frontend Grid
        if ('y' == $request->input('fg')) {
            file_put_contents($dir . '/public/index.blade.php', $build_front);
        }
        // Frontend View
        if ('y' == $request->input('fv')) {
            file_put_contents($dir . '/public/view.blade.php', $build_frontview);
        }
        // Frontend Form
        if ('y' == $request->input('ff')) {
            file_put_contents($dir . '/public/form.blade.php', $build_frontform);
        }
    }
} else {
    file_put_contents($dirC . "{$ctr}Controller.php", $build_controller);
    file_put_contents($dirM . "{$ctr}.php", $build_model);
    file_put_contents($dir . '/index.blade.php', $build_grid);
    file_put_contents($dir . '/form.blade.php', $build_form);
    file_put_contents($dir . '/view.blade.php', $build_view);
    file_put_contents($dir . '/public/index.blade.php', $build_front);
    file_put_contents($dir . '/public/view.blade.php', $build_frontview);
    file_put_contents($dir . '/public/form.blade.php', $build_frontform);
}

?>             