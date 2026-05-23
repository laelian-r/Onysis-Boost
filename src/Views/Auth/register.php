<?php
ob_start();
$title = "Onysis Boost • Inscription";
$css = "/assets/css/auth.css";
?>

<div class="bg">
  <section class="formRegister">
    <h1>S'inscrire</h1>
    <form action="/register/" method="post">

      <div class="blockInput">
        <div class="labelInput">
          <label for="username"><i class="fas fa-user-tie icon"></i></label>
          <input type="text" name="username" value="<?php echo old("username");?>" placeholder="Nom d'utilisateur">
        </div>
        <span class="error"><?php echo error("username");?></span>
      </div>

      <div class="blockInput">
        <div class="labelInput">
          <label for="email"><i class="fas fa-envelope icon"></i></label>
          <input type="email" name="email" value="<?php echo old("email");?>" placeholder="Email">
        </div>
        <span class="error"><?php echo error("email");?></span>
      </div>

      <div class="blockInput">
        <div class="labelInput">
          <label for="password"><i class="fas fa-key icon"></i></label>
          <input id="inputPassword" class="inputPassword" type="password" name="password" value="<?php echo old("password");?>" placeholder="Mot de passe">
          <button id="btnPassword" class="viewPassword" type="button" name="button"><i class="far fa-eye"></i></button>
        </div>
        <span class="error"><?php echo error("password");?></span>
      </div>

      <div class="blockInput">
        <div class="labelInput">
          <label for="passwordConfirm"><i class="fas fa-key icon"></i></label>
          <input id="inputPasswordConfirm" class="inputPassword" type="password" name="passwordConfirm" value="<?php echo old("passwordConfirm");?>" placeholder="Confirmer le mot de passe">
          <button id="btnPasswordConfirm" class="viewPassword" type="button" name="button"><i class="far fa-eye"></i></button>
        </div>
        <span class="error"><?php echo error("passwordConfirm");?></span>
        <span class="error"><?php echo error("confirm");?></span>
      </div>

      <button type="submit" name="button">S'inscrire</button>
    </form>

    <div class="more">
      <p>Vous avez déjà un compte ? <a href="/login">Connectez-vous !</a></p>
    </div>
  </section>
</div>


<script>
var btnPass = document.getElementById("btnPassword");
var inputPass = document.getElementById("inputPassword");
btnPass.onclick = function() {
    if (inputPass.type === "password") {
        inputPass.type = "text";
    } else {
        inputPass.type = "password";
    }
};

var btnPassConf = document.getElementById("btnPasswordConfirm");
var inputPassConf = document.getElementById("inputPasswordConfirm");
btnPassConf.onclick = function() {
    if (inputPassConf.type === "password") {
        inputPassConf.type = "text";
    } else {
        inputPassConf.type = "password";
    }
};
</script>

<?php

$content = ob_get_clean();
require VIEWS . 'layout.php';