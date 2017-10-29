<header>
    <div class="jumbotron jumbotron-fluid">
        <div class="container">
            <h1 class="display-3">ILKOM 2015</h1>
            <p class="lead">Built with love from ./native</p>
        </div>
    </div>

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">Navbar</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="<?php echo base_url()?>">Home <span class="sr-only">(current)</span></a>
                </li>

                <?php if(!isset($_SESSION['username'])){
                    echo '
                        <li class="nav-item active">
                            <a class="nav-link" href="'.base_url("Register").'"> Registrasi <span class="sr-only">(current)</span></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" data-toggle="modal" data-target="#loginModal">Login</a>
                        </li>';
                    }else{
                    echo '<li class="nav-item">
                            <a class="nav-link" href="'.base_url("Home/logout").'">Logout</a>
                        </li>';
                    }
                ?>

            </ul>
            <form class="form-inline my-2 my-lg-0">
                <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
            </form>
        </div>
    </nav>
</header>

<!-- Modal -->
<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="loginModalLabel">Login</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="loginForm" action="<?php echo base_url('Home/login')?>">
                    <div>
                        <div class="alert" id="logAlert" role="alert" style="display: none">

                        </div>
                    </div>
                    <div class="form-group">
                        <label for="emailLogin">Username</label>
                        <input type="text" name="username" class="form-control" id="usernameLogin" placeholder="Enter username">
                    </div>
                    <div class="form-group">
                        <label for="passLogin">Password</label>
                        <input type="password" name="password" class="form-control" id="passLogin" placeholder="Password">
                    </div>
                    <span>Lupa password? <a style="color: blue;cursor: pointer" id="forgPass">Klik disini</a></span>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button class="btn btn-primary">Login</button>
                </form>
            </div>
        </div>
    </div>
</div>

    <!-- Forgot Pass -->
    <div class="modal fade" id="forgPassModal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="loginModalLabel">Lupa password</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="forgPassForm" action="<?php echo base_url('Home/reqPass')?>">
                        <div>
                            <div class="alert" id="forgAlert" role="alert" style="display: none">

                            </div>
                        </div>
                           <div class="form-group">
                            <label for="emailForg">Email</label>
                            <input type="email" name="email" class="form-control" id="emailForg" placeholder="Enter email">
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button class="btn btn-primary">Send request</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $("#loginModal").on('shown.bs.modal', function () {
            $('#usernameLogin').focus();
        });

        //submit register
        $('#loginForm').submit(function(e) {
            e.preventDefault();

            $.ajax({
                type: 'post', //gaperlu dijelasin lagi bu ratna udah pernah jelasin ini buat apa
                url: $(this).attr("action"), //ini buat ngambil nilai attribute action dari registerform
                data: $(this).find('input, select, textarea').serialize(), //buat ngambil input yang mau di register
                dataType: 'json', //type data response dari server
                success: function(data) {
                    if(data.status ==  'fail'){
                        $('#logAlert').removeClass('alert-success').addClass('alert-danger')
                            .attr('style', 'display: block').html(data.msg);
                        //menampilkan pesen error ke div yang ada diatas cari aja ya idnya logAlert
                    }else{
                        //menampilkan pesen succ ke div yang ada diatas cari aja ya idnya logAlert
                        $('#logAlert').removeClass('alert-danger').addClass('alert-success')
                            .attr('style', 'display: block').html(data.msg);
                        setTimeout(function () {
                            window.location.href = "<?php echo  base_url()?>";
                            $('#loginForm')[0].reset(); //reset Formnya kalo udah bener
                        },1000);

                    }
                }
            });
        });

        //code dibawah buat forgot password
        $("#forgPass").on('click', function () {
           $('#loginModal').modal('hide');
           setTimeout(function () {
               $('#forgPassModal').modal('show');
           },500);
        });

        $("#forgPassModal").on("shown.bs.modal", function () {
            $('#emailForg').focus();
        });

        $('#forgPassForm').on('submit', function (e) {
            e.preventDefault();

            $.ajax({
                type: 'post', //gaperlu dijelasin lagi bu ratna udah pernah jelasin ini buat apa
                url: $(this).attr("action"), //ini buat ngambil nilai attribute action dari registerform
                data: $(this).find('input, select, textarea').serialize(), //buat ngambil input yang mau di register
                dataType: 'json', //type data response dari server
                success: function(data) {
                    if(data.status ==  'fail'){
                        $('#forgAlert').removeClass('alert-success').addClass('alert-danger')
                            .attr('style', 'display: block').html(data.msg);
                        //menampilkan pesen error ke div yang ada diatas cari aja ya idnya logAlert
                    }else{
                        //menampilkan pesen succ ke div yang ada diatas cari aja ya idnya logAlert
                        $('#forgAlert').removeClass('alert-danger').addClass('alert-success')
                            .attr('style', 'display: block').html(data.msg);
                        setTimeout(function () {
                            $('#forgPassModal').modal('hide');
                            $('#forgPassForm')[0].reset(); //reset Formnya kalo udah bener
                        },1000);

                    }
                }
            });
        });

    });
</script>
                                                