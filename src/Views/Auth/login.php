<?php
ob_start();
$title = "Onysis Boost · Connexion";
$css = "/assets/css/auth.css";
?>

<div class="bg">
  <section>
    <h1>Se connecter</h1>

    <form action="/login/" method="post">

      <div class="blockInput">
        <div class="labelInput">
          <label for="username"><i class="fas fa-user-tie"></i></label>
          <input type="text" name="username" value="<?php echo old("username");?>" placeholder="Username">
        </div>
        <span class="error"><?php echo error("username");?></span>
      </div>

      <div class="blockInput">
        <div class="labelInput">
          <label for="email"><i class="fas fa-envelope"></i></label>
          <input type="email" name="email" value="<?php echo old("email");?>" placeholder="Email">
        </div>
        <span class="error"><?php echo error("email");?></span>
      </div>

      <div class="blockInput">
        <div class="labelInput">
          <label for="password"><i class="fas fa-key"></i></label>
          <input id="inputPassword" class="inputPassword" type="password" name="password" value="<?php echo old("password");?>" placeholder="Password">
          <button id="btnPassword" class="viewPassword" type="button" name="button"><i class="far fa-eye"></i></button>
        </div>
        <span class="error"><?php echo error("password");?></span>
      </div>

      <button type="submit" name="button">Se connecter</button>
    </form>

    <?php if (!empty($_SESSION["error"]["message"])): ?>
      <p class="error"><?php echo $_SESSION["error"]["message"]; unset($_SESSION["error"]["message"]); ?></p>
    <?php endif; ?>

    <div class="more">
      <p>Vous n'avez pas de compte ? <a href="/register">Inscrivez-vous !</a></p>
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
</script>

<?php

$content = ob_get_clean();
require VIEWS . 'layout.php';