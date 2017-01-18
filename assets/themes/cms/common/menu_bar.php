<aside class="main-sidebar">
    <section class="sidebar" style="height: auto;">
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu">
            <?php 
            if(\Current_User::isSuperUser()):?>
            <li>
                <a href="<?php echo site_url('config');?>">
                    <i class="fa fa-gears"></i> <span>Configuration</span>
                </a>
            </li>
            <?php endif;?>
            <li class="treeview">
                <a href="<?php echo site_url()?>">
                   <i class="fa fa-university"></i> <span>Dashboard</span>
                </a>
            </li>
            <?php echo \MainMenu::render(true);?>
        </ul>
    </section>
</aside>