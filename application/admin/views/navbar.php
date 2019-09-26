
<?php
$super = $_SESSION['adm_super'] == 1 ? true : false;
$ncik_name = $_SESSION['adm_nick_name'];
$avatar = $_SESSION['adm_avatar'] ? $_SESSION['adm_avatar'] : ADMIN_THEME . '/img/profile_small.jpg';
?>
<nav class="navbar-default navbar-static-side" role="navigation">
	<div class="sidebar-collapse">
		<ul class="nav metismenu" id="side-menu">
			<li class="nav-header">
				<div class="dropdown profile-element">
					<img alt="image" width="48px" height="48px" class="rounded-circle" src="<?=$avatar?>"/>
					<a data-toggle="dropdown" class="dropdown-toggle" href="#">
						<span class="block m-t-xs font-bold"><?=$ncik_name?></span>
<!--						<span class="text-muted text-xs block">Art Director <b class="caret"></b></span>-->
					</a>
<!--					<ul class="dropdown-menu animated fadeInRight m-t-xs">-->
<!--						<li><a class="dropdown-item" href="profile.html">Profile</a></li>-->
<!--						<li><a class="dropdown-item" href="contacts.html">Contacts</a></li>-->
<!--						<li><a class="dropdown-item" href="mailbox.html">Mailbox</a></li>-->
<!--						<li class="dropdown-divider"></li>-->
<!--						<li><a class="dropdown-item" href="/home/logout">Logout</a></li>-->
<!--					</ul>-->
				</div>
				<div class="logo-element">
					Blog
				</div>
			</li>
			<li <?php if ($title == '首页') echo 'class="active"' ?> >
				<a href="/home"><i class="fa fa-home"></i> <span class="nav-label">首页</span></a>
			</li>
			<li <?php if ($title == '博客管理') echo 'class="active"' ?> >
				<a href="/blog/index/blog_list"><i class="fa fa-bold"></i> <span class="nav-label">博客管理</span></a>
			</li>
			<li <?php if(in_array($title,['系统设置','管理账号','添加账号','编辑账号'])) echo 'class="active"' ?> >
				<a href="/setting/index"><i class="fa fa-th-large"></i> <span class="nav-label">系统设置</span></a>
				<ul class="nav nav-second-level collapse">
					<?php if($super) {?>
						<li <?php if(in_array($title,['管理账号','添加账号','编辑账号'])) echo 'class="active"' ?>><a href="/setting/index">管理账号</a></li>
					<?php }?>
					<li <?php if(in_array($title,['编辑账号'])) echo 'class="active"' ?>><a href="/user/index/edit">编辑账号</a></li>
				</ul>
			</li>
			<li>
				<a href="mailbox.html"><i class="fa fa-envelope"></i> <span class="nav-label">Mailbox </span></a>
				<ul class="nav nav-second-level collapse">
					<li><a href="mailbox.html">Inbox</a></li>
					<li><a href="mail_detail.html">Email view</a></li>
					<li><a href="mail_compose.html">Compose email</a></li>
					<li><a href="email_template.html">Email templates</a></li>
				</ul>
			</li>
		</ul>
	</div>
</nav>

<div id="page-wrapper" class="gray-bg">
	<div class="row border-bottom">
		<nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
			<div class="navbar-header">
				<a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
				<form role="search" class="navbar-form-custom" action="search_results.html">
<!--					<div class="form-group">-->
<!--						<input type="text" placeholder="Search for something..." class="form-control" name="top-search" id="top-search">-->
<!--					</div>-->
				</form>
			</div>
			<ul class="nav navbar-top-links navbar-right">
