<!DOCTYPE html>
<?php
    $bootstrap = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'bootstap';
?>
<html>
    <head>
        <title>{title}</title>
        <script type="text/javascript" src="<?php echo $bootstrap ?>/js/jquery.js"></script>
        <script type="text/javascript" src="<?php echo $bootstrap ?>/js/bootstrap.js"></script>
        <link rel="stylesheet" type="text/css" href="<?php echo $bootstrap ?>/css/bootstrap.css" />
        <script type="text/javascript">
        $(function(){
            alert('Oi');
        });
        </script>
    </head>
    <body>
        {content}
    </body>
</html>