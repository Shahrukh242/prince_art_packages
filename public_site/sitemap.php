<?php
require_once __DIR__ . '/../includes/functions.php';
header('Content-Type: application/xml; charset=UTF-8');
echo generate_sitemap_xml();
