<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>

    <?php 
        loadCss(array('bootstrap.min.css', 
            'font-awesome.min.css',
            'AdminLTE.min.css'    
        )); 
    ?>

    <?php loadPlugin('jQuery/jQuery-2.1.4.min.js', 'js'); ?>
    <?php loadJS(array('bootstrap.min.js', 'jquery.validate')); ?>

</head>
<body>
    
    <?php $this->load->theme($maincontent); ?>

</body>
</html>