<!--				<li>-->
<!--					<span class="m-r-sm text-muted welcome-message">Welcome to BLOG.</span>-->
<!--				</li>-->
<!--				<li class="dropdown">-->
<!--					<a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">-->
<!--						<i class="fa fa-envelope"></i>  <span class="label label-warning">16</span>-->
<!--					</a>-->
<!--					<ul class="dropdown-menu dropdown-messages">-->
<!--						<li>-->
<!--							<div class="dropdown-messages-box">-->
<!--								<a class="dropdown-item float-left" href="profile.html">-->
<!--									<img alt="image" class="rounded-circle" src="--><?//=ADMIN_THEME?><!--/img/a7.jpg">-->
<!--								</a>-->
<!--								<div class="media-body">-->
<!--									<small class="float-right">46h ago</small>-->
<!--									<strong>Mike Loreipsum</strong> started following <strong>Monica Smith</strong>. <br>-->
<!--									<small class="text-muted">3 days ago at 7:58 pm - 10.06.2014</small>-->
<!--								</div>-->
<!--							</div>-->
<!--						</li>-->
<!--						<li class="dropdown-divider"></li>-->
<!--						<li>-->
<!--							<div class="dropdown-messages-box">-->
<!--								<a class="dropdown-item float-left" href="profile.html">-->
<!--									<img alt="image" class="rounded-circle" src="--><?//=ADMIN_THEME?><!--/img/a4.jpg">-->
<!--								</a>-->
<!--								<div class="media-body ">-->
<!--									<small class="float-right text-navy">5h ago</small>-->
<!--									<strong>Chris Johnatan Overtunk</strong> started following <strong>Monica Smith</strong>. <br>-->
<!--									<small class="text-muted">Yesterday 1:21 pm - 11.06.2014</small>-->
<!--								</div>-->
<!--							</div>-->
<!--						</li>-->
<!--						<li class="dropdown-divider"></li>-->
<!--						<li>-->
<!--							<div class="dropdown-messages-box">-->
<!--								<a class="dropdown-item float-left" href="profile.html">-->
<!--									<img alt="image" class="rounded-circle" src="--><?//=ADMIN_THEME?><!--/img/profile.jpg">-->
<!--								</a>-->
<!--								<div class="media-body ">-->
<!--									<small class="float-right">23h ago</small>-->
<!--									<strong>Monica Smith</strong> love <strong>Kim Smith</strong>. <br>-->
<!--									<small class="text-muted">2 days ago at 2:30 am - 11.06.2014</small>-->
<!--								</div>-->
<!--							</div>-->
<!--						</li>-->
<!--						<li class="dropdown-divider"></li>-->
<!--						<li>-->
<!--							<div class="text-center link-block">-->
<!--								<a href="mailbox.html" class="dropdown-item">-->
<!--									<i class="fa fa-envelope"></i> <strong>Read All Messages</strong>-->
<!--								</a>-->
<!--							</div>-->
<!--						</li>-->
<!--					</ul>-->
<!--				</li>-->
<!--				<li class="dropdown">-->
<!--					<a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">-->
<!--						<i class="fa fa-bell"></i>  <span class="label label-primary">8</span>-->
<!--					</a>-->
<!--					<ul class="dropdown-menu dropdown-alerts">-->
<!--						<li>-->
<!--							<a href="mailbox.html" class="dropdown-item">-->
<!--								<div>-->
<!--									<i class="fa fa-envelope fa-fw"></i> You have 16 messages-->
<!--									<span class="float-right text-muted small">4 minutes ago</span>-->
<!--								</div>-->
<!--							</a>-->
<!--						</li>-->
<!--						<li class="dropdown-divider"></li>-->
<!--						<li>-->
<!--							<a href="profile.html" class="dropdown-item">-->
<!--								<div>-->
<!--									<i class="fa fa-twitter fa-fw"></i> 3 New Followers-->
<!--									<span class="float-right text-muted small">12 minutes ago</span>-->
<!--								</div>-->
<!--							</a>-->
<!--						</li>-->
<!--						<li class="dropdown-divider"></li>-->
<!--						<li>-->
<!--							<a href="grid_options.html" class="dropdown-item">-->
<!--								<div>-->
<!--									<i class="fa fa-upload fa-fw"></i> Server Rebooted-->
<!--									<span class="float-right text-muted small">4 minutes ago</span>-->
<!--								</div>-->
<!--							</a>-->
<!--						</li>-->
<!--						<li class="dropdown-divider"></li>-->
<!--						<li>-->
<!--							<div class="text-center link-block">-->
<!--								<a href="notifications.html" class="dropdown-item">-->
<!--									<strong>See All Alerts</strong>-->
<!--									<i class="fa fa-angle-right"></i>-->
<!--								</a>-->
<!--							</div>-->
<!--						</li>-->
<!--					</ul>-->
<!--				</li>-->


				<li>
					<a href="/home/logout">
						<i class="fa fa-sign-out"></i> Log out
					</a>
				</li>
			</ul>

		</nav>
	</div>

	<div class="row wrapper border-bottom white-bg page-heading">
		<div class="col-lg-10">
			<h2><?=$title?></h2>

		</div>
	</div>
