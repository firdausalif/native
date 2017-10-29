<main>
    <div class="container">
        <form id="resetPassForm" action="<?php echo base_url("Resetpassword/updatePass")?>">
            <div>
                <div class="alert" id="resPassAlert" role="alert" style="display: none">

                </div>
            </div>
            <div class="form-group">
                <label for="resetPassEmail">Email address</label>
                <input readonly value="<?php echo $email?>" type="email" class="form-control" name="email" id="resetPassEmail" aria-describedby="emailHelp" placeholder="Enter email">
                <small id="regEmailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
            </div>
            <div class="form-group">
                <label for="regPass">Password</label>
                <input type="password" class="form-control" name="password" id="resetPass" placeholder="Password">
            </div>
            <div class="form-group">
                <label for="regRePass">re-Password</label>
                <input type="password" class="form-control" name="repassword" id="resetRePass" placeholder="Retype your password">
            </div>
            <button class="btn btn-primary">Submit</button>
        </form>
    </div>
</main>
<script>
    $(document).ready(function () {
       $('#resetPass').focus();
    });

    $('#resetPassForm').on('submit', function (e) {
        e.preventDefault();

        $.ajax({
            type: 'post', //gaperlu dijelasin lagi bu ratna udah pernah jelasin ini buat apa
            url: $(this).attr("action"), //ini buat ngambil nilai attribute action dari registerform
            data: $(this).find('input, select, textarea').serialize(), //buat ngambil input yang mau di register
            dataType: 'json', //type data response dari server
            success: function(data) {
                if(data.status ==  'fail'){
                    $('#resPassAlert').removeClass('alert-success').addClass('alert-danger')
                        .attr('style', 'display: block').html(data.msg);
                    //menampilkan pesen error ke div yang ada diatas cari aja ya idnya logAlert
                }else{
                    //menampilkan pesen succ ke div yang ada diatas cari aja ya idnya logAlert
                    $('#resPassAlert').removeClass('alert-danger').addClass('alert-success')
                        .attr('style', 'display: block').html(data.msg);
                    setTimeout(function () {
                        $('#resetPassForm')[0].reset(); //reset Formnya kalo udah bener
                    },1000);

                }
            }
        });
    });
</script>