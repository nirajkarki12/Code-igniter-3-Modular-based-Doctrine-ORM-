<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?php echo (isset($pageTitle) && $pageTitle) ? $pageTitle." :: " : ''; ?> <?php echo $this->config->item('project_name'); ?></title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <?php sm_get_header_assets(); ?>
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo base_url('assets/themes/cms/images/favicon.ico')?>">
    <base href="<?php echo base_url()?>" />
</head>
<body class="skin-purple sidebar-mini wysihtml5-supported">
    <div class="wrapper">
        <?php sm_get_menu(); ?>
        <div class="content-wrapper">
            <section class="content-header" style="margin-bottom: 10px;">
                <h1><?php echo (isset($pageTitle) && $pageTitle) ? $pageTitle : '' ?></h1>

                <?php echo $this->breadcrumb->output();?>
            </section>
            <?php 
                if(isset($critical_alerts)){
                    foreach($critical_alerts as $type => $msg){
                        echo '<div class="alert alert-danger alert-dismissable">';
                        echo '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>';
                        echo '<span>'.$msg.'</span>';
                        echo '</div>';
                    }
                }
                
                if(isset($feedback)){
                    foreach($feedback as $type => $messages){
                        
                        if($type == 'error')
                            $alertClass = "alert alert-danger";
                        elseif($type =='success')
                            $alertClass = "alert alert-success";
                        else
                            $alertClass = "alert alert-info";

                        echo '<div class="'.$alertClass.'">';
                        
                        foreach($messages as $msg)
                            echo '<p>'.$msg.'</p>';
                        
                        echo '</div>';
                    }
                }
                
            ?>
            <?php
                if(isset($exceptionalError))
                {
                    echo '<div class="alert alert-danger alert-dismissable">';
                    echo '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>';
                    echo $exceptionalError;
                    echo '</div>';
                }

                if($validation_errors = validation_errors('<p>','</p>'))
                {
                    echo '<div class="alert alert-danger alert-dismissable">';
                    echo '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>';
                    echo $validation_errors;
                    echo '</div>';
                }else echo ""; 
            ?>
            