<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Passwordless Authentication</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5 text-center">
        <h2>Passwordless Authentication</h2>
        <p>Click below to login with a Magic Link sent to your email</p>
        <!-- Trigger button for modal -->
        <button type="button" class="btn btn-primary" id="login-button">
            Login with Magic Link
        </button>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/magic-sdk/dist/magic.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@magic-ext/oauth2/dist/extension.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@magic-ext/oauth2/dist/extension.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/web3/4.15.0/web3.min.js" integrity="sha512-RaLadHE5GTplpQJfyGFiG9cttM8Ya84HMQjK3drtC5M6IDQSfK5dDZVatZwtPy3clOdOB6/HA2RgQPiUlX4lkA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <!-- End of JavaScript Libraries -->

    <script type="text/javascript">
        $(document).ready(function() {
            const magic = new Magic('pk_live_AA4E9089795358CC');
            $("#login-button").click(async function() {
                try {
                    // const web3 = new Web3(magic.rpcProvider);
                    await magic.wallet.connectWithUI().on('id-token-created', (params) => {
                        const {
                            idToken
                        } = params;
                        console.log(idToken);

                        // Send the token to the server for validation using ajax
                        $.ajax({
                            url: 'login.php',
                            method: 'POST',
                            data: {
                                token: idToken
                            },
                            success: function(response) {
                                console.log(response);
                            }
                        });
                    });
                } catch (error) {
                    console.error("Magic Link Login Error:", error);
                    alert("An error occurred while logging in.");
                }
            });
        });
    </script>
</body>

</html>