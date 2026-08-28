<?php
/**
 * Redirect Nginx / Apache otomatis ke folder public/
 * Khusus untuk development server (Laragon)
 */
header("Location: /public/");
exit;