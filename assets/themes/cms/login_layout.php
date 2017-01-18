<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?php echo $this->config->item('project_name'); ?></title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo base_url('assets/themes/cms/images/favicon.ico')?>">
    <?php 
        loadCss(array('bootstrap.min.css', 
            'font-awesome.min.css',
            'AdminLTE.min.css'    
        )); 
    ?>

    <?php loadPlugin('jQuery/jQuery-2.1.4.min.js', 'js'); ?>
    <?php loadJS(array('bootstrap.min.js', 'jquery.validate')); ?>

    <style type="text/css">
        .form-control-feedback {
            top: 10px;
        }

        .login-box-body{
            padding-bottom: 10px;
            margin-bottom: 10px;
        }
        .form-group span.error{
            color: #CC1111;
        }
        .login-logo{
            margin-bottom: 1px;
        }
    </style>
    <base href="<?php echo base_url()?>" />
</head>
<body class="login-page">
    <div class="login-box">
        <div class="login-logo">
            <h2 style="color:rgb(208, 35, 44);"><strong>CMS</strong> <span style="color: rgb(112, 128, 143);">Crediantial</span></h2>
        </div><!-- /.login-logo -->

        <div class="login-box-body">
            <?php echo get_login_validation_errors();?>
            <?php 
               if($this->session->userdata('msg'))
               {
                    echo  '<div class="alert alert-success">';
                    echo '<p>'.$this->session->userdata('msg').'</p>';
                    echo '</div>';
                    $this->session->unset_userdata('msg');
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

            <?php $this->load->theme($maincontent); ?>

            <br>
        </div><!-- /.login-box-body -->
    </div><!-- /.login-box -->

    <script>
    $(function(){
        $('.sm-login').validate({
            errorElement:'span'
        });
        
    });
    </script>

</body>
</html>