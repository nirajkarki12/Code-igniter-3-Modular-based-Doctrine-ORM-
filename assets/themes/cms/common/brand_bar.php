<header class="main-header">
    <!-- Logo -->
    <a href="<?php echo site_url()?>" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><b>A</b>LT</span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><b>Admin</b>LTE</span>
    </a>

    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
              <!-- User Account: style can be found in dropdown.less -->
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <span class="hidden-xs"><i class="fa fa-fw fa-user fa-lg"></i> Welcome, <?php echo Current_User::user()->getUsername();?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <p><?php echo  Current_User::user()->getName();?></p>
                            <p><?= date("D, M-j, Y")?> </p>
                            <p><span id="cur_time"></span></p>
                        </li>
                        <li class="user-body">
                            <div class="col-xs-4 text-center">
                                 <a href="<?php echo site_url('user/profile')?>"><i class="fa fa-fw fa-user"></i> Profile</a>
                            </div>
                            <div class="col-xs-8 text-center">
                                 <a href="<?php echo site_url('user/changepwd')?>"><i class="fa fa-fw fa-key"></i><br> Change Password</a>
                            </div>
                            
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <!-- <div class="pull-left">
                                <a href="<?php echo site_url('user/profile')?>" class="btn btn-default btn-flat">Profile</a>
                            </div> -->
                            <div class="pull-right">
                                <a href="<?php echo site_url('auth/logout')?>" class="btn btn-default btn-flat">Sign out</a>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>
<script>
    $(document).ready(function(){
        new showLocalTime("cur_time", "server-php", 0, "short");
    });
</script>
