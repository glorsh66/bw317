<html>
<head>
        <title><?php echo $title;?></title>
</head>
<body>
        <h1><?php echo $heading;?></h1>

        <h3>My Todo List</h3>

        <ul>
        <?php foreach ($files as $item):?>

                <li><a href="<?php echo site_url($item);?>"><?php echo site_url($item);?></a></li>

        <?php endforeach;?>
        </ul>
	<p class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds. <?php echo  (ENVIRONMENT === 'development') ?  'CodeIgniter Version <strong>' . CI_VERSION . '</strong>' : '' ?></p>
</body>
</html>
