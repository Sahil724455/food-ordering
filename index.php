<?php
// Redirect root to Swagger UI
$projectPath = dirname($_SERVER['PHP_SELF']);
header("Location: {$projectPath}/swagger/index.html", true, 302);
exit;
?>
