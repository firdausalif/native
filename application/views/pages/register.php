<main>
    <div class="container" style="margin-top: 80px; margin-bottom: 80px">
        <form id="registerForm" action="<?php echo base_url("Register/register")?>">
            <div>
                <div class="alert" id="regAlert" role="alert" style="display: none">

                </div>
            </div>
            <div class="form-group">
                <label for="regPass">Username</label>
                <input type="text" class="form-control" name="username" id="regUsername" placeholder="Username">
            </div>
            <div class="form-group">
                <label for="registerEmail">Email address</label>
                <input type="email" class="form-control" name="email" id="registerEmail" aria-describedby="emailHelp" placeholder="Enter email">
                <small id="regEmailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
            </div>
            <div class="form-group">
                <label for="regPass">Password</label>
                <input type="password" class="form-control" name="password" id="regPass" placeholder="Password">
            </div>
            <div class="form-group">
                <label for="regRePass">re-Password</label>
                <input type="password" class="form-control" name="repassword" id="regRePass" placeholder="Retype your password">
            </div>
            <div class="form-group">
                <label for="regName">Full Name</label>
                <input type="text" class="form-control" name="name" id="regName" placeholder="Nama Lengkap">
            </div>

            <button class="btn btn-primary">Submit</button>
        </form>
    </div>
</main>
<script>
    $(document).ready(function () {
        $('#regUsername').focus(); //biar cursor langsung ke username

        //submit register
        $('#registerForm').submit(function(e) {
            e.preventDefault();

            $.ajax({
                type: 'post', //gaperlu dijelasin lagi bu ratna udah pernah jelasin ini buat apa
                url: $(this).attr("action"), //ini buat ngambil nilai attribute action dari registerform
                data: $(this).find('input, select, textarea').serialize(), //buat ngambil input yang mau di register
                dataType: 'json', //type data response dari server
                success: function(data) {
                    if(data.status ==  'fail'){
                        $('#regAlert').removeClass('alert-success').addClass('alert-danger')
                            .attr('style', 'display: block').html(data.msg);
                        //menampilkan pesen error ke div yang ada diatas cari aja ya idnya regErr
                    }else{
                        $('#regAlert').removeClass('alert-danger').addClass('alert-success')
                            .attr('style', 'display: block').html(data.msg);
                        //menampilkan pesen succ ke div yang ada diatas cari aja ya idnya regSucc
                        $('#registerForm')[0].reset(); //reset Formnya kalo udah bener
                    }
                }
            });
        });
    });
</script>

