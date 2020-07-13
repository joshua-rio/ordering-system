<?php
if($AdminInSession->type != "Manager"){
        echo "<script>alert('You are not allowed here!');
              window.location.href='index.php';</script>";
}
?>