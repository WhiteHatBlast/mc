<?php
include("../connection/config.php");
include("../include/auth.php");
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LAPORAN SAKIT PAGI / KHAS></title>

    <!-- Bootstrap CSS v3.0.0 or higher -->
    <link rel="stylesheet" href="../css/bootstrap.min.css">

    <!-- FormValidation CSS file -->
    <link rel="stylesheet" href="../css/formValidation.min.css">
    <link rel="stylesheet" href="../css/sweetalert.css">

    <!-- jQuery v1.9.1 or higher -->
    <script type="text/javascript" src="../js/jquery.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="../js/bootstrap.min.js"></script>

    <!-- FormValidation plugin and the class supports validating Bootstrap form -->
    <script src="../js/formValidation.min.js"></script>
    <script src="../js/validation.bootstrap.min.js"></script>
    <script src="../js/sweetalert.min.js"></script>

    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="../js/generateMC.js"></script>
    <script src="../js/lodash.js"></script>
    <script src="../js/functions.js"></script>

  </head>
  <body>

  <?php

  if(isset($_SESSION['session_roles'])){

  ?>

  <ul class="nav nav-tabs" role="tablist">

    <li class="dropdown">
      <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
        <?=$_SESSION['session_name']; ?> <span class="caret"></span>
      </a>
      <ul class="dropdown-menu">
        <li role="presentation">
          <a xaction="logout" aria-controls="generateMC">Logout</a>
        </li>
      </ul>
    </li>

      <?php
      if($_SESSION['session_roles'] == '03'){
      ?>
        <li role="presentation" class="active">
          <a href="#generateMC" aria-controls="generateMC" role="tab" data-toggle="tab">Generate MC</a>
        </li>
        <li role="presentation">
          <a href="#file_generated" aria-controls="file_generated" role="tab" data-toggle="tab">Senarai Laporan</a>
        </li>

      <?php } ?>

    <?php

    if($_SESSION['session_roles'] == '02'){

      ?>

      <li role="presentation" class="active">
        <a href="#file_generated" aria-controls="file_generated" role="tab" data-toggle="tab">Carian Batalion</a>
      </li>

    <?php
    }

    ?>


    <?php

    if($_SESSION['session_roles'] == '04'){

      ?>

      <li role="presentation" class="active">
        <a href="#file_generated" aria-controls="file_generated" role="tab" data-toggle="tab">Carian Fakulti</a>
      </li>

    <?php
    }

    ?>

  </ul>

  <div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="generateMC">
    	
    	<?php

        if($_SESSION['session_roles'] == '03'){
          include("generateMC.html");
        }

      if($_SESSION['session_roles'] == '02' || $_SESSION['session_roles'] == '04'){

        $s_roles = $_SESSION['session_roles'];

          ?>
          <br/>
          <div class="row">

            <div class="col-md-12">

              <div class="col-md-4">

                <label> Carian <?php if($s_roles == '02') { echo "Batalion"; } else { echo "Fakulti"; }?> </label>
                <div class="input-group">
                  <input type="text" class="form-control input-sm" name="searching" value="">
                  <span xsearch="fakulti" class="input-group-addon" style="cursor: pointer">Cari</span>
                </div>

              </div>

            </div>

            <?php

            if(isset($_REQUEST['q'])){

              $query = $_REQUEST['q'];

              if($s_roles == '02'){

                $search_something = "SELECT*FROM kadet as a,users as b WHERE a.organization LIKE '%".$query."%' AND b.id=a.userID";

              } else {

                $search_something = "SELECT*FROM kadet as a,users as b WHERE a.faculty LIKE '%".$query."%' AND b.id=a.userID";

              }

              $result = mysqli_query($connection, $search_something);

                ?>

                <div class="row">
                  <div class="col-md-9" style="margin-top: 20px; margin-left:30px; padding-right: 10px;">

                    <table class="table table-bordered clearfix">
                      <thead class="thead-inverse">
                      <tr style="background: #EEEEEE">
                        <th style="width: 30px">Bil</th>
                        <th>Date</th>
                        <th>Reason</th>
                        <th>Person</th>
                        <th>Fakulti</th>
                        <th>Batalion</th>
                      </tr>
                      </thead>
                      <tbody>
                      <?php

                      $index = 1;

                      while($rows = mysqli_fetch_array($result)){

                        echo '
                      <tr class="hover_blue" style="cursor: pointer;" xviewQRCODE='.$rows['qrHashKey'].'>
                        <td>'.$index++.'</td>
                        <td>'.$rows['currentDate'].'</td>
                        <td>'.$rows['description'].'</td>
                        <td>'.$rows['name'].'</td>
                        <td>'.$rows['faculty'].'</td>
                        <td>'.$rows['organization'].'</td>
                      </tr>
                     ';

                      }

                      ?>
                      </tbody>
                    </table>

                  </div>
                </div>

                <script>

                  $('[xviewQRCODE]').click(function (e, tpl) {

                    var value = $(e.currentTarget).attr('xviewQRCODE');

                    window.location = 'info/?q='+value

                  })

                </script>

                <?php

            }

            ?>

          </div>

      <?php
        }

      ?>

    </div>

    <div role="tabpanel" class="tab-pane" id="file_generated">

      <?php include("include/user_file.php"); ?>

    </div>

  </div>

    <br/>

  <?php }

  ?>

  <script type="text/javascript">

    $('[xsearch="fakulti"]').click(function(e, tpl){

      var value = $('[name="searching"]').val();

      window.location = "?q="+value

    })

  </script>

  </body>
</html>