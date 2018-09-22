<?php
function showNavBar($n)
{
    echo '<nav class="navbar navbar-inverse navbar-static-top">
                  <div class="container-fluid">
                    <div class="col-lg-1 col-sm-0"></div>
                    <div class="col-lg-10 col-sm-12">
                        <div class="navbar-header">
                          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                          </button>
                          <span class="navbar-brand" style="color: white"><span class="glyphicon glyphicon-wrench"></span> Pannello di amministrazione</span>
                        </div>
                        <div class="collapse navbar-collapse" id="myNavbar">
                          <ul class="nav navbar-nav">
                            <li ' . ($n == 0 ? 'class="active"' : '') . ' ><a href="courseManager.php">Gestisci corsi</a></li>
                            <li ' . ($n == 1 ? 'class="active"' : '') . '><a href="courseResults.php">Risultati</a></li>
                          </ul>
                          <ul class="nav navbar-nav navbar-right">
                            <li><a href="logout.php"><span class="glyphicon glyphicon-log-in"></span> Logout</a></li>
                          </ul>
                        </div>
                    </div>
                  </div>
                </nav>';
}
