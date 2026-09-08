<?php
$zip = new ZipArchive();
$zip->open('C:/laragon/www/KKP-Forklift/FIMS_Business_Rules_v1.0_KKP.docx');
$content = $zip->getFromName('word/document.xml');
$xml = simplexml_load_string($content);
$namespaces = $xml->getNamespaces(true);
$body = $xml->xpath('//w:tbl|//w:p');
$txt = '';
foreach ($body as $node) {
    $txt .= strip_tags($node->asXML());
}
$txt = preg_replace('/<[^>]*>/', ' ', $txt);
$txt = html_entity_decode(preg_replace('/&[a-zA-Z0-9#]+;/', ' ', $txt));
$txt = preg_replace('/\s+/', ' ', $txt);
echo substr($txt, 0, 30000);
